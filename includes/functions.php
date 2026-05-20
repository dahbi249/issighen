<?php
// includes/functions.php

function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)), ENT_QUOTES, 'UTF-8');
}

function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
        return true;
    }
    return false;
}

function set_flash_message($type, $message) {
    $_SESSION['flash_' . $type] = $message;
}

function display_flash_messages() {
    $types = ['error', 'success', 'warning', 'info'];
    $output = '';
    foreach ($types as $type) {
        if (isset($_SESSION['flash_' . $type])) {
            $output .= '<div class="alert alert-' . $type . '">' . sanitize_input($_SESSION['flash_' . $type]) . '</div>';
            unset($_SESSION['flash_' . $type]);
        }
    }
    return $output;
}

// Basic Brute Force Protection
function check_login_attempts($pdo, $email) {
    $stmt = $pdo->prepare("SELECT COUNT(*) as attempts FROM login_attempts WHERE email = ? AND attempt_time > (NOW() - INTERVAL 15 MINUTE)");
    $stmt->execute([$email]);
    $row = $stmt->fetch();
    if ($row['attempts'] >= 5) {
        return false; // Blocked
    }
    return true;
}

function record_login_attempt($pdo, $email) {
    $stmt = $pdo->prepare("INSERT INTO login_attempts (email, attempt_time) VALUES (?, NOW())");
    $stmt->execute([$email]);
}

function clear_login_attempts($pdo, $email) {
    $stmt = $pdo->prepare("DELETE FROM login_attempts WHERE email = ?");
    $stmt->execute([$email]);
}

function create_remember_token($pdo, $user_id) {
    $token = bin2hex(random_bytes(32));
    $token_hash = hash('sha256', $token);
    $expires = date('Y-m-d H:i:s', time() + (86400 * 30)); // 30 days
    
    // Clear existing tokens for user
    $stmt = $pdo->prepare("DELETE FROM remember_tokens WHERE user_id = ?");
    $stmt->execute([$user_id]);

    $stmt = $pdo->prepare("INSERT INTO remember_tokens (user_id, token_hash, expires_at) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $token_hash, $expires]);

    setcookie('remember_me', $token, time() + (86400 * 30), '/', '', isset($_SERVER['HTTPS']), true);
}
?>
