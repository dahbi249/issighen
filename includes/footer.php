<!-- includes/footer.php — Site Footer (variables $lang, $t, $dir must be set by the calling page) -->
<?php $t = $t ?? []; $lang = $lang ?? 'ar'; ?>
<footer class="footer">
    <div class="container">
        <div class="footer-grid">

            <!-- Brand column -->
            <div class="footer-brand">
                <div class="logo">✈ <?= htmlspecialchars($t['site_name'] ?? 'Isighène') ?></div>
                <div class="tagline"><?= htmlspecialchars($t['site_tagline'] ?? 'Travel & Tourism Agency') ?></div>
                <p><?= htmlspecialchars($t['about_text'] ?? 'Your trusted travel partner for Umrah, Hajj and tourism.') ?></p>
                <div class="social-links">
                    <a href="#" class="social-link fb" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link ig" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link wa" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" class="social-link yt" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-col">
                <h4><?= htmlspecialchars($t['footer_links'] ?? 'Quick Links') ?></h4>
                <ul class="footer-links">
                    <li><a href="/tourism-agency/public/pages/home.php"><i class="fas fa-chevron-right" style="font-size:.7rem;"></i> <?= htmlspecialchars($t['nav_home']    ?? 'Home') ?></a></li>
                    <li><a href="/tourism-agency/public/pages/offers.php"><i class="fas fa-chevron-right" style="font-size:.7rem;"></i> <?= htmlspecialchars($t['nav_offers']  ?? 'Offers') ?></a></li>
                    <li><a href="/tourism-agency/public/pages/offers.php?cat=umrah"><i class="fas fa-chevron-right" style="font-size:.7rem;"></i> <?= htmlspecialchars($t['nav_umrah']   ?? 'Umrah') ?></a></li>
                    <li><a href="/tourism-agency/public/pages/about.php"><i class="fas fa-chevron-right" style="font-size:.7rem;"></i> <?= htmlspecialchars($t['nav_about']   ?? 'About') ?></a></li>
                    <li><a href="/tourism-agency/public/pages/contact.php"><i class="fas fa-chevron-right" style="font-size:.7rem;"></i> <?= htmlspecialchars($t['nav_contact'] ?? 'Contact') ?></a></li>
                </ul>
            </div>

            <!-- Services -->
            <div class="footer-col">
                <h4><?= htmlspecialchars($t['nav_services'] ?? 'Services') ?></h4>
                <ul class="footer-links">
                    <li><a href="/tourism-agency/public/pages/offers.php?cat=umrah"><i class="fas fa-chevron-right" style="font-size:.7rem;"></i> <?= htmlspecialchars($t['service_umrah']   ?? 'Umrah & Hajj') ?></a></li>
                    <li><a href="/tourism-agency/public/pages/offers.php?cat=tourism"><i class="fas fa-chevron-right" style="font-size:.7rem;"></i> <?= htmlspecialchars($t['service_tourism'] ?? 'Tourism') ?></a></li>
                    <li><a href="/tourism-agency/public/pages/offers.php?cat=hotel"><i class="fas fa-chevron-right" style="font-size:.7rem;"></i> <?= htmlspecialchars($t['service_hotels']   ?? 'Hotels') ?></a></li>
                    <li><a href="/tourism-agency/public/pages/offers.php?cat=flight"><i class="fas fa-chevron-right" style="font-size:.7rem;"></i> <?= htmlspecialchars($t['service_flights']  ?? 'Flights') ?></a></li>
                    <li><a href="/tourism-agency/public/pages/offers.php?cat=visa"><i class="fas fa-chevron-right" style="font-size:.7rem;"></i> <?= htmlspecialchars($t['service_visa']     ?? 'Visa') ?></a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="footer-col">
                <h4><?= htmlspecialchars($t['footer_contact'] ?? 'Contact') ?></h4>
                <ul class="footer-contact">
                    <li>
                        <span class="fc-icon"><i class="fas fa-map-marker-alt"></i></span>
                        <span>Algeria, Algiers — Main Street</span>
                    </li>
                    <li>
                        <span class="fc-icon"><i class="fas fa-phone"></i></span>
                        <span dir="ltr">+213 555 000 000</span>
                    </li>
                    <li>
                        <span class="fc-icon"><i class="fas fa-phone"></i></span>
                        <span dir="ltr">+213 555 111 111</span>
                    </li>
                    <li>
                        <span class="fc-icon"><i class="fas fa-envelope"></i></span>
                        <span>contact@isighene.com</span>
                    </li>
                </ul>
            </div>

        </div>

        <!-- Footer bottom -->
        <div class="footer-bottom">
            <span>&copy; <?= date('Y') ?> <?= htmlspecialchars($t['site_name'] ?? 'Isighène') ?> — <?= htmlspecialchars($t['copyright'] ?? 'All rights reserved') ?></span>
            <div class="lang-switcher" style="background:rgba(255,255,255,.07);">
                <a href="?lang=ar" style="color:rgba(255,255,255,.55);" class="<?= $lang==='ar' ? 'active-lang':'' ?>">AR</a>
                <a href="?lang=fr" style="color:rgba(255,255,255,.55);" class="<?= $lang==='fr' ? 'active-lang':'' ?>">FR</a>
                <a href="?lang=en" style="color:rgba(255,255,255,.55);" class="<?= $lang==='en' ? 'active-lang':'' ?>">EN</a>
            </div>
        </div>
    </div>
</footer>
