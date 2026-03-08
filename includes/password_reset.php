<?php

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/password_reset_mailer.php';

function satrangi_base_url(): string
{
    if (defined('BASE_URL')) {
        return rtrim(BASE_URL, '/');
    }

    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    return $scheme . '://' . $host;
}

function satrangi_ensure_password_resets_table(mysqli $conn): void
{
    $sql = "CREATE TABLE IF NOT EXISTS password_resets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        token_hash CHAR(64) NOT NULL,
        expires_at DATETIME NOT NULL,
        used_at DATETIME NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX token_hash_idx (token_hash),
        INDEX user_id_idx (user_id),
        INDEX expires_idx (expires_at)
    )";

    if (!$conn->query($sql)) {
        throw new RuntimeException('Failed to ensure password_resets table: ' . $conn->error);
    }
}

function satrangi_generate_reset_token(): string
{
    $bytes = random_bytes(32);
    $b64 = base64_encode($bytes);
    $token = rtrim(strtr($b64, '+/', '-_'), '=');
    return $token;
}

function satrangi_token_hash(string $token): string
{
    return hash('sha256', $token);
}

function satrangi_create_password_reset_for_email(mysqli $conn, string $email): ?array
{
    satrangi_ensure_password_resets_table($conn);

    $stmt = $conn->prepare('SELECT id, email FROM users WHERE email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result ? $result->fetch_assoc() : null;
    $stmt->close();

    if (!$user) {
        return null;
    }

    $userId = (int)$user['id'];

    // Invalidate previous unused tokens for this user
    $stmt = $conn->prepare('UPDATE password_resets SET used_at = NOW() WHERE user_id = ? AND used_at IS NULL');
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $stmt->close();

    $token = satrangi_generate_reset_token();
    $tokenHash = satrangi_token_hash($token);

    $stmt = $conn->prepare('INSERT INTO password_resets (user_id, token_hash, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 60 MINUTE))');
    $stmt->bind_param('is', $userId, $tokenHash);
    if (!$stmt->execute()) {
        $err = $stmt->error;
        $stmt->close();
        throw new RuntimeException('Failed to create password reset token: ' . $err);
    }
    $stmt->close();

    return [
        'email' => $user['email'],
        'token' => $token,
    ];
}

function satrangi_send_password_reset_email(string $toEmail, string $token, bool $debug = false): void
{
    $resetLink = satrangi_base_url() . '/public/reset_password?token=' . rawurlencode($token);

    $mail = satrangi_create_mailer($debug);
    $mail->addAddress($toEmail);
    $mail->isHTML(true);
    $mail->Subject = 'Password reset request';

    $mail->Body = "
        <div style='font-family: Arial, sans-serif; background:#ffffff; color:#111; padding:16px;'>
          <h2 style='margin:0 0 12px;'>Reset your password</h2>
          <p style='margin:0 0 12px;'>We received a request to reset the password for your account.</p>
          <p style='margin:0 0 12px;'><a href='" . htmlspecialchars($resetLink, ENT_QUOTES, 'UTF-8') . "'>Click here to reset your password</a></p>
          <p style='margin:0 0 12px;'>This link will expire in 60 minutes.</p>
          <p style='margin:0;'>If you did not request this, you can ignore this email.</p>
        </div>
    ";

    $mail->AltBody = "Reset your password using this link (expires in 60 minutes): " . $resetLink;

    $mail->send();
}

function satrangi_invalidate_password_reset_token(mysqli $conn, string $token): void
{
    satrangi_ensure_password_resets_table($conn);
    $tokenHash = satrangi_token_hash($token);
    $stmt = $conn->prepare('UPDATE password_resets SET used_at = NOW() WHERE token_hash = ? AND used_at IS NULL');
    $stmt->bind_param('s', $tokenHash);
    $stmt->execute();
    $stmt->close();
}

function satrangi_find_valid_reset(mysqli $conn, string $token): ?array
{
    satrangi_ensure_password_resets_table($conn);

    $tokenHash = satrangi_token_hash($token);

    $stmt = $conn->prepare('SELECT pr.id AS reset_id, pr.user_id, u.email FROM password_resets pr JOIN users u ON u.id = pr.user_id WHERE pr.token_hash = ? AND pr.used_at IS NULL AND pr.expires_at > NOW() LIMIT 1');
    $stmt->bind_param('s', $tokenHash);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result ? $result->fetch_assoc() : null;
    $stmt->close();

    return $row ?: null;
}

function satrangi_consume_reset_and_update_password(mysqli $conn, int $resetId, int $userId, string $newPassword): void
{
    $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

    $stmt = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
    $stmt->bind_param('si', $passwordHash, $userId);
    if (!$stmt->execute()) {
        $err = $stmt->error;
        $stmt->close();
        throw new RuntimeException('Failed to update password: ' . $err);
    }
    $stmt->close();

    $stmt = $conn->prepare('UPDATE password_resets SET used_at = NOW() WHERE id = ?');
    $stmt->bind_param('i', $resetId);
    $stmt->execute();
    $stmt->close();
}
