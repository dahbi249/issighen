<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/middleware.php';
require_once __DIR__ . '/../../includes/send_mailer.php';

redirect_if_logged_in();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        set_flash_message('error', 'Invalid security token.');
        header('Location: /issighen/public/auth/register.php');
        exit;
    }

    $name = sanitize_input($_POST['name'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $phone = sanitize_input($_POST['phone'] ?? '');
    $passport_number = sanitize_input($_POST['passport_number'] ?? '');

    $errors = [];

    // Validation
    if (empty($name)) $errors[] = "Name is required.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (strlen($password) < 8) $errors[] = "Password must be at least 8 characters.";
    if ($password !== $confirm_password) $errors[] = "Passwords do not match.";

    if (empty($errors)) {
        // Check if email unique
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = "Email is already registered.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
            $default_role = 1; // user

            // Insert user
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role_id, phone, passport_number, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
            try {
                $stmt->execute([$name, $email, $hashed_password, $default_role, $phone, $passport_number]);

                $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
                $loginUrl = $scheme . $_SERVER['HTTP_HOST'] . '/issighen/public/auth/login.php';
                $subject = 'Welcome to Issighen Agency';
                $body = "<h3>Welcome to Issighen Agency</h3>"
                    . "<p>Hello " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ",</p>"
                    . "<p>Thank you for joining Issighen Agency. We're happy to have you on board and look forward to helping you book your next trip.</p>"
                    . "<p>To access your account, please <a href='" . $loginUrl . "'>log in here</a>.</p>"
                    . "<p>If you need any help, feel free to reply to this email.</p>";
                sendHtmlEmail($subject, $body, $email);

                set_flash_message('success', 'Registration successful. You can now log in.');
                header('Location: /issighen/public/auth/login.php');
                exit;
            } catch (PDOException $e) {
                // Log actual error and show generic one
                error_log($e->getMessage());
                set_flash_message('error', 'An error occurred during registration. Please try again.');
            }
        }
    }

    if (!empty($errors)) {
        set_flash_message('error', implode('<br>', $errors));
        header('Location: /issighen/public/auth/register.php');
        exit;
    }
} else {
    header('Location: /issighen/public/auth/register.php');
    exit;
}
