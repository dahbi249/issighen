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
    <title><?= htmlspecialchars($t['profile_title']) ?> — <?= htmlspecialchars($t['site_name']) ?></title>
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
            <a href="profile.php?lang=<?= $lang ?>" class="active"><span class="nav-icon"><i class="fas fa-user-circle"></i></span><?= htmlspecialchars($t['dashboard_my_profile']) ?></a>
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
                <h1><?= htmlspecialchars($t['profile_title']) ?></h1>
            </div>
        </div>
        <?= display_flash_messages() ?>
        <div class="profile-layout">
            <div class="profile-side-card">
                <div class="profile-avatar"><?= strtoupper(mb_substr($_SESSION['name'] ?? 'U', 0, 1)) ?></div>
                <div class="profile-name"><?= htmlspecialchars($_SESSION['name'] ?? '') ?></div>
                <div class="profile-email"><?= $lang==='ar'?'مستخدم مسجل':($lang==='fr'?'Membre enregistré':'Registered member') ?></div>
                <div class="profile-meta">
                    <strong><?= htmlspecialchars($t['profile_member_since']) ?></strong><br>
                    <?= date('Y') ?>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><h3><?= htmlspecialchars($t['profile_edit']) ?></h3></div>
                <div class="card-body">
                    <form method="POST" action="#" novalidate>
                        <?php if (function_exists('generate_csrf_token')): ?>
                        <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                        <?php endif; ?>
                        <div class="form-row">
                            <div class="form-group">
                                <label><?= htmlspecialchars($t['register_name']) ?></label>
                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($_SESSION['name'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label><?= htmlspecialchars($t['register_email']) ?></label>
                                <input type="email" name="email" class="form-control" placeholder="email@example.com">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label><?= htmlspecialchars($t['profile_phone']) ?></label>
                                <input type="tel" name="phone" class="form-control" placeholder="+213 555 000 000">
                            </div>
                            <div class="form-group">
                                <label><?= htmlspecialchars($t['profile_passport']) ?></label>
                                <input type="text" name="passport_number" class="form-control" placeholder="AB1234567">
                            </div>
                        </div>
                        <hr style="margin:24px 0;border:none;border-top:1px solid var(--border);">
                        <h4 style="color:var(--primary);margin-bottom:16px;font-size:1rem;">
                            <?= $lang==='ar'?'تغيير كلمة المرور':($lang==='fr'?'Changer le mot de passe':'Change Password') ?>
                        </h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label><?= $lang==='ar'?'كلمة المرور الجديدة':($lang==='fr'?'Nouveau mot de passe':'New Password') ?></label>
                                <div class="input-group">
                                    <input type="password" name="new_password" class="form-control" placeholder="••••••••">
                                    <button type="button" class="toggle-password"><i class="fas fa-eye"></i></button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?= htmlspecialchars($t['register_confirm']) ?></label>
                                <div class="input-group">
                                    <input type="password" name="confirm_password" class="form-control" placeholder="••••••••">
                                    <button type="button" class="toggle-password"><i class="fas fa-eye"></i></button>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-dark" style="padding:12px 32px;">
                            <i class="fas fa-save"></i> <?= htmlspecialchars($t['profile_save']) ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
<script src="../../assets/js/main.js"></script>
</body>
</html>
