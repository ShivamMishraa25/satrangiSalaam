<?php
include 'db.php';
require '../includes/PHPMailer/PHPMailer.php';
require '../includes/PHPMailer/SMTP.php';
require '../includes/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$email_credentials = include __DIR__ . '/../../Safe/emailPasswordAnnouncement.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);

    // Handle image uploads
    $uploaded_images = [];
    if (!empty($_FILES['images']['name'][0])) {
        $target_dir = "../uploads/announcements/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $original_name = basename($_FILES['images']['name'][$key]);
            $unique_name = pathinfo($original_name, PATHINFO_FILENAME) . '_' . time() . '_' . $key . '.' . pathinfo($original_name, PATHINFO_EXTENSION);
            $target_file = $target_dir . $unique_name;

            if (move_uploaded_file($tmp_name, $target_file)) {
                $uploaded_images[] = $target_file;
            }
        }
    }

    $images = implode(',', $uploaded_images);
    $sql = "INSERT INTO announcements (title, content, images) VALUES ('$title', '$content', '$images')";

    if ($conn->query($sql) === TRUE) {
        $result = $conn->query("SELECT email FROM users");

        if ($result && $result->num_rows > 0) {
            $mail = new PHPMailer(true);
            $successful_emails = [];
            $failed_emails = [];

            try {
                // SMTP Configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = $email_credentials['email_username'];
                $mail->Password = $email_credentials['email_password'];
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('satrangisalamss@gmail.com', 'Satrangi Salaam Notifications');
                $mail->Subject = 'New Announcement: Read Now';
                $mail->isHTML(true);

                // ✅ Fix for Escaped Characters
                $decodedContent = str_replace(['\\r\\n', '\\n', '\\r'], PHP_EOL, $content);
                $formattedContent = nl2br($decodedContent);

                // ✅ Styled HTML Email Body
                $htmlContent = "
                    <div style='background-color: #ffffff; color: #000000; font-family: Arial, sans-serif; padding: 20px;'>
                        <h2 style='color: #D63384;'>📢 New Announcement!</h2>
                        <h3 style='color: #000000;'>$title</h3>
                        <p style='color: #333333;'>$formattedContent</p>
                        <p><a href='https://www.satrangisalaam.in/public/announcements' style='color: #007BFF;'>Satrangi Salaam</a></p>
                    </div>
                ";

                // ✅ Plain Text Fallback
                $plainText = "New Announcement: $title\n\n" . $decodedContent . "\n\nView more: https://satrangisalaam.in/public/announcements";

                $mail->Body = $htmlContent;
                $mail->AltBody = $plainText;

                // ✅ Attach Images
                foreach ($uploaded_images as $image) {
                    $mail->addAttachment($image);
                }

                // ✅ Add All Users in BCC (Faster)
                while ($row = $result->fetch_assoc()) {
                    $mail->addBCC($row['email']);
                    $successful_emails[] = $row['email']; // Track sent emails
                }

                // ✅ Send Once Instead of a Loop
                $mail->send();

                echo "<h3>✅ Announcement uploaded successfully!</h3>";
                echo "<p><a href='../public/announcements'>Go to Announcements Page</a></p>";

                // ✅ Display Successfully Sent Emails
                if (!empty($successful_emails)) {
                    echo "<h4>✅ Emails sent successfully to:</h4><ul>";
                    foreach ($successful_emails as $email) {
                        echo "<li>$email</li>";
                    }
                    echo "</ul>";
                }
            } catch (Exception $e) {
                // If email sending fails, log it and show the error
                $failed_emails[] = "Error: " . $mail->ErrorInfo;
                echo "<h4>❌ Email failed: " . $mail->ErrorInfo . "</h4>";
            }
        } else {
            echo "No registered users found.<br>";
        }
    } else {
        echo "Database Error: " . $conn->error;
    }
}

$conn->close();
?>
