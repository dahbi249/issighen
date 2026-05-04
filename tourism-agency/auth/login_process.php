<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/middleware.php';

redirect_if_logged_in();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        set_flash_message('error', 'Invalid security token.');
        header('Location: /tourism-agency/public/login.php');
        exit;
    }

    $email = sanitize_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if (empty($email) || empty($password)) {
        set_flash_message('error', 'Please fill in all fields.');
        header('Location: /tourism-agency/public/login.php');
        exit;
    }

    if (!check_login_attempts($pdo, $email)) {
        set_flash_message('error', 'Too many failed login attempts. Please try again in 15 minutes.');
        header('Location: /tourism-agency/public/login.php');
        exit;
    }

    $stmt = $pdo->prepare("SELECT id, name, password, role_id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Successful login
        clear_login_attempts($pdo, $email);
        
        session_regenerate_id(true); // Prevent session fixation
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['name'] = $user['name'];

        if ($remember) {
            create_remember_token($pdo, $user['id']);
        }

        set_flash_message('success', 'Welcome back, ' . htmlspecialchars($user['name']) . '!');
        header('Location: /tourism-agency/public/dashboard.php');
        exit;
    } else {
        // Failed login
        record_login_attempt($pdo, $email);
        set_flash_message('error', 'Invalid email or password.');
        header('Location: /tourism-agency/public/login.php');
        exit;
    }
} else {
    header('Location: /tourism-agency/public/login.php');
    exit;
}
?>
