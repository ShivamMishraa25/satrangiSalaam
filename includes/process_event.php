<?php
declare(strict_types=1);

session_start();
require_once __DIR__ . '/db.php';

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

$eventName = trim((string)($_POST['event_name'] ?? ''));
$eventDate = trim((string)($_POST['event_date'] ?? ''));
$eventLocation = trim((string)($_POST['event_location'] ?? ''));
$eventDescription = trim((string)($_POST['event_description'] ?? ''));

$eventNameHi = trim((string)($_POST['event_name_hi'] ?? ''));
$eventLocationHi = trim((string)($_POST['event_location_hi'] ?? ''));
$eventDescriptionHi = trim((string)($_POST['event_description_hi'] ?? ''));

$errors = [];

if ($eventName === '' || safeLen($eventName) > 255) {
    $errors[] = 'Event name is required and must be <= 255 characters.';
}

$dateObj = DateTime::createFromFormat('Y-m-d', $eventDate);
$isValidDate = $dateObj && $dateObj->format('Y-m-d') === $eventDate;
if (!$isValidDate) {
    $errors[] = 'A valid event date is required.';
}

if ($eventLocation === '' || safeLen($eventLocation) > 255) {
    $errors[] = 'Event location is required and must be <= 255 characters.';
}

if ($eventDescription === '' || safeLen($eventDescription) > 5000) {
    $errors[] = 'Event description is required and must be <= 5000 characters.';
}

$hasHindiColumns = false;
$columnsResult = $conn->query("SHOW COLUMNS FROM events WHERE Field IN ('event_name_hi','event_location_hi','event_description_hi')");
if ($columnsResult && $columnsResult->num_rows === 3) {
    $hasHindiColumns = true;
}

if ($hasHindiColumns) {
    if ($eventNameHi === '' || safeLen($eventNameHi) > 255) {
        $errors[] = 'Hindi event name is required and must be <= 255 characters.';
    }
    if ($eventLocationHi === '' || safeLen($eventLocationHi) > 255) {
        $errors[] = 'Hindi event location is required and must be <= 255 characters.';
    }
    if ($eventDescriptionHi === '' || safeLen($eventDescriptionHi) > 5000) {
        $errors[] = 'Hindi event description is required and must be <= 5000 characters.';
    }
}

$uploadedImages = [];
if (isset($_FILES['event_image']) && is_array($_FILES['event_image']['name'])) {
    $targetDir = dirname(__DIR__) . '/uploads/events/';
    if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true) && !is_dir($targetDir)) {
        $errors[] = 'Unable to prepare upload directory.';
    } else {
        $allowedMime = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $maxBytes = 5 * 1024 * 1024;
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        foreach ($_FILES['event_image']['tmp_name'] as $i => $tmpName) {
            if (!is_uploaded_file((string)$tmpName)) {
                continue;
            }

            $origName = (string)($_FILES['event_image']['name'][$i] ?? '');
            $fileSize = (int)($_FILES['event_image']['size'][$i] ?? 0);
            $fileErr = (int)($_FILES['event_image']['error'][$i] ?? UPLOAD_ERR_NO_FILE);

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
            $base = $base !== '' ? $base : 'event';
            $filename = uniqid($base . '_', true) . '.' . $ext;
            $destPath = $targetDir . $filename;

            if (!move_uploaded_file((string)$tmpName, $destPath)) {
                $errors[] = 'Failed to save ' . htmlspecialchars($origName, ENT_QUOTES, 'UTF-8') . '.';
                continue;
            }

            $uploadedImages[] = '../uploads/events/' . $filename;
        }

        if ($finfo) {
            finfo_close($finfo);
        }
    }
}

if (!empty($errors)) {
    $_SESSION['error_message'] = implode('<br>', $errors);
    redirectAdmin('?section=section-events');
}

$imageString = implode(',', $uploadedImages);

if ($hasHindiColumns) {
    $stmt = $conn->prepare('INSERT INTO events (event_name, event_name_hi, event_date, event_location, event_location_hi, event_description, event_description_hi, event_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    if (!$stmt) {
        $_SESSION['error_message'] = 'Database prepare failed.';
        redirectAdmin('?section=section-events');
    }
    $stmt->bind_param('ssssssss', $eventName, $eventNameHi, $eventDate, $eventLocation, $eventLocationHi, $eventDescription, $eventDescriptionHi, $imageString);
} else {
    $stmt = $conn->prepare('INSERT INTO events (event_name, event_date, event_location, event_description, event_image) VALUES (?, ?, ?, ?, ?)');
    if (!$stmt) {
        $_SESSION['error_message'] = 'Database prepare failed.';
        redirectAdmin('?section=section-events');
    }
    $stmt->bind_param('sssss', $eventName, $eventDate, $eventLocation, $eventDescription, $imageString);
}

if (!$stmt->execute()) {
    $_SESSION['error_message'] = 'Failed to save event. Please retry.';
    $stmt->close();
    redirectAdmin('?section=section-events');
}

$stmt->close();
$_SESSION['success_message'] = 'Event added successfully!';
redirectAdmin('?section=section-events');
?>