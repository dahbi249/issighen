<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/middleware.php';
require_login();
if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar','fr','en'])) $_SESSION['lang'] = $_GET['lang'];
$lang = $_SESSION['lang'] ?? 'ar';
$t    = require __DIR__ . '/../../lang/' . $lang . '.php';
$dir  = $lang === 'ar' ? 'rtl' : 'ltr';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($t['dashboard_my_reservations']) ?> — <?= htmlspecialchars($t['site_name']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="dashboard-layout">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div style="width:36px;height:36px;background:var(--gold);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--primary);font-weight:900;font-size:1rem;">✈</div>
            <div class="sidebar-logo"><?= htmlspecialchars($t['site_name']) ?></div>
        </div>
        <nav class="sidebar-nav">
            <a href="../dashboard.php?lang=<?= $lang ?>"><span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span><?= htmlspecialchars($t['dashboard_title']) ?></a>
            <a href="reservations.php?lang=<?= $lang ?>" class="active"><span class="nav-icon"><i class="fas fa-calendar-check"></i></span><?= htmlspecialchars($t['dashboard_my_reservations']) ?></a>
            <a href="profile.php?lang=<?= $lang ?>"><span class="nav-icon"><i class="fas fa-user-circle"></i></span><?= htmlspecialchars($t['dashboard_my_profile']) ?></a>
            <a href="travelers.php?lang=<?= $lang ?>"><span class="nav-icon"><i class="fas fa-users"></i></span><?= htmlspecialchars($t['dashboard_my_travelers']) ?></a>
            <a href="../pages/offers.php?lang=<?= $lang ?>"><span class="nav-icon"><i class="fas fa-compass"></i></span><?= htmlspecialchars($t['nav_offers']) ?></a>
        </nav>
        <div class="sidebar-footer">
            <a href="../../auth/logout.php"><i class="fas fa-sign-out-alt"></i><?= htmlspecialchars($t['nav_logout']) ?></a>
        </div>
    </aside>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <main class="main-content">
        <div class="page-header">
            <div class="page-header-text">
                <h1><?= htmlspecialchars($t['dashboard_my_reservations']) ?></h1>
            </div>
            <a href="../pages/offers.php?lang=<?= $lang ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> <?= htmlspecialchars($t['dashboard_new_booking']) ?>
            </a>
        </div>
        <?= display_flash_messages() ?>

        <!-- Filter tabs -->
        <div class="filter-bar">
            <button class="filter-pill active" data-filter="all"><?= htmlspecialchars($t['btn_all']) ?></button>
            <button class="filter-pill" data-filter="pending"><i class="fas fa-clock" style="margin-<?= $dir==='rtl'?'left':'right' ?>:5px;"></i><?= htmlspecialchars($t['status_pending']) ?></button>
            <button class="filter-pill" data-filter="confirmed"><i class="fas fa-check" style="margin-<?= $dir==='rtl'?'left':'right' ?>:5px;"></i><?= htmlspecialchars($t['status_confirmed']) ?></button>
            <button class="filter-pill" data-filter="cancelled"><i class="fas fa-times" style="margin-<?= $dir==='rtl'?'left':'right' ?>:5px;"></i><?= htmlspecialchars($t['status_cancelled']) ?></button>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="empty-state">
                    <span class="empty-icon">📭</span>
                    <h3><?= htmlspecialchars($t['dashboard_no_reservations']) ?></h3>
                    <p><?= $lang==='ar'?'لا توجد حجوزات حتى الآن. ابدأ بالبحث عن عروضنا المميزة.':($lang==='fr'?'Aucune réservation pour le moment. Commencez par explorer nos offres.':'No reservations yet. Start by exploring our special offers.') ?></p>
                    <a href="../pages/offers.php?lang=<?= $lang ?>" class="btn btn-dark btn-sm" style="margin-top:16px;">
                        <i class="fas fa-compass"></i> <?= htmlspecialchars($t['hero_cta']) ?>
                    </a>
                </div>
            </div>
        </div>
        <!-- When populated from DB, show table: -->
        <div class="card">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?= htmlspecialchars($t['nav_offers']) ?></th>
                            <th><?= htmlspecialchars($t['date']) ?></th>
                            <th><?= htmlspecialchars($t['price']) ?></th>
                            <th><?= $lang==='ar'?'الحالة':($lang==='fr'?'Statut':'Status') ?></th>
                            <th><?= htmlspecialchars($t['actions']) ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Package Name</td>
                            <td>2025-01-01</td>
                            <td>85,000 DA</td>
                            <td><span class="badge badge-pending"><?= htmlspecialchars($t['status_pending']) ?></span></td>
                            <td><a href="#" class="btn btn-outline btn-sm"><?= htmlspecialchars($t['btn_view']) ?></a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>
<script src="../../assets/js/main.js"></script>
</body>
</html>
