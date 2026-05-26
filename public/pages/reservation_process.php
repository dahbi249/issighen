<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/send_mailer.php';

if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar', 'fr', 'en'])) $_SESSION['lang'] = $_GET['lang'];
$lang = $_SESSION['lang'] ?? 'ar';
$t    = require __DIR__ . '/../../lang/' . $lang . '.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: offers.php?lang=' . $lang);
    exit;
}

// Verify CSRF token
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    set_flash_message('error', $t['error_csrf_token']);
    header('Location: offers.php?lang=' . $lang);
    exit;
}

// Get form data
$offer_id = (int)($_POST['offer_id'] ?? 0);
$full_name = sanitize_input($_POST['full_name'] ?? '');
$email = sanitize_input($_POST['email'] ?? '');
$phone = sanitize_input($_POST['phone'] ?? '');
$passport_number = sanitize_input($_POST['passport_number'] ?? '');
$travelers_count = (int)($_POST['travelers_count'] ?? 0);
$notes = sanitize_input($_POST['notes'] ?? '');

$errors = [];

// Validate offer ID
if ($offer_id <= 0) {
    $errors[] = $t['error_reservation_offer_required'];
}

// Validate name
if (empty($full_name)) {
    $errors[] = $t['error_reservation_name_required'];
} elseif (strlen($full_name) < 2) {
    $errors[] = $t['error_name_too_short'];
}

// Validate email
if (empty($email)) {
    $errors[] = $t['error_reservation_email_required'];
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = $t['error_invalid_email'];
}

// Validate phone
if (empty($phone)) {
    $errors[] = $t['error_reservation_phone_required'];
} elseif (strlen($phone) < 9) {
    $errors[] = $t['error_invalid_phone'];
}

// Validate travelers count
if ($travelers_count <= 0) {
    $errors[] = $t['error_reservation_travelers_required'];
} elseif ($travelers_count < 1) {
    $errors[] = $t['error_reservation_travelers_invalid'];
}

// If there are errors, show them and redirect back
if (!empty($errors)) {
    set_flash_message('error', implode('<br>', $errors));
    header('Location: offer-detail.php?id=' . $offer_id . '&lang=' . $lang . '#book');
    exit;
}

try {
    // Get offer details
    $stmt = $pdo->prepare("SELECT title_ar, title_en, title_fr, price FROM offers WHERE id = ?");
    $stmt->execute([$offer_id]);
    $offer = $stmt->fetch();

    if (!$offer) {
        set_flash_message('error', $t['error_not_found']);
        header('Location: offers.php?lang=' . $lang);
        exit;
    }

    // Get user ID if logged in
    $user_id = $_SESSION['user_id'] ?? null;

    // Insert reservation into database
    $status = 'pending';
    $total_price = $offer['price'] * $travelers_count;

    $stmt = $pdo->prepare("
        INSERT INTO reservations (user_id, offer_id, full_name, email, phone, passport_number, travelers_count, notes, total_price, status, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");

    $stmt->execute([$user_id, $offer_id, $full_name, $email, $phone, $passport_number, $travelers_count, $notes, $total_price, $status]);

    $reservation_id = $pdo->lastInsertId();

    // Send confirmation email to user
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';

    $offer_title = $offer['title_' . $lang] ?? $offer['title_en'];

    $user_subject = ($lang === 'ar' ? 'تأكيد الحجز' : ($lang === 'fr' ? 'Confirmation de réservation' : 'Booking Confirmation'));

    $user_body = ($lang === 'ar'
        ? "<h3>شكراً على حجزك معنا</h3>"
        . "<p>مرحباً " . htmlspecialchars($full_name, ENT_QUOTES, 'UTF-8') . ",</p>"
        . "<p>تم استقبال طلب الحجز الخاص بك بنجاح.</p>"
        . "<p><strong>تفاصيل الحجز:</strong></p>"
        . "<ul>"
        . "<li>رقم الحجز: " . $reservation_id . "</li>"
        . "<li>العرض: " . htmlspecialchars($offer_title, ENT_QUOTES, 'UTF-8') . "</li>"
        . "<li>عدد المرافقين: " . $travelers_count . "</li>"
        . "<li>السعر الإجمالي: " . number_format($total_price) . " دج</li>"
        . "<li>الحالة: قيد المراجعة</li>"
        . "</ul>"
        . "<p>سيتم التواصل معك قريباً لتأكيد الحجز والحصول على المزيد من المعلومات.</p>"
        . "<p>فريق ايسيغن للسياحة والأسفار</p>"
        : ($lang === 'fr'
            ? "<h3>Merci pour votre réservation</h3>"
            . "<p>Bonjour " . htmlspecialchars($full_name, ENT_QUOTES, 'UTF-8') . ",</p>"
            . "<p>Votre demande de réservation a bien été reçue.</p>"
            . "<p><strong>Détails de la réservation:</strong></p>"
            . "<ul>"
            . "<li>N° de réservation: " . $reservation_id . "</li>"
            . "<li>Offre: " . htmlspecialchars($offer_title, ENT_QUOTES, 'UTF-8') . "</li>"
            . "<li>Nombre de voyageurs: " . $travelers_count . "</li>"
            . "<li>Prix total: " . number_format($total_price) . " DA</li>"
            . "<li>Statut: En attente d'examen</li>"
            . "</ul>"
            . "<p>Nous vous contacterons bientôt pour confirmer votre réservation et plus de détails.</p>"
            . "<p>L'équipe Isighène Voyages</p>"
            : "<h3>Thank you for your booking</h3>"
            . "<p>Hello " . htmlspecialchars($full_name, ENT_QUOTES, 'UTF-8') . ",</p>"
            . "<p>Your booking request has been received successfully.</p>"
            . "<p><strong>Booking Details:</strong></p>"
            . "<ul>"
            . "<li>Booking #: " . $reservation_id . "</li>"
            . "<li>Offer: " . htmlspecialchars($offer_title, ENT_QUOTES, 'UTF-8') . "</li>"
            . "<li>Number of Travelers: " . $travelers_count . "</li>"
            . "<li>Total Price: " . number_format($total_price) . " DA</li>"
            . "<li>Status: Pending Review</li>"
            . "</ul>"
            . "<p>We will contact you soon to confirm your booking and provide more details.</p>"
            . "<p>Isighène Travel & Tourism Team</p>"
        )
    );

    sendHtmlEmail($user_subject, $user_body, $email);

    // Send notification to admin
    $admin_email = 'contact@isighene.com';
    $admin_subject = ($lang === 'ar' ? 'حجز جديد من' : ($lang === 'fr' ? 'Nouvelle réservation de' : 'New Booking from')) . ' ' . htmlspecialchars($full_name, ENT_QUOTES, 'UTF-8');

    $admin_body = "<h3>" . htmlspecialchars(($lang === 'ar' ? 'حجز جديد' : ($lang === 'fr' ? 'Nouvelle réservation' : 'New Booking')), ENT_QUOTES, 'UTF-8') . "</h3>"
        . "<p><strong>" . ($lang === 'ar' ? 'رقم الحجز:' : ($lang === 'fr' ? 'N° de réservation:' : 'Booking #:')) . "</strong> " . $reservation_id . "</p>"
        . "<p><strong>" . ($lang === 'ar' ? 'الاسم:' : ($lang === 'fr' ? 'Nom:' : 'Name:')) . "</strong> " . htmlspecialchars($full_name, ENT_QUOTES, 'UTF-8') . "</p>"
        . "<p><strong>" . ($lang === 'ar' ? 'البريد الإلكتروني:' : ($lang === 'fr' ? 'Email:' : 'Email:')) . "</strong> " . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . "</p>"
        . "<p><strong>" . ($lang === 'ar' ? 'الهاتف:' : ($lang === 'fr' ? 'Téléphone:' : 'Phone:')) . "</strong> " . htmlspecialchars($phone, ENT_QUOTES, 'UTF-8') . "</p>"
        . "<p><strong>" . ($lang === 'ar' ? 'العرض:' : ($lang === 'fr' ? 'Offre:' : 'Offer:')) . "</strong> " . htmlspecialchars($offer_title, ENT_QUOTES, 'UTF-8') . "</p>"
        . "<p><strong>" . ($lang === 'ar' ? 'عدد المرافقين:' : ($lang === 'fr' ? 'Nombre de voyageurs:' : 'Travelers:')) . "</strong> " . $travelers_count . "</p>"
        . "<p><strong>" . ($lang === 'ar' ? 'السعر الإجمالي:' : ($lang === 'fr' ? 'Prix total:' : 'Total Price:')) . "</strong> " . number_format($total_price) . " DA</p>";

    if (!empty($notes)) {
        $admin_body .= "<p><strong>" . ($lang === 'ar' ? 'الملاحظات:' : ($lang === 'fr' ? 'Notes:' : 'Notes:')) . "</strong></p>"
            . "<p>" . nl2br(htmlspecialchars($notes, ENT_QUOTES, 'UTF-8')) . "</p>";
    }

    sendHtmlEmail($admin_subject, $admin_body, $admin_email);

    set_flash_message('success', $t['success_reservation_created']);
    header('Location: offer-detail.php?id=' . $offer_id . '&lang=' . $lang . '#book');
    exit;
} catch (PDOException $e) {
    error_log('Reservation error: ' . $e->getMessage());
    set_flash_message('error', $t['error_reservation_create_failed']);
    header('Location: offer-detail.php?id=' . $offer_id . '&lang=' . $lang . '#book');
    exit;
}
