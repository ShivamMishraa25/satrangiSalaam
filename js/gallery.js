(function () {
  'use strict';

  const translations = {
    en: {
      badge: 'AISSA Moments',
      title: 'Gallery',
      lead: 'A living archive of community actions, awareness drives, collaborations, and impact stories across India.',
      statTotal: 'Total Photos',
      statRecent: 'Recent Uploads',
      statArchive: 'Archive Photos',
      panelTitle: 'Browse By',
      filterAll: 'All',
      filterRecent: 'Recent',
      filterArchive: 'Archive',
      panelHint: 'Click any photo to view in full screen. Use keyboard arrows for navigation.',
      uploadedOn: 'Uploaded on',
      chipRecent: 'Recent Upload',
      chipArchive: 'Archive',
      emptyTitle: 'No Photos Yet',
      emptyText: 'Gallery images will appear here as soon as new uploads are available.'
    },
    hi: {
      badge: 'AISSA के पल',
      title: 'गैलरी',
      lead: 'सामुदायिक पहल, जागरूकता अभियान, सहयोग और देशभर में असर डालने वाले कार्यों का जीवंत संग्रह।',
      statTotal: 'कुल फोटो',
      statRecent: 'नए अपलोड',
      statArchive: 'आर्काइव फोटो',
      panelTitle: 'देखें श्रेणी के अनुसार',
      filterAll: 'सभी',
      filterRecent: 'नए',
      filterArchive: 'आर्काइव',
      panelHint: 'फोटो को पूर्ण आकार में देखने के लिए उस पर क्लिक करें। नेविगेशन के लिए कीबोर्ड एरो का उपयोग करें।',
      uploadedOn: 'अपलोड तिथि',
      chipRecent: 'नया अपलोड',
      chipArchive: 'आर्काइव',
      emptyTitle: 'अभी कोई फोटो नहीं',
      emptyText: 'नई फोटो उपलब्ध होते ही यहां दिखाई देंगी।'
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

  function initFilters() {
    const filterButtons = document.querySelectorAll('.gallery-filter');
    const cards = document.querySelectorAll('.gallery-card');

    if (!filterButtons.length || !cards.length) return;

    filterButtons.forEach((button) => {
      button.addEventListener('click', () => {
        const activeFilter = button.getAttribute('data-filter');

        filterButtons.forEach((b) => b.classList.remove('is-active'));
        button.classList.add('is-active');

        cards.forEach((card) => {
          const source = card.getAttribute('data-source');
          const show = activeFilter === 'all' || source === activeFilter;
          card.classList.toggle('is-hidden', !show);
        });
      });
    });
  }

  function initLightbox() {
    const lightbox = document.getElementById('galleryLightbox');
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxCaption = document.getElementById('lightboxCaption');
    const closeBtn = document.getElementById('lightboxClose');
    const prevBtn = document.getElementById('lightboxPrev');
    const nextBtn = document.getElementById('lightboxNext');
    const downloadBtn = document.getElementById('lightboxDownload');
    const triggers = document.querySelectorAll('[data-lightbox-index]');

    if (!lightbox || !lightboxImage || !lightboxCaption || !triggers.length) return;

    const items = Array.from(triggers).map((trigger) => {
      const image = trigger.querySelector('img');
      return {
        previewSrc: image ? image.src : '',
        fullSrc: trigger.getAttribute('data-full-src') || (image ? image.src : ''),
        uploadedAt: trigger.getAttribute('data-uploaded-at') || ''
      };
    });

    let currentIndex = 0;

    function render(index) {
      const item = items[index];
      if (!item) return;
      currentIndex = index;
      lightboxImage.src = item.fullSrc || item.previewSrc;

      const lang = document.documentElement.lang === 'hi' ? 'hi' : 'en';
      const label = translations[lang].uploadedOn;
      lightboxCaption.textContent = item.uploadedAt ? (label + ': ' + item.uploadedAt) : '';

      if (downloadBtn) {
        const downloadSrc = item.fullSrc || item.previewSrc;
        downloadBtn.setAttribute('href', downloadSrc || '#');
        const filename = downloadSrc ? downloadSrc.split('/').pop().split('?')[0] : 'gallery-image';
        downloadBtn.setAttribute('download', filename || 'gallery-image');
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

    triggers.forEach((trigger, index) => {
      trigger.addEventListener('click', () => open(index));
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

  function initMasonry() {
    const grid = document.getElementById('galleryGrid');
    const cards = grid ? Array.from(grid.querySelectorAll('.gallery-card')) : [];

    if (!grid || !cards.length) return;

    function toNumber(value) {
      const parsed = parseInt(String(value || '').replace('px', ''), 10);
      return Number.isNaN(parsed) ? 0 : parsed;
    }

    function setCardSpan(card) {
      if (card.classList.contains('is-hidden')) return;

      const image = card.querySelector('img');
      if (!image || !image.complete || image.naturalWidth === 0) return;

      const rowHeight = toNumber(getComputedStyle(grid).getPropertyValue('grid-auto-rows')) || 8;
      const rowGap = toNumber(getComputedStyle(grid).getPropertyValue('gap')) || 14;
      const cardWidth = card.getBoundingClientRect().width;
      if (cardWidth === 0) return;

      const renderedImageHeight = cardWidth * (image.naturalHeight / image.naturalWidth);
      const totalHeight = Math.max(120, renderedImageHeight);
      const rowSpan = Math.ceil((totalHeight + rowGap) / (rowHeight + rowGap));

      card.style.setProperty('--row-span', String(rowSpan));
      card.classList.add('is-loaded');
    }

    function relayoutAll() {
      cards.forEach(setCardSpan);
    }

    cards.forEach((card) => {
      const image = card.querySelector('img');
      if (!image) return;

      if (image.complete && image.naturalWidth > 0) {
        setCardSpan(card);
      } else {
        image.addEventListener('load', () => setCardSpan(card), { once: true });
      }
    });

    window.addEventListener('resize', relayoutAll);

    const filterButtons = document.querySelectorAll('.gallery-filter');
    filterButtons.forEach((button) => {
      button.addEventListener('click', () => {
        window.requestAnimationFrame(relayoutAll);
      });
    });
  }

  function init() {
    const initialLang = getInitialLang();
    let isHindi = initialLang === 'hi';

    applyLanguage(initialLang);
    initFilters();
    initMasonry();
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
