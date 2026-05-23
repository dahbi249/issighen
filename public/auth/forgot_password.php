<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/middleware.php';

redirect_if_logged_in();

if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar', 'fr', 'en'])) {
    $_SESSION['lang'] = $_GET['lang'];
}
$lang = $_SESSION['lang'] ?? 'ar';
$t = require __DIR__ . '/../../lang/' . $lang . '.php';
$dir = $lang === 'ar' ? 'rtl' : 'ltr';
$logoExists = file_exists(__DIR__ . '/../../assets/images/logo.png');
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($t['forgot_password_title'] ?? 'Forgot Password') ?> — <?= htmlspecialchars($t['site_name']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-logo">
                <?php if ($logoExists): ?>
                    <img src="../../assets/images/logo.png" alt="<?= htmlspecialchars($t['site_name']) ?>" class="auth-logo-img">
                <?php else: ?>
                    <div class="logo-circle"><i class="fas fa-plane"></i></div>
                <?php endif; ?>
                <h2><?= htmlspecialchars($t['site_name']) ?></h2>
                <span class="logo-tagline"><?= htmlspecialchars($t['site_tagline']) ?></span>
            </div>

            <h3 class="auth-title"><?= htmlspecialchars($t['forgot_password_title'] ?? 'Forgot Password') ?></h3>
            <p class="auth-subtitle"><?= htmlspecialchars($t['forgot_password_subtitle'] ?? 'Enter your email to receive a reset link.') ?></p>

            <?= display_flash_messages() ?>

            <form action="forgot_password_process.php" method="POST" id="forgotPasswordForm" novalidate>
                <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">

                <div class="form-group">
                    <label for="email"><?= htmlspecialchars($t['forgot_password_email'] ?? 'Email Address') ?></label>
                    <div class="input-group">
                        <input type="email" id="email" name="email" class="form-control"
                            placeholder="name@example.com"
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                            required autocomplete="email">
                        <span class="input-icon"><i class="fas fa-envelope"></i></span>
                    </div>
                </div>

                <button type="submit" class="btn btn-dark btn-block" style="padding:12px;font-size:.95rem;">
                    <i class="fas fa-envelope-open-text"></i>
                    <?= htmlspecialchars($t['forgot_password_btn'] ?? 'Send Reset Link') ?>
                </button>
            </form>

            <div class="auth-links">
                <a href="login.php?lang=<?= $lang ?>"><?= htmlspecialchars($t['login_back'] ?? 'Back to Login') ?></a>
            </div>

            <div style="text-align:center;margin-top:14px;">
                <div style="display:inline-flex;background:rgba(13,59,102,.07);border-radius:50px;padding:3px;gap:2px;">
                    <?php foreach (['ar', 'fr', 'en'] as $lc): ?>
                        <a href="?lang=<?= $lc ?>"
                            style="font-size:.74rem;padding:4px 10px;border-radius:50px;font-weight:600;color:<?= $lang === $lc ? 'white' : 'var(--text-medium)' ?>;background:<?= $lang === $lc ? 'var(--primary)' : 'transparent' ?>;">
                            <?= htmlspecialchars($t['lang_' . $lc] ?? strtoupper($lc)) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/main.js"></script>
</body>

</html>