<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/middleware.php';
require_role([2, 3]);

$id   = (int)($_GET['id'] ?? 0);
$lang = $_GET['lang'] ?? ($_SESSION['lang'] ?? 'ar');
header('Location: create.php?id=' . $id . '&lang=' . $lang);
exit;
