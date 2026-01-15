(function () {
  'use strict';

  // Shared (site-wide) translations for includes/nav.php and includes/footer.php
  const sharedTranslations = {
    en: {
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
        description:
          'Promoting unity, harmony, and social justice across India since our establishment in Prayagraj.',
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
    hi: {
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
        description:
          'प्रयागराज में हमारी स्थापना के बाद से भारत भर में एकता, सद्भाव और सामाजिक न्याय को बढ़ावा देना।',
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

  function applyNavTranslations(dict) {
    const nav = document.querySelector('.navbar');
    if (!nav) return;

    const navTitle = nav.querySelector('#nav-title');
    if (navTitle && typeof dict.navTitle === 'string') {
      navTitle.textContent = dict.navTitle;
    }

    const links = nav.querySelectorAll('.nav-menu a');
    if (!links || links.length === 0) return;

    // nav.php order: About, Events, News, Join Us, Log In, Contact, Donate
    const keysInOrder = ['about', 'events', 'announcements', 'join', 'login', 'contact', 'donate'];
    keysInOrder.forEach((key, index) => {
      const a = links[index];
      if (!a) return;

      // Keep styling class (donate-nav) etc, only update text.
      setText(a, dict.nav[key]);
    });
  }

  function applyFooterTranslations(dict) {
    const footer = document.querySelector('footer.footer');
    if (!footer) return;

    const topSection = footer.querySelector('.footer-grid .footer-section');
    if (topSection) {
      setText(topSection.querySelector('h3'), dict.footer.orgName);
      setText(topSection.querySelector('p'), dict.footer.description);
    }

    const footerSections = footer.querySelectorAll('.footer-grid .footer-section');

    // 2nd column: Quick Links
    const quick = footerSections[1];
    if (quick) {
      setText(quick.querySelector('h4'), dict.footer.quickLinks);
      const items = quick.querySelectorAll('ul li a');
      const keys = ['article', 'gallery', 'careers', 'postholders', 'other'];
      keys.forEach((key, i) => setText(items[i], dict.footer[key]));
    }

    // 3rd column: Programs
    const programs = footerSections[2];
    if (programs) {
      setText(programs.querySelector('h4'), dict.footer.programs);
      const items = programs.querySelectorAll('ul li a');
      const keys = ['inTheNews', 'collab', 'impact', 'affiliates', 'reach'];
      keys.forEach((key, i) => setText(items[i], dict.footer[key]));
    }

    // 4th column: Contacts
    const contacts = footerSections[3];
    if (contacts) {
      setText(contacts.querySelector('h4'), dict.footer.contacts);
      const items = contacts.querySelectorAll('ul li a');
      const keys = ['whatsapp', 'teligram', 'wordpress', 'email', 'dev'];
      keys.forEach((key, i) => setText(items[i], dict.footer[key]));
    }

    // Footer bottom lines
    const bottom = footer.querySelector('.footer-bottom');
    if (bottom) {
      const ps = bottom.querySelectorAll('p');
      setText(ps[0], dict.footer.copyright);
      setText(ps[1], dict.footer.registered);
    }
  }

  function applySharedLanguage(lang) {
    const dict = sharedTranslations[lang] || sharedTranslations.en;
    applyNavTranslations(dict);
    applyFooterTranslations(dict);
  }

  // Expose a small API
  window.SharedI18n = {
    apply: applySharedLanguage
  };
})();
