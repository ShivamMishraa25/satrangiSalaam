<?php
include 'db.php';
require '../includes/PHPMailer/PHPMailer.php';
require '../includes/PHPMailer/SMTP.php';
require '../includes/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $name = htmlspecialchars($_POST["name"]);
    $email = !empty($_POST["email"]) ? filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) : NULL;
    $phone = htmlspecialchars($_POST["phone"]);
    $subject = isset($_POST["subject"]) ? htmlspecialchars($_POST["subject"]) : NULL;
    $message = isset($_POST["message"]) ? htmlspecialchars($_POST["message"]) : NULL;

    // Detect optional columns independently to keep inserts compatible across schema versions.
    $columns_query = "SHOW COLUMNS FROM contactUs WHERE Field IN ('subject', 'message')";
    $columns_result = $conn->query($columns_query);
    $has_subject_column = false;
    $has_message_column = false;
    if ($columns_result) {
        while ($column = $columns_result->fetch_assoc()) {
            if ($column['Field'] === 'subject') {
                $has_subject_column = true;
            }
            if ($column['Field'] === 'message') {
                $has_message_column = true;
            }
        }
    }

    // Insert into database based on available table columns.
    try {
        if ($has_subject_column && $has_message_column) {
            $stmt = $conn->prepare("INSERT INTO contactUs (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $email, $phone, $subject, $message);
        } elseif ($has_message_column) {
            // Keep saving message even if subject column is missing.
            $stmt = $conn->prepare("INSERT INTO contactUs (name, email, phone, message) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $phone, $message);
        } elseif ($has_subject_column) {
            $stmt = $conn->prepare("INSERT INTO contactUs (name, email, phone, subject) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $phone, $subject);
        } else {
            $stmt = $conn->prepare("INSERT INTO contactUs (name, email, phone) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $phone);
        }

        if (!$stmt) {
            throw new \Exception("Database prepare failed: " . $conn->error);
        }
        
        $stmt->execute();
        $stmt->close();
        
        // Database insertion successful
        $db_success = true;
    } catch (\Exception $e) {
        $db_success = false;
        $db_error = $e->getMessage();
    }

    // Try to send email notification, but don't fail if it doesn't work
    $email_sent = false;
    $email_error = '';
    
    // Check if email credentials file exists
    $email_credentials_file = '../../Safe/emailPasswordAnnouncement.php';
    if (file_exists($email_credentials_file)) {
        try {
            $email_credentials = include $email_credentials_file;
            
            $mail = new PHPMailer(true);

            // SMTP Configuration with better error handling
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $email_credentials['email_username'];
            $mail->Password   = $email_credentials['email_password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            
            // Additional SMTP options for better compatibility
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            // Email Settings
            $mail->setFrom($email_credentials['email_username'], 'NGO Contact Form');
            $mail->addAddress('bhartenduvimaldubey@gmail.com');
            
            // Add reply-to if user provided email
            if ($email) {
                $mail->addReplyTo($email, $name);
            }

            // Email content with enhanced formatting
            $mail->isHTML(true);
            $mail->Subject = "New Contact Form Submission" . ($subject ? " - " . $subject : "");
            
            $emailBody = "
            <h2>📩 New Contact Form Submission</h2>
            <hr>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> " . ($email ?? "Not Provided") . "</p>
            <p><strong>Phone:</strong> $phone</p>";
            
            if ($subject) {
                $emailBody .= "<p><strong>Subject:</strong> $subject</p>";
            }
            
            if ($message) {
                $emailBody .= "
                <hr>
                <h3>Message:</h3>
                <p>" . nl2br($message) . "</p>";
            }
            
            $emailBody .= "
            <hr>
            <p><em>Sent from NGO Contact Form</em></p>
            <p><small>Database Status: " . ($db_success ? "✅ Saved" : "❌ Error") . "</small></p>
            ";
            
            $mail->Body = $emailBody;

            $mail->send();
            $email_sent = true;
            
        } catch (Exception $e) {
            $email_error = $e->getMessage();
        }
    } else {
        $email_error = "Email configuration file not found";
    }

    // Show appropriate response based on what succeeded
    if ($db_success || $email_sent) {
        // Success if either database or email worked
        if (isset($_POST["message"])) {
            // From new home.php form (has message field)
            echo "<!DOCTYPE html>
            <html>
            <head>
                <title>Thank You</title>
                <style>
                    body { font-family: Arial, sans-serif; text-align: center; padding: 50px; background: #f8f9fa; }
                    .container { max-width: 600px; margin: 0 auto; background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                    .success { color: #28a745; margin-bottom: 1rem; }
                    .warning { color: #ffc107; margin-bottom: 1rem; font-size: 0.9rem; }
                    .btn { background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 1rem; }
                    .status { background: #e9ecef; padding: 1rem; border-radius: 5px; margin: 1rem 0; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <h3 class='success'>✅ Thank you for contacting us!</h3>
                    <p>Your message has been received. We will get back to you soon.</p>
                    
                    <div class='status'>
                        <h4>Submission Status:</h4>
                        <p>📝 Form Data: " . ($db_success ? "✅ Saved to database" : "❌ Database error") . "</p>
                        <p>📧 Email Notification: " . ($email_sent ? "✅ Sent successfully" : "❌ " . $email_error) . "</p>
                    </div>
                    
                    " . (!$email_sent ? "<p class='warning'>⚠️ Email notification failed, but your message was recorded. You can also reach us directly at +91 94554 39320 or +91 87653 72798</p>" : "") . "
                    
                    <a href='../' class='btn'>← Back to Home</a>
                </div>
            </body>
            </html>";
        } else {
            // From old index.php form (simple form)
            header("Location: thankYou_form.php");
        }
    } else {
        // Both failed
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>Submission Error</title>
            <style>
                body { font-family: Arial, sans-serif; text-align: center; padding: 50px; background: #f8f9fa; }
                .container { max-width: 600px; margin: 0 auto; background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .error { color: #dc3545; margin-bottom: 1rem; }
                .btn { background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 1rem; }
                .contact-info { background: #e9ecef; padding: 1rem; border-radius: 5px; margin: 1rem 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h3 class='error'>❌ Submission Failed</h3>
                <p>We're sorry, but there was a technical issue with your submission.</p>
                
                <div class='contact-info'>
                    <h4>Please contact us directly:</h4>
                    <p>📞 Phone: +91 94554 39320, +91 87653 72798</p>
                    <p>📧 Email: satrangisalamss@gmail.com</p>
                    <p>💬 WhatsApp: <a href='https://chat.whatsapp.com/Jifh0MGROxAJQD4bueRFN0'>Join our group</a></p>
                </div>
                
                <a href='../home' class='btn'>← Back to Home</a>
                <a href='javascript:history.back()' class='btn'>← Try Again</a>
            </div>
        </body>
        </html>";
    }
}

$conn->close();
?>