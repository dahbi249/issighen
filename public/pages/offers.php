<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';
if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar', 'fr', 'en'])) $_SESSION['lang'] = $_GET['lang'];
$lang = $_SESSION['lang'] ?? 'ar';
$t    = require __DIR__ . '/../../lang/' . $lang . '.php';
$dir  = $lang === 'ar' ? 'rtl' : 'ltr';
$activeCat = htmlspecialchars($_GET['cat'] ?? 'all');
$searchQ   = htmlspecialchars($_GET['q']   ?? '');

// Sample offers data (in production: fetch from DB)
$offers = [
    ['id' => 1, 'cat' => 'umrah', 'emoji' => '🕌', 'title_ar' => 'برنامج العمرة المميز',      'title_fr' => 'Programme Omra Premium',        'title_en' => 'Premium Umrah Package',    'price' => 85000,  'days' => 10, 'loc' => 'Makkah'],
    ['id' => 2, 'cat' => 'umrah', 'emoji' => '🕌', 'title_ar' => 'عمرة رمضان الذهبية',        'title_fr' => 'Omra Ramadan Dorée',            'title_en' => 'Golden Ramadan Umrah',     'price' => 110000, 'days' => 15, 'loc' => 'Makkah & Madinah'],
    ['id' => 3, 'cat' => 'umrah', 'emoji' => '🕋', 'title_ar' => 'برنامج الحج الكامل',         'title_fr' => 'Programme Hajj Complet',        'title_en' => 'Complete Hajj Package',    'price' => 450000, 'days' => 25, 'loc' => 'Makkah'],
    ['id' => 4, 'cat' => 'tourism', 'emoji' => '🌍', 'title_ar' => 'رحلة إسطنبول السياحية',    'title_fr' => 'Voyage Touristique Istanbul',    'title_en' => 'Istanbul Tourism Trip',    'price' => 120000, 'days' => 7, 'loc' => 'Istanbul'],
    ['id' => 5, 'cat' => 'tourism', 'emoji' => '🏖', 'title_ar' => 'شواطئ تركيا الرائعة',       'title_fr' => 'Plages de Turquie',             'title_en' => 'Turkey Beach Holiday',     'price' => 95000,  'days' => 6, 'loc' => 'Antalya'],
    ['id' => 6, 'cat' => 'tourism', 'emoji' => '🏰', 'title_ar' => 'جولة أوروبا العريقة',       'title_fr' => 'Tour d\'Europe Classique',      'title_en' => 'Classic Europe Tour',      'price' => 280000, 'days' => 14, 'loc' => 'Europe'],
    ['id' => 7, 'cat' => 'hotel',  'emoji' => '🏨', 'title_ar' => 'فنادق دبي الفاخرة',         'title_fr' => 'Hôtels de Luxe Dubai',          'title_en' => 'Dubai Luxury Hotels',      'price' => 45000,  'days' => 0, 'loc' => 'Dubai'],
    ['id' => 8, 'cat' => 'flight', 'emoji' => '✈️', 'title_ar' => 'تذاكر باريس الاقتصادية',   'title_fr' => 'Billets Paris Économiques',     'title_en' => 'Budget Paris Flights',     'price' => 60000,  'days' => 0, 'loc' => 'Paris'],
    ['id' => 9, 'cat' => 'visa',   'emoji' => '📋', 'title_ar' => 'فيزا شنغن أوروبية',         'title_fr' => 'Visa Schengen Européen',        'title_en' => 'European Schengen Visa',   'price' => 15000,  'days' => 0, 'loc' => 'Europe'],
    ['id' => 10, 'cat' => 'car',   'emoji' => '🚗', 'title_ar' => 'تأجير سيارات VIP',          'title_fr' => 'Location Voitures VIP',         'title_en' => 'VIP Car Rental',           'price' => 8000,   'days' => 0, 'loc' => 'Algeria'],
];
// Filter
if ($activeCat !== 'all') $offers = array_filter($offers, fn($o) => $o['cat'] === $activeCat);
if ($searchQ) $offers = array_filter($offers, function ($o) use ($searchQ, $lang) {
    $key = 'title_' . $lang;
    return stripos($o[$key] ?? '', $searchQ) !== false;
});
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($t['offers_title']) ?> — <?= htmlspecialchars($t['site_name']) ?></title>
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
                <span><?= htmlspecialchars($t['offers_title']) ?></span>
            </div>
            <h1><?= htmlspecialchars($t['offers_title']) ?></h1>
            <p><?= htmlspecialchars($t['offers_subtitle']) ?></p>
        </div>
    </section>

    <section class="section" style="background:var(--bg-light);">
        <div class="container">

            <!-- Filter + Search bar -->
            <div class="filter-bar">
                <div style="display:flex;gap:8px;flex-wrap:wrap;flex:1;">
                    <?php
                    $cats = [
                        'all'    => $t['btn_all'],
                        'umrah'  => $t['category_umrah'],
                        'hajj'   => $t['category_hajj'],
                        'tourism' => $t['category_tourism'],
                        'hotel'  => $t['category_hotel'],
                        'flight' => $t['category_flight'],
                        'visa'   => $t['category_visa'],
                        'car'    => $t['category_car'],
                    ];
                    foreach ($cats as $catKey => $catLabel):
                    ?>
                        <a href="?cat=<?= $catKey ?>&lang=<?= $lang ?>"
                            class="filter-pill <?= $activeCat === $catKey ? 'active' : '' ?>">
                            <?= htmlspecialchars($catLabel) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                <form action="" method="GET" class="filter-search" style="max-width:280px;">
                    <input type="hidden" name="lang" value="<?= $lang ?>">
                    <input type="hidden" name="cat" value="<?= $activeCat ?>">
                    <input type="text" name="q" id="offerSearch"
                        value="<?= $searchQ ?>"
                        placeholder="<?= htmlspecialchars($t['search_placeholder']) ?>">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <?php if (empty($offers)): ?>
                <div class="empty-state">
                    <span class="empty-icon">🔍</span>
                    <h3><?= htmlspecialchars($t['no_results']) ?></h3>
                    <p><?= $lang === 'ar' ? 'لم يتم العثور على نتائج، حاول تعديل البحث.' : ($lang === 'fr' ? 'Aucun résultat trouvé, essayez de modifier votre recherche.' : 'No results found, try adjusting your search.') ?></p>
                    <a href="?lang=<?= $lang ?>" class="btn btn-dark btn-sm" style="margin-top:16px;"><?= htmlspecialchars($t['btn_all']) ?></a>
                </div>
            <?php else: ?>
                <div class="offers-grid">
                    <?php foreach ($offers as $offer):
                        $titleKey = 'title_' . $lang;
                        $title    = $offer[$titleKey] ?? $offer['title_en'];
                        $catClass = 'cat-' . $offer['cat'];
                    ?>
                        <div class="offer-card" data-cat="<?= $offer['cat'] ?>">
                            <div class="offer-image <?= $catClass ?>">
                                <span class="offer-badge"><?= htmlspecialchars($t['category_' . $offer['cat']] ?? $offer['cat']) ?></span>
                                <?= $offer['emoji'] ?>
                            </div>
                            <div class="offer-body">
                                <div class="offer-meta">
                                    <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($offer['loc']) ?></span>
                                    <?php if ($offer['days'] > 0): ?>
                                        <span><i class="fas fa-clock"></i> <?= $offer['days'] ?> <?= htmlspecialchars($t['offer_days']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="offer-title"><?= htmlspecialchars($title) ?></div>
                                <div class="offer-footer">
                                    <div class="offer-price">
                                        <span class="from"><?= htmlspecialchars($t['offer_from']) ?></span>
                                        <span><span class="amount"><?= number_format($offer['price']) ?></span><span class="currency"> <?= htmlspecialchars($t['offer_currency']) ?></span></span>
                                    </div>
                                    <div style="display:flex;gap:6px;">
                                        <a href="offer-detail.php?id=<?= $offer['id'] ?>&lang=<?= $lang ?>" class="btn btn-outline btn-sm"><?= htmlspecialchars($t['offer_details']) ?></a>
                                        <a href="offer-detail.php?id=<?= $offer['id'] ?>&lang=<?= $lang ?>#book" class="btn btn-dark btn-sm"><?= htmlspecialchars($t['offer_book']) ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </section>

    <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
    <script src="../../assets/js/main.js"></script>
</body>

</html>