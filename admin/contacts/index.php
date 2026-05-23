<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/middleware.php';
require_role([2, 3]);
if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar', 'fr', 'en'])) $_SESSION['lang'] = $_GET['lang'];
$lang = $_SESSION['lang'] ?? 'ar';
$t    = require __DIR__ . '/../../lang/' . $lang . '.php';
$dir  = $lang === 'ar' ? 'rtl' : 'ltr';

$messages = [
    ['id' => 1, 'name' => 'Karim Boudiaf', 'email' => 'karim@example.com', 'subject' => 'Demande d\'information Omra', 'message' => 'Bonjour, je voudrais avoir plus d\'informations sur votre programme Omra pour le mois de Ramadan...', 'date' => '2025-01-15', 'read' => false],
    ['id' => 2, 'name' => 'محمد الأمين', 'email' => 'amine@example.com', 'subject' => 'استفسار عن برنامج الحج', 'message' => 'السلام عليكم، أريد الاستفسار عن برنامج الحج للعام القادم وما هي الشروط المطلوبة...', 'date' => '2025-01-14', 'read' => true],
    ['id' => 3, 'name' => 'Sara Hamidi', 'email' => 'sara@example.com', 'subject' => 'Trip to Turkey', 'message' => 'Hi, I am interested in your Turkey package. Could you please provide more details about the itinerary...', 'date' => '2025-01-13', 'read' => false],
    ['id' => 4, 'name' => 'Nadia Khelil', 'email' => 'nadia@example.com', 'subject' => 'Question sur les prix', 'message' => 'Bonjour, pourriez-vous me donner les tarifs pour une famille de 4 personnes pour l\'Omra...', 'date' => '2025-01-12', 'read' => true],
];
$unread = count(array_filter($messages, fn($m) => !$m['read']));
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang === 'ar' ? 'رسائل التواصل' : ($lang === 'fr' ? 'Messages de contact' : 'Contact Messages') ?> — <?= htmlspecialchars($t['site_name']) ?></title>
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
                <a href="index.php?lang=<?= $lang ?>" class="active">
                    <span class="nav-icon"><i class="fas fa-envelope"></i></span>
                    <?= $lang === 'ar' ? 'الرسائل' : ($lang === 'fr' ? 'Messages' : 'Messages') ?>
                    <?php if ($unread > 0): ?>
                        <span class="nav-badge"><?= $unread ?></span>
                    <?php endif; ?>
                </a>
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
                    <h1 style="font-size:1.3rem;color:var(--primary);margin:0;">
                        <?= $lang === 'ar' ? 'رسائل التواصل' : ($lang === 'fr' ? 'Messages de contact' : 'Contact Messages') ?>
                        <?php if ($unread > 0): ?>
                            <span class="badge badge-pending" style="font-size:.75rem;margin-<?= $dir === 'rtl' ? 'right' : 'left' ?>:8px;"><?= $unread ?> <?= $lang === 'ar' ? 'جديد' : ($lang === 'fr' ? 'nouveau(x)' : 'new') ?></span>
                        <?php endif; ?>
                    </h1>
                </div>
            </div>
            <?= display_flash_messages() ?>

            <div class="card">
                <div class="card-body" style="padding:0;">
                    <?php foreach ($messages as $msg): ?>
                        <div class="message-item <?= !$msg['read'] ? 'message-unread' : '' ?>">
                            <div class="message-avatar"><?= strtoupper(mb_substr($msg['name'], 0, 1)) ?></div>
                            <div class="message-body">
                                <div class="message-header-row">
                                    <span class="message-sender"><?= htmlspecialchars($msg['name']) ?></span>
                                    <?php if (!$msg['read']): ?>
                                        <span class="badge badge-pending" style="font-size:.7rem;"><?= $lang === 'ar' ? 'جديد' : ($lang === 'fr' ? 'Nouveau' : 'New') ?></span>
                                    <?php endif; ?>
                                    <span class="message-date"><?= $msg['date'] ?></span>
                                </div>
                                <div class="message-subject"><?= htmlspecialchars($msg['subject']) ?></div>
                                <div class="message-preview"><?= htmlspecialchars(mb_substr($msg['message'], 0, 120)) ?>…</div>
                            </div>
                            <div class="message-actions">
                                <button class="btn btn-outline btn-sm" data-modal="msgModal<?= $msg['id'] ?>" title="<?= $lang === 'ar' ? 'قراءة' : ($lang === 'fr' ? 'Lire' : 'Read') ?>">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="mailto:<?= htmlspecialchars($msg['email']) ?>" class="btn btn-outline btn-sm" title="<?= $lang === 'ar' ? 'رد' : ($lang === 'fr' ? 'Répondre' : 'Reply') ?>">
                                    <i class="fas fa-reply"></i>
                                </a>
                                <button class="btn btn-danger btn-sm" data-confirm="<?= $lang === 'ar' ? 'حذف الرسالة؟' : ($lang === 'fr' ? 'Supprimer ce message ?' : 'Delete this message?') ?>" title="<?= $lang === 'ar' ? 'حذف' : ($lang === 'fr' ? 'Supprimer' : 'Delete') ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Message Modal -->
                        <div class="modal-overlay" id="msgModal<?= $msg['id'] ?>">
                            <div class="modal" style="max-width:560px;">
                                <button class="modal-close" data-dismiss="modal"><i class="fas fa-times"></i></button>
                                <div class="modal-title"><?= htmlspecialchars($msg['subject']) ?></div>
                                <div class="modal-subtitle">
                                    <?= htmlspecialchars($msg['name']) ?> &lt;<?= htmlspecialchars($msg['email']) ?>&gt; — <?= $msg['date'] ?>
                                </div>
                                <p style="color:var(--text-medium);line-height:1.7;margin-top:16px;"><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                                <a href="mailto:<?= htmlspecialchars($msg['email']) ?>" class="btn btn-primary btn-block" style="margin-top:16px;padding:12px;">
                                    <i class="fas fa-reply"></i> <?= $lang === 'ar' ? 'رد على الرسالة' : ($lang === 'fr' ? 'Répondre' : 'Reply') ?>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
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