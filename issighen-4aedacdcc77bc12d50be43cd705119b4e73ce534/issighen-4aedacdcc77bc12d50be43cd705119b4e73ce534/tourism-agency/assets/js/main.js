/* ============================================================
   ISIGHENE TOURISM AGENCY — Main JavaScript
============================================================ */
document.addEventListener('DOMContentLoaded', function () {

    /* Mobile Menu */
    var menuToggle = document.getElementById('menuToggle');
    var navMenu    = document.getElementById('navMenu');
    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', function () {
            var open = navMenu.classList.toggle('open');
            menuToggle.innerHTML = open ? '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
        });
        document.addEventListener('click', function (e) {
            if (!menuToggle.contains(e.target) && !navMenu.contains(e.target)) {
                navMenu.classList.remove('open');
                menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
            }
        });
    }

    /* User Dropdown */
    var userMenuBtn = document.getElementById('userMenuBtn');
    if (userMenuBtn) {
        userMenuBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            this.classList.toggle('open');
        });
        document.addEventListener('click', function () {
            if (userMenuBtn) userMenuBtn.classList.remove('open');
        });
    }

    /* Dashboard Sidebar */
    var sidebarToggle  = document.getElementById('sidebarToggle');
    var sidebar        = document.getElementById('sidebar');
    var sidebarOverlay = document.getElementById('sidebarOverlay');
    function openSidebar()  { if (sidebar) sidebar.classList.add('open'); if (sidebarOverlay) sidebarOverlay.classList.add('show'); }
    function closeSidebar() { if (sidebar) sidebar.classList.remove('open'); if (sidebarOverlay) sidebarOverlay.classList.remove('show'); }
    if (sidebarToggle)  sidebarToggle.addEventListener('click', openSidebar);
    if (sidebarOverlay) sidebarOverlay.addEventListener('click', closeSidebar);

    /* Password Toggle */
    document.querySelectorAll('.toggle-password').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = this.closest('.input-group') && this.closest('.input-group').querySelector('input');
            if (!input) return;
            if (input.type === 'password') { input.type = 'text';     this.innerHTML = '<i class="fas fa-eye-slash"></i>'; }
            else                           { input.type = 'password'; this.innerHTML = '<i class="fas fa-eye"></i>';       }
        });
    });

    /* Auto-fill booking form */
    var autofillBtn = document.getElementById('autofillBtn');
    if (autofillBtn) {
        autofillBtn.addEventListener('click', function () {
            var d = this.dataset;
            function fill(n, v) { var el = document.querySelector('[name="' + n + '"]'); if (el && v) el.value = v; }
            fill('full_name', d.name); fill('email', d.email); fill('phone', d.phone); fill('passport_number', d.passport);
        });
    }

    /* Category filter pills */
    var filterPills = document.querySelectorAll('.filter-pill[data-filter]');
    var offerCards  = document.querySelectorAll('.offer-card[data-cat]');
    if (filterPills.length) {
        filterPills.forEach(function (pill) {
            pill.addEventListener('click', function () {
                filterPills.forEach(function (p) { p.classList.remove('active'); });
                this.classList.add('active');
                var filter = this.dataset.filter;
                offerCards.forEach(function (card) {
                    card.style.display = (filter === 'all' || card.dataset.cat === filter) ? '' : 'none';
                });
            });
        });
    }

    /* Live offer search */
    var searchInput = document.getElementById('offerSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            var q = this.value.toLowerCase().trim();
            offerCards.forEach(function (card) {
                var t = (card.querySelector('.offer-title') || {textContent:''}).textContent.toLowerCase();
                card.style.display = (!q || t.includes(q)) ? '' : 'none';
            });
        });
    }

    /* Admin status buttons */
    document.querySelectorAll('.status-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var group = this.closest('.status-changer');
            if (!group) return;
            group.querySelectorAll('.status-btn').forEach(function (b) { b.classList.remove('active'); });
            this.classList.add('active');
            var hi = group.querySelector('input[name="status"]');
            if (hi) hi.value = this.dataset.status;
        });
    });

    /* Delete confirmation */
    document.querySelectorAll('[data-confirm]').forEach(function (el) {
        el.addEventListener('click', function (e) {
            if (!confirm(this.dataset.confirm || 'Are you sure?')) e.preventDefault();
        });
    });

    /* Modal helpers */
    window.openModal  = function (id) { var m = document.getElementById(id); if (m) m.classList.add('open'); };
    window.closeModal = function (id) { var m = document.getElementById(id); if (m) m.classList.remove('open'); };
    document.querySelectorAll('[data-modal]').forEach(function (t) {
        t.addEventListener('click', function () { window.openModal(this.dataset.modal); });
    });
    document.querySelectorAll('.modal-close, [data-dismiss="modal"]').forEach(function (b) {
        b.addEventListener('click', function () { var m = this.closest('.modal-overlay'); if (m) m.classList.remove('open'); });
    });
    document.querySelectorAll('.modal-overlay').forEach(function (o) {
        o.addEventListener('click', function (e) { if (e.target === this) this.classList.remove('open'); });
    });

    /* Animated counters */
    document.querySelectorAll('.stat-number[data-target]').forEach(function (el) {
        var target = parseInt(el.dataset.target, 10);
        var suffix = el.dataset.suffix || '';
        var step = Math.ceil(target / 80);
        var current = 0;
        var timer = setInterval(function () {
            current = Math.min(current + step, target);
            el.textContent = current.toLocaleString() + suffix;
            if (current >= target) clearInterval(timer);
        }, 20);
    });

    /* Fade-in on scroll */
    if ('IntersectionObserver' in window) {
        var fadeEls = document.querySelectorAll('.service-card, .offer-card, .why-card, .value-card');
        var fadeObs = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    fadeObs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        fadeEls.forEach(function (el) {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity .45s ease, transform .45s ease';
            fadeObs.observe(el);
        });
    }

    /* Smooth scroll anchors */
    document.querySelectorAll('a[href^="#"]').forEach(function (a) {
        a.addEventListener('click', function (e) {
            var id = this.getAttribute('href').slice(1);
            var target = document.getElementById(id);
            if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
        });
    });

});
