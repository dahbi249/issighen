<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/middleware.php';
require_role([2, 3]);
if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar', 'fr', 'en'])) $_SESSION['lang'] = $_GET['lang'];
$lang = $_SESSION['lang'] ?? 'ar';
$t    = require __DIR__ . '/../../lang/' . $lang . '.php';
$dir  = $lang === 'ar' ? 'rtl' : 'ltr';

$payments = [
    ['id' => 1, 'user' => 'محمد أمين', 'offer' => 'برنامج العمرة المميز', 'amount' => 170000, 'method' => 'cash', 'status' => 'paid', 'date' => '2025-01-15'],
    ['id' => 2, 'user' => 'Karim Boudiaf', 'offer' => 'Circuit Sahara', 'amount' => 45000, 'method' => 'bank', 'status' => 'paid', 'date' => '2025-01-14'],
    ['id' => 3, 'user' => 'Sara Hamidi', 'offer' => 'Hajj Package', 'amount' => 1050000, 'method' => 'cash', 'status' => 'partial', 'date' => '2025-01-13'],
    ['id' => 4, 'user' => 'يوسف بن علي', 'offer' => 'رحلة تركيا', 'amount' => 240000, 'method' => 'bank', 'status' => 'pending', 'date' => '2025-01-12'],
    ['id' => 5, 'user' => 'Nadia Khelil', 'offer' => 'Omra Ramadan', 'amount' => 95000, 'method' => 'cash', 'status' => 'pending', 'date' => '2025-01-11'],
];
$total = array_sum(array_column(array_filter($payments, fn($p) => $p['status'] === 'paid'), 'amount'));
$statusLabel = fn($s) => match ($s) {
    'paid'    => [$lang === 'ar' ? 'مدفوع' : ($lang === 'fr' ? 'Payé' : 'Paid'), 'badge-confirmed'],
    'partial' => [$lang === 'ar' ? 'جزئي' : ($lang === 'fr' ? 'Partiel' : 'Partial'), 'badge-pending'],
    default   => [$lang === 'ar' ? 'معلق' : ($lang === 'fr' ? 'En attente' : 'Pending'), 'badge-pending'],
};
$methodLabel = fn($m) => match ($m) {
    'bank' => $lang === 'ar' ? 'تحويل بنكي' : ($lang === 'fr' ? 'Virement' : 'Bank Transfer'),
    default => $lang === 'ar' ? 'نقداً' : ($lang === 'fr' ? 'Espèces' : 'Cash'),
};
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang === 'ar' ? 'المدفوعات' : ($lang === 'fr' ? 'Paiements' : 'Payments') ?> — <?= htmlspecialchars($t['site_name']) ?></title>
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
                <a href="../users/index.php?lang=<?= $lang ?>"><span class="nav-icon"><i class="fas fa-users"></i></span><?= $lang === 'ar' ? 'المستخدمون' : ($lang === 'fr' ? 'Utilisateurs' : 'Users') ?></a>
                <a href="../contacts/index.php?lang=<?= $lang ?>"><span class="nav-icon"><i class="fas fa-envelope"></i></span><?= $lang === 'ar' ? 'الرسائل' : ($lang === 'fr' ? 'Messages' : 'Messages') ?></a>
                <a href="index.php?lang=<?= $lang ?>" class="active"><span class="nav-icon"><i class="fas fa-wallet"></i></span><?= $lang === 'ar' ? 'المدفوعات' : ($lang === 'fr' ? 'Paiements' : 'Payments') ?></a>
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
                    <h1 style="font-size:1.3rem;color:var(--primary);margin:0;"><?= $lang === 'ar' ? 'المدفوعات' : ($lang === 'fr' ? 'Paiements' : 'Payments') ?></h1>
                </div>
                <div class="stat-card" style="padding:10px 20px;border:none;box-shadow:none;background:var(--bg-light);">
                    <div style="font-size:.8rem;color:var(--text-light);"><?= $lang === 'ar' ? 'إجمالي المحصّل' : ($lang === 'fr' ? 'Total encaissé' : 'Total Collected') ?></div>
                    <div style="font-size:1.1rem;font-weight:800;color:var(--gold-dark);"><?= number_format($total) ?> <?= htmlspecialchars($t['offer_currency'] ?? 'DA') ?></div>
                </div>
            </div>
            <?= display_flash_messages() ?>

            <div class="card">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?= $lang === 'ar' ? 'العميل' : ($lang === 'fr' ? 'Client' : 'Client') ?></th>
                                <th><?= $lang === 'ar' ? 'العرض' : ($lang === 'fr' ? 'Offre' : 'Offer') ?></th>
                                <th><?= $lang === 'ar' ? 'المبلغ' : ($lang === 'fr' ? 'Montant' : 'Amount') ?></th>
                                <th><?= $lang === 'ar' ? 'طريقة الدفع' : ($lang === 'fr' ? 'Mode de paiement' : 'Payment Method') ?></th>
                                <th><?= $lang === 'ar' ? 'الحالة' : ($lang === 'fr' ? 'Statut' : 'Status') ?></th>
                                <th><?= $lang === 'ar' ? 'التاريخ' : ($lang === 'fr' ? 'Date' : 'Date') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payments as $p):
                                [$sl, $sc] = $statusLabel($p['status']);
                            ?>
                                <tr>
                                    <td>#<?= $p['id'] ?></td>
                                    <td><strong><?= htmlspecialchars($p['user']) ?></strong></td>
                                    <td><?= htmlspecialchars($p['offer']) ?></td>
                                    <td style="font-weight:700;color:var(--gold-dark);"><?= number_format($p['amount']) ?> <?= htmlspecialchars($t['offer_currency'] ?? 'DA') ?></td>
                                    <td>
                                        <span style="display:inline-flex;align-items:center;gap:5px;font-size:.85rem;">
                                            <i class="fas fa-<?= $p['method'] === 'bank' ? 'university' : 'money-bill-wave' ?>" style="color:var(--text-light);"></i>
                                            <?= $methodLabel($p['method']) ?>
                                        </span>
                                    </td>
                                    <td><span class="badge <?= $sc ?>"><?= $sl ?></span></td>
                                    <td style="font-size:.85rem;color:var(--text-medium);"><?= $p['date'] ?></td>
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