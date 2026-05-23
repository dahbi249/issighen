<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/middleware.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: reset_password.php');
    exit;
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    set_flash_message('error', 'Invalid security token.');
    header('Location: login.php');
    exit;
}

$token = $_POST['token'] ?? '';
$email = sanitize_input($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if (empty($token) || empty($email)) {
    set_flash_message('error', 'Invalid password reset request.');
    header('Location: login.php');
    exit;
}

$errors = [];
if (strlen($password) < 8) {
    $errors[] = 'Password must be at least 8 characters.';
}
if ($password !== $confirm_password) {
    $errors[] = 'Passwords do not match.';
}

if (!empty($errors)) {
    set_flash_message('error', implode('<br>', $errors));
    header('Location: reset_password.php?token=' . urlencode($token) . '&email=' . urlencode($email));
    exit;
}

$tokenHash = hash('sha256', $token);
$stmt = $pdo->prepare('SELECT email FROM password_resets WHERE email = ? AND token_hash = ? AND expires_at > NOW()');
$stmt->execute([$email, $tokenHash]);
$reset = $stmt->fetch();

if (!$reset) {
    set_flash_message('error', 'Invalid or expired password reset token.');
    header('Location: login.php');
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
$stmt = $pdo->prepare('UPDATE users SET password = ?, updated_at = NOW() WHERE email = ?');
$stmt->execute([$hashedPassword, $email]);
$stmt = $pdo->prepare('DELETE FROM password_resets WHERE email = ?');
$stmt->execute([$email]);

set_flash_message('success', 'Your password has been updated. You can now log in.');
header('Location: login.php');
exit;
