<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/middleware.php';

require_role([2, 3]);

if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar', 'fr', 'en'])) $_SESSION['lang'] = $_GET['lang'];
$lang = $_SESSION['lang'] ?? 'ar';
$t    = require __DIR__ . '/../../lang/' . $lang . '.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?lang=' . $lang);
    exit;
}

// Verify CSRF token
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    set_flash_message('error', $t['error_csrf_token']);
    header('Location: index.php?lang=' . $lang);
    exit;
}

// Get form data
$user_id = (int)($_POST['user_id'] ?? 0);
$name = sanitize_input($_POST['name'] ?? '');
$email = sanitize_input($_POST['email'] ?? '');
$phone = sanitize_input($_POST['phone'] ?? '');
$role = (int)($_POST['role'] ?? 1);
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

$errors = [];

// Validate name
if (empty($name)) {
    $errors[] = $t['error_profile_name_required'];
} elseif (strlen($name) < 2) {
    $errors[] = $t['error_name_too_short'];
}

// Validate email
if (empty($email)) {
    $errors[] = $t['error_profile_email_required'];
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = $t['error_invalid_email'];
} else {
    // Check if email is unique (for other users)
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->execute([$email, $user_id]);
    if ($stmt->fetch()) {
        $errors[] = $t['error_email_exists'];
    }
}

// Validate phone if provided
if (!empty($phone) && strlen($phone) < 9) {
    $errors[] = $t['error_invalid_phone'];
}

// Validate password if provided
if (!empty($new_password)) {
    if (strlen($new_password) < 8) {
        $errors[] = $t['error_password_short'];
    }
    if ($new_password !== $confirm_password) {
        $errors[] = $t['error_password_mismatch'];
    }
}

// If there are errors, show them and redirect back
if (!empty($errors)) {
    set_flash_message('error', implode('<br>', $errors));
    header('Location: edit.php?id=' . $user_id . '&lang=' . $lang);
    exit;
}

try {
    // Build update query
    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $pdo->prepare("
            UPDATE users 
            SET name = ?, email = ?, phone = ?, role_id = ?, password = ?, updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$name, $email, $phone, $role, $hashed_password, $user_id]);
    } else {
        $stmt = $pdo->prepare("
            UPDATE users 
            SET name = ?, email = ?, phone = ?, role_id = ?, updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$name, $email, $phone, $role, $user_id]);
    }

    set_flash_message('success', $t['success_profile_updated']);
    header('Location: edit.php?id=' . $user_id . '&lang=' . $lang);
    exit;
} catch (PDOException $e) {
    error_log('User edit error: ' . $e->getMessage());
    set_flash_message('error', $t['error_profile_update_failed']);
    header('Location: edit.php?id=' . $user_id . '&lang=' . $lang);
    exit;
}
