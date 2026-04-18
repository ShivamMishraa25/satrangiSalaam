<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';
require_once __DIR__ . '/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

function redirectAdmin(string $query = ''): void
{
    header('Location: ../admin/index.php' . $query);
    exit();
}

function safeLen(string $value): int
{
    return function_exists('mb_strlen') ? mb_strlen($value) : strlen($value);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectAdmin();
}

if (empty($_SESSION['moderator_logged_in']) && empty($_SESSION['admin_logged_in'])) {
    $_SESSION['error_message'] = 'Please login to continue.';
    redirectAdmin();
}

$title = trim((string)($_POST['title'] ?? ''));
$content = trim((string)($_POST['content'] ?? ''));
$titleHi = trim((string)($_POST['title_hi'] ?? ''));
$contentHi = trim((string)($_POST['content_hi'] ?? ''));

$errors = [];

if ($title === '' || safeLen($title) > 255) {
    $errors[] = 'Title is required and must be <= 255 characters.';
}

if ($content === '' || safeLen($content) > 20000) {
    $errors[] = 'Content is required and must be <= 20000 characters.';
}

$hasHindiColumns = false;
$langCols = $conn->query("SHOW COLUMNS FROM announcements WHERE Field IN ('title_hi','content_hi')");
if ($langCols && $langCols->num_rows === 2) {
    $hasHindiColumns = true;
}

if ($hasHindiColumns) {
    if ($titleHi === '' || safeLen($titleHi) > 255) {
        $errors[] = 'Hindi title is required and must be <= 255 characters.';
    }
    if ($contentHi === '' || safeLen($contentHi) > 20000) {
        $errors[] = 'Hindi content is required and must be <= 20000 characters.';
    }
}

$uploadedImages = [];
if (isset($_FILES['images']) && is_array($_FILES['images']['name'])) {
    $targetDir = dirname(__DIR__) . '/uploads/announcements/';
    if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true) && !is_dir($targetDir)) {
        $errors[] = 'Unable to prepare upload directory.';
    } else {
        $allowedMime = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $maxBytes = 5 * 1024 * 1024;
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        foreach ($_FILES['images']['tmp_name'] as $i => $tmpName) {
            if (!is_uploaded_file((string)$tmpName)) {
                continue;
            }

            $origName = (string)($_FILES['images']['name'][$i] ?? '');
            $fileErr = (int)($_FILES['images']['error'][$i] ?? UPLOAD_ERR_NO_FILE);
            $fileSize = (int)($_FILES['images']['size'][$i] ?? 0);

            if ($fileErr !== UPLOAD_ERR_OK) {
                $errors[] = 'Upload failed for ' . htmlspecialchars($origName, ENT_QUOTES, 'UTF-8') . '.';
                continue;
            }
            if ($fileSize <= 0 || $fileSize > $maxBytes) {
                $errors[] = 'Invalid size for ' . htmlspecialchars($origName, ENT_QUOTES, 'UTF-8') . '.';
                continue;
            }

            $ext = strtolower((string)pathinfo($origName, PATHINFO_EXTENSION));
            $mime = $finfo ? (string)finfo_file($finfo, (string)$tmpName) : '';
            if (!in_array($ext, $allowedExt, true) || !in_array($mime, $allowedMime, true)) {
                $errors[] = 'Invalid image type for ' . htmlspecialchars($origName, ENT_QUOTES, 'UTF-8') . '.';
                continue;
            }

            $base = preg_replace('/[^a-zA-Z0-9_-]/', '_', (string)pathinfo($origName, PATHINFO_FILENAME));
            $base = $base !== '' ? $base : 'announcement';
            $filename = uniqid($base . '_', true) . '.' . $ext;
            $destPath = $targetDir . $filename;

            if (!move_uploaded_file((string)$tmpName, $destPath)) {
                $errors[] = 'Failed to save ' . htmlspecialchars($origName, ENT_QUOTES, 'UTF-8') . '.';
                continue;
            }

            $uploadedImages[] = '../uploads/announcements/' . $filename;
        }

        if ($finfo) {
            finfo_close($finfo);
        }
    }
}

if (!empty($errors)) {
    $_SESSION['error_message'] = implode('<br>', $errors);
    redirectAdmin('?section=section-announcements');
}

$imagesCsv = implode(',', $uploadedImages);

if ($hasHindiColumns) {
    $stmt = $conn->prepare('INSERT INTO announcements (title, title_hi, content, content_hi, images) VALUES (?, ?, ?, ?, ?)');
    if (!$stmt) {
        $_SESSION['error_message'] = 'Database prepare failed.';
        redirectAdmin('?section=section-announcements');
    }
    $stmt->bind_param('sssss', $title, $titleHi, $content, $contentHi, $imagesCsv);
} else {
    $stmt = $conn->prepare('INSERT INTO announcements (title, content, images) VALUES (?, ?, ?)');
    if (!$stmt) {
        $_SESSION['error_message'] = 'Database prepare failed.';
        redirectAdmin('?section=section-announcements');
    }
    $stmt->bind_param('sss', $title, $content, $imagesCsv);
}

if (!$stmt->execute()) {
    $_SESSION['error_message'] = 'Failed to save announcement.';
    $stmt->close();
    redirectAdmin('?section=section-announcements');
}

$stmt->close();

$emailCredentials = [];
$credentialsPath = dirname(__DIR__, 2) . '/Safe/emailPasswordAnnouncement.php';
if (is_file($credentialsPath)) {
    $loaded = include $credentialsPath;
    if (is_array($loaded)) {
        $emailCredentials = $loaded;
    }
}

$emailWarnings = [];
$smtpUser = (string)($emailCredentials['email_username'] ?? '');
$smtpPass = (string)($emailCredentials['email_password'] ?? '');

if ($smtpUser !== '' && $smtpPass !== '') {
    $emailResult = $conn->query("SELECT email FROM users WHERE email IS NOT NULL AND email <> ''");
    $recipientEmails = [];

    if ($emailResult) {
        while ($row = $emailResult->fetch_assoc()) {
            $email = trim((string)($row['email'] ?? ''));
            if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $recipientEmails[$email] = true;
            }
        }
    }

    if (!empty($recipientEmails)) {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $smtpUser;
            $mail->Password = $smtpPass;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->isHTML(true);

            $mail->setFrom($smtpUser, 'Satrangi Salaam Notifications');
            $mail->Subject = 'New Announcement: Read Now';

            $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
            $safeBody = nl2br(htmlspecialchars($content, ENT_QUOTES, 'UTF-8'));

            $mail->Body = "<div style='background-color:#ffffff;color:#000;font-family:Arial,sans-serif;padding:20px;'>"
                . "<h2 style='color:#c62828;'>New Announcement</h2>"
                . "<h3 style='color:#000;'>" . $safeTitle . "</h3>"
                . "<p style='color:#333;'>" . $safeBody . "</p>"
                . "<p><a href='https://www.satrangisalaam.in/public/announcements' style='color:#0056b3;'>Read on Satrangi Salaam</a></p>"
                . '</div>';

            $mail->AltBody = "New Announcement: " . $title . "\n\n" . $content . "\n\nRead more: https://www.satrangisalaam.in/public/announcements";

            foreach ($uploadedImages as $imgPath) {
                $absPath = dirname(__DIR__) . '/' . ltrim(str_replace('../', '', $imgPath), '/');
                if (is_file($absPath)) {
                    $mail->addAttachment($absPath);
                }
            }

            $batch = [];
            foreach (array_keys($recipientEmails) as $email) {
                $batch[] = $email;
                if (count($batch) >= 80) {
                    foreach ($batch as $bccEmail) {
                        $mail->addBCC($bccEmail);
                    }
                    $mail->send();
                    $mail->clearAllRecipients();
                    $batch = [];
                }
            }

            if (!empty($batch)) {
                foreach ($batch as $bccEmail) {
                    $mail->addBCC($bccEmail);
                }
                $mail->send();
                $mail->clearAllRecipients();
            }
        } catch (Exception $e) {
            $emailWarnings[] = 'Announcement saved, but email notification failed.';
        }
    }
} else {
    $emailWarnings[] = 'Announcement saved, but email credentials are missing.';
}

if (!empty($emailWarnings)) {
    $_SESSION['success_message'] = 'Announcement saved. ' . implode(' ', $emailWarnings);
} else {
    $_SESSION['success_message'] = 'Announcement uploaded successfully and email notifications sent.';
}

redirectAdmin('?section=section-announcements');
?>
