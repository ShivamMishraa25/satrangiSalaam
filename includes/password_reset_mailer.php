<?php

require_once __DIR__ . '/../config.php';

require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';
require_once __DIR__ . '/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

function satrangi_load_email_credentials(): array
{
    $candidates = [
        __DIR__ . '/../../Safe/emailPasswordAnnouncement.php',
        __DIR__ . '/../../Safe/emailPasswordReset.php',
        __DIR__ . '/../../Safe/emailPasswordMailer.php',
        __DIR__ . '/../../Safe/emailPassword.php',
        __DIR__ . '/../safe/emailPasswordAnnouncement.php',
        __DIR__ . '/../Safe/emailPasswordAnnouncement.php',
        __DIR__ . '/../../safe/emailPasswordAnnouncement.php',
    ];

    foreach ($candidates as $path) {
        if (is_file($path)) {
            $creds = include $path;
            if (is_array($creds) && !empty($creds['email_username']) && !empty($creds['email_password'])) {
                return $creds;
            }
        }
    }

    throw new RuntimeException('Email credentials file not found or invalid. Expected array with email_username/email_password in Safe folder.');
}

function satrangi_create_mailer(bool $debug = false): PHPMailer
{
    $creds = satrangi_load_email_credentials();

    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $creds['email_username'];
    $mail->Password = $creds['email_password'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    if ($debug) {
        // Helpful logging (shows up in Apache/PHP error log).
        // This does not display to end users unless you echo it manually.
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->Debugoutput = static function (string $str, int $level): void {
            error_log('PHPMailer[' . $level . ']: ' . $str);
        };
    }

    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
        ]
    ];

    $fromEmail = $creds['email_username'];
    $mail->setFrom($fromEmail, 'Satrangi Salaam');

    return $mail;
}
