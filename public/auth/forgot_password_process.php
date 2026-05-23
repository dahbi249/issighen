<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/send_mailer.php';
require_once __DIR__ . '/../../includes/middleware.php';

redirect_if_logged_in();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: forgot_password.php');
    exit;
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    set_flash_message('error', 'Invalid security token.');
    header('Location: forgot_password.php');
    exit;
}

$email = sanitize_input($_POST['email'] ?? '');

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    set_flash_message('error', 'Please enter a valid email address.');
    header('Location: forgot_password.php');
    exit;
}

// Ensure password_resets table exists.
$pdo->exec("CREATE TABLE IF NOT EXISTS password_resets (
    id INT(11) NOT NULL AUTO_INCREMENT,
    email VARCHAR(150) NOT NULL,
    token_hash VARCHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY email_idx (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

$stmt = $pdo->prepare('SELECT id, name FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user) {
    // Remove any existing reset tokens for this email.
    $stmt = $pdo->prepare('DELETE FROM password_resets WHERE email = ?');
    $stmt->execute([$email]);

    $token = bin2hex(random_bytes(32));
    $tokenHash = hash('sha256', $token);
    $expiresAt = date('Y-m-d H:i:s', time() + 3600); // 1 hour

    $stmt = $pdo->prepare('INSERT INTO password_resets (email, token_hash, expires_at) VALUES (?, ?, ?)');
    $stmt->execute([$email, $tokenHash, $expiresAt]);

    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $basePath = '';
    if (strpos($_SERVER['REQUEST_URI'], '/public/') !== false) {
        $basePath = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '/public/'));
    }
    $basePath = rtrim($basePath, '/');
    $resetUrl = $scheme . $_SERVER['HTTP_HOST'] . $basePath . '/public/auth/reset_password.php?token=' . urlencode($token) . '&email=' . urlencode($email);

    $subject = 'Reset your Issighen Agency password';
    $body = "<h3>Password Reset Request</h3>"
        . "<p>Hello " . htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') . ",</p>"
        . "<p>We received a request to reset your password. Click the button below to choose a new one. This link will expire in one hour.</p>"
        . "<p><a href='" . $resetUrl . "' style='display:inline-block;padding:12px 20px;background:#0d3b66;color:#fff;text-decoration:none;border-radius:6px;'>Reset Password</a></p>"
        . "<p>If you did not request a password reset, you can safely ignore this email.</p>";

    sendHtmlEmail($subject, $body, $email);
}

set_flash_message('success', 'If this email is registered, a password reset link has been sent.');
header('Location: login.php');
exit;
