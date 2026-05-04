<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/middleware.php';

// Requires user to be logged in
require_login();

$role_name = 'User';
if ($_SESSION['role_id'] == 2) $role_name = 'Admin';
if ($_SESSION['role_id'] == 3) $role_name = 'Super Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Tourism Agency</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="dashboard-page">
    <nav class="navbar">
        <div class="nav-brand">Tourism Agency</div>
        <div class="nav-links">
            <span class="user-greeting">Hello, <?= htmlspecialchars($_SESSION['name']) ?> (<?= $role_name ?>)</span>
            <a href="../auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </nav>

    <main class="dashboard-container">
        <?= display_flash_messages() ?>
        
        <header class="dashboard-header">
            <h1>Dashboard</h1>
            <p>Welcome to your control panel.</p>
        </header>

        <div class="dashboard-grid">
            <div class="card">
                <h3>Profile Overview</h3>
                <p>Manage your personal information and preferences.</p>
                <a href="#" class="btn btn-outline">Edit Profile</a>
            </div>
            
            <?php if ($_SESSION['role_id'] >= 2): // Admin or Super Admin ?>
            <div class="card">
                <h3>Admin Controls</h3>
                <p>Manage users, bookings, and system settings.</p>
                <a href="#" class="btn btn-outline">Go to Settings</a>
            </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
