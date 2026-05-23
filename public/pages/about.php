<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';
if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar', 'fr', 'en'])) $_SESSION['lang'] = $_GET['lang'];
$lang = $_SESSION['lang'] ?? 'ar';
$t    = require __DIR__ . '/../../lang/' . $lang . '.php';
$dir  = $lang === 'ar' ? 'rtl' : 'ltr';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($t['about_title']) ?> — <?= htmlspecialchars($t['site_name']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body>
    <?php require_once __DIR__ . '/../../includes/header.php'; ?>

    <!-- Page Hero -->
    <section class="page-hero">
        <div class="container">
            <div class="breadcrumbs">
                <a href="../../?lang=<?= $lang ?>"><?= htmlspecialchars($t['nav_home']) ?></a>
                <span class="sep"><i class="fas fa-chevron-<?= $dir === 'rtl' ? 'left' : 'right' ?>"></i></span>
                <span><?= htmlspecialchars($t['about_title']) ?></span>
            </div>
            <h1><?= htmlspecialchars($t['about_title']) ?></h1>
            <p><?= htmlspecialchars($t['about_subtitle']) ?></p>
        </div>
    </section>

    <!-- About Content -->
    <section class="section" style="background:white;">
        <div class="container">
            <div class="about-layout">
                <div>
                    <h2 style="color:var(--primary);font-size:1.8rem;margin-bottom:18px;">
                        <?= htmlspecialchars($t['about_subtitle']) ?>
                    </h2>
                    <p style="color:var(--text-medium);font-size:1.05rem;line-height:1.9;margin-bottom:24px;">
                        <?= htmlspecialchars($t['about_text']) ?>
                    </p>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:28px;">
                        <div style="padding:20px;background:var(--bg-light);border-radius:var(--radius-md);border-left:4px solid var(--gold);">
                            <h4 style="color:var(--primary);margin-bottom:8px;"><i class="fas fa-bullseye" style="color:var(--gold);margin-<?= $dir === 'rtl' ? 'left' : 'right' ?>:8px;"></i><?= htmlspecialchars($t['about_mission']) ?></h4>
                            <p style="font-size:.9rem;color:var(--text-medium);"><?= htmlspecialchars($t['about_mission_text']) ?></p>
                        </div>
                        <div style="padding:20px;background:var(--bg-light);border-radius:var(--radius-md);border-left:4px solid var(--primary);">
                            <h4 style="color:var(--primary);margin-bottom:8px;"><i class="fas fa-eye" style="color:var(--primary);margin-<?= $dir === 'rtl' ? 'left' : 'right' ?>:8px;"></i><?= htmlspecialchars($t['about_vision']) ?></h4>
                            <p style="font-size:.9rem;color:var(--text-medium);"><?= htmlspecialchars($t['about_vision_text']) ?></p>
                        </div>
                    </div>
                    <div style="margin-top:32px;display:flex;gap:16px;flex-wrap:wrap;">
                        <a href="offers.php?lang=<?= $lang ?>" class="btn btn-dark btn-lg">
                            <i class="fas fa-compass"></i> <?= htmlspecialchars($t['hero_cta']) ?>
                        </a>
                        <a href="contact.php?lang=<?= $lang ?>" class="btn btn-outline btn-lg">
                            <i class="fas fa-phone"></i> <?= htmlspecialchars($t['nav_contact']) ?>
                        </a>
                    </div>
                </div>
                <div class="about-image-block">🕌</div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="section why-section">
        <div class="container">
            <div class="why-grid">
                <div class="why-card">
                    <span class="why-icon"><span class="stat-number" data-target="5000" data-suffix="+">5000+</span></span>
                    <h3><?= htmlspecialchars($t['hero_stat_clients']) ?></h3>
                    <p><?= $lang === 'ar' ? 'عميل راضٍ وثق بخدماتنا' : ($lang === 'fr' ? 'Clients satisfaits nous ayant fait confiance' : 'Satisfied clients who trusted our services') ?></p>
                </div>
                <div class="why-card">
                    <span class="why-icon"><span class="stat-number" data-target="120" data-suffix="+">120+</span></span>
                    <h3><?= htmlspecialchars($t['hero_stat_offers']) ?></h3>
                    <p><?= $lang === 'ar' ? 'عرض سياحي متنوع' : ($lang === 'fr' ? 'Offres touristiques variées' : 'Diverse travel packages') ?></p>
                </div>
                <div class="why-card">
                    <span class="why-icon"><span class="stat-number" data-target="14" data-suffix="+">14+</span></span>
                    <h3><?= htmlspecialchars($t['hero_stat_years']) ?></h3>
                    <p><?= $lang === 'ar' ? 'سنة خبرة في قطاع السياحة' : ($lang === 'fr' ? 'Ans d\'expérience dans le tourisme' : 'Years of experience in tourism') ?></p>
                </div>
                <div class="why-card">
                    <span class="why-icon">🌍</span>
                    <h3><?= $lang === 'ar' ? '30+ وجهة' : ($lang === 'fr' ? '30+ Destinations' : '30+ Destinations') ?></h3>
                    <p><?= $lang === 'ar' ? 'وجهة سياحية حول العالم' : ($lang === 'fr' ? 'Destinations touristiques mondiales' : 'Tourist destinations around the world') ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Values -->
    <section class="section" style="background:white;">
        <div class="container">
            <div class="section-header">
                <h2><?= htmlspecialchars($t['about_values']) ?></h2>
            </div>
            <div class="values-grid">
                <div class="value-card">
                    <span class="value-icon">🤝</span>
                    <h3><?= $lang === 'ar' ? 'الأمانة والشفافية' : ($lang === 'fr' ? 'Honnêteté & Transparence' : 'Honesty & Transparency') ?></h3>
                    <p><?= $lang === 'ar' ? 'نلتزم بالشفافية الكاملة في جميع تعاملاتنا مع عملائنا.' : ($lang === 'fr' ? 'Nous nous engageons à une totale transparence avec nos clients.' : 'We commit to full transparency in all our client dealings.') ?></p>
                </div>
                <div class="value-card">
                    <span class="value-icon">⭐</span>
                    <h3><?= $lang === 'ar' ? 'الجودة والتميز' : ($lang === 'fr' ? 'Qualité & Excellence' : 'Quality & Excellence') ?></h3>
                    <p><?= $lang === 'ar' ? 'نسعى دائماً لتقديم أعلى مستويات الجودة في جميع خدماتنا.' : ($lang === 'fr' ? 'Nous visons toujours les plus hauts standards de qualité.' : 'We always aim for the highest quality standards in all services.') ?></p>
                </div>
                <div class="value-card">
                    <span class="value-icon">❤️</span>
                    <h3><?= $lang === 'ar' ? 'الاهتمام بالعميل' : ($lang === 'fr' ? 'Satisfaction Client' : 'Customer Care') ?></h3>
                    <p><?= $lang === 'ar' ? 'رضا عملائنا هو أولويتنا القصوى في كل قرار نتخذه.' : ($lang === 'fr' ? 'La satisfaction de nos clients est notre priorité absolue.' : 'Our client\'s satisfaction is our highest priority in every decision.') ?></p>
                </div>
            </div>
        </div>
    </section>

    <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
    <script src="../../assets/js/main.js"></script>
</body>

</html>