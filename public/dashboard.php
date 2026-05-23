<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/middleware.php';

require_login();

if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar', 'fr', 'en'])) $_SESSION['lang'] = $_GET['lang'];
$lang = $_SESSION['lang'] ?? 'ar';
$t    = require __DIR__ . '/../lang/' . $lang . '.php';
$dir  = $lang === 'ar' ? 'rtl' : 'ltr';
$userName = htmlspecialchars($_SESSION['name'] ?? '');
$userInitial = strtoupper(mb_substr($_SESSION['name'] ?? 'U', 0, 1));
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($t['dashboard_title']) ?> — <?= htmlspecialchars($t['site_name']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="dashboard-layout">

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo-circle" style="width:36px;height:36px;background:var(--gold);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--primary);font-weight:900;font-size:1rem;flex-shrink:0;">✈</div>
                <div class="sidebar-logo"><?= htmlspecialchars($t['site_name']) ?><br><span style="font-size:.7rem;font-weight:400;color:rgba(255,255,255,.5);"><?= htmlspecialchars($t['site_tagline']) ?></span></div>
            </div>
            <nav class="sidebar-nav">
                <div class="sidebar-section-title"><?= $lang === 'ar' ? 'القائمة الرئيسية' : ($lang === 'fr' ? 'Menu Principal' : 'Main Menu') ?></div>
                <a href="dashboard.php?lang=<?= $lang ?>" class="active">
                    <span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span>
                    <?= htmlspecialchars($t['dashboard_title']) ?>
                </a>
                <a href="user/reservations.php?lang=<?= $lang ?>">
                    <span class="nav-icon"><i class="fas fa-calendar-check"></i></span>
                    <?= htmlspecialchars($t['dashboard_my_reservations']) ?>
                </a>
                <a href="user/profile.php?lang=<?= $lang ?>">
                    <span class="nav-icon"><i class="fas fa-user-circle"></i></span>
                    <?= htmlspecialchars($t['dashboard_my_profile']) ?>
                </a>
                <a href="user/travelers.php?lang=<?= $lang ?>">
                    <span class="nav-icon"><i class="fas fa-users"></i></span>
                    <?= htmlspecialchars($t['dashboard_my_travelers']) ?>
                </a>
                <div class="sidebar-section-title"><?= $lang === 'ar' ? 'تصفح' : ($lang === 'fr' ? 'Parcourir' : 'Browse') ?></div>
                <a href="pages/offers.php?lang=<?= $lang ?>">
                    <span class="nav-icon"><i class="fas fa-compass"></i></span>
                    <?= htmlspecialchars($t['nav_offers']) ?>
                </a>
                <a href="pages/contact.php?lang=<?= $lang ?>">
                    <span class="nav-icon"><i class="fas fa-headset"></i></span>
                    <?= htmlspecialchars($t['nav_contact']) ?>
                </a>
            </nav>
            <div class="sidebar-footer">
                <!-- Language -->
                <div style="display:flex;gap:4px;padding:8px 14px;">
                    <?php foreach (['ar', 'fr', 'en'] as $lc): ?>
                        <a href="?lang=<?= $lc ?>" style="font-size:.72rem;padding:3px 8px;border-radius:50px;font-weight:600;color:<?= $lang === $lc ? 'white' : 'rgba(255,255,255,.5)' ?>;background:<?= $lang === $lc ? 'rgba(255,255,255,.18)' : 'transparent' ?>;">
                            <?= strtoupper($lc) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                <a href="/issighen/public/auth/logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <?= htmlspecialchars($t['nav_logout']) ?>
                </a>
            </div>
        </aside>
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Main -->
        <main class="main-content">
            <!-- Topbar -->
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:12px;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <button id="sidebarToggle" style="display:none;background:none;border:none;font-size:1.4rem;color:var(--primary);cursor:pointer;">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div>
                        <h1 style="font-size:1.5rem;color:var(--primary);margin-bottom:2px;"><?= htmlspecialchars($t['dashboard_welcome']) ?>, <?= $userName ?>!</h1>
                        <p style="font-size:.84rem;color:var(--text-light);"><?= date('l, j F Y') ?></p>
                    </div>
                </div>
                <a href="pages/offers.php?lang=<?= $lang ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> <?= htmlspecialchars($t['dashboard_new_booking']) ?>
                </a>
            </div>

            <?= display_flash_messages() ?>

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-info">
                        <div class="number">0</div>
                        <div class="label"><?= htmlspecialchars($t['dashboard_total_bookings']) ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon gold"><i class="fas fa-clock"></i></div>
                    <div class="stat-info">
                        <div class="number">0</div>
                        <div class="label"><?= htmlspecialchars($t['dashboard_pending']) ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-info">
                        <div class="number">0</div>
                        <div class="label"><?= htmlspecialchars($t['dashboard_confirmed']) ?></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red"><i class="fas fa-times-circle"></i></div>
                    <div class="stat-info">
                        <div class="number">0</div>
                        <div class="label"><?= htmlspecialchars($t['dashboard_cancelled']) ?></div>
                    </div>
                </div>
            </div>

            <!-- Quick actions -->
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-bottom:28px;">
                <a href="user/reservations.php?lang=<?= $lang ?>" class="card" style="padding:22px;text-align:center;text-decoration:none;transition:var(--transition);" onmouseover="this.style.boxShadow='0 8px 24px rgba(13,59,102,.12)'" onmouseout="this.style.boxShadow=''">
                    <div style="font-size:2rem;margin-bottom:10px;">📋</div>
                    <div style="font-weight:700;color:var(--primary);"><?= htmlspecialchars($t['dashboard_my_reservations']) ?></div>
                    <div style="font-size:.8rem;color:var(--text-light);margin-top:4px;"><?= $lang === 'ar' ? 'عرض جميع حجوزاتي' : ($lang === 'fr' ? 'Voir mes réservations' : 'View all my bookings') ?></div>
                </a>
                <a href="user/profile.php?lang=<?= $lang ?>" class="card" style="padding:22px;text-align:center;text-decoration:none;transition:var(--transition);" onmouseover="this.style.boxShadow='0 8px 24px rgba(13,59,102,.12)'" onmouseout="this.style.boxShadow=''">
                    <div style="font-size:2rem;margin-bottom:10px;">👤</div>
                    <div style="font-weight:700;color:var(--primary);"><?= htmlspecialchars($t['dashboard_my_profile']) ?></div>
                    <div style="font-size:.8rem;color:var(--text-light);margin-top:4px;"><?= $lang === 'ar' ? 'تعديل بياناتي الشخصية' : ($lang === 'fr' ? 'Modifier mes informations' : 'Edit my personal info') ?></div>
                </a>
                <a href="user/travelers.php?lang=<?= $lang ?>" class="card" style="padding:22px;text-align:center;text-decoration:none;transition:var(--transition);" onmouseover="this.style.boxShadow='0 8px 24px rgba(13,59,102,.12)'" onmouseout="this.style.boxShadow=''">
                    <div style="font-size:2rem;margin-bottom:10px;">👨‍👩‍👧</div>
                    <div style="font-weight:700;color:var(--primary);"><?= htmlspecialchars($t['dashboard_my_travelers']) ?></div>
                    <div style="font-size:.8rem;color:var(--text-light);margin-top:4px;"><?= $lang === 'ar' ? 'إدارة أفراد العائلة' : ($lang === 'fr' ? 'Gérer les voyageurs' : 'Manage family members') ?></div>
                </a>
            </div>

            <!-- Recent Reservations -->
            <div class="card">
                <div class="card-header">
                    <h3><?= htmlspecialchars($t['dashboard_my_reservations']) ?></h3>
                    <a href="user/reservations.php?lang=<?= $lang ?>" class="btn btn-outline btn-sm"><?= htmlspecialchars($t['btn_view']) ?></a>
                </div>
                <div class="card-body">
                    <div class="empty-state">
                        <span class="empty-icon">📭</span>
                        <h3><?= htmlspecialchars($t['dashboard_no_reservations']) ?></h3>
                        <p><?= $lang === 'ar' ? 'ابدأ بحجز رحلتك الأولى معنا' : ($lang === 'fr' ? 'Commencez par réserver votre premier voyage' : 'Start by booking your first trip with us') ?></p>
                        <a href="pages/offers.php?lang=<?= $lang ?>" class="btn btn-dark btn-sm" style="margin-top:16px;">
                            <i class="fas fa-compass"></i> <?= htmlspecialchars($t['hero_cta']) ?>
                        </a>
                    </div>
                </div>
            </div>

        </main>
    </div>
    <script src="../assets/js/main.js"></script>
    <script>
        // Show sidebar toggle on mobile
        if (window.innerWidth < 900) document.getElementById('sidebarToggle').style.display = 'flex';
        window.addEventListener('resize', function() {
            document.getElementById('sidebarToggle').style.display = window.innerWidth < 900 ? 'flex' : 'none';
        });
    </script>
</body>

</html>