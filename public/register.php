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
    <title><?= htmlspecialchars($t['register_title']) ?> — <?= htmlspecialchars($t['site_name']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>.auth-container { max-width: 560px; }</style>
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

            <h3 class="auth-title"><?= htmlspecialchars($t['register_title']) ?></h3>
            <p class="auth-subtitle"><?= htmlspecialchars($t['register_subtitle']) ?></p>

            <?= display_flash_messages() ?>

            <form action="../auth/register_process.php" method="POST" id="registerForm" novalidate>
                <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">

                <div class="form-group">
                    <label for="name"><?= htmlspecialchars($t['register_name']) ?> <span style="color:var(--error)">*</span></label>
                    <div class="input-group">
                        <input type="text" id="name" name="name" class="form-control"
                               placeholder="<?= $lang==='ar'?'الاسم الكامل':($lang==='fr'?'Votre nom complet':'Your full name') ?>"
                               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                               required minlength="2" autocomplete="name">
                        <span class="input-icon"><i class="fas fa-user"></i></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email"><?= htmlspecialchars($t['register_email']) ?> <span style="color:var(--error)">*</span></label>
                    <div class="input-group">
                        <input type="email" id="email" name="email" class="form-control"
                               placeholder="name@example.com"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                               required autocomplete="email">
                        <span class="input-icon"><i class="fas fa-envelope"></i></span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone"><?= htmlspecialchars($t['register_phone']) ?></label>
                        <div class="input-group">
                            <input type="tel" id="phone" name="phone" class="form-control"
                                   placeholder="+213 555 000 000"
                                   value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                                   autocomplete="tel">
                            <span class="input-icon"><i class="fas fa-phone"></i></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="passport_number"><?= htmlspecialchars($t['register_passport']) ?></label>
                        <div class="input-group">
                            <input type="text" id="passport_number" name="passport_number" class="form-control"
                                   placeholder="AB1234567"
                                   value="<?= htmlspecialchars($_POST['passport_number'] ?? '') ?>">
                            <span class="input-icon"><i class="fas fa-passport"></i></span>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password"><?= htmlspecialchars($t['register_password']) ?> <span style="color:var(--error)">*</span></label>
                        <div class="input-group">
                            <input type="password" id="password" name="password" class="form-control"
                                   placeholder="••••••••" required minlength="8" autocomplete="new-password">
                            <button type="button" class="toggle-password" tabindex="-1">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <span class="input-hint"><?= htmlspecialchars($t['password_hint']) ?></span>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password"><?= htmlspecialchars($t['register_confirm']) ?> <span style="color:var(--error)">*</span></label>
                        <div class="input-group">
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                                   placeholder="••••••••" required minlength="8" autocomplete="new-password">
                            <button type="button" class="toggle-password" tabindex="-1">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-dark btn-block" style="padding:12px;font-size:.95rem;margin-top:4px;">
                    <i class="fas fa-user-plus"></i>
                    <?= htmlspecialchars($t['register_btn']) ?>
                </button>
            </form>

            <div class="auth-links">
                <?= htmlspecialchars($t['register_have_account']) ?>
                <a href="login.php?lang=<?= $lang ?>"><?= htmlspecialchars($t['register_login_link']) ?></a>
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
