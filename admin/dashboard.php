<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/middleware.php';
require_role([2, 3]);
if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar', 'fr', 'en'])) $_SESSION['lang'] = $_GET['lang'];
$lang = $_SESSION['lang'] ?? 'ar';
$t    = require __DIR__ . '/../lang/' . $lang . '.php';
$dir  = $lang === 'ar' ? 'rtl' : 'ltr';
$adminName = htmlspecialchars($_SESSION['name'] ?? 'Admin');
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($t['admin_dashboard'] ?? 'Admin Dashboard') ?> — <?= htmlspecialchars($t['site_name']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>
    <div class="admin-layout">

        <!-- Admin Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <div style="width:36px;height:36px;background:var(--gold);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--primary);font-weight:900;font-size:1rem;flex-shrink:0;">✈</div>
                <div class="sidebar-logo">
                    <?= htmlspecialchars($t['site_name']) ?><br>
                    <span class="admin-role-badge"><?= $lang === 'ar' ? 'لوحة التحكم' : ($lang === 'fr' ? 'Panneau Admin' : 'Admin Panel') ?></span>
                </div>
            </div>
            <nav class="sidebar-nav">
                <div class="sidebar-section-title"><?= $lang === 'ar' ? 'الرئيسية' : ($lang === 'fr' ? 'Principal' : 'Main') ?></div>
                <a href="dashboard.php?lang=<?= $lang ?>" class="active">
                    <span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span>
                    <?= htmlspecialchars($t['admin_dashboard'] ?? 'Dashboard') ?>
                </a>
                <div class="sidebar-section-title"><?= $lang === 'ar' ? 'الإدارة' : ($lang === 'fr' ? 'Gestion' : 'Management') ?></div>
                <a href="reservations/index.php?lang=<?= $lang ?>">
                    <span class="nav-icon"><i class="fas fa-calendar-check"></i></span>
                    <?= $lang === 'ar' ? 'الحجوزات' : ($lang === 'fr' ? 'Réservations' : 'Reservations') ?>
                </a>
                <a href="offers/index.php?lang=<?= $lang ?>">
                    <span class="nav-icon"><i class="fas fa-compass"></i></span>
                    <?= $lang === 'ar' ? 'العروض' : ($lang === 'fr' ? 'Offres' : 'Offers') ?>
                </a>
                <a href="users/index.php?lang=<?= $lang ?>">
                    <span class="nav-icon"><i class="fas fa-users"></i></span>
                    <?= $lang === 'ar' ? 'المستخدمون' : ($lang === 'fr' ? 'Utilisateurs' : 'Users') ?>
                </a>
                <a href="contacts/index.php?lang=<?= $lang ?>">
                    <span class="nav-icon"><i class="fas fa-envelope"></i></span>
                    <?= $lang === 'ar' ? 'رسائل التواصل' : ($lang === 'fr' ? 'Messages' : 'Contact Messages') ?>
                </a>
                <a href="payments/index.php?lang=<?= $lang ?>">
                    <span class="nav-icon"><i class="fas fa-wallet"></i></span>
                    <?= $lang === 'ar' ? 'المدفوعات' : ($lang === 'fr' ? 'Paiements' : 'Payments') ?>
                </a>
            </nav>
            <div class="sidebar-footer">
                <div style="display:flex;gap:4px;padding:8px 14px;">
                    <?php foreach (['ar', 'fr', 'en'] as $lc): ?>
                        <a href="?lang=<?= $lc ?>" style="font-size:.72rem;padding:3px 8px;border-radius:50px;font-weight:600;color:<?= $lang === $lc ? 'white' : 'rgba(255,255,255,.5)' ?>;background:<?= $lang === $lc ? 'rgba(255,255,255,.18)' : 'transparent' ?>;">
                            <?= strtoupper($lc) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                <a href="../public/dashboard.php?lang=<?= $lang ?>">
                    <i class="fas fa-external-link-alt"></i>
                    <?= $lang === 'ar' ? 'عرض الموقع' : ($lang === 'fr' ? 'Voir le site' : 'View Site') ?>
                </a>
                <a href="/issighen/public/auth/logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <?= htmlspecialchars($t['nav_logout']) ?>
                </a>
            </div>
        </aside>
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Main -->
        <main class="admin-main">
            <!-- Topbar -->
            <div class="admin-topbar">
                <div style="display:flex;align-items:center;gap:12px;">
                    <button id="sidebarToggle" class="sidebar-toggle-btn">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div>
                        <h1 style="font-size:1.3rem;color:var(--primary);margin:0;"><?= $lang === 'ar' ? 'لوحة تحكم المشرف' : ($lang === 'fr' ? 'Tableau de bord Admin' : 'Admin Dashboard') ?></h1>
                        <p style="font-size:.8rem;color:var(--text-light);margin:0;"><?= date('l, j F Y') ?></p>
                    </div>
                </div>
                <div class="admin-topbar-right">
                    <span style="font-size:.85rem;color:var(--text-medium);">
                        <i class="fas fa-user-shield" style="color:var(--gold);margin-<?= $dir === 'rtl' ? 'left' : 'right' ?>:6px;"></i>
                        <?= $adminName ?>
                    </span>
                </div>
            </div>

            <?= display_flash_messages() ?>

            <!-- Stats -->
            <div class="stats-grid" style="margin-bottom:28px;">
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="fas fa-users"></i></div>
                    <div class="stat-info">
                        <div class="number" data-target="248">0</div>
                        <div class="label"><?= $lang === 'ar' ? 'إجمالي المستخدمين' : ($lang === 'fr' ? 'Total utilisateurs' : 'Total Users') ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon gold"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-info">
                        <div class="number" data-target="47">0</div>
                        <div class="label"><?= $lang === 'ar' ? 'حجوزات جديدة' : ($lang === 'fr' ? 'Nouvelles réservations' : 'New Bookings') ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green"><i class="fas fa-compass"></i></div>
                    <div class="stat-info">
                        <div class="number" data-target="12">0</div>
                        <div class="label"><?= $lang === 'ar' ? 'العروض النشطة' : ($lang === 'fr' ? 'Offres actives' : 'Active Offers') ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red"><i class="fas fa-envelope"></i></div>
                    <div class="stat-info">
                        <div class="number" data-target="8">0</div>
                        <div class="label"><?= $lang === 'ar' ? 'رسائل جديدة' : ($lang === 'fr' ? 'Nouveaux messages' : 'New Messages') ?></div>
                    </div>
                </div>
            </div>

            <div class="admin-grid-2">

                <!-- Recent Reservations -->
                <div class="card">
                    <div class="card-header">
                        <h3><?= $lang === 'ar' ? 'آخر الحجوزات' : ($lang === 'fr' ? 'Dernières réservations' : 'Recent Reservations') ?></h3>
                        <a href="reservations/index.php?lang=<?= $lang ?>" class="btn btn-outline btn-sm"><?= htmlspecialchars($t['btn_view'] ?? 'View') ?></a>
                    </div>
                    <div class="admin-recent-list">
                        <?php
                        $sampleRes = [
                            ['name' => 'محمد أمين', 'offer' => 'برنامج العمرة المميز', 'status' => 'pending', 'date' => '2025-01-15'],
                            ['name' => 'Karim Boudiaf', 'offer' => 'Circuit Sahara', 'status' => 'confirmed', 'date' => '2025-01-14'],
                            ['name' => 'Sara Hamidi', 'offer' => 'Hajj Package', 'status' => 'confirmed', 'date' => '2025-01-13'],
                            ['name' => 'يوسف بن علي', 'offer' => 'رحلة تركيا', 'status' => 'cancelled', 'date' => '2025-01-12'],
                            ['name' => 'Nadia Khelil', 'offer' => 'Omra Ramadan', 'status' => 'pending', 'date' => '2025-01-11'],
                        ];
                        foreach ($sampleRes as $i => $r):
                            $badge = $r['status'] === 'confirmed' ? 'badge-confirmed' : ($r['status'] === 'cancelled' ? 'badge-cancelled' : 'badge-pending');
                            $label = $r['status'] === 'confirmed' ? ($lang === 'ar' ? 'مؤكد' : ($lang === 'fr' ? 'Confirmé' : 'Confirmed')) : ($r['status'] === 'cancelled' ? ($lang === 'ar' ? 'ملغى' : ($lang === 'fr' ? 'Annulé' : 'Cancelled')) : ($lang === 'ar' ? 'قيد الانتظار' : ($lang === 'fr' ? 'En attente' : 'Pending')));
                        ?>
                            <div class="admin-recent-item">
                                <div class="recent-avatar"><?= strtoupper(mb_substr($r['name'], 0, 1)) ?></div>
                                <div class="recent-info">
                                    <div class="recent-name"><?= htmlspecialchars($r['name']) ?></div>
                                    <div class="recent-sub"><?= htmlspecialchars($r['offer']) ?></div>
                                </div>
                                <div style="text-align:<?= $dir === 'rtl' ? 'left' : 'right' ?>;">
                                    <span class="badge <?= $badge ?>"><?= $label ?></span>
                                    <div style="font-size:.75rem;color:var(--text-light);margin-top:4px;"><?= $r['date'] ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Category breakdown -->
                <div class="card">
                    <div class="card-header">
                        <h3><?= $lang === 'ar' ? 'توزيع الحجوزات' : ($lang === 'fr' ? 'Répartition des réservations' : 'Booking Breakdown') ?></h3>
                    </div>
                    <div class="card-body">
                        <?php
                        $cats = [
                            ['label' => $lang === 'ar' ? 'عمرة' : ($lang === 'fr' ? 'Omra' : 'Umrah'), 'pct' => 45, 'color' => 'var(--gold)'],
                            ['label' => $lang === 'ar' ? 'حج' : ($lang === 'fr' ? 'Hajj' : 'Hajj'), 'pct' => 25, 'color' => 'var(--primary)'],
                            ['label' => $lang === 'ar' ? 'سياحة' : ($lang === 'fr' ? 'Tourisme' : 'Tourism'), 'pct' => 20, 'color' => '#22c55e'],
                            ['label' => $lang === 'ar' ? 'أخرى' : ($lang === 'fr' ? 'Autres' : 'Other'), 'pct' => 10, 'color' => '#94a3b8'],
                        ];
                        foreach ($cats as $cat):
                        ?>
                            <div class="progress-item">
                                <div class="progress-header">
                                    <span><?= $cat['label'] ?></span>
                                    <span><?= $cat['pct'] ?>%</span>
                                </div>
                                <div class="progress-bar-track">
                                    <div class="progress-bar-fill" style="width:<?= $cat['pct'] ?>%;background:<?= $cat['color'] ?>;"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>

            <!-- Quick actions row -->
            <div class="admin-quick-actions" style="margin-top:24px;">
                <a href="offers/create.php?lang=<?= $lang ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> <?= $lang === 'ar' ? 'إضافة عرض جديد' : ($lang === 'fr' ? 'Ajouter une offre' : 'Add New Offer') ?>
                </a>
                <a href="reservations/index.php?lang=<?= $lang ?>" class="btn btn-outline">
                    <i class="fas fa-list"></i> <?= $lang === 'ar' ? 'إدارة الحجوزات' : ($lang === 'fr' ? 'Gérer les réservations' : 'Manage Reservations') ?>
                </a>
                <a href="users/index.php?lang=<?= $lang ?>" class="btn btn-outline">
                    <i class="fas fa-users-cog"></i> <?= $lang === 'ar' ? 'إدارة المستخدمين' : ($lang === 'fr' ? 'Gérer les utilisateurs' : 'Manage Users') ?>
                </a>
                <a href="contacts/index.php?lang=<?= $lang ?>" class="btn btn-outline">
                    <i class="fas fa-inbox"></i> <?= $lang === 'ar' ? 'عرض الرسائل' : ($lang === 'fr' ? 'Voir les messages' : 'View Messages') ?>
                </a>
            </div>

        </main>
    </div>
    <script src="../assets/js/main.js"></script>
    <script>
        if (window.innerWidth < 900) document.getElementById('sidebarToggle').style.display = 'flex';
        window.addEventListener('resize', function() {
            document.getElementById('sidebarToggle').style.display = window.innerWidth < 900 ? 'flex' : 'none';
        });
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('adminSidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        });
        document.getElementById('sidebarOverlay')?.addEventListener('click', function() {
            document.getElementById('adminSidebar').classList.remove('open');
            this.classList.remove('active');
        });
    </script>
</body>

</html>