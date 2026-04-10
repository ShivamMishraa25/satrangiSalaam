(function () {
  'use strict';

  const translations = {
    en: {
      eyebrow: 'Official Bulletin',
      title: 'Announcements',
      lead: 'Decisions, notices, and communication updates presented in one clear stream.',
      noAnnouncements: 'No announcements available.',
      captionDate: 'Published on'
    },
    hi: {
      eyebrow: 'आधिकारिक बुलेटिन',
      title: 'सूचनाएं',
      lead: 'निर्णय, नोटिस और महत्वपूर्ण अपडेट एक स्पष्ट स्ट्रीम में।',
      noAnnouncements: 'कोई सूचना उपलब्ध नहीं है।',
      captionDate: 'प्रकाशन तिथि'
    }
  };

  const englishMonths = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
  ];

  const hindiMonths = [
    'जनवरी', 'फ़रवरी', 'मार्च', 'अप्रैल', 'मई', 'जून',
    'जुलाई', 'अगस्त', 'सितंबर', 'अक्टूबर', 'नवंबर', 'दिसंबर'
  ];

  const hindiMonthAliases = {
    'फरवरी': 'फ़रवरी',
    'सितम्बर': 'सितंबर'
  };

  function getMonthIndex(monthName) {
    const text = String(monthName || '').trim();
    if (!text) return -1;

    const enIndex = englishMonths.findIndex((month) => month.toLowerCase() === text.toLowerCase());
    if (enIndex >= 0) return enIndex;

    const normalizedHindi = hindiMonthAliases[text] || text;
    return hindiMonths.findIndex((month) => month === normalizedHindi);
  }

  function formatParsedDate(parts, lang) {
    if (!parts || parts.monthIndex < 0) return '';

    const day = String(parts.day || '').trim();
    const year = String(parts.year || '').trim();

    if (lang === 'hi') {
      const monthHi = hindiMonths[parts.monthIndex];
      return year ? (day + ' ' + monthHi + ' ' + year) : (day + ' ' + monthHi);
    }

    const monthEn = englishMonths[parts.monthIndex];
    return year ? (monthEn + ' ' + day + ', ' + year) : (day + ' ' + monthEn);
  }

  function parseAnnouncementDateText(text) {
    const value = String(text || '').trim();
    if (!value) return null;

    // Matches: 11 September 2025 / 11 सितंबर 2025 / 11 September
    let match = value.match(/^(\d{1,2})\s+([^,\d]+?)\s+(\d{4})$/u);
    if (match) {
      const monthIndex = getMonthIndex(match[2]);
      if (monthIndex >= 0) {
        return { day: match[1], monthIndex, year: match[3] };
      }
    }

    match = value.match(/^(\d{1,2})\s+([^,\d]+?)$/u);
    if (match) {
      const monthIndex = getMonthIndex(match[2]);
      if (monthIndex >= 0) {
        return { day: match[1], monthIndex, year: '' };
      }
    }

    // Matches: September 11, 2025 / सितंबर 11, 2025
    match = value.match(/^([^,\d]+?)\s+(\d{1,2}),\s*(\d{4})$/u);
    if (match) {
      const monthIndex = getMonthIndex(match[1]);
      if (monthIndex >= 0) {
        return { day: match[2], monthIndex, year: match[3] };
      }
    }

    return null;
  }

  function translateMonthNamesOnly(text, lang) {
    const value = String(text || '').trim();
    if (!value) return '';

    if (lang === 'hi') {
      return englishMonths.reduce((acc, month, idx) => {
        const pattern = new RegExp('\\b' + month + '\\b', 'gi');
        return acc.replace(pattern, hindiMonths[idx]);
      }, value);
    }

    let output = value;
    Object.keys(hindiMonthAliases).forEach((alias) => {
      output = output.replace(new RegExp(alias, 'g'), hindiMonthAliases[alias]);
    });
    hindiMonths.forEach((month, idx) => {
      output = output.replace(new RegExp(month, 'g'), englishMonths[idx]);
    });
    return output;
  }

  function localizeAnnouncementDate(text, lang) {
    const parsed = parseAnnouncementDateText(text);
    if (parsed) {
      return formatParsedDate(parsed, lang);
    }
    return translateMonthNamesOnly(text, lang);
  }

  function translateAnnouncementDates(lang) {
    document.querySelectorAll('.bulletin-card').forEach((card) => {
      const dateEl = card.querySelector('.bulletin-card__date');
      if (!dateEl) return;

      const initial = String(dateEl.textContent || '').trim();
      if (!dateEl.dataset.originalDate && initial) {
        dateEl.dataset.originalDate = initial;
      }

      const base = dateEl.dataset.originalDate || initial;
      const translated = localizeAnnouncementDate(base, lang);
      dateEl.textContent = translated;

      const gallery = card.querySelector('.bulletin-gallery');
      if (gallery) {
        gallery.setAttribute('data-announcement-date', translated);
      }
    });
  }

  function getCurrentLang() {
    try {
      if (typeof window.__INITIAL_LANG__ === 'string' && window.__INITIAL_LANG__) {
        const preset = window.__INITIAL_LANG__.toLowerCase();
        return (preset === 'hi' || preset === 'hindi') ? 'hi' : 'en';
      }
      const params = new URLSearchParams(window.location.search);
      const q = (params.get('lang') || '').toLowerCase();
      const match = document.cookie.match(/(?:^|;\s*)lang=([^;]+)/);
      const cookie = match ? decodeURIComponent(match[1]) : '';
      const lang = (q || cookie || '').toLowerCase();
      return (lang === 'hi' || lang === 'hindi') ? 'hi' : 'en';
    } catch (e) {
      return 'en';
    }
  }

  function persistLang(lang) {
    const normalized = lang === 'hi' ? 'hi' : 'en';
    try {
      document.cookie = 'lang=' + encodeURIComponent(normalized) + '; path=/; SameSite=Lax';
      const url = new URL(window.location.href);
      url.searchParams.set('lang', normalized);
      window.history.replaceState({}, '', url.toString());
    } catch (e) {}

    if (window.SharedLayout && typeof window.SharedLayout.updateNavLinkLang === 'function') {
      window.SharedLayout.updateNavLinkLang(normalized);
    }
  }

  function applyLanguage(lang) {
    const dict = translations[lang] || translations.en;
    persistLang(lang);

    document.documentElement.lang = lang === 'hi' ? 'hi' : 'en';
    document.body.classList.toggle('hindi', lang === 'hi');

    document.querySelectorAll('[data-i18n]').forEach((el) => {
      const key = el.getAttribute('data-i18n');
      if (dict[key]) el.textContent = dict[key];
    });

    const btn = document.querySelector('.translate-btn');
    if (btn) btn.textContent = lang === 'hi' ? 'English' : 'हिंदी';

    if (window.SharedLayout && typeof window.SharedLayout.applySharedTranslations === 'function') {
      window.SharedLayout.applySharedTranslations(lang === 'hi' ? 'hindi' : 'english');
    }

    translateAnnouncementDates(lang);
    document.dispatchEvent(new CustomEvent('announcements:language-changed'));
  }

  function initReveal() {
    const items = document.querySelectorAll('.reveal');
    if (!items.length) return;

    if (!('IntersectionObserver' in window)) {
      items.forEach((item) => item.classList.add('is-visible'));
      return;
    }

    const observer = new IntersectionObserver((entries, obs) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('is-visible');
        obs.unobserve(entry.target);
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -28px 0px' });

    items.forEach((item) => observer.observe(item));
  }

  function initPhotoLoading() {
    document.querySelectorAll('.bulletin-photo').forEach((btn) => {
      const img = btn.querySelector('img');
      if (!img) return;

      const mark = () => btn.classList.add('is-loaded');
      if (img.complete && img.naturalWidth > 0) mark();
      else img.addEventListener('load', mark, { once: true });
    });
  }

  function initLightbox() {
    const root = document.getElementById('bulletinLightbox');
    const image = document.getElementById('bulletinImage');
    const caption = document.getElementById('bulletinCaption');
    const meta = document.getElementById('bulletinLightboxMeta');
    const close = document.getElementById('bulletinClose');
    const prev = document.getElementById('bulletinPrev');
    const next = document.getElementById('bulletinNext');
    const download = document.getElementById('bulletinDownload');

    if (!root || !image || !caption) return;

    const triggers = Array.from(document.querySelectorAll('.bulletin-photo'));
    if (!triggers.length) return;

    const items = triggers.map((btn) => {
      const img = btn.querySelector('img');
      const group = btn.closest('.bulletin-gallery');
      return {
        full: img ? (img.getAttribute('data-full-src') || img.src || '') : '',
        thumb: img ? (img.src || '') : '',
        group
      };
    });

    let index = 0;

    function render(i) {
      const item = items[i];
      if (!item) return;
      index = i;

      const src = item.full || item.thumb;
      image.src = src;

      const lang = document.documentElement.lang === 'hi' ? 'hi' : 'en';
      const label = translations[lang].captionDate;
      const date = item.group ? (item.group.getAttribute('data-announcement-date') || '').trim() : '';
      const text = date ? (label + ': ' + date) : '';

      caption.textContent = text;
      if (meta) meta.textContent = text;

      if (download) {
        download.setAttribute('href', src || '#');
        const filename = src ? (src.split('/').pop() || 'announcement-image').split('?')[0] : 'announcement-image';
        download.setAttribute('download', filename || 'announcement-image');
      }
    }

    function open(i) {
      render(i);
      root.classList.add('is-open');
      root.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
      root.classList.remove('is-open');
      root.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
    }

    function prevItem() {
      render((index - 1 + items.length) % items.length);
    }

    function nextItem() {
      render((index + 1) % items.length);
    }

    triggers.forEach((btn, i) => {
      btn.addEventListener('click', () => open(i));
    });

    if (close) close.addEventListener('click', closeLightbox);
    if (prev) prev.addEventListener('click', prevItem);
    if (next) next.addEventListener('click', nextItem);

    root.addEventListener('click', (e) => {
      if (e.target === root) closeLightbox();
    });

    document.addEventListener('keydown', (e) => {
      if (!root.classList.contains('is-open')) return;
      if (e.key === 'Escape') closeLightbox();
      if (e.key === 'ArrowLeft') prevItem();
      if (e.key === 'ArrowRight') nextItem();
    });

    document.addEventListener('announcements:language-changed', () => {
      if (root.classList.contains('is-open')) {
        render(index);
      }
    });
  }

  function init() {
    const initialLang = getCurrentLang();
    let isHindi = initialLang === 'hi';

    applyLanguage(initialLang);
    initReveal();
    initPhotoLoading();
    initLightbox();

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
