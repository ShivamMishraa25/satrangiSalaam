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

  const englishMonths = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
  ];

  const hindiMonths = [
    'जनवरी', 'फ़रवरी', 'मार्च', 'अप्रैल', 'मई', 'जून',
    'जुलाई', 'अगस्त', 'सितंबर', 'अक्टूबर', 'नवंबर', 'दिसंबर'
  ];

  function getInitialLang() {
    try {
      if (typeof window.__INITIAL_LANG__ === 'string' && window.__INITIAL_LANG__.length) {
        const preset = window.__INITIAL_LANG__.toLowerCase();
        return (preset === 'hi' || preset === 'hindi') ? 'hi' : 'en';
      }

      const params = new URLSearchParams(window.location.search);
      const qLang = (params.get('lang') || '').toLowerCase();
      const cookieMatch = document.cookie.match(/(?:^|;\s*)lang=([^;]+)/);
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

  function applySharedLanguage(lang) {
    const normalized = lang === 'hi' ? 'hi' : 'en';
    const dict = translations[normalized];
    if (!dict) return;

    persistLang(normalized);
    document.documentElement.lang = normalized;
    document.body.classList.toggle('hindi', normalized === 'hi');

    document.querySelectorAll('[data-i18n]').forEach((el) => {
      const key = el.getAttribute('data-i18n');
      if (dict[key]) {
        el.textContent = dict[key];
      }
    });

    const btn = document.querySelector('.translate-btn');
    if (btn) btn.textContent = normalized === 'hi' ? 'English' : 'हिंदी';

    if (window.SharedLayout && typeof window.SharedLayout.applySharedTranslations === 'function') {
      window.SharedLayout.applySharedTranslations(normalized === 'hi' ? 'hindi' : 'english');
    }

    translateEventCards(document, normalized);
    document.dispatchEvent(new CustomEvent('events:language-changed', { detail: { lang: normalized } }));
  }

  function escapeHtml(text) {
    return String(text || '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  function pickLocalizedText(english, hindi, lang, fallback) {
    const en = String(english || '').trim();
    const hi = String(hindi || '').trim();

    if (lang === 'hi') {
      if (hi) return hi;
      if (en) return en;
    } else {
      if (en) return en;
      if (hi) return hi;
    }

    return fallback || '';
  }

  function formatEventDateValue(rawValue, lang) {
    const value = String(rawValue || '').trim();
    if (!value) return '';

    let date = null;
    if (/^\d{4}-\d{2}-\d{2}/.test(value)) {
      const [year, month, day] = value.split(' ')[0].split('-').map((part) => parseInt(part, 10));
      if (year && month && day) {
        date = new Date(year, month - 1, day);
      }
    } else {
      const parsed = new Date(value);
      if (!Number.isNaN(parsed.getTime())) {
        date = parsed;
      }
    }

    if (!date || Number.isNaN(date.getTime())) {
      return value;
    }

    const day = String(date.getDate()).padStart(2, '0');
    const monthIndex = date.getMonth();
    const year = String(date.getFullYear());
    if (lang === 'hi') {
      return day + ' ' + hindiMonths[monthIndex] + ' ' + year;
    }

    return englishMonths[monthIndex] + ' ' + day + ', ' + year;
  }

  function translateEventCards(root, lang) {
    const scope = root || document;

    scope.querySelectorAll('.js-localized-event').forEach((card) => {
      const titleEn = card.getAttribute('data-event-name-en') || '';
      const titleHi = card.getAttribute('data-event-name-hi') || '';
      const locationEn = card.getAttribute('data-event-location-en') || '';
      const locationHi = card.getAttribute('data-event-location-hi') || '';
      const descriptionEn = card.getAttribute('data-event-description-en') || '';
      const descriptionHi = card.getAttribute('data-event-description-hi') || '';
      const dateValue = card.getAttribute('data-event-date-value') || '';

      const title = pickLocalizedText(titleEn, titleHi, lang, 'Event');
      const location = pickLocalizedText(locationEn, locationHi, lang, '');
      const description = pickLocalizedText(descriptionEn, descriptionHi, lang, '');
      const date = formatEventDateValue(dateValue, lang);

      const titleEl = card.querySelector('.event-card__title');
      if (titleEl) titleEl.textContent = title;

      const dateEl = card.querySelector('.event-date');
      if (dateEl) dateEl.textContent = date;

      const locationEl = card.querySelector('.event-location');
      if (locationEl) {
        if (location) {
          locationEl.textContent = location;
          locationEl.hidden = false;
        } else {
          locationEl.textContent = '';
          locationEl.hidden = true;
        }
      }

      const descriptionEl = card.querySelector('.event-description');
      if (descriptionEl) {
        if (description) {
          descriptionEl.innerHTML = escapeHtml(description).replace(/\n/g, '<br>');
          descriptionEl.hidden = false;
        } else {
          descriptionEl.textContent = '';
          descriptionEl.hidden = true;
        }
      }

      const images = card.querySelector('.event-images');
      if (images && date) {
        images.setAttribute('data-event-date', date);
      }
    });
  }

  function initReveal(root) {
    const scope = root || document;
    const revealItems = scope.querySelectorAll('.reveal');
    if (!revealItems.length) return;

    if (!('IntersectionObserver' in window)) {
      revealItems.forEach((item) => item.classList.add('is-visible'));
      return;
    }

    if (!window.__eventRevealObserver) {
      window.__eventRevealObserver = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;
          entry.target.classList.add('is-visible');
          obs.unobserve(entry.target);
        });
      }, {
        threshold: 0.15,
        rootMargin: '0px 0px -30px 0px'
      });
    }

    revealItems.forEach((item) => window.__eventRevealObserver.observe(item));
  }

  function initDenseImageGroups(root) {
    const scope = root || document;
    const groups = scope.querySelectorAll('.event-images');

    groups.forEach((group) => {
      const imageCount = group.querySelectorAll('.event-image-btn').length;
      group.classList.toggle('event-images--dense', imageCount >= 4);
    });
  }

  function initImageLoadingState(root) {
    const scope = root || document;
    const buttons = scope.querySelectorAll('.event-image-btn');

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

  function initLightbox() {
    const lightbox = document.getElementById('eventLightbox');
    const imageEl = document.getElementById('eventLightboxImage');
    const captionEl = document.getElementById('eventLightboxCaption');
    const closeBtn = document.getElementById('eventLightboxClose');
    const prevBtn = document.getElementById('eventLightboxPrev');
    const nextBtn = document.getElementById('eventLightboxNext');
    const downloadBtn = document.getElementById('eventLightboxDownload');

    if (!lightbox || !imageEl || !captionEl) return;

    const state = {
      items: [],
      index: 0
    };

    function getItems() {
      return Array.from(document.querySelectorAll('.event-image-btn')).map((button) => {
        const img = button.querySelector('img');
        const parent = button.closest('.event-images');
        const eventDate = parent ? (parent.getAttribute('data-event-date') || '').trim() : '';

        return {
          fullSrc: img ? (img.getAttribute('data-full-src') || img.src || '') : '',
          thumbSrc: img ? (img.src || '') : '',
          eventDate
        };
      });
    }

    function render(index) {
      state.items = getItems();
      const item = state.items[index];
      if (!item) return;

      state.index = index;
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
      const items = getItems();
      if (!items.length) return;
      const nextIndex = (state.index - 1 + items.length) % items.length;
      render(nextIndex);
    }

    function next() {
      const items = getItems();
      if (!items.length) return;
      const nextIndex = (state.index + 1) % items.length;
      render(nextIndex);
    }

    document.addEventListener('click', (event) => {
      const button = event.target instanceof HTMLElement ? event.target.closest('.event-image-btn') : null;
      if (!button) return;

      const buttons = Array.from(document.querySelectorAll('.event-image-btn'));
      const index = buttons.indexOf(button);
      if (index >= 0) {
        open(index);
      }
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

    document.addEventListener('events:language-changed', () => {
      if (lightbox.classList.contains('is-open')) {
        render(state.index);
      }
    });
  }

  function initInfiniteScroll() {
    const feed = document.getElementById('eventsDynamicFeed');
    const sentinel = document.getElementById('eventsFeedSentinel');
    if (!feed || !sentinel) return;

    let nextOffset = parseInt(feed.getAttribute('data-next-offset') || '0', 10);
    let hasMore = feed.getAttribute('data-has-more') === '1';
    let isLoading = false;

    function appendHtml(html) {
      if (!html) return;

      const template = document.createElement('template');
      template.innerHTML = html;
      const fragment = template.content;

      translateEventCards(fragment, document.documentElement.lang === 'hi' ? 'hi' : 'en');
      initReveal(fragment);
      initDenseImageGroups(fragment);
      initImageLoadingState(fragment);

      feed.appendChild(fragment);
    }

    function loadMore() {
      if (!hasMore || isLoading) return;
      isLoading = true;

      const lang = document.documentElement.lang === 'hi' ? 'hi' : 'en';
      const url = new URL(window.location.href);
      url.searchParams.set('ajax', '1');
      url.searchParams.set('offset', String(nextOffset));
      url.searchParams.set('lang', lang);

      fetch(url.toString(), {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
        .then((response) => response.json())
        .then((data) => {
          if (data && data.html) {
            appendHtml(data.html);
          }

          nextOffset = data && typeof data.nextOffset === 'number' ? data.nextOffset : nextOffset;
          hasMore = !!(data && data.hasMore);
          feed.setAttribute('data-next-offset', String(nextOffset));
          feed.setAttribute('data-has-more', hasMore ? '1' : '0');

          if (!hasMore && observer) {
            observer.disconnect();
          }
        })
        .catch(() => {})
        .finally(() => {
          isLoading = false;
        });
    }

    let observer = null;
    if ('IntersectionObserver' in window) {
      observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            loadMore();
          }
        });
      }, {
        rootMargin: '300px 0px',
        threshold: 0.1
      });
      observer.observe(sentinel);
    } else {
      const onScroll = () => {
        const distance = document.documentElement.scrollHeight - window.scrollY - window.innerHeight;
        if (distance < 500) {
          loadMore();
        }
      };
      window.addEventListener('scroll', onScroll, { passive: true });
      onScroll();
    }
  }

  function init() {
    const initialLang = getInitialLang();

    applySharedLanguage(initialLang);
    initDenseImageGroups(document);
    initImageLoadingState(document);
    initLightbox();
    initReveal(document);
    initInfiniteScroll();

    window.toggleLanguage = function () {
      const nextLang = document.documentElement.lang === 'hi' ? 'en' : 'hi';
      applySharedLanguage(nextLang);
    };
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();