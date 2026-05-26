<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/send_mailer.php';

if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar', 'fr', 'en'])) $_SESSION['lang'] = $_GET['lang'];
$lang = $_SESSION['lang'] ?? 'ar';
$t    = require __DIR__ . '/../../lang/' . $lang . '.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.php?lang=' . $lang);
    exit;
}

// Verify CSRF token
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    set_flash_message('error', $t['error_csrf_token']);
    header('Location: contact.php?lang=' . $lang);
    exit;
}

// Get form data
$name = sanitize_input($_POST['name'] ?? '');
$email = sanitize_input($_POST['email'] ?? '');
$subject = sanitize_input($_POST['subject'] ?? '');
$message = sanitize_input($_POST['message'] ?? '');

$errors = [];

// Validate name
if (empty($name)) {
    $errors[] = $t['error_contact_name_required'];
} elseif (strlen($name) < 2) {
    $errors[] = $t['error_name_too_short'];
}

// Validate email
if (empty($email)) {
    $errors[] = $t['error_contact_email_required'];
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = $t['error_invalid_email'];
}

// Validate message
if (empty($message)) {
    $errors[] = $t['error_contact_message_required'];
} elseif (strlen($message) < 10) {
    $errors[] = $t['error_contact_message_short'];
}

// If there are errors, show them and redirect back
if (!empty($errors)) {
    set_flash_message('error', implode('<br>', $errors));
    header('Location: contact.php?lang=' . $lang);
    exit;
}

try {
    // Insert contact message into database
    $stmt = $pdo->prepare("
        INSERT INTO contacts (name, email, subject, message, ip_address, created_at) 
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $stmt->execute([$name, $email, $subject, $message, $ip_address]);

    // Send confirmation email to user
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    
    $user_subject = ($lang === 'ar' ? 'شكراً لتواصلك معنا' : ($lang === 'fr' ? 'Merci de nous avoir contacté' : 'Thank you for contacting us'));
    
    $user_body = ($lang === 'ar' 
        ? "<h3>شكراً لتواصلك معنا</h3>"
            . "<p>مرحباً " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ",</p>"
            . "<p>شكراً على رسالتك. سنقوم بالرد عليك في أقرب وقت ممكن.</p>"
            . "<p>فريق ايسيغن للسياحة والأسفار</p>"
        : ($lang === 'fr'
            ? "<h3>Merci de nous avoir contacté</h3>"
                . "<p>Bonjour " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ",</p>"
                . "<p>Merci pour votre message. Nous vous répondrons dès que possible.</p>"
                . "<p>L'équipe Isighène Voyages et Tourisme</p>"
            : "<h3>Thank you for contacting us</h3>"
                . "<p>Hello " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ",</p>"
                . "<p>Thank you for your message. We will get back to you as soon as possible.</p>"
                . "<p>Isighène Travel & Tourism Team</p>"
        )
    );
    
    sendHtmlEmail($user_subject, $user_body, $email);

    // Send notification email to admin
    $admin_email = 'contact@isighene.com'; // Change this to your admin email
    $admin_subject = ($lang === 'ar' ? 'رسالة اتصال جديدة من' : ($lang === 'fr' ? 'Nouveau message de contact de' : 'New contact message from')) . ' ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    
    $admin_body = "<h3>" . htmlspecialchars(($lang === 'ar' ? 'رسالة اتصال جديدة' : ($lang === 'fr' ? 'Nouveau message de contact' : 'New Contact Message')), ENT_QUOTES, 'UTF-8') . "</h3>"
        . "<p><strong>" . ($lang === 'ar' ? 'الاسم:' : ($lang === 'fr' ? 'Nom:' : 'Name:')) . "</strong> " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . "</p>"
        . "<p><strong>" . ($lang === 'ar' ? 'البريد الإلكتروني:' : ($lang === 'fr' ? 'Email:' : 'Email:')) . "</strong> " . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . "</p>"
        . "<p><strong>" . ($lang === 'ar' ? 'الموضوع:' : ($lang === 'fr' ? 'Sujet:' : 'Subject:')) . "</strong> " . htmlspecialchars($subject, ENT_QUOTES, 'UTF-8') . "</p>"
        . "<p><strong>" . ($lang === 'ar' ? 'الرسالة:' : ($lang === 'fr' ? 'Message:' : 'Message:')) . "</strong></p>"
        . "<p>" . nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')) . "</p>"
        . "<p><strong>" . ($lang === 'ar' ? 'عنوان IP:' : ($lang === 'fr' ? 'Adresse IP:' : 'IP Address:')) . "</strong> " . htmlspecialchars($ip_address, ENT_QUOTES, 'UTF-8') . "</p>";
    
    sendHtmlEmail($admin_subject, $admin_body, $admin_email);

    set_flash_message('success', $t['success_contact_sent']);
    header('Location: contact.php?lang=' . $lang);
    exit;

} catch (PDOException $e) {
    error_log('Contact form error: ' . $e->getMessage());
    set_flash_message('error', $t['error_database']);
    header('Location: contact.php?lang=' . $lang);
    exit;
}
?>
