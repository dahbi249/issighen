<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/middleware.php';
require_admin();
if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar','fr','en'])) $_SESSION['lang'] = $_GET['lang'];
$lang = $_SESSION['lang'] ?? 'ar';
$t    = require __DIR__ . '/../../lang/' . $lang . '.php';
$dir  = $lang === 'ar' ? 'rtl' : 'ltr';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang==='ar'?'تعديل الصفحة':($lang==='fr'?'Modifier la page':'Edit Page') ?> — <?= htmlspecialchars($t['site_name']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
<div class="admin-layout">
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-header">
            <div style="width:36px;height:36px;background:var(--gold);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--primary);font-weight:900;font-size:1rem;flex-shrink:0;">✈</div>
            <div class="sidebar-logo"><?= htmlspecialchars($t['site_name']) ?><br><span class="admin-role-badge"><?= $lang==='ar'?'لوحة التحكم':($lang==='fr'?'Panneau Admin':'Admin Panel') ?></span></div>
        </div>
        <nav class="sidebar-nav">
            <a href="../dashboard.php?lang=<?= $lang ?>"><span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span><?= $lang==='ar'?'لوحة التحكم':($lang==='fr'?'Tableau de bord':'Dashboard') ?></a>
            <a href="../reservations/index.php?lang=<?= $lang ?>"><span class="nav-icon"><i class="fas fa-calendar-check"></i></span><?= $lang==='ar'?'الحجوزات':($lang==='fr'?'Réservations':'Reservations') ?></a>
            <a href="../offers/index.php?lang=<?= $lang ?>"><span class="nav-icon"><i class="fas fa-compass"></i></span><?= $lang==='ar'?'العروض':($lang==='fr'?'Offres':'Offers') ?></a>
            <a href="../users/index.php?lang=<?= $lang ?>"><span class="nav-icon"><i class="fas fa-users"></i></span><?= $lang==='ar'?'المستخدمون':($lang==='fr'?'Utilisateurs':'Users') ?></a>
            <a href="../contacts/index.php?lang=<?= $lang ?>"><span class="nav-icon"><i class="fas fa-envelope"></i></span><?= $lang==='ar'?'الرسائل':($lang==='fr'?'Messages':'Messages') ?></a>
        </nav>
        <div class="sidebar-footer">
            <a href="../../auth/logout.php"><i class="fas fa-sign-out-alt"></i><?= htmlspecialchars($t['nav_logout']) ?></a>
        </div>
    </aside>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <main class="admin-main">
        <div class="admin-topbar">
            <div style="display:flex;align-items:center;gap:12px;">
                <button id="sidebarToggle" class="sidebar-toggle-btn"><i class="fas fa-bars"></i></button>
                <h1 style="font-size:1.3rem;color:var(--primary);margin:0;"><?= $lang==='ar'?'تعديل محتوى الصفحة':($lang==='fr'?'Modifier le contenu':'Edit Page Content') ?></h1>
            </div>
            <a href="../dashboard.php?lang=<?= $lang ?>" class="btn btn-outline btn-sm">
                <i class="fas fa-arrow-<?= $dir==='rtl'?'right':'left' ?>"></i> <?= htmlspecialchars($t['btn_back'] ?? 'Back') ?>
            </a>
        </div>
        <?= display_flash_messages() ?>

        <div style="max-width:700px;">
            <div class="card">
                <div class="card-header"><h3><?= $lang==='ar'?'محتوى الصفحة':($lang==='fr'?'Contenu de la page':'Page Content') ?></h3></div>
                <div class="card-body">
                    <form method="POST" action="#" novalidate>
                        <?php if (function_exists('generate_csrf_token')): ?>
                        <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                        <?php endif; ?>
                        <div class="form-group">
                            <label><?= $lang==='ar'?'الصفحة':($lang==='fr'?'Page':'Page') ?></label>
                            <select name="page_key" class="form-control">
                                <option value="about"><?= $lang==='ar'?'من نحن':($lang==='fr'?'À propos':'About') ?></option>
                                <option value="contact"><?= $lang==='ar'?'تواصل معنا':($lang==='fr'?'Contact':'Contact') ?></option>
                                <option value="home_hero"><?= $lang==='ar'?'البانر الرئيسي':($lang==='fr'?'Bannière principale':'Home Hero') ?></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?= $lang==='ar'?'العنوان (عربي)':($lang==='fr'?'Titre (Arabe)':'Title (Arabic)') ?></label>
                            <input type="text" name="title_ar" class="form-control" dir="rtl">
                        </div>
                        <div class="form-group">
                            <label><?= $lang==='ar'?'العنوان (فرنسي)':($lang==='fr'?'Titre (Français)':'Title (French)') ?></label>
                            <input type="text" name="title_fr" class="form-control">
                        </div>
                        <div class="form-group">
                            <label><?= $lang==='ar'?'العنوان (إنجليزي)':($lang==='fr'?'Titre (Anglais)':'Title (English)') ?></label>
                            <input type="text" name="title_en" class="form-control">
                        </div>
                        <div class="form-group">
                            <label><?= $lang==='ar'?'المحتوى (عربي)':($lang==='fr'?'Contenu (Arabe)':'Content (Arabic)') ?></label>
                            <textarea name="content_ar" class="form-control" rows="6" dir="rtl"></textarea>
                        </div>
                        <div class="form-group">
                            <label><?= $lang==='ar'?'المحتوى (فرنسي)':($lang==='fr'?'Contenu (Français)':'Content (French)') ?></label>
                            <textarea name="content_fr" class="form-control" rows="6"></textarea>
                        </div>
                        <div class="form-group">
                            <label><?= $lang==='ar'?'المحتوى (إنجليزي)':($lang==='fr'?'Contenu (Anglais)':'Content (English)') ?></label>
                            <textarea name="content_en" class="form-control" rows="6"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="padding:12px 32px;">
                            <i class="fas fa-save"></i> <?= $lang==='ar'?'حفظ التغييرات':($lang==='fr'?'Enregistrer':'Save Changes') ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
<script src="../../assets/js/main.js"></script>
<script>
document.getElementById('sidebarToggle')?.addEventListener('click',function(){
    document.getElementById('adminSidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('active');
});
document.getElementById('sidebarOverlay')?.addEventListener('click',function(){
    document.getElementById('adminSidebar').classList.remove('open');
    this.classList.remove('active');
});
</script>
</body>
</html>
