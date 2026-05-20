<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';
if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar','fr','en'])) $_SESSION['lang'] = $_GET['lang'];
$lang = $_SESSION['lang'] ?? 'ar';
$t    = require __DIR__ . '/../../lang/' . $lang . '.php';
$dir  = $lang === 'ar' ? 'rtl' : 'ltr';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($t['contact_title']) ?> — <?= htmlspecialchars($t['site_name']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>

<section class="page-hero">
    <div class="container">
        <div class="breadcrumbs">
            <a href="home.php?lang=<?= $lang ?>"><?= htmlspecialchars($t['nav_home']) ?></a>
            <span class="sep"><i class="fas fa-chevron-<?= $dir==='rtl'?'left':'right' ?>"></i></span>
            <span><?= htmlspecialchars($t['contact_title']) ?></span>
        </div>
        <h1><?= htmlspecialchars($t['contact_title']) ?></h1>
        <p><?= htmlspecialchars($t['contact_subtitle']) ?></p>
    </div>
</section>

<section class="section" style="background:var(--bg-light);">
    <div class="container">
        <?= display_flash_messages() ?>
        <div class="contact-layout">

            <!-- Contact Form -->
            <div>
                <div class="card">
                    <div class="card-header">
                        <h3><?= htmlspecialchars($t['contact_form_title']) ?></h3>
                    </div>
                    <div class="card-body">
                        <form action="#" method="POST" novalidate>
                            <?php if (function_exists('generate_csrf_token')): ?>
                            <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                            <?php endif; ?>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="contact_name"><?= htmlspecialchars($t['contact_name']) ?></label>
                                    <input type="text" id="contact_name" name="name" class="form-control"
                                           placeholder="<?= htmlspecialchars($t['contact_name']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="contact_email"><?= htmlspecialchars($t['contact_email']) ?></label>
                                    <input type="email" id="contact_email" name="email" class="form-control"
                                           placeholder="name@example.com" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="contact_subject"><?= htmlspecialchars($t['contact_subject']) ?></label>
                                <input type="text" id="contact_subject" name="subject" class="form-control"
                                       placeholder="<?= htmlspecialchars($t['contact_subject']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="contact_message"><?= htmlspecialchars($t['contact_message']) ?></label>
                                <textarea id="contact_message" name="message" class="form-control" rows="5"
                                          placeholder="<?= htmlspecialchars($t['contact_message']) ?>..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-dark btn-block" style="padding:14px;">
                                <i class="fas fa-paper-plane"></i> <?= htmlspecialchars($t['contact_send']) ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div>
                <div class="contact-info-card">
                    <h3><?= htmlspecialchars($t['contact_phones']) ?></h3>
                    <div class="contact-info-item">
                        <div class="ci-icon"><i class="fas fa-phone"></i></div>
                        <div class="ci-text">
                            <p><?= $lang==='ar' ? 'هاتف رقم 1' : ($lang==='fr' ? 'Téléphone 1' : 'Phone 1') ?></p>
                            <strong dir="ltr">+213 555 000 000</strong>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="ci-icon"><i class="fas fa-phone"></i></div>
                        <div class="ci-text">
                            <p><?= $lang==='ar' ? 'هاتف رقم 2' : ($lang==='fr' ? 'Téléphone 2' : 'Phone 2') ?></p>
                            <strong dir="ltr">+213 555 111 111</strong>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="ci-icon"><i class="fab fa-whatsapp"></i></div>
                        <div class="ci-text">
                            <p>WhatsApp</p>
                            <strong dir="ltr">+213 555 222 222</strong>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="ci-icon"><i class="fas fa-envelope"></i></div>
                        <div class="ci-text">
                            <p><?= htmlspecialchars($t['contact_email']) ?></p>
                            <strong>contact@isighene.com</strong>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="ci-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="ci-text">
                            <p><?= htmlspecialchars($t['contact_address']) ?></p>
                            <strong><?= $lang==='ar' ? 'الجزائر العاصمة، الشارع الرئيسي' : ($lang==='fr' ? 'Alger, Rue Principale' : 'Algiers, Main Street') ?></strong>
                        </div>
                    </div>

                    <div style="margin-top:24px;padding-top:20px;border-top:1px solid rgba(255,255,255,.15);">
                        <p style="color:rgba(255,255,255,.7);font-size:.9rem;margin-bottom:14px;"><?= htmlspecialchars($t['contact_social']) ?></p>
                        <div class="social-links">
                            <a href="#" class="social-link fb"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-link ig"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="social-link wa"><i class="fab fa-whatsapp"></i></a>
                            <a href="#" class="social-link yt"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Map placeholder -->
                <div style="margin-top:20px;background:var(--border);border-radius:var(--radius-md);height:200px;display:flex;align-items:center;justify-content:center;color:var(--text-light);font-size:.9rem;border:1px solid var(--border);">
                    <div style="text-align:center;">
                        <i class="fas fa-map-marked-alt" style="font-size:2rem;display:block;margin-bottom:8px;opacity:.4;"></i>
                        <?= htmlspecialchars($t['contact_map']) ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
<script src="../../assets/js/main.js"></script>
</body>
</html>
