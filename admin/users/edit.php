<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/middleware.php';
require_role([2, 3]);
if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar', 'fr', 'en'])) $_SESSION['lang'] = $_GET['lang'];
$lang = $_SESSION['lang'] ?? 'ar';
$t    = require __DIR__ . '/../../lang/' . $lang . '.php';
$dir  = $lang === 'ar' ? 'rtl' : 'ltr';
$uid  = (int)($_GET['id'] ?? 1);
// Sample user data for display
$user = ['id' => $uid, 'name' => 'محمد أمين بوعلام', 'email' => 'amin@example.com', 'phone' => '0550123456', 'role' => 1];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang === 'ar' ? 'تعديل المستخدم' : ($lang === 'fr' ? 'Modifier utilisateur' : 'Edit User') ?> — <?= htmlspecialchars($t['site_name']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>

<body>
    <div class="admin-layout">
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <div style="width:36px;height:36px;background:var(--gold);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--primary);font-weight:900;font-size:1rem;flex-shrink:0;">✈</div>
                <div class="sidebar-logo"><?= htmlspecialchars($t['site_name']) ?><br><span class="admin-role-badge"><?= $lang === 'ar' ? 'لوحة التحكم' : ($lang === 'fr' ? 'Panneau Admin' : 'Admin Panel') ?></span></div>
            </div>
            <nav class="sidebar-nav">
                <a href="../dashboard.php?lang=<?= $lang ?>"><span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span><?= $lang === 'ar' ? 'لوحة التحكم' : ($lang === 'fr' ? 'Tableau de bord' : 'Dashboard') ?></a>
                <a href="../reservations/index.php?lang=<?= $lang ?>"><span class="nav-icon"><i class="fas fa-calendar-check"></i></span><?= $lang === 'ar' ? 'الحجوزات' : ($lang === 'fr' ? 'Réservations' : 'Reservations') ?></a>
                <a href="../offers/index.php?lang=<?= $lang ?>"><span class="nav-icon"><i class="fas fa-compass"></i></span><?= $lang === 'ar' ? 'العروض' : ($lang === 'fr' ? 'Offres' : 'Offers') ?></a>
                <a href="index.php?lang=<?= $lang ?>" class="active"><span class="nav-icon"><i class="fas fa-users"></i></span><?= $lang === 'ar' ? 'المستخدمون' : ($lang === 'fr' ? 'Utilisateurs' : 'Users') ?></a>
                <a href="../contacts/index.php?lang=<?= $lang ?>"><span class="nav-icon"><i class="fas fa-envelope"></i></span><?= $lang === 'ar' ? 'الرسائل' : ($lang === 'fr' ? 'Messages' : 'Messages') ?></a>
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
                    <h1 style="font-size:1.3rem;color:var(--primary);margin:0;"><?= $lang === 'ar' ? 'تعديل المستخدم' : ($lang === 'fr' ? 'Modifier utilisateur' : 'Edit User') ?> #<?= $uid ?></h1>
                </div>
                <a href="index.php?lang=<?= $lang ?>" class="btn btn-outline btn-sm">
                    <i class="fas fa-arrow-<?= $dir === 'rtl' ? 'right' : 'left' ?>"></i> <?= htmlspecialchars($t['btn_back'] ?? 'Back') ?>
                </a>
            </div>
            <?= display_flash_messages() ?>

            <div style="max-width:620px;">
                <div class="card">
                    <div class="card-header">
                        <h3><?= $lang === 'ar' ? 'بيانات المستخدم' : ($lang === 'fr' ? 'Informations utilisateur' : 'User Information') ?></h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="#" novalidate>
                            <?php if (function_exists('generate_csrf_token')): ?>
                                <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                            <?php endif; ?>
                            <input type="hidden" name="user_id" value="<?= $uid ?>">
                            <div class="form-row">
                                <div class="form-group">
                                    <label><?= htmlspecialchars($t['register_name'] ?? 'Name') ?></label>
                                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label><?= htmlspecialchars($t['register_email'] ?? 'Email') ?></label>
                                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label><?= htmlspecialchars($t['profile_phone'] ?? 'Phone') ?></label>
                                    <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>">
                                </div>
                                <div class="form-group">
                                    <label><?= $lang === 'ar' ? 'الدور' : ($lang === 'fr' ? 'Rôle' : 'Role') ?></label>
                                    <select name="role" class="form-control">
                                        <option value="1" <?= $user['role'] === 1 ? 'selected' : '' ?>><?= $lang === 'ar' ? 'مستخدم' : ($lang === 'fr' ? 'Utilisateur' : 'User') ?></option>
                                        <option value="2" <?= $user['role'] === 2 ? 'selected' : '' ?>><?= $lang === 'ar' ? 'مشرف' : ($lang === 'fr' ? 'Admin' : 'Admin') ?></option>
                                        <option value="3" <?= $user['role'] === 3 ? 'selected' : '' ?>><?= $lang === 'ar' ? 'مشرف عام' : ($lang === 'fr' ? 'Super Admin' : 'Super Admin') ?></option>
                                    </select>
                                </div>
                            </div>
                            <hr style="margin:20px 0;border:none;border-top:1px solid var(--border);">
                            <h4 style="color:var(--primary);font-size:.95rem;margin-bottom:16px;"><?= $lang === 'ar' ? 'إعادة تعيين كلمة المرور' : ($lang === 'fr' ? 'Réinitialiser le mot de passe' : 'Reset Password') ?></h4>
                            <div class="form-row">
                                <div class="form-group">
                                    <label><?= $lang === 'ar' ? 'كلمة مرور جديدة' : ($lang === 'fr' ? 'Nouveau mot de passe' : 'New Password') ?></label>
                                    <div class="input-group">
                                        <input type="password" name="new_password" class="form-control" placeholder="••••••••">
                                        <button type="button" class="toggle-password"><i class="fas fa-eye"></i></button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><?= htmlspecialchars($t['register_confirm'] ?? 'Confirm Password') ?></label>
                                    <div class="input-group">
                                        <input type="password" name="confirm_password" class="form-control" placeholder="••••••••">
                                        <button type="button" class="toggle-password"><i class="fas fa-eye"></i></button>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" style="padding:12px 32px;">
                                <i class="fas fa-save"></i> <?= $lang === 'ar' ? 'حفظ التغييرات' : ($lang === 'fr' ? 'Enregistrer' : 'Save Changes') ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="../../assets/js/main.js"></script>
    <script>
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