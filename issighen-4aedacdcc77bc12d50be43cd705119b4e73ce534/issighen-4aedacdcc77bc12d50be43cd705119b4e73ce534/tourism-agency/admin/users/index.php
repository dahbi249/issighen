<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/middleware.php';
require_admin();
if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar','fr','en'])) $_SESSION['lang'] = $_GET['lang'];
$lang = $_SESSION['lang'] ?? 'ar';
$t    = require __DIR__ . '/../../lang/' . $lang . '.php';
$dir  = $lang === 'ar' ? 'rtl' : 'ltr';

$users = [
    ['id'=>1,'name'=>'محمد أمين بوعلام','email'=>'amin@example.com','phone'=>'0550123456','role'=>1,'bookings'=>3,'joined'=>'2024-10-01'],
    ['id'=>2,'name'=>'Karim Boudiaf','email'=>'karim@example.com','phone'=>'0661987654','role'=>1,'bookings'=>1,'joined'=>'2024-11-05'],
    ['id'=>3,'name'=>'Sara Hamidi','email'=>'sara@example.com','phone'=>'0770345678','role'=>1,'bookings'=>5,'joined'=>'2024-09-20'],
    ['id'=>4,'name'=>'Admin User','email'=>'admin@isighene.dz','phone'=>'0554000000','role'=>2,'bookings'=>0,'joined'=>'2024-01-01'],
    ['id'=>5,'name'=>'Nadia Khelil','email'=>'nadia@example.com','phone'=>'0662234567','role'=>1,'bookings'=>2,'joined'=>'2024-12-15'],
];
$roleLabels = [
    1 => $lang==='ar'?'مستخدم':($lang==='fr'?'Utilisateur':'User'),
    2 => $lang==='ar'?'مشرف':($lang==='fr'?'Admin':'Admin'),
    3 => $lang==='ar'?'مشرف عام':($lang==='fr'?'Super Admin':'Super Admin'),
];
$roleClasses = [1=>'role-user', 2=>'role-admin', 3=>'role-superadmin'];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang==='ar'?'إدارة المستخدمين':($lang==='fr'?'Gestion des utilisateurs':'Manage Users') ?> — <?= htmlspecialchars($t['site_name']) ?></title>
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
            <a href="index.php?lang=<?= $lang ?>" class="active"><span class="nav-icon"><i class="fas fa-users"></i></span><?= $lang==='ar'?'المستخدمون':($lang==='fr'?'Utilisateurs':'Users') ?></a>
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
                <h1 style="font-size:1.3rem;color:var(--primary);margin:0;"><?= $lang==='ar'?'إدارة المستخدمين':($lang==='fr'?'Gestion des utilisateurs':'Manage Users') ?></h1>
            </div>
            <span style="font-size:.85rem;color:var(--text-medium);"><?= count($users) ?> <?= $lang==='ar'?'مستخدم':($lang==='fr'?'utilisateur(s)':'user(s)') ?></span>
        </div>
        <?= display_flash_messages() ?>

        <!-- Search -->
        <div style="margin-bottom:20px;">
            <input type="text" id="userSearch" class="form-control" style="max-width:320px;" placeholder="<?= $lang==='ar'?'بحث عن مستخدم...':($lang==='fr'?'Rechercher un utilisateur...':'Search user...') ?>">
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table" id="usersTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?= $lang==='ar'?'المستخدم':($lang==='fr'?'Utilisateur':'User') ?></th>
                            <th><?= $lang==='ar'?'الهاتف':($lang==='fr'?'Téléphone':'Phone') ?></th>
                            <th><?= $lang==='ar'?'الدور':($lang==='fr'?'Rôle':'Role') ?></th>
                            <th><?= $lang==='ar'?'الحجوزات':($lang==='fr'?'Réservations':'Bookings') ?></th>
                            <th><?= $lang==='ar'?'تاريخ التسجيل':($lang==='fr'?'Inscription':'Joined') ?></th>
                            <th><?= $lang==='ar'?'الإجراءات':($lang==='fr'?'Actions':'Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:34px;height:34px;background:var(--primary);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:.9rem;flex-shrink:0;">
                                        <?= strtoupper(mb_substr($user['name'],0,1)) ?>
                                    </div>
                                    <div>
                                        <div style="font-weight:600;"><?= htmlspecialchars($user['name']) ?></div>
                                        <div style="font-size:.8rem;color:var(--text-light);"><?= htmlspecialchars($user['email']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td dir="ltr" style="font-size:.9rem;"><?= htmlspecialchars($user['phone']) ?></td>
                            <td><span class="user-role-badge <?= $roleClasses[$user['role']] ?? 'role-user' ?>"><?= $roleLabels[$user['role']] ?? 'User' ?></span></td>
                            <td style="text-align:center;font-weight:600;"><?= $user['bookings'] ?></td>
                            <td style="font-size:.85rem;color:var(--text-medium);"><?= $user['joined'] ?></td>
                            <td>
                                <div style="display:flex;gap:6px;">
                                    <a href="edit.php?id=<?= $user['id'] ?>&lang=<?= $lang ?>" class="btn btn-outline btn-sm" title="<?= $lang==='ar'?'تعديل':($lang==='fr'?'Modifier':'Edit') ?>">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-danger btn-sm" data-confirm="<?= $lang==='ar'?'حذف المستخدم؟':($lang==='fr'?'Supprimer cet utilisateur ?':'Delete this user?') ?>" title="<?= $lang==='ar'?'حذف':($lang==='fr'?'Supprimer':'Delete') ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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
document.getElementById('userSearch')?.addEventListener('input',function(){
    var q = this.value.toLowerCase();
    document.querySelectorAll('#usersTable tbody tr').forEach(function(row){
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
</body>
</html>
