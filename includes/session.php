<?php
// includes/session.php

// Set secure session parameters before starting
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

$session_cookie_params = session_get_cookie_params();
session_set_cookie_params([
    'lifetime' => $session_cookie_params['lifetime'],
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => isset($_SERVER['HTTPS']), // True if HTTPS
    'httponly' => true, // Prevent JavaScript access to session cookie
    'samesite' => 'Strict' // Prevent CSRF via cross-site requests
]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if session is hijacked (IP and User Agent)
if (isset($_SESSION['user_agent']) && $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
    session_unset();
    session_destroy();
    session_start();
} else if (!isset($_SESSION['user_agent'])) {
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
}

if (isset($_SESSION['user_ip']) && $_SESSION['user_ip'] !== $_SERVER['REMOTE_ADDR']) {
    session_unset();
    session_destroy();
    session_start();
} else if (!isset($_SESSION['user_ip'])) {
    $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
}

// Session timeout logic (30 minutes)
$timeout_duration = 1800;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset();
    session_destroy();
    session_start();
    $_SESSION['flash_error'] = "Your session has expired due to inactivity. Please log in again.";
    header('Location: /tourism-agency/public/login.php');
    exit;
}
$_SESSION['last_activity'] = time();

// Regenerate session ID periodically to prevent fixation (e.g., every 30 mins)
if (!isset($_SESSION['created'])) {
    $_SESSION['created'] = time();
} else if (time() - $_SESSION['created'] > $timeout_duration) {
    session_regenerate_id(true);
    $_SESSION['created'] = time();
}

// Check "Remember Me" cookie if session is not active
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
    require_once __DIR__ . '/../config/database.php';
    $token = $_COOKIE['remember_me'];
    $stmt = $pdo->prepare("SELECT user_id, token_hash, expires_at FROM remember_tokens WHERE token_hash = ?");
    $stmt->execute([hash('sha256', $token)]);
    $remember = $stmt->fetch();

    if ($remember && strtotime($remember['expires_at']) > time()) {
        // Log user in
        $stmtUser = $pdo->prepare("SELECT id, name, role_id FROM users WHERE id = ?");
        $stmtUser->execute([$remember['user_id']]);
        $user = $stmtUser->fetch();
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['name'] = $user['name'];
            session_regenerate_id(true);
        }
    }
}
?>
