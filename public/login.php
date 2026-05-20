<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/middleware.php';

redirect_if_logged_in();

if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar','fr','en'])) $_SESSION['lang'] = $_GET['lang'];
$lang = $_SESSION['lang'] ?? 'ar';
$t    = require __DIR__ . '/../lang/' . $lang . '.php';
$dir  = $lang === 'ar' ? 'rtl' : 'ltr';
$logoExists = file_exists(__DIR__ . '/../assets/images/logo.png');
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($t['login_title']) ?> — <?= htmlspecialchars($t['site_name']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="auth-page">

    <div class="auth-container">
        <div class="auth-card">

            <!-- Logo -->
            <div class="auth-logo">
                <?php if ($logoExists): ?>
                <img src="../assets/images/logo.png" alt="<?= htmlspecialchars($t['site_name']) ?>" class="auth-logo-img">
                <?php else: ?>
                <div class="logo-circle"><i class="fas fa-plane"></i></div>
                <?php endif; ?>
                <h2><?= htmlspecialchars($t['site_name']) ?></h2>
                <span class="logo-tagline"><?= htmlspecialchars($t['site_tagline']) ?></span>
            </div>

            <h3 class="auth-title"><?= htmlspecialchars($t['login_title']) ?></h3>
            <p class="auth-subtitle"><?= htmlspecialchars($t['login_subtitle']) ?></p>

            <?= display_flash_messages() ?>

            <form action="../auth/login_process.php" method="POST" id="loginForm" novalidate>
                <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">

                <div class="form-group">
                    <label for="email"><?= htmlspecialchars($t['login_email']) ?></label>
                    <div class="input-group">
                        <input type="email" id="email" name="email" class="form-control"
                               placeholder="name@example.com"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                               required autocomplete="email">
                        <span class="input-icon"><i class="fas fa-envelope"></i></span>
                    </div>
                </div>

                <div class="form-group">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                        <label for="password" style="margin:0;"><?= htmlspecialchars($t['login_password']) ?></label>
                        <a href="#" style="font-size:.82rem;color:var(--text-light);"><?= htmlspecialchars($t['login_forgot']) ?></a>
                    </div>
                    <div class="input-group">
                        <input type="password" id="password" name="password" class="form-control"
                               placeholder="••••••••" required autocomplete="current-password">
                        <button type="button" class="toggle-password" tabindex="-1">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:20px;">
                    <label class="form-check">
                        <input type="checkbox" name="remember" value="1">
                        <span><?= htmlspecialchars($t['login_remember']) ?></span>
                    </label>
                </div>

                <button type="submit" class="btn btn-dark btn-block" style="padding:12px;font-size:.95rem;">
                    <i class="fas fa-sign-in-alt"></i>
                    <?= htmlspecialchars($t['login_btn']) ?>
                </button>
            </form>

            <div class="auth-links">
                <?= htmlspecialchars($t['login_no_account']) ?>
                <a href="register.php?lang=<?= $lang ?>"><?= htmlspecialchars($t['login_register_link']) ?></a>
            </div>

            <!-- Language switcher -->
            <div style="text-align:center;margin-top:14px;">
                <div style="display:inline-flex;background:rgba(13,59,102,.07);border-radius:50px;padding:3px;gap:2px;">
                    <?php foreach (['ar','fr','en'] as $lc): ?>
                    <a href="?lang=<?= $lc ?>"
                       style="font-size:.74rem;padding:4px 10px;border-radius:50px;font-weight:600;color:<?= $lang===$lc?'white':'var(--text-medium)' ?>;background:<?= $lang===$lc?'var(--primary)':'transparent' ?>;">
                        <?= htmlspecialchars($t['lang_'.$lc]) ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div style="text-align:center;margin-top:14px;padding-bottom:8px;">
            <a href="pages/home.php" style="color:rgba(255,255,255,.65);font-size:.84rem;">
                <i class="fas fa-arrow-<?= $dir==='rtl'?'right':'left' ?>"></i>
                <?= $lang==='ar'?'العودة إلى الموقع':($lang==='fr'?'Retour au site':'Back to site') ?>
            </a>
        </div>
    </div>

    <script src="../assets/js/main.js"></script>
</body>
</html>
