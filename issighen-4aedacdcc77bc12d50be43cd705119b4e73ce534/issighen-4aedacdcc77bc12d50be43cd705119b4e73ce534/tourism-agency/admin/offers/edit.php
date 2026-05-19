<?php
// Redirect to create.php with id parameter (reuses the same form)
$id   = (int)($_GET['id'] ?? 0);
$lang = $_GET['lang'] ?? ($_SESSION['lang'] ?? 'ar');
header('Location: create.php?id=' . $id . '&lang=' . $lang);
exit;
