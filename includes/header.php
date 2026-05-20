<!-- includes/header.php — Public Navbar (variables $lang, $t, $dir must be set by the calling page) -->
<?php
$lang = $lang ?? 'ar';
$t    = $t    ?? [];
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<nav class="navbar" role="navigation">
    <div class="navbar-inner">

        <a href="/tourism-agency/public/pages/home.php" class="navbar-brand">
            <?php
            $logoFile = $_SERVER['DOCUMENT_ROOT'] . '/tourism-agency/assets/images/logo.png';
            if (file_exists($logoFile)): ?>
            <img src="/tourism-agency/assets/images/logo.png" alt="<?= htmlspecialchars($t['site_name'] ?? 'Isighene') ?>" class="navbar-logo-img">
            <?php else: ?>
            <div class="logo-circle">✈</div>
            <?php endif; ?>
            <div class="brand-text">
                <span class="brand-name"><?= htmlspecialchars($t['site_name'] ?? 'Isighene Tours') ?></span>
                <span class="brand-tagline"><?= htmlspecialchars($t['site_tagline'] ?? 'Travel & Tourism') ?></span>
            </div>
        </a>

        <div class="navbar-nav" id="navMenu">
            <a href="/tourism-agency/public/pages/home.php"    class="<?= $currentPage==='home'    ? 'active':'' ?>"><?= htmlspecialchars($t['nav_home']    ?? 'Home') ?></a>
            <a href="/tourism-agency/public/pages/offers.php"  class="<?= $currentPage==='offers'  ? 'active':'' ?>"><?= htmlspecialchars($t['nav_offers']  ?? 'Offers') ?></a>
            <a href="/tourism-agency/public/pages/offers.php?cat=umrah"><?= htmlspecialchars($t['nav_umrah'] ?? 'Umrah & Hajj') ?></a>
            <a href="/tourism-agency/public/pages/offers.php?cat=tourism"><?= htmlspecialchars($t['nav_tourism'] ?? 'Tourism') ?></a>
            <a href="/tourism-agency/public/pages/about.php"   class="<?= $currentPage==='about'   ? 'active':'' ?>"><?= htmlspecialchars($t['nav_about']   ?? 'About') ?></a>
            <a href="/tourism-agency/public/pages/contact.php" class="<?= $currentPage==='contact' ? 'active':'' ?>"><?= htmlspecialchars($t['nav_contact'] ?? 'Contact') ?></a>
        </div>

        <div class="navbar-actions">
            <div class="lang-switcher">
                <a href="?lang=ar" class="<?= $lang==='ar' ? 'active-lang':'' ?>">AR</a>
                <a href="?lang=fr" class="<?= $lang==='fr' ? 'active-lang':'' ?>">FR</a>
                <a href="?lang=en" class="<?= $lang==='en' ? 'active-lang':'' ?>">EN</a>
            </div>

            <?php if (!empty($_SESSION['user_id'])): ?>
                <div class="user-menu-btn" id="userMenuBtn">
                    <div class="user-avatar"><?= strtoupper(mb_substr($_SESSION['name'] ?? 'U', 0, 1)) ?></div>
                    <span class="uname"><?= htmlspecialchars(explode(' ', $_SESSION['name'] ?? '')[0]) ?></span>
                    <i class="fas fa-chevron-down" style="font-size:.72rem;opacity:.7;"></i>
                    <div class="dropdown-menu">
                        <a href="/tourism-agency/public/dashboard.php"><i class="fas fa-tachometer-alt"></i> <?= htmlspecialchars($t['nav_dashboard'] ?? 'Dashboard') ?></a>
                        <a href="/tourism-agency/public/user/profile.php"><i class="fas fa-user-circle"></i> <?= htmlspecialchars($t['nav_profile'] ?? 'Profile') ?></a>
                        <a href="/tourism-agency/public/user/reservations.php"><i class="fas fa-calendar-check"></i> <?= htmlspecialchars($t['nav_reservations'] ?? 'Bookings') ?></a>
                        <?php if (!empty($_SESSION['role_id']) && $_SESSION['role_id'] >= 2): ?>
                        <div class="menu-divider"></div>
                        <a href="/tourism-agency/admin/dashboard.php"><i class="fas fa-cog"></i> Admin Panel</a>
                        <?php endif; ?>
                        <div class="menu-divider"></div>
                        <a href="/tourism-agency/public/auth/logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> <?= htmlspecialchars($t['nav_logout'] ?? 'Logout') ?></a>
                    </div>
                </div>
            <?php else: ?>
                <a href="/tourism-agency/public/login.php"    class="btn-nav-login"    style="padding:8px 16px;border-radius:50px;font-size:.88rem;font-weight:600;"><?= htmlspecialchars($t['nav_login']    ?? 'Login') ?></a>
                <a href="/tourism-agency/public/register.php" class="btn-nav-register" style="padding:8px 18px;border-radius:50px;font-size:.88rem;font-weight:700;"><?= htmlspecialchars($t['nav_register'] ?? 'Register') ?></a>
            <?php endif; ?>

            <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">
                <i class="fas fa-bars"></i>
            </button>
        </div>

    </div>
</nav>
