<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/functions.php';
if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar', 'fr', 'en'])) $_SESSION['lang'] = $_GET['lang'];
$lang = $_SESSION['lang'] ?? 'ar';
$t    = require __DIR__ . '/lang/' . $lang . '.php';
$dir  = $lang === 'ar' ? 'rtl' : 'ltr';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($t['site_name']) ?> — <?= htmlspecialchars($t['site_tagline']) ?></title>
    <meta name="description" content="<?= htmlspecialchars($t['hero_subtitle']) ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <?php require_once __DIR__ . '/includes/header.php'; ?>

    <!-- ===== HERO ===== -->
    <section class="hero">
        <div class="hero-bg-shapes">
            <div class="hero-shape hero-shape-1"></div>
            <div class="hero-shape hero-shape-2"></div>
            <div class="hero-shape hero-shape-3"></div>
        </div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-star"></i>
                    <?= $lang === 'ar' ? 'وكالة موثوقة منذ 2010' : ($lang === 'fr' ? 'Agence de confiance depuis 2010' : 'Trusted agency since 2010') ?>
                </div>
                <h1><?= $t['hero_title'] ?></h1>
                <p><?= htmlspecialchars($t['hero_subtitle']) ?></p>

                <form class="hero-search" action="public/pages/offers.php" method="GET">
                    <input type="hidden" name="lang" value="<?= $lang ?>">
                    <input type="text" name="q" placeholder="<?= htmlspecialchars($t['search_placeholder']) ?>">
                    <button type="submit"><?= htmlspecialchars($t['search_btn']) ?> <i class="fas fa-search"></i></button>
                </form>

                <div class="hero-buttons">
                    <a href="public/pages/offers.php?lang=<?= $lang ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-compass"></i> <?= htmlspecialchars($t['hero_cta']) ?>
                    </a>
                    <a href="public/pages/contact.php?lang=<?= $lang ?>" class="btn btn-outline-white btn-lg">
                        <i class="fas fa-phone"></i> <?= htmlspecialchars($t['hero_contact']) ?>
                    </a>
                </div>

                <div class="hero-stats">
                    <div class="hero-stat">
                        <span class="stat-number" data-target="5000" data-suffix="+">0</span>
                        <span class="stat-label"><?= htmlspecialchars($t['hero_stat_clients']) ?></span>
                    </div>
                    <div class="hero-stat">
                        <span class="stat-number" data-target="120" data-suffix="+">0</span>
                        <span class="stat-label"><?= htmlspecialchars($t['hero_stat_offers']) ?></span>
                    </div>
                    <div class="hero-stat">
                        <span class="stat-number" data-target="14" data-suffix="+">0</span>
                        <span class="stat-label"><?= htmlspecialchars($t['hero_stat_years']) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== SERVICES ===== -->
    <section class="section services-section">
        <div class="container">
            <div class="section-header">
                <h2><?= htmlspecialchars($t['services_title']) ?></h2>
                <p><?= htmlspecialchars($t['services_subtitle']) ?></p>
            </div>
            <div class="services-grid">
                <a href="public/pages/offers.php?cat=umrah&lang=<?= $lang ?>" class="service-card">
                    <div class="service-icon">🕌</div>
                    <div class="service-card-title"><?= htmlspecialchars($t['service_umrah']) ?></div>
                    <div class="service-card-desc"><?= htmlspecialchars($t['service_umrah_desc']) ?></div>
                </a>
                <a href="public/pages/offers.php?cat=tourism&lang=<?= $lang ?>" class="service-card">
                    <div class="service-icon">🌍</div>
                    <div class="service-card-title"><?= htmlspecialchars($t['service_tourism']) ?></div>
                    <div class="service-card-desc"><?= htmlspecialchars($t['service_tourism_desc']) ?></div>
                </a>
                <a href="public/pages/offers.php?cat=hotel&lang=<?= $lang ?>" class="service-card">
                    <div class="service-icon">🏨</div>
                    <div class="service-card-title"><?= htmlspecialchars($t['service_hotels']) ?></div>
                    <div class="service-card-desc"><?= htmlspecialchars($t['service_hotels_desc']) ?></div>
                </a>
                <a href="public/pages/offers.php?cat=flight&lang=<?= $lang ?>" class="service-card">
                    <div class="service-icon">✈️</div>
                    <div class="service-card-title"><?= htmlspecialchars($t['service_flights']) ?></div>
                    <div class="service-card-desc"><?= htmlspecialchars($t['service_flights_desc']) ?></div>
                </a>
                <a href="public/pages/offers.php?cat=visa&lang=<?= $lang ?>" class="service-card">
                    <div class="service-icon">📋</div>
                    <div class="service-card-title"><?= htmlspecialchars($t['service_visa']) ?></div>
                    <div class="service-card-desc"><?= htmlspecialchars($t['service_visa_desc']) ?></div>
                </a>
                <a href="public/pages/offers.php?cat=car&lang=<?= $lang ?>" class="service-card">
                    <div class="service-icon">🚗</div>
                    <div class="service-card-title"><?= htmlspecialchars($t['service_cars']) ?></div>
                    <div class="service-card-desc"><?= htmlspecialchars($t['service_cars_desc']) ?></div>
                </a>
            </div>
        </div>
    </section>

    <!-- ===== FEATURED OFFERS ===== -->
    <section class="section" style="background:var(--bg-light);">
        <div class="container">
            <div class="section-header">
                <h2><?= htmlspecialchars($t['offers_title']) ?></h2>
                <p><?= htmlspecialchars($t['offers_subtitle']) ?></p>
            </div>

            <!-- Filter pills -->
            <div class="filter-bar" style="justify-content:center;margin-bottom:36px;">
                <button class="filter-pill active" data-filter="all"><?= htmlspecialchars($t['btn_all']) ?></button>
                <button class="filter-pill" data-filter="umrah"><?= htmlspecialchars($t['category_umrah']) ?></button>
                <button class="filter-pill" data-filter="tourism"><?= htmlspecialchars($t['category_tourism']) ?></button>
                <button class="filter-pill" data-filter="hotel"><?= htmlspecialchars($t['category_hotel']) ?></button>
                <button class="filter-pill" data-filter="flight"><?= htmlspecialchars($t['category_flight']) ?></button>
                <button class="filter-pill" data-filter="visa"><?= htmlspecialchars($t['category_visa']) ?></button>
            </div>

            <div class="offers-grid">
                <!-- Sample offer cards — in production these come from DB -->
                <div class="offer-card" data-cat="umrah">
                    <div class="offer-image cat-umrah">
                        <span class="offer-badge"><?= htmlspecialchars($t['category_umrah']) ?></span>
                        🕌
                    </div>
                    <div class="offer-body">
                        <div class="offer-meta">
                            <span><i class="fas fa-map-marker-alt"></i> Makkah</span>
                            <span><i class="fas fa-clock"></i> 10 <?= htmlspecialchars($t['offer_days']) ?></span>
                        </div>
                        <div class="offer-title"><?= $lang === 'ar' ? 'برنامج العمرة المميز' : ($lang === 'fr' ? 'Programme Omra Premium' : 'Premium Umrah Package') ?></div>
                        <div class="offer-desc"><?= $lang === 'ar' ? 'برنامج متكامل يشمل الإقامة الفندقية، النقل، والمرشد السياحي.' : ($lang === 'fr' ? 'Programme complet incluant hébergement, transport et guide.' : 'Complete package including hotel, transport and guide.') ?></div>
                        <div class="offer-footer">
                            <div class="offer-price">
                                <span class="from"><?= htmlspecialchars($t['offer_from']) ?></span>
                                <span><span class="amount">85,000</span><span class="currency"><?= htmlspecialchars($t['offer_currency']) ?></span></span>
                            </div>
                            <a href="public/pages/offer-detail.php?id=1&lang=<?= $lang ?>" class="btn btn-dark btn-sm"><?= htmlspecialchars($t['offer_book']) ?></a>
                        </div>
                    </div>
                </div>

                <div class="offer-card" data-cat="tourism">
                    <div class="offer-image cat-tourism">
                        <span class="offer-badge"><?= htmlspecialchars($t['category_tourism']) ?></span>
                        🌍
                    </div>
                    <div class="offer-body">
                        <div class="offer-meta">
                            <span><i class="fas fa-map-marker-alt"></i> Istanbul</span>
                            <span><i class="fas fa-clock"></i> 7 <?= htmlspecialchars($t['offer_days']) ?></span>
                        </div>
                        <div class="offer-title"><?= $lang === 'ar' ? 'رحلة إسطنبول السياحية' : ($lang === 'fr' ? 'Voyage Touristique Istanbul' : 'Istanbul Tourism Trip') ?></div>
                        <div class="offer-desc"><?= $lang === 'ar' ? 'استكشف جمال إسطنبول مع أفضل الفنادق والجولات السياحية.' : ($lang === 'fr' ? 'Découvrez la beauté d\'Istanbul avec les meilleurs hôtels.' : 'Explore the beauty of Istanbul with the best hotels and tours.') ?></div>
                        <div class="offer-footer">
                            <div class="offer-price">
                                <span class="from"><?= htmlspecialchars($t['offer_from']) ?></span>
                                <span><span class="amount">120,000</span><span class="currency"><?= htmlspecialchars($t['offer_currency']) ?></span></span>
                            </div>
                            <a href="public/pages/offer-detail.php?id=2&lang=<?= $lang ?>" class="btn btn-dark btn-sm"><?= htmlspecialchars($t['offer_book']) ?></a>
                        </div>
                    </div>
                </div>

                <div class="offer-card" data-cat="umrah">
                    <div class="offer-image cat-hajj">
                        <span class="offer-badge"><?= htmlspecialchars($t['category_hajj']) ?></span>
                        🕋
                    </div>
                    <div class="offer-body">
                        <div class="offer-meta">
                            <span><i class="fas fa-map-marker-alt"></i> Madinah</span>
                            <span><i class="fas fa-clock"></i> 15 <?= htmlspecialchars($t['offer_days']) ?></span>
                        </div>
                        <div class="offer-title"><?= $lang === 'ar' ? 'برنامج الحج الكامل' : ($lang === 'fr' ? 'Programme Hajj Complet' : 'Complete Hajj Package') ?></div>
                        <div class="offer-desc"><?= $lang === 'ar' ? 'برنامج حج شامل مع أفضل الخدمات والإقامة قرب الحرم.' : ($lang === 'fr' ? 'Programme de Hajj complet avec les meilleurs services.' : 'Full Hajj package with the best services near the Haram.') ?></div>
                        <div class="offer-footer">
                            <div class="offer-price">
                                <span class="from"><?= htmlspecialchars($t['offer_from']) ?></span>
                                <span><span class="amount">450,000</span><span class="currency"><?= htmlspecialchars($t['offer_currency']) ?></span></span>
                            </div>
                            <a href="public/pages/offer-detail.php?id=3&lang=<?= $lang ?>" class="btn btn-dark btn-sm"><?= htmlspecialchars($t['offer_book']) ?></a>
                        </div>
                    </div>
                </div>

                <div class="offer-card" data-cat="visa">
                    <div class="offer-image cat-visa">
                        <span class="offer-badge"><?= htmlspecialchars($t['category_visa']) ?></span>
                        📋
                    </div>
                    <div class="offer-body">
                        <div class="offer-meta">
                            <span><i class="fas fa-globe"></i> Europe</span>
                            <span><i class="fas fa-clock"></i> 5-10 <?= htmlspecialchars($t['offer_days']) ?></span>
                        </div>
                        <div class="offer-title"><?= $lang === 'ar' ? 'فيزا شنغن أوروبية' : ($lang === 'fr' ? 'Visa Schengen Européen' : 'European Schengen Visa') ?></div>
                        <div class="offer-desc"><?= $lang === 'ar' ? 'خدمة استخراج تأشيرة شنغن لدول الاتحاد الأوروبي.' : ($lang === 'fr' ? 'Service d\'obtention du visa Schengen pour l\'Union Européenne.' : 'Schengen visa service for EU countries.') ?></div>
                        <div class="offer-footer">
                            <div class="offer-price">
                                <span class="from"><?= htmlspecialchars($t['offer_from']) ?></span>
                                <span><span class="amount">15,000</span><span class="currency"><?= htmlspecialchars($t['offer_currency']) ?></span></span>
                            </div>
                            <a href="public/pages/offer-detail.php?id=4&lang=<?= $lang ?>" class="btn btn-dark btn-sm"><?= htmlspecialchars($t['offer_book']) ?></a>
                        </div>
                    </div>
                </div>

                <div class="offer-card" data-cat="hotel">
                    <div class="offer-image cat-hotel">
                        <span class="offer-badge"><?= htmlspecialchars($t['category_hotel']) ?></span>
                        🏨
                    </div>
                    <div class="offer-body">
                        <div class="offer-meta">
                            <span><i class="fas fa-map-marker-alt"></i> Dubai</span>
                            <span><i class="fas fa-star" style="color:var(--gold);"></i><i class="fas fa-star" style="color:var(--gold);"></i><i class="fas fa-star" style="color:var(--gold);"></i><i class="fas fa-star" style="color:var(--gold);"></i><i class="fas fa-star" style="color:var(--gold);"></i></span>
                        </div>
                        <div class="offer-title"><?= $lang === 'ar' ? 'حجز فنادق دبي الفاخرة' : ($lang === 'fr' ? 'Hôtels de Luxe Dubai' : 'Dubai Luxury Hotels') ?></div>
                        <div class="offer-desc"><?= $lang === 'ar' ? 'أفضل الفنادق الفاخرة في دبي بأسعار تنافسية.' : ($lang === 'fr' ? 'Les meilleurs hôtels de luxe à Dubai à des prix compétitifs.' : 'Best luxury hotels in Dubai at competitive prices.') ?></div>
                        <div class="offer-footer">
                            <div class="offer-price">
                                <span class="from"><?= htmlspecialchars($t['offer_from']) ?></span>
                                <span><span class="amount">45,000</span><span class="currency"><?= htmlspecialchars($t['offer_currency']) ?></span></span>
                            </div>
                            <a href="public/pages/offer-detail.php?id=5&lang=<?= $lang ?>" class="btn btn-dark btn-sm"><?= htmlspecialchars($t['offer_book']) ?></a>
                        </div>
                    </div>
                </div>

                <div class="offer-card" data-cat="flight">
                    <div class="offer-image cat-flight">
                        <span class="offer-badge"><?= htmlspecialchars($t['category_flight']) ?></span>
                        ✈️
                    </div>
                    <div class="offer-body">
                        <div class="offer-meta">
                            <span><i class="fas fa-map-marker-alt"></i> Paris</span>
                            <span><i class="fas fa-clock"></i> <?= $lang === 'ar' ? 'ذهاب وإياب' : ($lang === 'fr' ? 'Aller-Retour' : 'Round Trip') ?></span>
                        </div>
                        <div class="offer-title"><?= $lang === 'ar' ? 'تذاكر باريس الاقتصادية' : ($lang === 'fr' ? 'Billets Paris Économiques' : 'Budget Paris Flights') ?></div>
                        <div class="offer-desc"><?= $lang === 'ar' ? 'تذاكر طيران مباشرة إلى باريس بأفضل الأسعار.' : ($lang === 'fr' ? 'Vols directs vers Paris aux meilleurs prix.' : 'Direct flights to Paris at the best prices.') ?></div>
                        <div class="offer-footer">
                            <div class="offer-price">
                                <span class="from"><?= htmlspecialchars($t['offer_from']) ?></span>
                                <span><span class="amount">60,000</span><span class="currency"><?= htmlspecialchars($t['offer_currency']) ?></span></span>
                            </div>
                            <a href="public/pages/offer-detail.php?id=6&lang=<?= $lang ?>" class="btn btn-dark btn-sm"><?= htmlspecialchars($t['offer_book']) ?></a>
                        </div>
                    </div>
                </div>
            </div>

            <div style="text-align:center;margin-top:44px;">
                <a href="public/pages/offers.php?lang=<?= $lang ?>" class="btn btn-dark btn-lg">
                    <?= htmlspecialchars($t['offers_view_all']) ?> <i class="fas fa-arrow-<?= $dir === 'rtl' ? 'left' : 'right' ?>"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- ===== WHY CHOOSE US ===== -->
    <section class="section why-section">
        <div class="container">
            <div class="section-header">
                <h2><?= htmlspecialchars($t['why_title']) ?></h2>
                <p><?= htmlspecialchars($t['why_subtitle']) ?></p>
            </div>
            <div class="why-grid">
                <div class="why-card">
                    <span class="why-icon">🏆</span>
                    <h3><?= htmlspecialchars($t['why_experience']) ?></h3>
                    <p><?= htmlspecialchars($t['why_experience_text']) ?></p>
                </div>
                <div class="why-card">
                    <span class="why-icon">💰</span>
                    <h3><?= htmlspecialchars($t['why_prices']) ?></h3>
                    <p><?= htmlspecialchars($t['why_prices_text']) ?></p>
                </div>
                <div class="why-card">
                    <span class="why-icon">🎯</span>
                    <h3><?= htmlspecialchars($t['why_service']) ?></h3>
                    <p><?= htmlspecialchars($t['why_service_text']) ?></p>
                </div>
                <div class="why-card">
                    <span class="why-icon">🔒</span>
                    <h3><?= htmlspecialchars($t['why_trust']) ?></h3>
                    <p><?= htmlspecialchars($t['why_trust_text']) ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== CTA SECTION ===== -->
    <section class="section" style="background:var(--bg-light);text-align:center;">
        <div class="container" style="max-width:700px;">
            <div style="font-size:3rem;margin-bottom:16px;">✈️</div>
            <h2 style="color:var(--primary);font-size:1.9rem;margin-bottom:14px;">
                <?= $lang === 'ar' ? 'هل أنت مستعد للسفر معنا؟' : ($lang === 'fr' ? 'Prêt à voyager avec nous ?' : 'Ready to travel with us?') ?>
            </h2>
            <p style="color:var(--text-medium);margin-bottom:32px;font-size:1.05rem;">
                <?= $lang === 'ar' ? 'سجّل الآن واحصل على أفضل العروض السياحية حصراً لأعضائنا.' : ($lang === 'fr' ? 'Inscrivez-vous maintenant et bénéficiez des meilleures offres exclusives.' : 'Register now and get the best exclusive travel deals for our members.') ?>
            </p>
            <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
                <a href="public/auth/register.php?lang=<?= $lang ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-user-plus"></i>
                    <?= htmlspecialchars($t['nav_register']) ?>
                </a>
                <a href="public/pages/contact.php?lang=<?= $lang ?>" class="btn btn-outline btn-lg">
                    <i class="fas fa-phone"></i>
                    <?= htmlspecialchars($t['nav_contact']) ?>
                </a>
            </div>
        </div>
    </section>

    <?php require_once __DIR__ . '/includes/footer.php'; ?>

    <script src="assets/js/main.js"></script>
</body>

</html>