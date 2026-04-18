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

  function escapeHtml(text) {
    return String(text || '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

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

  function getInitialLang() {
    try {
      if (typeof window.__INITIAL_LANG__ === 'string' && window.__INITIAL_LANG__.length) {
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

  function translateAnnouncementDates(root, lang) {
    const scope = root || document;

    scope.querySelectorAll('.bulletin-card').forEach((card) => {
      const dateEl = card.querySelector('.bulletin-card__date');
      if (!dateEl) return;

      const base = dateEl.dataset.originalDate || String(dateEl.textContent || '').trim();
      if (!dateEl.dataset.originalDate && base) {
        dateEl.dataset.originalDate = base;
      }

      const translated = localizeAnnouncementDate(base, lang);
      dateEl.textContent = translated;

      const gallery = card.querySelector('.bulletin-gallery');
      if (gallery) {
        gallery.setAttribute('data-announcement-date', translated);
      }
    });
  }

  function translateAnnouncementCards(root, lang) {
    const scope = root || document;

    scope.querySelectorAll('.js-localized-announcement').forEach((card) => {
      const titleEn = card.getAttribute('data-announcement-title-en') || '';
      const titleHi = card.getAttribute('data-announcement-title-hi') || '';
      const contentEn = card.getAttribute('data-announcement-content-en') || '';
      const contentHi = card.getAttribute('data-announcement-content-hi') || '';

      const title = lang === 'hi'
        ? (titleHi || titleEn || 'Announcement')
        : (titleEn || titleHi || 'Announcement');
      const content = lang === 'hi'
        ? (contentHi || contentEn || '')
        : (contentEn || contentHi || '');

      const titleEl = card.querySelector('.bulletin-card__title') || card.querySelector('h2');
      if (titleEl) titleEl.textContent = title;

      const contentEl = card.querySelector('.bulletin-card__content');
      if (contentEl) {
        if (content) {
          contentEl.innerHTML = escapeHtml(content).replace(/\n/g, '<br>');
          contentEl.hidden = false;
        } else {
          contentEl.textContent = '';
          contentEl.hidden = true;
        }
      }
    });

    translateAnnouncementDates(scope, lang);
  }

  function applyLanguage(lang) {
    const normalized = lang === 'hi' ? 'hi' : 'en';
    const dict = translations[normalized] || translations.en;
    persistLang(normalized);

    document.documentElement.lang = normalized;
    document.body.classList.toggle('hindi', normalized === 'hi');

    document.querySelectorAll('[data-i18n]').forEach((el) => {
      const key = el.getAttribute('data-i18n');
      if (dict[key]) el.textContent = dict[key];
    });

    const btn = document.querySelector('.translate-btn');
    if (btn) btn.textContent = normalized === 'hi' ? 'English' : 'हिंदी';

    if (window.SharedLayout && typeof window.SharedLayout.applySharedTranslations === 'function') {
      window.SharedLayout.applySharedTranslations(normalized === 'hi' ? 'hindi' : 'english');
    }

    translateAnnouncementCards(document, normalized);
    document.dispatchEvent(new CustomEvent('announcements:language-changed', { detail: { lang: normalized } }));
  }

  function initReveal(root) {
    const scope = root || document;
    const items = scope.querySelectorAll('.reveal');
    if (!items.length) return;

    if (!('IntersectionObserver' in window)) {
      items.forEach((item) => item.classList.add('is-visible'));
      return;
    }

    if (!window.__announcementRevealObserver) {
      window.__announcementRevealObserver = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;
          entry.target.classList.add('is-visible');
          obs.unobserve(entry.target);
        });
      }, { threshold: 0.12, rootMargin: '0px 0px -28px 0px' });
    }

    items.forEach((item) => window.__announcementRevealObserver.observe(item));
  }

  function initPhotoLoading(root) {
    const scope = root || document;
    scope.querySelectorAll('.bulletin-photo').forEach((btn) => {
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

    const state = {
      items: [],
      index: 0
    };

    function getItems() {
      return Array.from(document.querySelectorAll('.bulletin-photo')).map((btn) => {
        const img = btn.querySelector('img');
        const group = btn.closest('.bulletin-gallery');
        return {
          full: img ? (img.getAttribute('data-full-src') || img.src || '') : '',
          thumb: img ? (img.src || '') : '',
          group
        };
      });
    }

    function render(index) {
      state.items = getItems();
      const item = state.items[index];
      if (!item) return;

      state.index = index;
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

    function open(index) {
      render(index);
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
      const items = getItems();
      if (!items.length) return;
      render((state.index - 1 + items.length) % items.length);
    }

    function nextItem() {
      const items = getItems();
      if (!items.length) return;
      render((state.index + 1) % items.length);
    }

    document.addEventListener('click', (event) => {
      const button = event.target instanceof HTMLElement ? event.target.closest('.bulletin-photo') : null;
      if (!button) return;

      const buttons = Array.from(document.querySelectorAll('.bulletin-photo'));
      const index = buttons.indexOf(button);
      if (index >= 0) {
        open(index);
      }
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
        render(state.index);
      }
    });
  }

  function initInfiniteScroll() {
    const feed = document.getElementById('announcementDynamicFeed');
    const sentinel = document.getElementById('announcementFeedSentinel');
    if (!feed || !sentinel) return;

    let nextOffset = parseInt(feed.getAttribute('data-next-offset') || '0', 10);
    let hasMore = feed.getAttribute('data-has-more') === '1';
    let isLoading = false;
    let observer = null;

    function appendHtml(html) {
      if (!html) return;

      const template = document.createElement('template');
      template.innerHTML = html;
      const fragment = template.content;

      translateAnnouncementCards(fragment, document.documentElement.lang === 'hi' ? 'hi' : 'en');
      initReveal(fragment);
      initPhotoLoading(fragment);

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

    applyLanguage(initialLang);
    initReveal(document);
    initPhotoLoading(document);
    initLightbox();
    initInfiniteScroll();

    window.toggleLanguage = function () {
      const nextLang = document.documentElement.lang === 'hi' ? 'en' : 'hi';
      applyLanguage(nextLang);
    };
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();