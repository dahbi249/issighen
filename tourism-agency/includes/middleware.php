<?php
// includes/middleware.php

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['flash_error'] = "You must be logged in to access this page.";
        header('Location: /tourism-agency/public/login.php');
        exit;
    }
}

function require_role($role_ids) {
    require_login();
    if (!is_array($role_ids)) {
        $role_ids = [$role_ids];
    }
    
    if (!in_array($_SESSION['role_id'], $role_ids)) {
        $_SESSION['flash_error'] = "You do not have permission to access this page.";
        header('Location: /tourism-agency/public/dashboard.php');
        exit;
    }
}

function redirect_if_logged_in() {
    if (isset($_SESSION['user_id'])) {
        header('Location: /tourism-agency/public/dashboard.php');
        exit;
    }
}
?>
