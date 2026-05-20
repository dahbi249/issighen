<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/middleware.php';
require_role([2, 3]);
if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar', 'fr', 'en'])) $_SESSION['lang'] = $_GET['lang'];
$lang = $_SESSION['lang'] ?? 'ar';
$t    = require __DIR__ . '/../../lang/' . $lang . '.php';
$dir  = $lang === 'ar' ? 'rtl' : 'ltr';
$statusFilter = $_GET['status'] ?? 'all';

$sampleReservations = [];
$filtered = $statusFilter === 'all' ? $sampleReservations :
    array_values(array_filter($sampleReservations, fn($r) => $r['status'] === $statusFilter));
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang === 'ar' ? 'إدارة الحجوزات' : ($lang === 'fr' ? 'Gestion des réservations' : 'Manage Reservations') ?> — <?= htmlspecialchars($t['site_name']) ?></title>
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
                <a href="index.php?lang=<?= $lang ?>" class="active"><span class="nav-icon"><i class="fas fa-calendar-check"></i></span><?= $lang === 'ar' ? 'الحجوزات' : ($lang === 'fr' ? 'Réservations' : 'Reservations') ?></a>
                <a href="../offers/index.php?lang=<?= $lang ?>"><span class="nav-icon"><i class="fas fa-compass"></i></span><?= $lang === 'ar' ? 'العروض' : ($lang === 'fr' ? 'Offres' : 'Offers') ?></a>
                <a href="../users/index.php?lang=<?= $lang ?>"><span class="nav-icon"><i class="fas fa-users"></i></span><?= $lang === 'ar' ? 'المستخدمون' : ($lang === 'fr' ? 'Utilisateurs' : 'Users') ?></a>
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
                    <h1 style="font-size:1.3rem;color:var(--primary);margin:0;"><?= $lang === 'ar' ? 'إدارة الحجوزات' : ($lang === 'fr' ? 'Gestion des réservations' : 'Manage Reservations') ?></h1>
                </div>
                <div style="display:flex;gap:8px;">
                    <span style="font-size:.85rem;color:var(--text-medium);align-self:center;">
                        <?= count($filtered) ?> <?= $lang === 'ar' ? 'حجز' : ($lang === 'fr' ? 'réservation(s)' : 'booking(s)') ?>
                    </span>
                </div>
            </div>
            <?= display_flash_messages() ?>

            <!-- Filter tabs -->
            <div class="filter-bar" style="margin-bottom:20px;">
                <?php
                $tabs = [
                    'all' => ($lang === 'ar' ? 'الكل' : ($lang === 'fr' ? 'Tous' : 'All')),
                    'pending' => ($lang === 'ar' ? 'قيد الانتظار' : ($lang === 'fr' ? 'En attente' : 'Pending')),
                    'confirmed' => ($lang === 'ar' ? 'مؤكد' : ($lang === 'fr' ? 'Confirmé' : 'Confirmed')),
                    'cancelled' => ($lang === 'ar' ? 'ملغى' : ($lang === 'fr' ? 'Annulé' : 'Cancelled'))
                ];
                foreach ($tabs as $key => $label):
                ?>
                    <a href="?status=<?= $key ?>&lang=<?= $lang ?>" class="filter-pill <?= $statusFilter === $key ? 'active' : '' ?>"><?= $label ?></a>
                <?php endforeach; ?>
            </div>

            <div class="card">
                <?php if (empty($filtered)): ?>
                    <div class="card-body">
                        <div class="empty-state">
                            <span class="empty-icon">📭</span>
                            <h3><?= $lang === 'ar' ? 'لا توجد حجوزات' : ($lang === 'fr' ? 'Aucune réservation' : 'No Reservations') ?></h3>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?= $lang === 'ar' ? 'العميل' : ($lang === 'fr' ? 'Client' : 'Client') ?></th>
                                    <th><?= $lang === 'ar' ? 'العرض' : ($lang === 'fr' ? 'Offre' : 'Offer') ?></th>
                                    <th><?= $lang === 'ar' ? 'المسافرون' : ($lang === 'fr' ? 'Voyageurs' : 'Travelers') ?></th>
                                    <th><?= $lang === 'ar' ? 'الإجمالي' : ($lang === 'fr' ? 'Total' : 'Total') ?></th>
                                    <th><?= $lang === 'ar' ? 'التاريخ' : ($lang === 'fr' ? 'Date' : 'Date') ?></th>
                                    <th><?= $lang === 'ar' ? 'الحالة' : ($lang === 'fr' ? 'Statut' : 'Status') ?></th>
                                    <th><?= $lang === 'ar' ? 'الإجراءات' : ($lang === 'fr' ? 'Actions' : 'Actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($filtered as $res):
                                    $badge = $res['status'] === 'confirmed' ? 'badge-confirmed' : ($res['status'] === 'cancelled' ? 'badge-cancelled' : 'badge-pending');
                                    $statusLabel = $res['status'] === 'confirmed' ? ($lang === 'ar' ? 'مؤكد' : ($lang === 'fr' ? 'Confirmé' : 'Confirmed')) : ($res['status'] === 'cancelled' ? ($lang === 'ar' ? 'ملغى' : ($lang === 'fr' ? 'Annulé' : 'Cancelled')) : ($lang === 'ar' ? 'قيد الانتظار' : ($lang === 'fr' ? 'En attente' : 'Pending')));
                                ?>
                                    <tr>
                                        <td>#<?= $res['id'] ?></td>
                                        <td>
                                            <div style="font-weight:600;"><?= htmlspecialchars($res['user']) ?></div>
                                            <div style="font-size:.8rem;color:var(--text-light);"><?= htmlspecialchars($res['phone']) ?></div>
                                        </td>
                                        <td><?= htmlspecialchars($res['offer']) ?></td>
                                        <td style="text-align:center;"><?= $res['travelers'] ?></td>
                                        <td><strong><?= number_format($res['price']) ?> <?= htmlspecialchars($t['offer_currency'] ?? 'DA') ?></strong></td>
                                        <td style="font-size:.85rem;color:var(--text-medium);"><?= $res['date'] ?></td>
                                        <td><span class="badge <?= $badge ?>"><?= $statusLabel ?></span></td>
                                        <td>
                                            <div style="display:flex;gap:6px;">
                                                <a href="view.php?id=<?= $res['id'] ?>&lang=<?= $lang ?>" class="btn btn-outline btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <!-- Status change buttons -->
                                                <?php if ($res['status'] !== 'confirmed'): ?>
                                                    <button class="btn btn-sm admin-status-btn" data-status="confirmed" style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;" title="<?= $lang === 'ar' ? 'تأكيد' : ($lang === 'fr' ? 'Confirmer' : 'Confirm') ?>">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <?php if ($res['status'] !== 'cancelled'): ?>
                                                    <button class="btn btn-sm admin-status-btn" data-status="cancelled" style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;" title="<?= $lang === 'ar' ? 'إلغاء' : ($lang === 'fr' ? 'Annuler' : 'Cancel') ?>">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
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