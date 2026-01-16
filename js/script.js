// Mobile navigation behavior is handled by js/shared-layout.js

// Language Translation
let isHindi = false;

// Initialize language from querystring or cookie (persists across pages)
(function () {
    try {
        const params = new URLSearchParams(window.location.search);
        const qLang = params.get('lang');
        const cookieLang = (document.cookie.match(/(?:^|;\s*)lang=([^;]+)/) || [])[1];
        const lang = (qLang || (cookieLang ? decodeURIComponent(cookieLang) : '') || '').toLowerCase();
        isHindi = (lang === 'hi' || lang === 'hindi');
    } catch (e) {
        // default stays English
    }
})();

function applyInitialLanguage() {
    const langKey = isHindi ? 'hindi' : 'english';
    const translateBtn = document.querySelector('.translate-btn');
    if (translateBtn) translateBtn.innerHTML = isHindi ? 'English' : 'हिंदी';
    document.body.classList.toggle('hindi', isHindi);

    if (window.SharedLayout) {
        if (typeof window.SharedLayout.persistLangParam === 'function') {
            window.SharedLayout.persistLangParam(isHindi ? 'hi' : 'en');
        }
        if (typeof window.SharedLayout.applySharedTranslations === 'function') {
            window.SharedLayout.applySharedTranslations(langKey);
        }
        if (typeof window.SharedLayout.updateNavLinkLang === 'function') {
            window.SharedLayout.updateNavLinkLang(isHindi ? 'hi' : 'en');
        }
    }

    // Apply full page translations (same logic as toggleLanguage) without requiring a click.
    // This covers sections that render later in the page (announcements/events/gallery/map/etc.).
    const lang = langKey;

    // Update hero section
    const heroTitle = document.querySelector('.hero-title');
    const heroSubtitle = document.querySelector('.hero-subtitle');
    const heroLocation = document.querySelector('.hero-location');
    const heroBtns = document.querySelectorAll('.hero-buttons .btn');
    if (heroTitle) heroTitle.textContent = translations[lang].hero.title;
    if (heroSubtitle) heroSubtitle.textContent = translations[lang].hero.subtitle;
    if (heroLocation) heroLocation.textContent = translations[lang].hero.location;
    if (heroBtns[0]) heroBtns[0].textContent = translations[lang].hero.donateBtn;
    if (heroBtns[1]) heroBtns[1].textContent = translations[lang].hero.learnBtn;

    // Update about section
    const aboutTitle = document.querySelector('#about .section-header h2');
    const aboutSubtitle = document.querySelector('#about .section-header p');
    const visionTitle = document.querySelector('.about-text h3');
    const visionText = document.querySelector('.about-text > p');
    const missionPoints = document.querySelectorAll('.point h4');
    const missionTexts = document.querySelectorAll('.point p');
    if (aboutTitle) aboutTitle.textContent = translations[lang].about.title;
    if (aboutSubtitle) aboutSubtitle.textContent = translations[lang].about.subtitle;
    if (visionTitle) visionTitle.textContent = translations[lang].about.visionTitle;
    if (visionText) visionText.textContent = translations[lang].about.visionText;
    if (missionPoints[0]) missionPoints[0].textContent = translations[lang].about.communityTitle;
    if (missionPoints[1]) missionPoints[1].textContent = translations[lang].about.educationTitle;
    if (missionPoints[2]) missionPoints[2].textContent = translations[lang].about.healthcareTitle;
    if (missionTexts[0]) missionTexts[0].textContent = translations[lang].about.communityText;
    if (missionTexts[1]) missionTexts[1].textContent = translations[lang].about.educationText;
    if (missionTexts[2]) missionTexts[2].textContent = translations[lang].about.healthcareText;

    // Update donate section
    const donateTitle = document.querySelector('#donate h2');
    const donateSubtitle = document.querySelector('#donate > .container > .donate-content > p');
    const donateOptions = document.querySelectorAll('.donate-card p');
    const customInput = document.querySelector('#customAmount');
    const customBtn = document.querySelector('.custom-amount .btn');
    if (donateTitle) donateTitle.textContent = translations[lang].donate.title;
    if (donateSubtitle) donateSubtitle.textContent = translations[lang].donate.subtitle;
    if (donateOptions[0]) donateOptions[0].textContent = translations[lang].donate.option1;
    if (donateOptions[1]) donateOptions[1].textContent = translations[lang].donate.option2;
    if (donateOptions[2]) donateOptions[2].textContent = translations[lang].donate.option3;
    if (customInput) customInput.placeholder = translations[lang].donate.customPlaceholder;
    if (customBtn) customBtn.textContent = translations[lang].donate.customBtn;

    // Update announcements section
    const announcementsTitle = document.querySelector('#announcements .section-header h2');
    const announcementsSubtitle = document.querySelector('#announcements .section-header p');
    const announcementTitles = document.querySelectorAll('.announcement-content h3');
    const announcementTexts = document.querySelectorAll('.announcement-content p');
    const readMoreLinks = document.querySelectorAll('.read-more');
    if (announcementsTitle) announcementsTitle.textContent = translations[lang].announcements.title;
    if (announcementsSubtitle) announcementsSubtitle.textContent = translations[lang].announcements.subtitle;
    if (announcementTitles[0]) announcementTitles[0].textContent = translations[lang].announcements.medicalCamp;
    if (announcementTitles[1]) announcementTitles[1].textContent = translations[lang].announcements.scholarshipProgram;
    if (announcementTitles[2]) announcementTitles[2].textContent = translations[lang].announcements.unityRally;
    if (announcementTexts[0]) announcementTexts[0].textContent = translations[lang].announcements.medicalCampText;
    if (announcementTexts[1]) announcementTexts[1].textContent = translations[lang].announcements.scholarshipText;
    if (announcementTexts[2]) announcementTexts[2].textContent = translations[lang].announcements.unityRallyText;
    readMoreLinks.forEach((link) => {
        if (link) link.textContent = translations[lang].announcements.readMore;
    });

    // Update events section
    const eventTitle = document.querySelector('#events .section-header h2');
    const eventSubtitle = document.querySelector('#events .section-header p');
    if (eventTitle) eventTitle.textContent = translations[lang].events.title;
    if (eventSubtitle) eventSubtitle.textContent = translations[lang].events.subtitle;
    const eventBtns = document.querySelectorAll('.event-card .btn');
    eventBtns.forEach((btn) => {
        if (btn) btn.textContent = translations[lang].events.readMoreBtn;
    });

    // Update join us section
    const joinTitle = document.querySelector('#join .join-content h2');
    const joinSubtitle = document.querySelector('#join .join-content p');
    const joinCardTitles = document.querySelectorAll('.join-card h3');
    const joinCardTexts = document.querySelectorAll('.join-card p');
    const joinCardBtns = document.querySelectorAll('.join-card .btn');
    if (joinTitle) joinTitle.textContent = translations[lang].joinUs.title;
    if (joinSubtitle) joinSubtitle.textContent = translations[lang].joinUs.subtitle;
    if (joinCardTitles[0]) joinCardTitles[0].textContent = translations[lang].joinUs.memberTitle;
    if (joinCardTitles[1]) joinCardTitles[1].textContent = translations[lang].joinUs.volunteerTitle;
    if (joinCardTitles[2]) joinCardTitles[2].textContent = translations[lang].joinUs.partnerTitle;
    if (joinCardTexts[0]) joinCardTexts[0].textContent = translations[lang].joinUs.memberText;
    if (joinCardTexts[1]) joinCardTexts[1].textContent = translations[lang].joinUs.volunteerText;
    if (joinCardTexts[2]) joinCardTexts[2].textContent = translations[lang].joinUs.partnerText;
    if (joinCardBtns[0]) joinCardBtns[0].textContent = translations[lang].joinUs.memberBtn;
    if (joinCardBtns[1]) joinCardBtns[1].textContent = translations[lang].joinUs.volunteerBtn;
    if (joinCardBtns[2]) joinCardBtns[2].textContent = translations[lang].joinUs.partnerBtn;

    // Update contact section
    const contactTitle = document.querySelector('.contact-info h2');
    const contactSubtitle = document.querySelector('.contact-info > p');
    const contactHeaders = document.querySelectorAll('.contact-item h4');
    const contactTexts = document.querySelectorAll('.contact-item p');
    const formInputs = document.querySelectorAll('.contact-form input, .contact-form textarea, .contact-form select');
    const selectOptions = document.querySelectorAll('.contact-form option');
    const sendBtn = document.querySelector('.contact-form .btn');
    if (contactTitle) contactTitle.textContent = translations[lang].contact.title;
    if (contactSubtitle) contactSubtitle.textContent = translations[lang].contact.subtitle;
    if (contactHeaders[0]) contactHeaders[0].textContent = translations[lang].contact.address;
    if (contactHeaders[1]) contactHeaders[1].textContent = translations[lang].contact.phone;
    if (contactHeaders[2]) contactHeaders[2].textContent = translations[lang].contact.email;
    if (contactTexts[0]) contactTexts[0].innerHTML = translations[lang].contact.addressText;
    if (contactTexts[1]) contactTexts[1].innerHTML = translations[lang].contact.phoneText;
    if (contactTexts[2]) contactTexts[2].innerHTML = translations[lang].contact.emailText;
    if (formInputs[0]) formInputs[0].placeholder = translations[lang].contact.namePlaceholder;
    if (formInputs[1]) formInputs[1].placeholder = translations[lang].contact.emailPlaceholder;
    if (formInputs[2]) formInputs[2].placeholder = translations[lang].contact.phonePlaceholder;
    if (formInputs[4]) formInputs[4].placeholder = translations[lang].contact.messagePlaceholder;
    if (selectOptions[0]) selectOptions[0].textContent = translations[lang].contact.subjectPlaceholder;
    if (selectOptions[1]) selectOptions[1].textContent = translations[lang].contact.generalInquiry;
    if (selectOptions[2]) selectOptions[2].textContent = translations[lang].contact.volunteerOpp;
    if (selectOptions[3]) selectOptions[3].textContent = translations[lang].contact.donation;
    if (selectOptions[4]) selectOptions[4].textContent = translations[lang].contact.partnership;
    if (sendBtn) sendBtn.textContent = translations[lang].contact.sendBtn;

    // Update map + latest announcement + gallery
    const mapTitle = document.querySelector('.map-section .section-header h2');
    if (mapTitle) mapTitle.textContent = translations[lang].map.title;
    const latestAnnouncementTitle = document.querySelector('#latest-announcement-title');
    if (latestAnnouncementTitle) latestAnnouncementTitle.textContent = translations[lang].latestAnnouncement.title;
    const galleryTitle = document.querySelector('#gallery-title');
    const gallerySubtitle = document.querySelector('.gallery-preview .section-header p');
    const galleryBtn = document.querySelector('.gallery-action .btn');
    if (galleryTitle) galleryTitle.textContent = translations[lang].gallery.title;
    if (gallerySubtitle) gallerySubtitle.textContent = translations[lang].gallery.subtitle;
    if (galleryBtn) galleryBtn.innerHTML = `<i class="fas fa-images"></i> ${translations[lang].gallery.viewGallery}`;
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', applyInitialLanguage);
} else {
    applyInitialLanguage();
}
const translations = {
    english: {
        nav: {
            about: 'About',
            events: 'Events',
            announcements: 'News',
            join: 'Join Us',
            login: 'Log in',
            contact: 'Contact',
            donate: 'Donate'
        },
        hero: {
            title: 'All India Satrangi Salaam Association',
            subtitle: 'Promoting Unity, Harmony & Social Justice across India',
            location: 'Based in Prayagraj, Uttar Pradesh',
            donateBtn: 'Donate Now',
            learnBtn: 'Learn More'
        },
        about: {
            title: 'About Our Mission',
            subtitle: 'Working towards a harmonious and inclusive society',
            visionTitle: 'Our Vision',
            visionText: 'All India Satrangi Salaam Association is dedicated to fostering unity, promoting social harmony, and ensuring equal opportunities for all communities across India. Based in the historic city of Prayagraj, we work tirelessly to bridge divides and create a more inclusive society.',
            communityTitle: 'Community Service',
            communityText: 'Providing essential services to underserved communities',
            educationTitle: 'Education',
            educationText: 'Promoting education and skill development programs',
            healthcareTitle: 'Healthcare',
            healthcareText: 'Ensuring access to quality healthcare for all'
        },
        donate: {
            title: 'Support Our Cause',
            subtitle: 'Your contribution helps us create positive change in communities across India',
            option1: 'Spread Awareness',
            option2: 'Cleaner Environment',
            option3: 'Make a Difference',
            customPlaceholder: 'Enter custom amount',
            customBtn: 'Donate Custom Amount'
        },
        announcements: {
            title: 'Latest Announcements',
            subtitle: 'Stay updated with our recent activities and news',
            readMore: 'Read More',
            medicalCamp: 'Free Medical Camp',
            medicalCampText: 'We\'re organizing a free medical camp in Prayagraj. All community members are welcome to participate and benefit from free health checkups.',
            scholarshipProgram: 'Educational Scholarship Program',
            scholarshipText: 'Applications are now open for our annual scholarship program. We\'re providing financial assistance to deserving students from underprivileged backgrounds.',
            unityRally: 'Community Unity Rally',
            unityRallyText: 'Join us for a peaceful unity rally promoting harmony and understanding among all communities in Prayagraj.'
        },
        events: {
            title: 'Recent Events',
            subtitle: 'See our latest activities and community initiatives',
            readMoreBtn: 'Read More'
        },
        joinUs: {
            title: 'Join Our Movement',
            subtitle: 'Be part of the change you want to see in society',
            memberTitle: 'Become a Member',
            memberText: 'Join our association and contribute to community development',
            memberBtn: 'Apply for Membership',
            volunteerTitle: 'Volunteer',
            volunteerText: 'Dedicate your time and skills to help those in need',
            volunteerBtn: 'Become a Volunteer',
            partnerTitle: 'Partner with Us',
            partnerText: 'Collaborate with us to amplify our impact',
            partnerBtn: 'Partnership Inquiry'
        },
        contact: {
            title: 'Get in Touch',
            subtitle: 'Reach out to us for any inquiries or support',
            address: 'Address',
            addressText: '915 Shahpur urf Peepalgaon, Jhalwa, Sadar<br>Prayagraj, Uttar Pradesh, India - 211012',
            phone: 'Phone',
            phoneText: '+91 94554 39320<br>+91 87653 72798',
            email: 'Email',
            emailText: 'satrangisalamss@gmail.com<br><a href="https://chat.whatsapp.com/Jifh0MGROxAJQD4bueRFN0">whatsapp</a>',
            namePlaceholder: 'Your Name',
            emailPlaceholder: 'Your Email',
            phonePlaceholder: 'Your Phone',
            subjectPlaceholder: 'Select Subject',
            messagePlaceholder: 'Your Message',
            sendBtn: 'Send Message',
            generalInquiry: 'General Inquiry',
            volunteerOpp: 'Volunteer Opportunity',
            donation: 'Donation',
            partnership: 'Partnership'
        },
        map: {
            title: 'Find Us'
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
        },
        latestAnnouncement: {
            title: 'Latest Announcement'
        },
        gallery: {
            title: 'Our Impact in Pictures',
            subtitle: 'See how we\'re making a difference in communities across India',
            viewGallery: 'View Full Gallery'
        }
    },


    hindi: {
        nav: {
            about: 'हमारे बारे में',
            events: 'कार्यक्रम',
            announcements: 'समाचार',
            join: 'जुड़ें',
            login: 'लॉग-इन',
            contact: 'संपर्क',
            donate: 'दान करें'
        },
        hero: {
            title: 'अखिल भारतीय सतरंगी सलाम एसोसिएशन',
            subtitle: 'भारत भर में एकता, सद्भाव और सामाजिक न्याय को बढ़ावा देना',
            location: 'प्रयागराज, उत्तर प्रदेश में स्थित',
            donateBtn: 'अभी दान करें',
            learnBtn: 'और जानें'
        },
        about: {
            title: 'हमारे मिशन के बारे में',
            subtitle: 'एक सामंजस्यपूर्ण और समावेशी समाज की दिशा में काम करना',
            visionTitle: 'हमारा दृष्टिकोण',
            visionText: 'अखिल भारतीय सतरंगी सलाम एसोसिएशन एकता को बढ़ावा देने, सामाजिक सद्भाव को बढ़ावा देने और भारत भर के सभी समुदायों के लिए समान अवसर सुनिश्चित करने के लिए समर्पित है। ऐतिहासिक शहर प्रयागराज में स्थित, हम मतभेदों को पाटने और अधिक समावेशी समाज बनाने के लिए अथक प्रयास करते हैं।',
            communityTitle: 'सामुदायिक सेवा',
            communityText: 'वंचित समुदायों को आवश्यक सेवाएं प्रदान करना',
            educationTitle: 'शिक्षा',
            educationText: 'शिक्षा और कौशल विकास कार्यक्रमों को बढ़ावा देना',
            healthcareTitle: 'स्वास्थ्य सेवा',
            healthcareText: 'सभी के लिए गुणवत्तापूर्ण स्वास्थ्य सेवा तक पहुंच सुनिश्चित करना'
        },
        donate: {
            title: 'हमारे उद्देश्य का समर्थन करें',
            subtitle: 'आपका योगदान भारत भर के समुदायों में सकारात्मक बदलाव लाने में मदद करता है',
            option1: 'जागरूकता फैलाएं',
            option2: 'स्वच्छ पर्यावरण',
            option3: 'बदलाव लाएं',
            customPlaceholder: 'कस्टम राशि दर्ज करें',
            customBtn: 'कस्टम राशि दान करें'
        },
        announcements: {
            title: 'नवीनतम घोषणाएं',
            subtitle: 'हमारी हाल की गतिविधियों और समाचारों से अपडेट रहें',
            readMore: 'और पढ़ें',
            medicalCamp: 'निःशुल्क चिकित्सा शिविर',
            medicalCampText: 'हम प्रयागराज में एक निःशुल्क चिकित्सा शिविर का आयोजन कर रहे हैं। सभी समुदाय के सदस्य भाग लेने और निःशुल्क स्वास्थ्य जांच का लाभ उठाने के लिए आमंत्रित हैं।',
            scholarshipProgram: 'शैक्षणिक छात्रवृत्ति कार्यक्रम',
            scholarshipText: 'हमारे वार्षिक छात्रवृत्ति कार्यक्रम के लिए आवेदन खुले हैं। हम गरीब पृष्ठभूमि के योग्य छात्रों को वित्तीय सहायता प्रदान कर रहे हैं।',
            unityRally: 'सामुदायिक एकता रैली',
            unityRallyText: 'प्रयागराज में सभी समुदायों के बीच सद्भाव और समझ को बढ़ावा देने वाली शांतिपूर्ण एकता रैली में हमारे साथ जुड़ें।'
        },
        events: {
            title: 'हाल की घटनाएं',
            subtitle: 'हमारी नवीनतम गतिविधियों और सामुदायिक पहलों को देखें',
            readMoreBtn: 'और पढ़ें'
        },
        joinUs: {
            title: 'हमारे आंदोलन में शामिल हों',
            subtitle: 'समाज में आप जो बदलाव देखना चाहते हैं, उसका हिस्सा बनें',
            memberTitle: 'सदस्य बनें',
            memberText: 'हमारी संस्था में शामिल हों और सामुदायिक विकास में योगदान दें',
            memberBtn: 'सदस्यता के लिए आवेदन करें',
            volunteerTitle: 'स्वयंसेवक',
            volunteerText: 'जरूरतमंदों की मदद के लिए अपना समय और कौशल समर्पित करें',
            volunteerBtn: 'स्वयंसेवक बनें',
            partnerTitle: 'हमारे साथ साझेदारी करें',
            partnerText: 'हमारे प्रभाव को बढ़ाने के लिए हमारे साथ सहयोग करें',
            partnerBtn: 'साझेदारी पूछताछ'
        },
        contact: {
            title: 'संपर्क में रहें',
            subtitle: 'किसी भी पूछताछ या सहायता के लिए हमसे संपर्क करें',
            address: 'पता',
            addressText: '915 शाहपुर उर्फ़ पीपलगाँव, झलवा, सदर<br>प्रयागराज, उत्तर प्रदेश, भारत - 211012',
            phone: 'फोन',
            phoneText: '+91 94554 39320<br>+91 87653 72798',
            email: 'ईमेल',
            emailText: 'satrangisalamss@gmail.com<br><a href="https://chat.whatsapp.com/Jifh0MGROxAJQD4bueRFN0">whatsapp</a>',
            namePlaceholder: 'आपका नाम',
            emailPlaceholder: 'आपका ईमेल',
            phonePlaceholder: 'आपका फोन',
            subjectPlaceholder: 'विषय चुनें',
            messagePlaceholder: 'आपका संदेश',
            sendBtn: 'संदेश भेजें',
            generalInquiry: 'सामान्य पूछताछ',
            volunteerOpp: 'स्वयंसेवक अवसर',
            donation: 'दान',
            partnership: 'साझेदारी'
        },
        map: {
            title: 'हमें खोजें'
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
        },
        latestAnnouncement: {
            title: 'नवीनतम घोषणा'
        },
        gallery: {
            title: 'तस्वीरों में हमारा प्रभाव',
            subtitle: 'देखें कि हम भारत भर के समुदायों में कैसे बदलाव ला रहे हैं',
            viewGallery: 'पूरी गैलरी देखें'
        }
    }
};

function toggleLanguage() {
    isHindi = !isHindi;
    const lang = isHindi ? 'hindi' : 'english';
    const langParam = isHindi ? 'hi' : 'en';
    const translateBtn = document.querySelector('.translate-btn');
    
    // Update translate button
    translateBtn.innerHTML = isHindi ? 'English' : 'हिंदी';
    
    // Update body class for font changes
    document.body.classList.toggle('hindi', isHindi);

    // Persist across pages (cookie + querystring)
    try {
        document.cookie = 'lang=' + encodeURIComponent(langParam) + '; path=/; SameSite=Lax';
    } catch (e) {}

    try {
        const url = new URL(window.location.href);
        url.searchParams.set('lang', langParam);
        window.history.replaceState({}, '', url.toString());
    } catch (e) {}

    // Update nav links so clicks keep the new language
    if (window.SharedLayout && typeof window.SharedLayout.updateNavLinkLang === 'function') {
        window.SharedLayout.updateNavLinkLang(langParam);
    }

    // Shared nav title, nav links, and footer translations
    if (window.SharedLayout && typeof window.SharedLayout.applySharedTranslations === 'function') {
        window.SharedLayout.applySharedTranslations(lang);
    }
    
    // Note: nav title + nav links are handled by SharedLayout.applySharedTranslations()
    
    // Update hero section
    const heroTitle = document.querySelector('.hero-title');
    const heroSubtitle = document.querySelector('.hero-subtitle');
    const heroLocation = document.querySelector('.hero-location');
    const heroBtns = document.querySelectorAll('.hero-buttons .btn');
    
    if (heroTitle) heroTitle.textContent = translations[lang].hero.title;
    if (heroSubtitle) heroSubtitle.textContent = translations[lang].hero.subtitle;
    if (heroLocation) heroLocation.textContent = translations[lang].hero.location;
    if (heroBtns[0]) heroBtns[0].textContent = translations[lang].hero.donateBtn;
    if (heroBtns[1]) heroBtns[1].textContent = translations[lang].hero.learnBtn;
    
    // Update about section
    const aboutTitle = document.querySelector('#about .section-header h2');
    const aboutSubtitle = document.querySelector('#about .section-header p');
    const visionTitle = document.querySelector('.about-text h3');
    const visionText = document.querySelector('.about-text > p');
    const missionPoints = document.querySelectorAll('.point h4');
    const missionTexts = document.querySelectorAll('.point p');
    
    if (aboutTitle) aboutTitle.textContent = translations[lang].about.title;
    if (aboutSubtitle) aboutSubtitle.textContent = translations[lang].about.subtitle;
    if (visionTitle) visionTitle.textContent = translations[lang].about.visionTitle;
    if (visionText) visionText.textContent = translations[lang].about.visionText;
    
    if (missionPoints[0]) missionPoints[0].textContent = translations[lang].about.communityTitle;
    if (missionPoints[1]) missionPoints[1].textContent = translations[lang].about.educationTitle;
    if (missionPoints[2]) missionPoints[2].textContent = translations[lang].about.healthcareTitle;
    
    if (missionTexts[0]) missionTexts[0].textContent = translations[lang].about.communityText;
    if (missionTexts[1]) missionTexts[1].textContent = translations[lang].about.educationText;
    if (missionTexts[2]) missionTexts[2].textContent = translations[lang].about.healthcareText;
    
    // Update donate section
    const donateTitle = document.querySelector('#donate h2');
    const donateSubtitle = document.querySelector('#donate > .container > .donate-content > p');
    const donateOptions = document.querySelectorAll('.donate-card p');
    const customInput = document.querySelector('#customAmount');
    const customBtn = document.querySelector('.custom-amount .btn');
    
    if (donateTitle) donateTitle.textContent = translations[lang].donate.title;
    if (donateSubtitle) donateSubtitle.textContent = translations[lang].donate.subtitle;
    if (donateOptions[0]) donateOptions[0].textContent = translations[lang].donate.option1;
    if (donateOptions[1]) donateOptions[1].textContent = translations[lang].donate.option2;
    if (donateOptions[2]) donateOptions[2].textContent = translations[lang].donate.option3;
    if (customInput) customInput.placeholder = translations[lang].donate.customPlaceholder;
    if (customBtn) customBtn.textContent = translations[lang].donate.customBtn;

    // Update announcements section
    const announcementsTitle = document.querySelector('#announcements .section-header h2');
    const announcementsSubtitle = document.querySelector('#announcements .section-header p');
    const announcementTitles = document.querySelectorAll('.announcement-content h3');
    const announcementTexts = document.querySelectorAll('.announcement-content p');
    const readMoreLinks = document.querySelectorAll('.read-more');

    if (announcementsTitle) announcementsTitle.textContent = translations[lang].announcements.title;
    if (announcementsSubtitle) announcementsSubtitle.textContent = translations[lang].announcements.subtitle;
    
    if (announcementTitles[0]) announcementTitles[0].textContent = translations[lang].announcements.medicalCamp;
    if (announcementTitles[1]) announcementTitles[1].textContent = translations[lang].announcements.scholarshipProgram;
    if (announcementTitles[2]) announcementTitles[2].textContent = translations[lang].announcements.unityRally;
    
    if (announcementTexts[0]) announcementTexts[0].textContent = translations[lang].announcements.medicalCampText;
    if (announcementTexts[1]) announcementTexts[1].textContent = translations[lang].announcements.scholarshipText;
    if (announcementTexts[2]) announcementTexts[2].textContent = translations[lang].announcements.unityRallyText;
    
    readMoreLinks.forEach(link => {
        if (link) link.textContent = translations[lang].announcements.readMore;
    });

    // Update only the 'Read More' button in Events section
    const eventBtns = document.querySelectorAll('.event-card .btn');
    if (eventBtns[0]) eventBtns[0].textContent = translations[lang].events.readMoreBtn;
    if (eventBtns[1]) eventBtns[1].textContent = translations[lang].events.readMoreBtn;
    if (eventBtns[2]) eventBtns[2].textContent = translations[lang].events.readMoreBtn;

    // Update join us section
    const joinTitle = document.querySelector('#join .join-content h2');
    const joinSubtitle = document.querySelector('#join .join-content p');
    const joinCardTitles = document.querySelectorAll('.join-card h3');
    const joinCardTexts = document.querySelectorAll('.join-card p');
    const joinCardBtns = document.querySelectorAll('.join-card .btn');

    if (joinTitle) joinTitle.textContent = translations[lang].joinUs.title;
    if (joinSubtitle) joinSubtitle.textContent = translations[lang].joinUs.subtitle;
    
    if (joinCardTitles[0]) joinCardTitles[0].textContent = translations[lang].joinUs.memberTitle;
    if (joinCardTitles[1]) joinCardTitles[1].textContent = translations[lang].joinUs.volunteerTitle;
    if (joinCardTitles[2]) joinCardTitles[2].textContent = translations[lang].joinUs.partnerTitle;
    
    if (joinCardTexts[0]) joinCardTexts[0].textContent = translations[lang].joinUs.memberText;
    if (joinCardTexts[1]) joinCardTexts[1].textContent = translations[lang].joinUs.volunteerText;
    if (joinCardTexts[2]) joinCardTexts[2].textContent = translations[lang].joinUs.partnerText;
    
    if (joinCardBtns[0]) joinCardBtns[0].textContent = translations[lang].joinUs.memberBtn;
    if (joinCardBtns[1]) joinCardBtns[1].textContent = translations[lang].joinUs.volunteerBtn;
    if (joinCardBtns[2]) joinCardBtns[2].textContent = translations[lang].joinUs.partnerBtn;

    // Update contact section
    const contactTitle = document.querySelector('.contact-info h2');
    const contactSubtitle = document.querySelector('.contact-info > p');
    const contactHeaders = document.querySelectorAll('.contact-item h4');
    const contactTexts = document.querySelectorAll('.contact-item p');
    const formInputs = document.querySelectorAll('.contact-form input, .contact-form textarea, .contact-form select');
    const selectOptions = document.querySelectorAll('.contact-form option');
    const sendBtn = document.querySelector('.contact-form .btn');

    if (contactTitle) contactTitle.textContent = translations[lang].contact.title;
    if (contactSubtitle) contactSubtitle.textContent = translations[lang].contact.subtitle;
    
    if (contactHeaders[0]) contactHeaders[0].textContent = translations[lang].contact.address;
    if (contactHeaders[1]) contactHeaders[1].textContent = translations[lang].contact.phone;
    if (contactHeaders[2]) contactHeaders[2].textContent = translations[lang].contact.email;
    
    if (contactTexts[0]) contactTexts[0].innerHTML = translations[lang].contact.addressText;
    if (contactTexts[1]) contactTexts[1].innerHTML = translations[lang].contact.phoneText;
    if (contactTexts[2]) contactTexts[2].innerHTML = translations[lang].contact.emailText;
    
    if (formInputs[0]) formInputs[0].placeholder = translations[lang].contact.namePlaceholder;
    if (formInputs[1]) formInputs[1].placeholder = translations[lang].contact.emailPlaceholder;
    if (formInputs[2]) formInputs[2].placeholder = translations[lang].contact.phonePlaceholder;
    if (formInputs[4]) formInputs[4].placeholder = translations[lang].contact.messagePlaceholder;
    
    if (selectOptions[0]) selectOptions[0].textContent = translations[lang].contact.subjectPlaceholder;
    if (selectOptions[1]) selectOptions[1].textContent = translations[lang].contact.generalInquiry;
    if (selectOptions[2]) selectOptions[2].textContent = translations[lang].contact.volunteerOpp;
    if (selectOptions[3]) selectOptions[3].textContent = translations[lang].contact.donation;
    if (selectOptions[4]) selectOptions[4].textContent = translations[lang].contact.partnership;
    
    if (sendBtn) sendBtn.textContent = translations[lang].contact.sendBtn;

    // Update map section
    const mapTitle = document.querySelector('.map-section .section-header h2');
    if (mapTitle) mapTitle.textContent = translations[lang].map.title;

    // Note: footer is handled by SharedLayout.applySharedTranslations()

    // Update latest announcement section
    const latestAnnouncementTitle = document.querySelector('#latest-announcement-title');
    if (latestAnnouncementTitle) latestAnnouncementTitle.textContent = translations[lang].latestAnnouncement.title;

    // Update gallery section
    const galleryTitle = document.querySelector('#gallery-title');
    const gallerySubtitle = document.querySelector('.gallery-preview .section-header p');
    const galleryBtn = document.querySelector('.gallery-action .btn');
    
    if (galleryTitle) galleryTitle.textContent = translations[lang].gallery.title;
    if (gallerySubtitle) gallerySubtitle.textContent = translations[lang].gallery.subtitle;
    if (galleryBtn) {
        galleryBtn.innerHTML = `<i class="fas fa-images"></i> ${translations[lang].gallery.viewGallery}`;
    }
}

// Smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Navbar background change on scroll
window.addEventListener('scroll', () => {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 100) {
        navbar.style.background = 'rgba(255, 255, 255, 0.98)';
        navbar.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.1)';
    } else {
        navbar.style.background = 'rgba(255, 255, 255, 0.95)';
        navbar.style.boxShadow = 'none';
    }
});

// FEATURE 2: Remove donation button alerts - let them navigate to donate.php instead
// (Donation buttons are now anchor tags in HTML, no JavaScript handlers needed)

// FEATURE 3: Change event buttons to "Read More" functionality - Navigate to events page
document.querySelectorAll('.event-card .btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Navigate to events page instead of showing alert
        window.location.href = 'public/events';
    });
});

// Join us button handlers - Navigate to joinUs page instead of showing alert
document.querySelectorAll('.join-card .btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Navigate to joinUs page instead of showing alert
        window.location.href = 'public/joinUs';
    });
});

// Intersection Observer for fade-in animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('fade-in-up');
        }
    });
}, observerOptions);

// Observe all sections for animation
document.querySelectorAll('section').forEach(section => {
    observer.observe(section);
});

// Close mobile menu when clicking outside is handled by js/shared-layout.js

// Prevent zoom on double tap for mobile
let lastTouchEnd = 0;
document.addEventListener('touchend', function (event) {
    const now = (new Date()).getTime();
    if (now - lastTouchEnd <= 300) {
        event.preventDefault();
    }
    lastTouchEnd = now;
}, false);

// Gallery Slideshow Functionality
let currentSlide = 0;
const slides = document.querySelectorAll('.gallery-slide');
const totalSlides = slides.length;

function showSlide(index) {
    slides.forEach(slide => slide.classList.remove('active'));
    
    if (index >= totalSlides) {
        currentSlide = 0;
    } else if (index < 0) {
        currentSlide = totalSlides - 1;
    } else {
        currentSlide = index;
    }
    
    if (slides[currentSlide]) {
        slides[currentSlide].classList.add('active');
    }
}

function changeSlide(direction) {
    showSlide(currentSlide + direction);
}

// Auto-advance slideshow every 5 seconds
function startSlideshow() {
    setInterval(() => {
        changeSlide(1);
    }, 2500);
}

// Initialize slideshow when page loads
document.addEventListener('DOMContentLoaded', function() {
    if (slides.length > 0) {
        showSlide(0);
        startSlideshow();
    }
});
