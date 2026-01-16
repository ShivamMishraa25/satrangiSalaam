(function () {
  'use strict';

  // Shared layout utilities:
  // 1) Mobile navigation (hamburger) behavior
  // 2) Shared translations for includes/nav.php + includes/footer.php

  function initMobileNav() {
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');

    if (!hamburger || !navMenu) return;

    hamburger.addEventListener('click', () => {
      hamburger.classList.toggle('active');
      navMenu.classList.toggle('active');
    });

    // Close mobile menu when clicking on a link
    navMenu.querySelectorAll('a').forEach((link) => {
      link.addEventListener('click', () => {
        hamburger.classList.remove('active');
        navMenu.classList.remove('active');
      });
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', (e) => {
      if (!hamburger.contains(e.target) && !navMenu.contains(e.target)) {
        hamburger.classList.remove('active');
        navMenu.classList.remove('active');
      }
    });
  }

  const sharedTranslations = {
    english: {
      navTitle: 'Satrangi Salaam',
      nav: {
        about: 'About',
        events: 'Events',
        announcements: 'News',
        join: 'Join Us',
        login: 'Log in',
        contact: 'Contact',
        donate: 'Donate'
      },
      footer: {
        orgName: 'All India Satrangi Salaam Association',
        description: 'Promoting unity, harmony, and social justice across India since our establishment in Prayagraj.',
        quickLinks: 'Quick Links',
        article: 'Articles',
        gallery: 'Gallery',
        careers: 'Careers',
        postholders: 'PostHolders',
        other: 'Other',
        programs: 'Programs',
        inTheNews: 'In The News',
        collab: 'collaborators And Sponsors',
        impact: 'Our Impact',
        affiliates: 'Our Affiliates',
        reach: 'Our Reach On State-Level',
        resources: 'Resources',
        annualReports: 'Annual Reports',
        photoGallery: 'Photo Gallery',
        mediaCoverage: 'Media Coverage',
        testimonials: 'Testimonials',
        careerOpp: 'Career Opportunities',
        contacts: 'Contacts',
        whatsapp: 'Whatsapp',
        teligram: 'Telegram',
        wordpress: 'Wordpress',
        email: 'E-Mail',
        dev: 'Developer',
        copyright: '© 2023 All India Satrangi Salaam Association. All rights reserved.',
        registered: 'Registered NGO | Prayagraj, Uttar Pradesh'
      }
    },
    hindi: {
      navTitle: 'सतरंगी सलाम',
      nav: {
        about: 'हमारे बारे में',
        events: 'कार्यक्रम',
        announcements: 'समाचार',
        join: 'जुड़ें',
        login: 'लॉग-इन',
        contact: 'संपर्क',
        donate: 'दान करें'
      },
      footer: {
        orgName: 'अखिल भारतीय सतरंगी सलाम एसोसिएशन',
        description: 'प्रयागराज में हमारी स्थापना के बाद से भारत भर में एकता, सद्भाव और सामाजिक न्याय को बढ़ावा देना।',
        quickLinks: 'त्वरित लिंक',
        article: 'आर्टिकल',
        gallery: 'गैलरी',
        careers: 'करियर',
        postholders: 'पदाधिकारी',
        other: 'अन्य',
        programs: 'Programs',
        inTheNews: 'खबरों में हम',
        collab: 'हमारे सहयोगी',
        impact: 'बदलाव (असर)',
        affiliates: 'हमारे स्वायत्त व अधीनस्थ संस्थाएं',
        reach: 'प्रदेश व विभिन्न स्तर पर हम',
        resources: 'Resources',
        annualReports: 'Annual Reports',
        photoGallery: 'Photo Gallery',
        mediaCoverage: 'Media Coverage',
        testimonials: 'Testimonials',
        careerOpp: 'Career Opportunities',
        contacts: 'संपर्क',
        whatsapp: 'वॉट्सऐप',
        teligram: 'टेलीग्राम',
        wordpress: 'वर्डप्रेस',
        email: 'ई-मेल',
        dev: 'निर्माता',
        copyright: '© 2023 अखिल भारतीय सतरंगी सलाम एसोसिएशन। सभी अधिकार सुरक्षित।',
        registered: 'पंजीकृत एनजीओ | प्रयागराज, उत्तर प्रदेश'
      }
    }
  };

  function setText(el, value) {
    if (!el || typeof value !== 'string') return;
    el.textContent = value;
  }

  function getCurrentLangParam() {
    try {
      const params = new URLSearchParams(window.location.search);
      const qLang = (params.get('lang') || '').toLowerCase();
      const cookieLangMatch = document.cookie.match(/(?:^|;\s*)lang=([^;]+)/);
      const cookieLang = cookieLangMatch ? decodeURIComponent(cookieLangMatch[1]) : '';
      const lang = (qLang || cookieLang || '').toLowerCase();
      return (lang === 'hi' || lang === 'hindi') ? 'hi' : 'en';
    } catch (e) {
      return 'en';
    }
  }

  function persistLangParam(langParam) {
    const normalized = (langParam === 'hi' || langParam === 'hindi') ? 'hi' : 'en';
    try {
      document.cookie = 'lang=' + encodeURIComponent(normalized) + '; path=/; SameSite=Lax';
    } catch (e) {}

    try {
      const url = new URL(window.location.href);
      url.searchParams.set('lang', normalized);
      window.history.replaceState({}, '', url.toString());
    } catch (e) {}

    return normalized;
  }

  function updateNavLinkLang(langParam) {
    const normalized = (langParam === 'hi' || langParam === 'hindi') ? 'hi' : 'en';

    // Update brand/logo link
    const brandLink = document.querySelector('.nav-logo a');
    if (brandLink) {
      try {
        const href = brandLink.getAttribute('href') || '';
        if (href && !href.startsWith('#')) {
          const url = new URL(href, window.location.origin);
          url.searchParams.set('lang', normalized);
          brandLink.setAttribute('href', url.pathname + url.search + url.hash);
        }
      } catch (e) {
        // Ignore malformed
      }
    }

    const links = document.querySelectorAll('.nav-menu a');
    links.forEach((a) => {
      try {
        // Skip hash-only links like #contact
        const href = a.getAttribute('href') || '';
        if (href.startsWith('#') || href === '') return;

        const url = new URL(href, window.location.origin);
        url.searchParams.set('lang', normalized);
        a.setAttribute('href', url.pathname + url.search + url.hash);
      } catch (e) {
        // Ignore malformed URLs
      }
    });
  }

  function applySharedTranslations(langKey) {
    const dict = sharedTranslations[langKey];
    if (!dict) return;

    // Nav title
    const navTitle = document.getElementById('nav-title');
    if (navTitle) navTitle.textContent = dict.navTitle;

    // Nav links (order as in includes/nav.php)
    const navLinks = document.querySelectorAll('.nav-menu a');
    const navKeys = ['about', 'events', 'announcements', 'join', 'login', 'contact', 'donate'];
    navLinks.forEach((link, index) => {
      const key = navKeys[index];
      if (!key) return;
      setText(link, dict.nav[key]);
    });

    // Ensure nav links preserve selected language.
    updateNavLinkLang(langKey === 'hindi' ? 'hi' : 'en');

    // Footer
    const footerOrgName = document.querySelector('.footer-section h3');
    const footerDescription = document.querySelector('.footer-section p');
    const footerHeaders = document.querySelectorAll('.footer-section h4');
    const footerLinks = document.querySelectorAll('.footer-section ul li a');
    const footerBottom = document.querySelectorAll('.footer-bottom p');

    if (footerOrgName) setText(footerOrgName, dict.footer.orgName);
    if (footerDescription) setText(footerDescription, dict.footer.description);

    // Headers: Quick Links, Programs, Contacts
    if (footerHeaders[0]) setText(footerHeaders[0], dict.footer.quickLinks);
    if (footerHeaders[1]) setText(footerHeaders[1], dict.footer.programs);
    if (footerHeaders[2]) setText(footerHeaders[2], dict.footer.contacts);

    // Links order in footer.php: 5 quick + 5 programs + 5 contacts
    const footerLinkTranslations = [
      dict.footer.article,
      dict.footer.gallery,
      dict.footer.careers,
      dict.footer.postholders,
      dict.footer.other,
      dict.footer.inTheNews,
      dict.footer.collab,
      dict.footer.impact,
      dict.footer.affiliates,
      dict.footer.reach,
      dict.footer.whatsapp,
      dict.footer.teligram,
      dict.footer.wordpress,
      dict.footer.email,
      dict.footer.dev
    ];

    footerLinks.forEach((link, index) => {
      if (footerLinkTranslations[index]) {
        setText(link, footerLinkTranslations[index]);
      }
    });

    if (footerBottom[0]) setText(footerBottom[0], dict.footer.copyright);
    if (footerBottom[1]) setText(footerBottom[1], dict.footer.registered);
  }

  // Expose for inline onclick="toggleLanguage()" in nav.php
  window.SharedLayout = {
    initMobileNav,
    applySharedTranslations,
    getCurrentLangParam,
    persistLangParam,
    updateNavLinkLang
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMobileNav);
  } else {
    initMobileNav();
  }
})();
