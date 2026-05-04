<?php
require_once __DIR__ . '/includes/session.php';

if (isset($_SESSION['user_id'])) {
    header('Location: /tourism-agency/public/dashboard.php');
} else {
    header('Location: /tourism-agency/public/login.php');
}
exit;
?>
