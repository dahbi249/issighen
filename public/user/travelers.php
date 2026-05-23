<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/middleware.php';
require_login();
if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar', 'fr', 'en'])) $_SESSION['lang'] = $_GET['lang'];
$lang = $_SESSION['lang'] ?? 'ar';
$t    = require __DIR__ . '/../../lang/' . $lang . '.php';
$dir  = $lang === 'ar' ? 'rtl' : 'ltr';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($t['travelers_title']) ?> — <?= htmlspecialchars($t['site_name']) ?></title>
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
                <a href="reservations.php?lang=<?= $lang ?>"><span class="nav-icon"><i class="fas fa-calendar-check"></i></span><?= htmlspecialchars($t['dashboard_my_reservations']) ?></a>
                <a href="profile.php?lang=<?= $lang ?>"><span class="nav-icon"><i class="fas fa-user-circle"></i></span><?= htmlspecialchars($t['dashboard_my_profile']) ?></a>
                <a href="travelers.php?lang=<?= $lang ?>" class="active"><span class="nav-icon"><i class="fas fa-users"></i></span><?= htmlspecialchars($t['dashboard_my_travelers']) ?></a>
                <a href="../pages/offers.php?lang=<?= $lang ?>"><span class="nav-icon"><i class="fas fa-compass"></i></span><?= htmlspecialchars($t['nav_offers']) ?></a>
            </nav>
            <div class="sidebar-footer">
                <a href="/issighen/public/auth/logout.php"><i class="fas fa-sign-out-alt"></i><?= htmlspecialchars($t['nav_logout']) ?></a>
            </div>
        </aside>
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        <main class="main-content">
            <div class="page-header">
                <div class="page-header-text">
                    <h1><?= htmlspecialchars($t['travelers_title']) ?></h1>
                    <p style="font-size:.85rem;color:var(--text-light);">
                        <?= $lang === 'ar' ? 'أضف أفراد عائلتك لتسهيل الحجز التلقائي' : ($lang === 'fr' ? 'Ajoutez vos proches pour faciliter la réservation automatique' : 'Add your family members for easy auto-booking') ?>
                    </p>
                </div>
                <button class="btn btn-primary btn-sm" data-modal="addTravelerModal">
                    <i class="fas fa-user-plus"></i> <?= htmlspecialchars($t['travelers_add']) ?>
                </button>
            </div>
            <?= display_flash_messages() ?>

            <div class="card">
                <div class="card-body">
                    <div class="empty-state">
                        <span class="empty-icon">👥</span>
                        <h3><?= htmlspecialchars($t['travelers_none']) ?></h3>
                        <p><?= $lang === 'ar' ? 'أضف أفراد عائلتك لتسريع عملية الحجز.' : ($lang === 'fr' ? 'Ajoutez des membres de votre famille pour accélérer la réservation.' : 'Add family members to speed up the booking process.') ?></p>
                        <button class="btn btn-dark btn-sm" style="margin-top:16px;" data-modal="addTravelerModal">
                            <i class="fas fa-plus"></i> <?= htmlspecialchars($t['travelers_add']) ?>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Traveler cards (when populated) -->
            <!--
        <div class="traveler-card">
            <div class="traveler-avatar"><i class="fas fa-user"></i></div>
            <div class="traveler-info">
                <div class="traveler-name">Name Here</div>
                <div class="traveler-meta">Passport: AB123456 — Relation: spouse</div>
            </div>
            <div style="display:flex;gap:6px;">
                <button class="btn btn-outline btn-sm"><i class="fas fa-edit"></i></button>
                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
            </div>
        </div>
        -->
        </main>
    </div>

    <!-- Add Traveler Modal -->
    <div class="modal-overlay" id="addTravelerModal">
        <div class="modal">
            <button class="modal-close" data-dismiss="modal"><i class="fas fa-times"></i></button>
            <div class="modal-title"><?= htmlspecialchars($t['travelers_add']) ?></div>
            <div class="modal-subtitle"><?= $lang === 'ar' ? 'أدخل بيانات المرافق' : ($lang === 'fr' ? 'Entrez les informations du voyageur' : 'Enter traveler information') ?></div>
            <form method="POST" action="#" novalidate>
                <?php if (function_exists('generate_csrf_token')): ?>
                    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                <?php endif; ?>
                <div class="form-group">
                    <label><?= htmlspecialchars($t['travelers_name']) ?></label>
                    <input type="text" name="full_name" class="form-control" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label><?= htmlspecialchars($t['travelers_passport']) ?></label>
                        <input type="text" name="passport_number" class="form-control">
                    </div>
                    <div class="form-group">
                        <label><?= htmlspecialchars($t['travelers_birthdate']) ?></label>
                        <input type="date" name="birth_date" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label><?= htmlspecialchars($t['travelers_relation']) ?></label>
                    <select name="relationship" class="form-control">
                        <?php foreach (['spouse', 'son', 'daughter', 'parent', 'other'] as $rel): ?>
                            <option value="<?= $rel ?>"><?= htmlspecialchars($t['rel_' . $rel] ?? $rel) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-dark btn-block" style="padding:12px;">
                    <i class="fas fa-save"></i> <?= htmlspecialchars($t['btn_save']) ?>
                </button>
            </form>
        </div>
    </div>

    <script src="../../assets/js/main.js"></script>
</body>

</html>