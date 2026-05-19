<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/middleware.php';
require_admin();
if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar','fr','en'])) $_SESSION['lang'] = $_GET['lang'];
$lang = $_SESSION['lang'] ?? 'ar';
$t    = require __DIR__ . '/../../lang/' . $lang . '.php';
$dir  = $lang === 'ar' ? 'rtl' : 'ltr';
$sampleOffers = [
    ['id'=>1,'title_ar'=>'برنامج العمرة المميز','title_fr'=>'Programme Omra Premium','title_en'=>'Premium Umrah Package','cat'=>'umrah','price'=>85000,'days'=>10,'status'=>'active'],
    ['id'=>2,'title_ar'=>'جولة الصحراء الجزائرية','title_fr'=>'Circuit Sahara Algérien','title_en'=>'Algerian Sahara Tour','cat'=>'tourism','price'=>45000,'days'=>5,'status'=>'active'],
    ['id'=>3,'title_ar'=>'رحلة تركيا','title_fr'=>'Voyage Turquie','title_en'=>'Turkey Trip','cat'=>'tourism','price'=>120000,'days'=>8,'status'=>'active'],
    ['id'=>4,'title_ar'=>'برنامج الحج','title_fr'=>'Programme Hajj','title_en'=>'Hajj Package','cat'=>'hajj','price'=>350000,'days'=>21,'status'=>'active'],
    ['id'=>5,'title_ar'=>'عمرة رمضان','title_fr'=>'Omra Ramadan','title_en'=>'Ramadan Umrah','cat'=>'umrah','price'=>95000,'days'=>14,'status'=>'inactive'],
];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang==='ar'?'إدارة العروض':($lang==='fr'?'Gestion des offres':'Manage Offers') ?> — <?= htmlspecialchars($t['site_name']) ?></title>
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
            <a href="index.php?lang=<?= $lang ?>" class="active"><span class="nav-icon"><i class="fas fa-compass"></i></span><?= $lang==='ar'?'العروض':($lang==='fr'?'Offres':'Offers') ?></a>
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
                <h1 style="font-size:1.3rem;color:var(--primary);margin:0;"><?= $lang==='ar'?'إدارة العروض':($lang==='fr'?'Gestion des offres':'Manage Offers') ?></h1>
            </div>
            <a href="create.php?lang=<?= $lang ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> <?= $lang==='ar'?'إضافة عرض':($lang==='fr'?'Ajouter une offre':'Add Offer') ?>
            </a>
        </div>
        <?= display_flash_messages() ?>

        <!-- Search + filter bar -->
        <div class="filter-bar" style="margin-bottom:20px;">
            <input type="text" id="offerSearch" class="form-control" style="max-width:260px;" placeholder="<?= $lang==='ar'?'بحث...':($lang==='fr'?'Rechercher...':'Search...') ?>">
            <button class="filter-pill active" data-filter="all"><?= $lang==='ar'?'الكل':($lang==='fr'?'Tous':'All') ?></button>
            <button class="filter-pill" data-filter="umrah"><?= $lang==='ar'?'عمرة':($lang==='fr'?'Omra':'Umrah') ?></button>
            <button class="filter-pill" data-filter="hajj"><?= $lang==='ar'?'حج':($lang==='fr'?'Hajj':'Hajj') ?></button>
            <button class="filter-pill" data-filter="tourism"><?= $lang==='ar'?'سياحة':($lang==='fr'?'Tourisme':'Tourism') ?></button>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?= $lang==='ar'?'العنوان':($lang==='fr'?'Titre':'Title') ?></th>
                            <th><?= $lang==='ar'?'الفئة':($lang==='fr'?'Catégorie':'Category') ?></th>
                            <th><?= $lang==='ar'?'السعر':($lang==='fr'?'Prix':'Price') ?></th>
                            <th><?= $lang==='ar'?'المدة':($lang==='fr'?'Durée':'Duration') ?></th>
                            <th><?= $lang==='ar'?'الحالة':($lang==='fr'?'Statut':'Status') ?></th>
                            <th><?= $lang==='ar'?'الإجراءات':($lang==='fr'?'Actions':'Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($sampleOffers as $offer):
                            $title = $offer['title_'.$lang] ?? $offer['title_en'];
                            $catLabel = $offer['cat']==='umrah'?($lang==='ar'?'عمرة':($lang==='fr'?'Omra':'Umrah')):
                                       ($offer['cat']==='hajj'?($lang==='ar'?'حج':'Hajj'):
                                       ($lang==='ar'?'سياحة':($lang==='fr'?'Tourisme':'Tourism')));
                        ?>
                        <tr data-category="<?= $offer['cat'] ?>">
                            <td><?= $offer['id'] ?></td>
                            <td><strong><?= htmlspecialchars($title) ?></strong></td>
                            <td><span class="badge cat-badge-<?= $offer['cat'] ?>"><?= $catLabel ?></span></td>
                            <td><?= number_format($offer['price']) ?> <?= htmlspecialchars($t['offer_currency'] ?? 'DA') ?></td>
                            <td><?= $offer['days'] ?> <?= htmlspecialchars($t['offer_days'] ?? 'days') ?></td>
                            <td>
                                <?php if($offer['status']==='active'): ?>
                                <span class="badge badge-confirmed"><?= $lang==='ar'?'نشط':($lang==='fr'?'Actif':'Active') ?></span>
                                <?php else: ?>
                                <span class="badge badge-cancelled"><?= $lang==='ar'?'غير نشط':($lang==='fr'?'Inactif':'Inactive') ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                    <a href="create.php?id=<?= $offer['id'] ?>&lang=<?= $lang ?>" class="btn btn-outline btn-sm" title="<?= $lang==='ar'?'تعديل':($lang==='fr'?'Modifier':'Edit') ?>"><i class="fas fa-edit"></i></a>
                                    <a href="../../public/pages/offer-detail.php?id=<?= $offer['id'] ?>&lang=<?= $lang ?>" class="btn btn-outline btn-sm" title="<?= $lang==='ar'?'عرض':($lang==='fr'?'Voir':'View') ?>" target="_blank"><i class="fas fa-eye"></i></a>
                                    <button class="btn btn-danger btn-sm" data-confirm="<?= $lang==='ar'?'هل أنت متأكد من الحذف؟':($lang==='fr'?'Confirmer la suppression ?':'Confirm delete?') ?>" title="<?= $lang==='ar'?'حذف':($lang==='fr'?'Supprimer':'Delete') ?>"><i class="fas fa-trash"></i></button>
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
// Category filter
document.querySelectorAll('.filter-pill').forEach(function(pill){
    pill.addEventListener('click',function(){
        document.querySelectorAll('.filter-pill').forEach(p=>p.classList.remove('active'));
        this.classList.add('active');
        var filter = this.dataset.filter;
        document.querySelectorAll('tbody tr').forEach(function(row){
            row.style.display = (filter==='all' || row.dataset.category===filter) ? '' : 'none';
        });
    });
});
// Search
document.getElementById('offerSearch')?.addEventListener('input',function(){
    var q = this.value.toLowerCase();
    document.querySelectorAll('tbody tr').forEach(function(row){
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
</body>
</html>
