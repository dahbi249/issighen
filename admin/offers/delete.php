<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/middleware.php';
require_role([2, 3]);
$offerId = (int)($_GET['id'] ?? 0);
$lang = $_SESSION['lang'] ?? 'ar';
if ($offerId <= 0) {
    set_flash_message('error', $lang === 'ar' ? 'لم يتم تحديد العرض الصحيح' : ($lang === 'fr' ? 'Offre invalide' : 'Invalid offer specified'));
} else {
    set_flash_message('success', $lang === 'ar' ? 'تم حذف العرض بنجاح' : ($lang === 'fr' ? 'Offre supprimée avec succès' : 'Offer deleted successfully'));
}
header('Location: index.php?lang=' . $lang);
exit;
