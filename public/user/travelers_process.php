<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/middleware.php';

require_login();

if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar', 'fr', 'en'])) $_SESSION['lang'] = $_GET['lang'];
$lang = $_SESSION['lang'] ?? 'ar';
$t    = require __DIR__ . '/../../lang/' . $lang . '.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: travelers.php?lang=' . $lang);
    exit;
}

// Verify CSRF token
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    set_flash_message('error', $t['error_csrf_token']);
    header('Location: travelers.php?lang=' . $lang);
    exit;
}

// Get current user ID
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    set_flash_message('error', $t['error_unauthorized']);
    header('Location: travelers.php?lang=' . $lang);
    exit;
}

// Get form data
$action = sanitize_input($_POST['action'] ?? 'add');
$traveler_id = (int)($_POST['traveler_id'] ?? 0);
$full_name = sanitize_input($_POST['full_name'] ?? '');
$passport_number = sanitize_input($_POST['passport_number'] ?? '');
$birth_date = sanitize_input($_POST['birth_date'] ?? '');
$relationship = sanitize_input($_POST['relationship'] ?? '');

$errors = [];

// Validate name
if (empty($full_name)) {
    $errors[] = $t['error_traveler_name_required'];
} elseif (strlen($full_name) < 2) {
    $errors[] = $t['error_traveler_name_too_short'];
}

// Validate passport (if provided)
if (!empty($passport_number) && strlen($passport_number) < 5) {
    $errors[] = $t['error_traveler_passport_invalid'];
}

// Validate birth date
if (empty($birth_date)) {
    $errors[] = $t['error_traveler_date_required'];
} else {
    // Validate date format
    $date_obj = DateTime::createFromFormat('Y-m-d', $birth_date);
    if (!$date_obj) {
        $errors[] = $t['error_invalid_date'];
    }
}

// Validate relationship
if (empty($relationship)) {
    $errors[] = $t['error_traveler_relationship_required'];
}

// If there are errors, show them and redirect back
if (!empty($errors)) {
    set_flash_message('error', implode('<br>', $errors));
    header('Location: travelers.php?lang=' . $lang);
    exit;
}

try {
    if ($action === 'edit' && $traveler_id > 0) {
        // Check if traveler belongs to current user
        $stmt = $pdo->prepare("SELECT id FROM travelers WHERE id = ? AND user_id = ?");
        $stmt->execute([$traveler_id, $user_id]);
        if (!$stmt->fetch()) {
            set_flash_message('error', $t['error_unauthorized']);
            header('Location: travelers.php?lang=' . $lang);
            exit;
        }

        // Update traveler
        $stmt = $pdo->prepare("
            UPDATE travelers 
            SET full_name = ?, passport_number = ?, birth_date = ?, relationship = ?, updated_at = NOW()
            WHERE id = ? AND user_id = ?
        ");
        $stmt->execute([$full_name, $passport_number, $birth_date, $relationship, $traveler_id, $user_id]);
        set_flash_message('success', $t['success_traveler_updated']);
    } else {
        // Add new traveler
        $stmt = $pdo->prepare("
            INSERT INTO travelers (user_id, full_name, passport_number, birth_date, relationship, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->execute([$user_id, $full_name, $passport_number, $birth_date, $relationship]);
        set_flash_message('success', $t['success_traveler_added']);
    }

    header('Location: travelers.php?lang=' . $lang);
    exit;
} catch (PDOException $e) {
    error_log('Traveler form error: ' . $e->getMessage());
    if ($action === 'edit') {
        set_flash_message('error', $t['error_traveler_update_failed']);
    } else {
        set_flash_message('error', $t['error_traveler_add_failed']);
    }
    header('Location: travelers.php?lang=' . $lang);
    exit;
}
