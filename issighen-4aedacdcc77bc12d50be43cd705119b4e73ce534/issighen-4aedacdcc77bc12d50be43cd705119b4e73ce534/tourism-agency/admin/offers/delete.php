<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/middleware.php';
require_admin();
// Placeholder: delete offer by ID and redirect back
$lang = $_SESSION['lang'] ?? 'ar';
set_flash_message('success', $lang==='ar'?'تم حذف العرض بنجاح':($lang==='fr'?'Offre supprimée avec succès':'Offer deleted successfully'));
header('Location: index.php?lang=' . $lang);
exit;
