(() => {
    const header = document.querySelector('[data-header]');
    const menuButton = document.querySelector('[data-menu-button]');
    const nav = document.querySelector('[data-nav]');
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const updateHeader = () => header?.classList.toggle('scrolled', window.scrollY > 24);
    updateHeader();
    window.addEventListener('scroll', updateHeader, { passive: true });

    const closeMenu = () => {
        menuButton?.setAttribute('aria-expanded', 'false');
        nav?.classList.remove('open');
        document.body.classList.remove('menu-open');
    };

    menuButton?.addEventListener('click', () => {
        const open = menuButton.getAttribute('aria-expanded') === 'true';
        menuButton.setAttribute('aria-expanded', String(!open));
        nav?.classList.toggle('open', !open);
        document.body.classList.toggle('menu-open', !open);
    });

    nav?.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMenu));
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') closeMenu();
    });

    const revealItems = document.querySelectorAll('.reveal');
    if (reducedMotion || !('IntersectionObserver' in window)) {
        revealItems.forEach((item) => item.classList.add('is-visible'));
    } else {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -25px' });

        revealItems.forEach((item) => observer.observe(item));
    }

    const sectionLinks = nav ? Array.from(nav.querySelectorAll('a[href^="#"]')) : [];
    const linkedSections = sectionLinks
        .map((link) => document.querySelector(link.getAttribute('href')))
        .filter(Boolean);

    if ('IntersectionObserver' in window && linkedSections.length) {
        const sectionObserver = new IntersectionObserver((entries) => {
            const visible = entries
                .filter((entry) => entry.isIntersecting)
                .sort((a, b) => b.intersectionRatio - a.intersectionRatio)[0];

            if (!visible) return;

            sectionLinks.forEach((link) => {
                const isCurrent = link.getAttribute('href') === `#${visible.target.id}`;
                if (isCurrent) link.setAttribute('aria-current', 'true');
                else link.removeAttribute('aria-current');
            });
        }, { rootMargin: '-30% 0px -55%', threshold: [0, 0.15, 0.35] });

        linkedSections.forEach((section) => sectionObserver.observe(section));
    }

    document.querySelectorAll('[data-project-gallery]').forEach((gallery) => {
        const track = gallery.querySelector('[data-gallery-track]');
        const slides = Array.from(gallery.querySelectorAll('[data-gallery-slide]'));
        const dots = Array.from(gallery.querySelectorAll('[data-gallery-dot]'));
        const previousButton = gallery.querySelector('[data-gallery-previous]');
        const nextButton = gallery.querySelector('[data-gallery-next]');
        const count = gallery.querySelector('[data-gallery-count]');
        const interval = Number.parseInt(gallery.dataset.autoplayInterval || '2000', 10);
        let currentIndex = 0;
        let autoplayTimer;

        const showSlide = (requestedIndex) => {
            currentIndex = (requestedIndex + slides.length) % slides.length;
            track.style.transform = `translateX(-${currentIndex * 100}%)`;

            slides.forEach((slide, index) => {
                slide.setAttribute('aria-hidden', String(index !== currentIndex));
            });
            dots.forEach((dot, index) => {
                dot.setAttribute('aria-current', String(index === currentIndex));
            });

            if (count) count.textContent = `${currentIndex + 1} / ${slides.length}`;
        };

        const stopAutoplay = () => window.clearInterval(autoplayTimer);
        const startAutoplay = () => {
            stopAutoplay();
            if (reducedMotion || slides.length < 2 || document.hidden) return;

            autoplayTimer = window.setInterval(() => showSlide(currentIndex + 1), interval);
        };
        const selectSlide = (index) => {
            showSlide(index);
            startAutoplay();
        };

        previousButton?.addEventListener('click', () => selectSlide(currentIndex - 1));
        nextButton?.addEventListener('click', () => selectSlide(currentIndex + 1));
        dots.forEach((dot) => {
            dot.addEventListener('click', () => selectSlide(Number.parseInt(dot.dataset.galleryDot, 10)));
        });
        gallery.addEventListener('mouseenter', stopAutoplay);
        gallery.addEventListener('mouseleave', startAutoplay);
        gallery.addEventListener('focusin', stopAutoplay);
        gallery.addEventListener('focusout', () => {
            window.setTimeout(() => {
                if (!gallery.contains(document.activeElement)) startAutoplay();
            });
        });
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) stopAutoplay();
            else startAutoplay();
        });

        showSlide(0);
        startAutoplay();
    });

    const form = document.querySelector('.contact-form');
    form?.addEventListener('submit', () => {
        const button = form.querySelector('.submit-button');
        if (!button) return;

        button.disabled = true;
        button.querySelector('span').textContent = 'Sending…';
    });
})();
