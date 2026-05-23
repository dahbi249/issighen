<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';
if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar', 'fr', 'en'])) $_SESSION['lang'] = $_GET['lang'];
$lang = $_SESSION['lang'] ?? 'ar';
$t    = require __DIR__ . '/../../lang/' . $lang . '.php';
$dir  = $lang === 'ar' ? 'rtl' : 'ltr';
$id   = (int)($_GET['id'] ?? 1);

// Sample offer detail data
$offers = [
    1 => [
        'cat' => 'umrah',
        'emoji' => '🕌',
        'price' => 85000,
        'days' => 10,
        'loc' => 'Makkah',
        'title_ar' => 'برنامج العمرة المميز',
        'title_fr' => 'Programme Omra Premium',
        'title_en' => 'Premium Umrah Package',
        'desc_ar' => 'برنامج عمرة متكامل يشمل الإقامة الفندقية المريحة قرب الحرم المكي، النقل من وإلى المطار، والمرشد الديني المتخصص طوال فترة الرحلة.',
        'desc_fr' => 'Programme d\'Omra complet incluant hébergement confortable près de la Kaaba, transferts aéroport et guide religieux spécialisé.',
        'desc_en' => 'Complete Umrah package including comfortable hotel near the Kaaba, airport transfers, and specialized religious guide throughout the trip.',
        'includes_ar' => ['إقامة فندقية 5 نجوم', 'تذاكر طيران ذهاباً وإياباً', 'النقل الداخلي', 'المرشد الديني', 'وجبات الإفطار', 'تأشيرة العمرة'],
        'includes_fr' => ['Hébergement 5 étoiles', 'Billets A/R', 'Transferts internes', 'Guide religieux', 'Petit-déjeuner', 'Visa Omra'],
        'includes_en' => ['5-star hotel', 'Round-trip flights', 'Local transfers', 'Religious guide', 'Breakfast', 'Umrah visa'],
    ],
];
$offer = $offers[$id] ?? $offers[1];
$title    = $offer['title_' . $lang]   ?? $offer['title_en'];
$desc     = $offer['desc_' . $lang]    ?? $offer['desc_en'];
$includes = $offer['includes_' . $lang] ?? $offer['includes_en'];
$catClass = 'cat-' . $offer['cat'];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> — <?= htmlspecialchars($t['site_name']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body>
    <?php require_once __DIR__ . '/../../includes/header.php'; ?>

    <section class="page-hero">
        <div class="container">
            <div class="breadcrumbs">
                <a href="../../?lang=<?= $lang ?>"><?= htmlspecialchars($t['nav_home']) ?></a>
                <span class="sep"><i class="fas fa-chevron-<?= $dir === 'rtl' ? 'left' : 'right' ?>"></i></span>
                <a href="offers.php?lang=<?= $lang ?>"><?= htmlspecialchars($t['nav_offers']) ?></a>
                <span class="sep"><i class="fas fa-chevron-<?= $dir === 'rtl' ? 'left' : 'right' ?>"></i></span>
                <span><?= htmlspecialchars($title) ?></span>
            </div>
            <h1><?= htmlspecialchars($title) ?></h1>
        </div>
    </section>

    <div class="container">
        <div class="offer-detail-layout">

            <!-- Main content -->
            <div>
                <!-- Hero image -->
                <div class="offer-detail-hero <?= $catClass ?>">
                    <?= $offer['emoji'] ?>
                </div>

                <!-- Info -->
                <div class="card" style="margin-bottom:24px;">
                    <div class="card-header">
                        <h3><?= $lang === 'ar' ? 'تفاصيل العرض' : ($lang === 'fr' ? 'Détails de l\'offre' : 'Offer Details') ?></h3>
                        <span class="badge badge-confirmed">
                            <i class="fas fa-check-circle"></i>
                            <?= $lang === 'ar' ? 'متاح' : ($lang === 'fr' ? 'Disponible' : 'Available') ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-bottom:24px;">
                            <div style="text-align:center;padding:16px;background:var(--bg-light);border-radius:var(--radius-sm);">
                                <i class="fas fa-map-marker-alt" style="color:var(--gold);font-size:1.4rem;display:block;margin-bottom:8px;"></i>
                                <div style="font-size:.8rem;color:var(--text-light);"><?= htmlspecialchars($t['offer_location']) ?></div>
                                <div style="font-weight:700;color:var(--primary);"><?= htmlspecialchars($offer['loc']) ?></div>
                            </div>
                            <?php if ($offer['days'] > 0): ?>
                                <div style="text-align:center;padding:16px;background:var(--bg-light);border-radius:var(--radius-sm);">
                                    <i class="fas fa-calendar-alt" style="color:var(--gold);font-size:1.4rem;display:block;margin-bottom:8px;"></i>
                                    <div style="font-size:.8rem;color:var(--text-light);"><?= htmlspecialchars($t['offer_duration']) ?></div>
                                    <div style="font-weight:700;color:var(--primary);"><?= $offer['days'] ?> <?= htmlspecialchars($t['offer_days']) ?></div>
                                </div>
                            <?php endif; ?>
                            <div style="text-align:center;padding:16px;background:var(--bg-light);border-radius:var(--radius-sm);">
                                <i class="fas fa-tag" style="color:var(--gold);font-size:1.4rem;display:block;margin-bottom:8px;"></i>
                                <div style="font-size:.8rem;color:var(--text-light);"><?= htmlspecialchars($t['offer_price']) ?></div>
                                <div style="font-weight:800;color:var(--gold-dark);font-size:1.2rem;"><?= number_format($offer['price']) ?> <?= htmlspecialchars($t['offer_currency']) ?></div>
                            </div>
                        </div>
                        <p style="color:var(--text-medium);line-height:1.8;"><?= htmlspecialchars($desc) ?></p>
                    </div>
                </div>

                <!-- Includes -->
                <div class="card">
                    <div class="card-header">
                        <h3><?= htmlspecialchars($t['offer_includes']) ?></h3>
                    </div>
                    <div class="card-body">
                        <ul class="offer-includes-list">
                            <?php foreach ($includes as $item): ?>
                                <li><i class="fas fa-check-circle check-icon"></i><?= htmlspecialchars($item) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Booking sidebar -->
            <div id="book">
                <div class="booking-summary-card" style="margin-bottom:20px;">
                    <h3><?= htmlspecialchars($t['booking_summary']) ?></h3>
                    <div class="summary-line">
                        <span class="sl-label"><?= htmlspecialchars($t['offer_location']) ?></span>
                        <span class="sl-val"><?= htmlspecialchars($offer['loc']) ?></span>
                    </div>
                    <?php if ($offer['days'] > 0): ?>
                        <div class="summary-line">
                            <span class="sl-label"><?= htmlspecialchars($t['offer_duration']) ?></span>
                            <span class="sl-val"><?= $offer['days'] ?> <?= htmlspecialchars($t['offer_days']) ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="summary-total">
                        <span class="sl-label"><?= htmlspecialchars($t['offer_price']) ?></span>
                        <span class="sl-val"><?= number_format($offer['price']) ?> <?= htmlspecialchars($t['offer_currency']) ?></span>
                    </div>
                </div>

                <!-- Booking form -->
                <div class="card">
                    <div class="card-header">
                        <h3><?= htmlspecialchars($t['booking_title']) ?></h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($_SESSION['user_id'])): ?>
                            <button type="button" id="autofillBtn" class="btn btn-outline btn-sm btn-block" style="margin-bottom:16px;"
                                data-name="<?= htmlspecialchars($_SESSION['name'] ?? '') ?>"
                                data-email="" data-phone="" data-passport="">
                                <i class="fas fa-magic"></i> <?= htmlspecialchars($t['booking_autofill']) ?>
                            </button>
                        <?php endif; ?>

                        <form action="#" method="POST" novalidate>
                            <?php if (function_exists('generate_csrf_token')): ?>
                                <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                            <?php endif; ?>
                            <input type="hidden" name="offer_id" value="<?= $id ?>">

                            <div class="form-group">
                                <label><?= htmlspecialchars($t['booking_full_name']) ?></label>
                                <input type="text" name="full_name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label><?= htmlspecialchars($t['booking_phone']) ?></label>
                                <input type="tel" name="phone" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label><?= htmlspecialchars($t['booking_passport']) ?></label>
                                <input type="text" name="passport_number" class="form-control">
                            </div>
                            <div class="form-group">
                                <label><?= htmlspecialchars($t['booking_travelers_count']) ?></label>
                                <select name="travelers_count" class="form-control">
                                    <?php for ($i = 1; $i <= 10; $i++) echo "<option value='$i'>$i</option>"; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><?= htmlspecialchars($t['booking_notes']) ?></label>
                                <textarea name="notes" class="form-control" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block" style="padding:14px;font-size:1rem;">
                                <i class="fas fa-calendar-check"></i> <?= htmlspecialchars($t['booking_confirm']) ?>
                            </button>
                        </form>
                    </div>
                </div>

                <div style="margin-top:16px;">
                    <a href="offers.php?lang=<?= $lang ?>" class="btn btn-outline btn-block" style="padding:12px;">
                        <i class="fas fa-arrow-<?= $dir === 'rtl' ? 'right' : 'left' ?>"></i> <?= htmlspecialchars($t['btn_back']) ?>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
    <script src="../../assets/js/main.js"></script>
</body>

</html>