<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/database.php';

// Clear remember me token from DB
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("DELETE FROM remember_tokens WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
}

// Clear cookie
if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', time() - 3600, '/');
}

// Destroy session
session_unset();
session_destroy();

// Start a new session just to set a flash message
session_start();
$_SESSION['flash_success'] = "You have been successfully logged out.";

header('Location: /tourism-agency/public/login.php');
exit;
?>
