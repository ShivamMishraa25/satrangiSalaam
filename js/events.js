(function () {
  'use strict';

  const translations = {
    en: {
      badge: 'AISSA Activities',
      title: 'Our Events',
      lead: 'Workshops, campaigns, celebrations, and social actions that shape our collective impact.',
      metricDynamic: 'Dynamic Events',
      heroNote: 'Tap any image to open full-size view with download option.',
      uploadedOn: 'Event date'
    },
    hi: {
      badge: 'AISSA गतिविधियां',
      title: 'हमारे कार्यक्रम',
      lead: 'वर्कशॉप, अभियान, उत्सव और सामाजिक पहल जो हमारे सामूहिक प्रभाव को आकार देती हैं।',
      metricDynamic: 'डायनामिक इवेंट्स',
      heroNote: 'किसी भी फोटो पर क्लिक करके फुल साइज और डाउनलोड विकल्प देखें।',
      uploadedOn: 'कार्यक्रम तिथि'
    }
  };

  function getInitialLang() {
    try {
      if (typeof window.__INITIAL_LANG__ === 'string' && window.__INITIAL_LANG__.length) {
        const preset = window.__INITIAL_LANG__.toLowerCase();
        return (preset === 'hi' || preset === 'hindi') ? 'hi' : 'en';
      }

      const params = new URLSearchParams(window.location.search);
      const qLang = (params.get('lang') || '').toLowerCase();
      const cookieMatch = document.cookie.match(/(?:^|;\\s*)lang=([^;]+)/);
      const cookieLang = cookieMatch ? decodeURIComponent(cookieMatch[1]) : '';
      const lang = (qLang || cookieLang || '').toLowerCase();
      return (lang === 'hi' || lang === 'hindi') ? 'hi' : 'en';
    } catch (e) {
      return 'en';
    }
  }

  function persistLang(lang) {
    const normalized = lang === 'hi' ? 'hi' : 'en';

    try {
      document.cookie = 'lang=' + encodeURIComponent(normalized) + '; path=/; SameSite=Lax';
    } catch (e) {}

    try {
      const url = new URL(window.location.href);
      url.searchParams.set('lang', normalized);
      window.history.replaceState({}, '', url.toString());
    } catch (e) {}

    if (window.SharedLayout && typeof window.SharedLayout.updateNavLinkLang === 'function') {
      window.SharedLayout.updateNavLinkLang(normalized);
    }
  }

  function applyLanguage(lang) {
    const dict = translations[lang];
    if (!dict) return;

    persistLang(lang);
    document.documentElement.lang = lang === 'hi' ? 'hi' : 'en';
    document.body.classList.toggle('hindi', lang === 'hi');

    document.querySelectorAll('[data-i18n]').forEach((el) => {
      const key = el.getAttribute('data-i18n');
      if (dict[key]) {
        el.textContent = dict[key];
      }
    });

    const btn = document.querySelector('.translate-btn');
    if (btn) btn.textContent = lang === 'hi' ? 'English' : 'हिंदी';

    if (window.SharedLayout && typeof window.SharedLayout.applySharedTranslations === 'function') {
      window.SharedLayout.applySharedTranslations(lang === 'hi' ? 'hindi' : 'english');
    }
  }

  function initImageLoadingState() {
    const buttons = document.querySelectorAll('.event-image-btn');

    buttons.forEach((button) => {
      const img = button.querySelector('img');
      if (!img) return;

      const markLoaded = () => button.classList.add('is-loaded');

      if (img.complete && img.naturalWidth > 0) {
        markLoaded();
      } else {
        img.addEventListener('load', markLoaded, { once: true });
      }
    });
  }

  function initDenseImageGroups() {
    const groups = document.querySelectorAll('.event-images');

    groups.forEach((group) => {
      const imageCount = group.querySelectorAll('.event-image-btn').length;
      group.classList.toggle('event-images--dense', imageCount >= 4);
    });
  }

  function initLightbox() {
    const lightbox = document.getElementById('eventLightbox');
    const imageEl = document.getElementById('eventLightboxImage');
    const captionEl = document.getElementById('eventLightboxCaption');
    const closeBtn = document.getElementById('eventLightboxClose');
    const prevBtn = document.getElementById('eventLightboxPrev');
    const nextBtn = document.getElementById('eventLightboxNext');
    const downloadBtn = document.getElementById('eventLightboxDownload');

    if (!lightbox || !imageEl || !captionEl) return;

    const triggerButtons = Array.from(document.querySelectorAll('.event-image-btn'));
    if (!triggerButtons.length) return;

    const items = triggerButtons.map((button) => {
      const img = button.querySelector('img');
      const parent = button.closest('.event-images');
      const eventDate = parent ? (parent.getAttribute('data-event-date') || '').trim() : '';

      const fullSrc = img ? (img.getAttribute('data-full-src') || img.src || '') : '';
      const thumbSrc = img ? (img.src || '') : '';

      return {
        fullSrc,
        thumbSrc,
        eventDate
      };
    });

    let currentIndex = 0;

    function render(index) {
      const item = items[index];
      if (!item) return;

      currentIndex = index;
      imageEl.src = item.fullSrc || item.thumbSrc;

      const lang = document.documentElement.lang === 'hi' ? 'hi' : 'en';
      const label = translations[lang].uploadedOn;
      captionEl.textContent = item.eventDate ? (label + ': ' + item.eventDate) : '';

      if (downloadBtn) {
        const src = item.fullSrc || item.thumbSrc;
        downloadBtn.setAttribute('href', src || '#');
        const fileName = src ? (src.split('/').pop() || 'event-image').split('?')[0] : 'event-image';
        downloadBtn.setAttribute('download', fileName || 'event-image');
      }
    }

    function open(index) {
      render(index);
      lightbox.classList.add('is-open');
      lightbox.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
    }

    function close() {
      lightbox.classList.remove('is-open');
      lightbox.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
    }

    function prev() {
      const nextIndex = (currentIndex - 1 + items.length) % items.length;
      render(nextIndex);
    }

    function next() {
      const nextIndex = (currentIndex + 1) % items.length;
      render(nextIndex);
    }

    triggerButtons.forEach((button, index) => {
      button.addEventListener('click', () => open(index));
    });

    if (closeBtn) closeBtn.addEventListener('click', close);
    if (prevBtn) prevBtn.addEventListener('click', prev);
    if (nextBtn) nextBtn.addEventListener('click', next);

    lightbox.addEventListener('click', (event) => {
      if (event.target === lightbox) close();
    });

    document.addEventListener('keydown', (event) => {
      if (!lightbox.classList.contains('is-open')) return;
      if (event.key === 'Escape') close();
      if (event.key === 'ArrowLeft') prev();
      if (event.key === 'ArrowRight') next();
    });
  }

  function initReveal() {
    const revealItems = document.querySelectorAll('.reveal');
    if (!revealItems.length) return;

    if (!('IntersectionObserver' in window)) {
      revealItems.forEach((item) => item.classList.add('is-visible'));
      return;
    }

    const observer = new IntersectionObserver((entries, obs) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('is-visible');
        obs.unobserve(entry.target);
      });
    }, {
      threshold: 0.15,
      rootMargin: '0px 0px -30px 0px'
    });

    revealItems.forEach((item) => observer.observe(item));
  }

  function init() {
    const initialLang = getInitialLang();
    let isHindi = initialLang === 'hi';

    applyLanguage(initialLang);
    initDenseImageGroups();
    initImageLoadingState();
    initLightbox();
    initReveal();

    window.toggleLanguage = function () {
      isHindi = !isHindi;
      applyLanguage(isHindi ? 'hi' : 'en');
    };
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
