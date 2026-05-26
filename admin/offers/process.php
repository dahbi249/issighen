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
    header('Location: create.php?lang=' . $lang);
    exit;
}

// Get form data
$offer_id = (int)($_POST['offer_id'] ?? 0);
$is_edit = $offer_id > 0;

$title_ar = sanitize_input($_POST['title_ar'] ?? '');
$title_fr = sanitize_input($_POST['title_fr'] ?? '');
$title_en = sanitize_input($_POST['title_en'] ?? '');
$desc_ar = sanitize_input($_POST['desc_ar'] ?? '');
$desc_fr = sanitize_input($_POST['desc_fr'] ?? '');
$desc_en = sanitize_input($_POST['desc_en'] ?? '');
$category = sanitize_input($_POST['category'] ?? '');
$price = (float)($_POST['price'] ?? 0);
$days = (int)($_POST['days'] ?? 0);
$location = sanitize_input($_POST['location'] ?? '');
$status = sanitize_input($_POST['status'] ?? 'active');
$featured = isset($_POST['featured']) ? 1 : 0;

$errors = [];

// Validate titles
if (empty($title_ar)) $errors[] = $t['error_offer_title_required'] . ' (Arabic)';
if (empty($title_fr)) $errors[] = $t['error_offer_title_required'] . ' (French)';
if (empty($title_en)) $errors[] = $t['error_offer_title_required'] . ' (English)';

// Validate descriptions
if (empty($desc_ar)) $errors[] = $t['error_offer_description_required'] . ' (Arabic)';
if (empty($desc_fr)) $errors[] = $t['error_offer_description_required'] . ' (French)';
if (empty($desc_en)) $errors[] = $t['error_offer_description_required'] . ' (English)';

// Validate category
if (empty($category)) $errors[] = $t['error_offer_category_required'];

// Validate price
if (empty($price) || $price <= 0) $errors[] = $t['error_offer_price_required'];
if ($price < 0) $errors[] = $t['error_offer_price_invalid'];

// Validate days
if (empty($days) || $days <= 0) $errors[] = $t['error_offer_days_required'];
if ($days < 1) $errors[] = $t['error_offer_days_invalid'];

// Validate location
if (empty($location)) $errors[] = $t['error_offer_location_required'];

// Handle image upload
$image_filename = null;
if (!empty($_FILES['image']['name'])) {
    $image_file = $_FILES['image'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5MB

    // Validate file type
    if (!in_array($image_file['type'], $allowed_types)) {
        $errors[] = $t['error_invalid_file_type'];
    }

    // Validate file size
    if ($image_file['size'] > $max_size) {
        $errors[] = $t['error_file_too_large'];
    }

    // If no errors, process upload
    if (empty($errors)) {
        $upload_dir = __DIR__ . '/../../uploads/offers/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_ext = strtolower(pathinfo($image_file['name'], PATHINFO_EXTENSION));
        $image_filename = uniqid('offer_') . '.' . $file_ext;
        $upload_path = $upload_dir . $image_filename;

        if (!move_uploaded_file($image_file['tmp_name'], $upload_path)) {
            $errors[] = $t['error_database'];
        }
    }
} elseif (!$is_edit) {
    // Image is required for new offers
    $errors[] = $t['error_offer_image_required'];
}

// If there are errors, show them and redirect back
if (!empty($errors)) {
    set_flash_message('error', implode('<br>', $errors));
    if ($is_edit) {
        header('Location: edit.php?id=' . $offer_id . '&lang=' . $lang);
    } else {
        header('Location: create.php?lang=' . $lang);
    }
    exit;
}

try {
    if ($is_edit) {
        // Get existing offer to preserve image if not changed
        $stmt = $pdo->prepare("SELECT image FROM offers WHERE id = ?");
        $stmt->execute([$offer_id]);
        $existing = $stmt->fetch();

        if (!$existing) {
            set_flash_message('error', $t['error_not_found']);
            header('Location: index.php?lang=' . $lang);
            exit;
        }

        $final_image = $image_filename ?? $existing['image'];

        // Update offer
        $stmt = $pdo->prepare("
            UPDATE offers 
            SET title_ar = ?, title_fr = ?, title_en = ?, 
                description_ar = ?, description_fr = ?, description_en = ?,
                category = ?, price = ?, days = ?, location = ?, 
                status = ?, featured = ?, image = ?, updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->execute([
            $title_ar, $title_fr, $title_en,
            $desc_ar, $desc_fr, $desc_en,
            $category, $price, $days, $location,
            $status, $featured, $final_image, $offer_id
        ]);

        set_flash_message('success', $t['success_offer_updated']);
        header('Location: index.php?lang=' . $lang);
    } else {
        // Add new offer
        $stmt = $pdo->prepare("
            INSERT INTO offers (title_ar, title_fr, title_en, description_ar, description_fr, description_en,
                category, price, days, location, status, featured, image, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->execute([
            $title_ar, $title_fr, $title_en,
            $desc_ar, $desc_fr, $desc_en,
            $category, $price, $days, $location,
            $status, $featured, $image_filename
        ]);

        set_flash_message('success', $t['success_offer_created']);
        header('Location: index.php?lang=' . $lang);
    }
    exit;

} catch (PDOException $e) {
    error_log('Offer form error: ' . $e->getMessage());
    if ($is_edit) {
        set_flash_message('error', $t['error_offer_update_failed']);
        header('Location: edit.php?id=' . $offer_id . '&lang=' . $lang);
    } else {
        set_flash_message('error', $t['error_offer_add_failed']);
        header('Location: create.php?lang=' . $lang);
    }
    exit;
}
?>
