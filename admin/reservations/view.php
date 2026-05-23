<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/middleware.php';
require_role([2, 3]);
if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar', 'fr', 'en'])) $_SESSION['lang'] = $_GET['lang'];
$lang = $_SESSION['lang'] ?? 'ar';
$t    = require __DIR__ . '/../../lang/' . $lang . '.php';
$dir  = $lang === 'ar' ? 'rtl' : 'ltr';
$id   = (int)($_GET['id'] ?? 1);

// Sample reservation detail
$res = [
    'id'        => $id,
    'user'      => 'محمد أمين بوعلام',
    'email'     => 'amin@example.com',
    'phone'     => '0550123456',
    'passport'  => 'AB1234567',
    'offer'     => 'برنامج العمرة المميز',
    'travelers' => 2,
    'price'     => 170000,
    'status'    => 'pending',
    'notes'     => 'نفضل الحجز في غرفة مطلة على الحرم',
    'date'      => '2025-01-15',
    'departure' => '2025-03-01',
];
$badge = $res['status'] === 'confirmed' ? 'badge-confirmed' : ($res['status'] === 'cancelled' ? 'badge-cancelled' : 'badge-pending');
$statusLabel = $res['status'] === 'confirmed' ? ($lang === 'ar' ? 'مؤكد' : ($lang === 'fr' ? 'Confirmé' : 'Confirmed')) : ($res['status'] === 'cancelled' ? ($lang === 'ar' ? 'ملغى' : ($lang === 'fr' ? 'Annulé' : 'Cancelled')) : ($lang === 'ar' ? 'قيد الانتظار' : ($lang === 'fr' ? 'En attente' : 'Pending')));
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang === 'ar' ? 'تفاصيل الحجز' : ($lang === 'fr' ? 'Détail réservation' : 'Reservation Detail') ?> #<?= $id ?> — <?= htmlspecialchars($t['site_name']) ?></title>
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
                <a href="/issighen/public/auth/logout.php"><i class="fas fa-sign-out-alt"></i><?= htmlspecialchars($t['nav_logout']) ?></a>
            </div>
        </aside>
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        <main class="admin-main">
            <div class="admin-topbar">
                <div style="display:flex;align-items:center;gap:12px;">
                    <button id="sidebarToggle" class="sidebar-toggle-btn"><i class="fas fa-bars"></i></button>
                    <div>
                        <h1 style="font-size:1.2rem;color:var(--primary);margin:0;"><?= $lang === 'ar' ? 'تفاصيل الحجز' : ($lang === 'fr' ? 'Détail de la réservation' : 'Reservation Detail') ?> #<?= $id ?></h1>
                    </div>
                </div>
                <div style="display:flex;gap:8px;align-items:center;">
                    <span class="badge <?= $badge ?>"><?= $statusLabel ?></span>
                    <a href="index.php?lang=<?= $lang ?>" class="btn btn-outline btn-sm">
                        <i class="fas fa-arrow-<?= $dir === 'rtl' ? 'right' : 'left' ?>"></i> <?= htmlspecialchars($t['btn_back'] ?? 'Back') ?>
                    </a>
                </div>
            </div>
            <?= display_flash_messages() ?>

            <div class="admin-grid-2">
                <!-- Reservation Info -->
                <div>
                    <div class="card" style="margin-bottom:20px;">
                        <div class="card-header">
                            <h3><i class="fas fa-user" style="color:var(--gold);margin-<?= $dir === 'rtl' ? 'left' : 'right' ?>:8px;"></i><?= $lang === 'ar' ? 'بيانات العميل' : ($lang === 'fr' ? 'Informations client' : 'Client Information') ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="reservation-detail-grid">
                                <div class="detail-row">
                                    <span class="detail-label"><?= $lang === 'ar' ? 'الاسم الكامل' : ($lang === 'fr' ? 'Nom complet' : 'Full Name') ?></span>
                                    <span class="detail-val"><?= htmlspecialchars($res['user']) ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label"><?= $lang === 'ar' ? 'البريد الإلكتروني' : ($lang === 'fr' ? 'E-mail' : 'Email') ?></span>
                                    <span class="detail-val"><?= htmlspecialchars($res['email']) ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label"><?= $lang === 'ar' ? 'الهاتف' : ($lang === 'fr' ? 'Téléphone' : 'Phone') ?></span>
                                    <span class="detail-val" dir="ltr"><?= htmlspecialchars($res['phone']) ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label"><?= $lang === 'ar' ? 'رقم جواز السفر' : ($lang === 'fr' ? 'Passeport' : 'Passport') ?></span>
                                    <span class="detail-val"><?= htmlspecialchars($res['passport']) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fas fa-calendar-check" style="color:var(--gold);margin-<?= $dir === 'rtl' ? 'left' : 'right' ?>:8px;"></i><?= $lang === 'ar' ? 'تفاصيل الحجز' : ($lang === 'fr' ? 'Détails réservation' : 'Booking Details') ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="reservation-detail-grid">
                                <div class="detail-row">
                                    <span class="detail-label"><?= $lang === 'ar' ? 'العرض' : ($lang === 'fr' ? 'Offre' : 'Offer') ?></span>
                                    <span class="detail-val"><strong><?= htmlspecialchars($res['offer']) ?></strong></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label"><?= $lang === 'ar' ? 'عدد المسافرين' : ($lang === 'fr' ? 'Voyageurs' : 'Travelers') ?></span>
                                    <span class="detail-val"><?= $res['travelers'] ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label"><?= $lang === 'ar' ? 'السعر الإجمالي' : ($lang === 'fr' ? 'Total' : 'Total Price') ?></span>
                                    <span class="detail-val" style="font-weight:800;color:var(--gold-dark);font-size:1.1rem;"><?= number_format($res['price']) ?> <?= htmlspecialchars($t['offer_currency'] ?? 'DA') ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label"><?= $lang === 'ar' ? 'تاريخ الحجز' : ($lang === 'fr' ? 'Date réservation' : 'Booking Date') ?></span>
                                    <span class="detail-val"><?= $res['date'] ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label"><?= $lang === 'ar' ? 'تاريخ المغادرة' : ($lang === 'fr' ? 'Départ' : 'Departure') ?></span>
                                    <span class="detail-val"><?= $res['departure'] ?></span>
                                </div>
                            </div>
                            <?php if ($res['notes']): ?>
                                <div style="margin-top:16px;padding:12px;background:var(--bg-light);border-radius:var(--radius-sm);">
                                    <strong style="font-size:.85rem;color:var(--text-medium);display:block;margin-bottom:6px;"><?= $lang === 'ar' ? 'ملاحظات' : ($lang === 'fr' ? 'Notes' : 'Notes') ?></strong>
                                    <p style="margin:0;color:var(--text-medium);"><?= htmlspecialchars($res['notes']) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div>
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fas fa-cogs" style="color:var(--gold);margin-<?= $dir === 'rtl' ? 'left' : 'right' ?>:8px;"></i><?= $lang === 'ar' ? 'إجراءات' : ($lang === 'fr' ? 'Actions' : 'Actions') ?></h3>
                        </div>
                        <div class="card-body">
                            <p style="font-size:.85rem;color:var(--text-medium);margin-bottom:16px;"><?= $lang === 'ar' ? 'تغيير حالة الحجز:' : ($lang === 'fr' ? 'Changer le statut :' : 'Change reservation status:') ?></p>
                            <form method="POST" action="#" style="display:flex;flex-direction:column;gap:10px;">
                                <?php if (function_exists('generate_csrf_token')): ?>
                                    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                                <?php endif; ?>
                                <input type="hidden" name="reservation_id" value="<?= $id ?>">
                                <button type="submit" name="status" value="confirmed" class="btn btn-block"
                                    style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;padding:12px;">
                                    <i class="fas fa-check-circle"></i> <?= $lang === 'ar' ? 'تأكيد الحجز' : ($lang === 'fr' ? 'Confirmer' : 'Confirm Booking') ?>
                                </button>
                                <button type="submit" name="status" value="pending" class="btn btn-block"
                                    style="background:#fef9c3;color:#854d0e;border:1px solid #fde68a;padding:12px;">
                                    <i class="fas fa-clock"></i> <?= $lang === 'ar' ? 'وضع قيد الانتظار' : ($lang === 'fr' ? 'Mettre en attente' : 'Set Pending') ?>
                                </button>
                                <button type="submit" name="status" value="cancelled" class="btn btn-block"
                                    style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;padding:12px;"
                                    data-confirm="<?= $lang === 'ar' ? 'هل أنت متأكد من إلغاء الحجز؟' : ($lang === 'fr' ? 'Confirmer l\'annulation ?' : 'Confirm cancellation?') ?>">
                                    <i class="fas fa-times-circle"></i> <?= $lang === 'ar' ? 'إلغاء الحجز' : ($lang === 'fr' ? 'Annuler' : 'Cancel Booking') ?>
                                </button>
                            </form>
                            <hr style="margin:20px 0;border:none;border-top:1px solid var(--border);">
                            <a href="mailto:<?= htmlspecialchars($res['email']) ?>" class="btn btn-outline btn-block" style="padding:10px;">
                                <i class="fas fa-envelope"></i> <?= $lang === 'ar' ? 'إرسال بريد' : ($lang === 'fr' ? 'Envoyer email' : 'Send Email') ?>
                            </a>
                        </div>
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