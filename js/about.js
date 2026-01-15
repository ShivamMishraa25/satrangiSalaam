// Mobile Navigation Toggle
const hamburger = document.querySelector('.hamburger');
const navMenu = document.querySelector('.nav-menu');

hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('active');
    navMenu.classList.toggle('active');
});

// Close mobile menu when clicking on a link
document.querySelectorAll('.nav-menu a').forEach(link => {
    link.addEventListener('click', () => {
        hamburger.classList.remove('active');
        navMenu.classList.remove('active');
    });
});

// Language translation
(function () {
  const translations = {
    en: {
      badge: 'About AISSA',
      title: 'About Us',
      lead:
        'All India Satrangi Salaam Association (AISSA) is a registered, lawful, and socially responsible organization that works in India with the core values of equality, dignity, inclusion, and social responsibility.',
      chip1: 'Equality & Dignity',
      chip2: 'Inclusion',
      chip3: 'Social Responsibility',
      overviewTitle: 'Quick Overview',
      overviewLabel1: 'Legal Status:',
      overviewVal1: 'Registered Trust (Indian Trusts Act)',
      overviewLabel2: 'Headquarters:',
      overviewVal2: 'Prayagraj, Uttar Pradesh',
      overviewLabel3: 'Core Values:',
      overviewVal3: 'Equality, dignity, inclusion, responsibility',
      introTitle: 'Who We Are',
      introP1:
        'AISSA is not meant for any single community, identity, caste, religion, or group. It exists for all those who believe in human dignity, mutual respect, and responsible social coexistence.',
      introP2:
        'AISSA is a Registered Trust, headquartered in Prayagraj, Uttar Pradesh, and all its activities are conducted strictly within a legal, ethical, and institutional framework.',
      historyTitle: 'Establishment and Historical Background',
      historyP1: 'Satrangi Salaam began in the year 2020 as an online initiative.',
      historyP2:
        'At that time, Bhartendu Vimal Dubey, along with a few associates, initiated dialogue through digital platforms to address discrimination, inequality, and prevailing social misconceptions.',
      historyP3:
        'This initiative was purely awareness-based and was not bound by any formal organizational structure.',
      historyP4:
        'Over time, considering the continuity of work, social necessity, and responsibility, it became evident that this initiative needed to be established as a lawful, accountable, and structured institution.',
      historyP5:
        'Accordingly, on 22 July 2024, formal resolutions were passed in Prayagraj, and the organization was officially constituted under the name:',
      historyQuote: '“All India Satrangi Salaam Association”',
      historyP6: 'Subsequently, on 2 August 2024, the organization was registered under the Indian Trusts Act.',
      historyP7: 'Since then, AISSA has been functioning as a registered public trust.',
      meaningTitle: '“Satrangi Salaam” — Meaning and Philosophy',
      meaningP1: '“Satrangi” means multiple colours, and “Salaam” means respect.',
      meaningP2:
        'This name is not derived from any religion, sect, or community, but is inspired by Indian social philosophy.',
      meaningP3:
        'We live in a society where discrimination based on colour, caste, language, identity, class, and ideology is common.',
      meaningP4:
        'Sometimes green–blue, sometimes black–white, sometimes caste and sub-caste — new reasons for division are continuously manufactured.',
      meaningQuote: 'Diversity is not a cause of conflict, but the strength of society.',
      meaningP5:
        'Nature itself is not of a single colour. Family members are not identical. Even our fingers are not the same — yet they function together.',
      meaningP6: 'AISSA believes in this principle of Unity in Diversity.',
      meaningP7:
        'The word “Salaam” is used here not as a religious expression, but as a symbol of respect, acceptance, and human dignity.',
      meaningP8:
        'It is also intended to promote linguistic tolerance. AISSA actively opposes harmful tendencies that detach us from our own languages and cultures.',
      forTitle: 'Who Is AISSA For?',
      forP1: 'AISSA is not limited to any single identity or group.',
      forL1: 'Not restricted to any one community',
      forL2: 'Not a caste-based, religion-based, or ideology-based institution',
      forL3: 'Not driven by any political or religious agenda',
      forP2:
        'AISSA is for all individuals who believe in equality, wish to stand against discrimination, understand social responsibility, and seek coexistence with dignity and respect.',
      forP3:
        'To become a member or collaborator of the organization, no identity claim is required — only agreement with the core principles.',
      workTitle: 'What We Do (Our Work & Approach)',
      workP1:
        'We work across education, environment, health, human rights, and all domains essential for holistic human development.',
      workP2:
        'AISSA’s scope is not limited to awareness and dialogue alone. The objectives of the organization include:',
      workL1: 'Challenging misconceptions prevalent in society',
      workL2: 'Promoting respectful and meaningful dialogue',
      workL3: 'Addressing health, mental balance, and dignity',
      workL4: 'Translating inclusion into actual practice',
      workP3:
        'We consciously work on neglected and uncomfortable issues that are often deliberately ignored.',
      workL5: 'Does not represent any individual',
      workL6: 'Does not interfere in anyone’s private life',
      workL7: 'Is not a surveillance or control body',
      workP4: 'Our approach is neutral, ethical, and responsible.',
      legalTitle: 'Legal Status and Transparency',
      legalP1: 'AISSA is a registered trust and possesses all necessary legal credentials:',
      legalL1: 'Registered under the Indian Trusts Act',
      legalL2: 'Registration Number: 290/24 (IV)',
      legalL3: 'NGO Darpan ID: UP/2025/0625395',
      legalL4: 'Registered Main Office: Prayagraj, Uttar Pradesh',
      legalL5: 'Email: SatrangiSalamSS@gmail.com',
      legalP2: 'Additional information is disclosed strictly on a Need-to-Know basis.',
      legalQuote: 'Not all information is meant for public disclosure.',
      legalP3:
        'Therefore, the organization follows the Need-to-Know Principle to ensure institutional security, member privacy, and protection against misuse.',
      govTitle: 'Governance, Ethics, and Accountability',
      govP1:
        'AISSA is not a person-centric organization. Its governance operates through Trust Deed, amended rules, internal codes, committees, and collective decision-making processes.',
      govP2: 'Every position, responsibility, and decision falls within a defined institutional framework.',
      discTitle: 'Disclaimer',
      discP1:
        'Content published on AISSA’s website and digital platforms may or may not reflect the official position of the organization, and may represent the personal views of authors or contributors.',
      discP2: 'AISSA does not support hate, discrimination, or any unlawful or unethical conduct.',
      discP3: 'The organization expects respectful and responsible behaviour from all users.'
    },
    hi: {
      badge: 'AISSA के बारे में',
      title: 'हमारे बारे में',
      lead:
        'All India Satrangi Salaam Association (AISSA) एक पंजीकृत, वैधानिक और सामाजिक रूप से उत्तरदायी संगठन है, जो भारत में समानता, गरिमा, समावेशन और सामाजिक जिम्मेदारी के मूल्यों के साथ कार्य करता है।',
      chip1: 'समानता और गरिमा',
      chip2: 'समावेशन',
      chip3: 'सामाजिक जिम्मेदारी',
      overviewTitle: 'संक्षिप्त परिचय',
      overviewLabel1: 'वैधानिक स्थिति:',
      overviewVal1: 'पंजीकृत ट्रस्ट (Indian Trusts Act)',
      overviewLabel2: 'मुख्यालय:',
      overviewVal2: 'प्रयागराज, उत्तर प्रदेश',
      overviewLabel3: 'मुख्य मूल्य:',
      overviewVal3: 'समानता, गरिमा, समावेशन, जिम्मेदारी',
      introTitle: 'हम कौन हैं',
      introP1:
        'AISSA किसी एक समुदाय, पहचान, जाति, धर्म या समूह के लिए नहीं है। यह उन सभी के लिए है जो मानव गरिमा, सम्मान और जिम्मेदार सामाजिक सह-अस्तित्व में विश्वास रखते हैं।',
      introP2:
        'AISSA एक Registered Trust है, जिसका मुख्यालय प्रयागराज, उत्तर प्रदेश में स्थित है, और यह संगठन अपने सभी कार्यों को विधिक, नैतिक और संस्थागत ढाँचे के अंतर्गत संचालित करता है।',
      historyTitle: 'स्थापना और ऐतिहासिक पृष्ठभूमि',
      historyP1: 'सतरंगी सलाम की शुरुआत वर्ष 2020 में एक ऑनलाइन पहल के रूप में हुई थी।',
      historyP2:
        'उस समय भारतेंदु विमल दुबे और उनके कुछ साथियों ने डिजिटल माध्यमों के ज़रिये समाज में चल रहे भेदभाव, असमानता और गलत धारणाओं पर संवाद शुरू किया।',
      historyP3:
        'यह पहल पूरी तरह जागरूकता आधारित थी और किसी औपचारिक संगठनात्मक ढाँचे से बंधी नहीं थी।',
      historyP4:
        'समय के साथ, कार्य की निरंतरता, सामाजिक ज़रूरत और जिम्मेदारी को देखते हुए यह महसूस किया गया कि इस पहल को एक वैधानिक, जवाबदेह और संरचित संस्था के रूप में स्थापित किया जाना चाहिए।',
      historyP5:
        'इसी क्रम में 22 जुलाई 2024 को प्रयागराज में विधिवत प्रस्ताव (Resolutions) पारित कर संस्था का औपचारिक गठन किया गया:',
      historyQuote: '“All India Satrangi Salaam Association”',
      historyP6:
        'इसके पश्चात 2 अगस्त 2024 को संस्था का पंजीकरण Indian Trusts Act के अंतर्गत कराया गया।',
      historyP7: 'तब से AISSA एक पंजीकृत ट्रस्ट के रूप में कार्य कर रही है।',
      meaningTitle: '“सतरंगी सलाम” — नाम का अर्थ और दर्शन',
      meaningP1: '“सतरंगी” का अर्थ है विविध रंग, और “सलाम” का अर्थ है सम्मान।',
      meaningP2:
        'AISSA के नाम का यह संयोजन किसी धर्म, पंथ या समुदाय से नहीं, बल्कि भारतीय सामाजिक दर्शन से प्रेरित है।',
      meaningP3:
        'हम ऐसे समाज में रहते हैं जहाँ रंग, जाति, भाषा, पहचान, वर्ग और विचारों के आधार पर भेदभाव आम है।',
      meaningP4:
        'कभी हरा-नीला, कभी काला-गोरा, कभी जाति-उपजाति — विभाजन के नए कारण लगातार गढ़े जाते हैं।',
      meaningQuote: 'विविधता संघर्ष का कारण नहीं, बल्कि समाज की शक्ति है।',
      meaningP5:
        'प्रकृति स्वयं एक रंग की नहीं है। परिवार के सभी सदस्य एक जैसे नहीं होते। हमारी उँगलियाँ भी एक-सी नहीं होतीं फिर भी वे साथ मिलकर काम करती हैं।',
      meaningP6: 'AISSA इसी Unity in Diversity के सिद्धांत में विश्वास रखता है।',
      meaningP7:
        '“सलाम” शब्द यहाँ किसी धार्मिक अभिव्यक्ति के लिए नहीं, बल्कि सम्मान, स्वीकार्यता और मानवीय गरिमा के प्रतीक के रूप में प्रयुक्त है।',
      meaningP8:
        'यह भाषाओं के प्रति सहिष्णुता बढ़ाने के लिए भी है। AISSA अपनी भाषाओं और संस्कृतियों से खुद को अलग करने वाली हानिकारक प्रवृत्तियों का विरोध करती है।',
      forTitle: 'AISSA किसके लिए है?',
      forP1: 'AISSA किसी एक पहचान या समूह तक सीमित संगठन नहीं है।',
      forL1: 'किसी एक समुदाय तक सीमित नहीं',
      forL2: 'किसी जाति, धर्म या विचारधारा की संस्था नहीं',
      forL3: 'किसी राजनीतिक या धार्मिक एजेंडे से संचालित नहीं',
      forP2:
        'AISSA उन सभी व्यक्तियों के लिए है जो समानता में विश्वास रखते हैं, भेदभाव के विरुद्ध खड़े होना चाहते हैं, सामाजिक जिम्मेदारी को समझते हैं, और गरिमा व सम्मान के साथ सह-अस्तित्व चाहते हैं।',
      forP3:
        'संस्था का सदस्य या सहयोगी बनने के लिए किसी पहचान का दावा नहीं, बल्कि मूल सिद्धांतों से सहमति आवश्यक है।',
      workTitle: 'हम क्या करते हैं (कार्य और दृष्टिकोण)',
      workP1:
        'हम शिक्षा, पर्यावरण, स्वास्थ्य, मानवाधिकार और मानव के सर्वांगीण विकास से जुड़े सभी क्षेत्रों में काम करते हैं।',
      workP2:
        'AISSA का कार्य-क्षेत्र जागरूकता और संवाद तक सीमित नहीं है। संस्था के उद्देश्य हैं:',
      workL1: 'समाज में फैली गलत धारणाओं को चुनौती देना',
      workL2: 'सम्मानजनक और सार्थक संवाद को बढ़ावा देना',
      workL3: 'स्वास्थ्य, मानसिक संतुलन और गरिमा पर कार्य करना',
      workL4: 'समावेशन को व्यवहार में उतारना',
      workP3:
        'हम उन अनछुए और असहज मुद्दों पर भी काम करते हैं जिन्हें अक्सर जानबूझकर नजरअंदाज किया जाता है।',
      workL5: 'किसी व्यक्ति का प्रतिनिधित्व नहीं करती',
      workL6: 'किसी की निजी जानकारी या जीवन में हस्तक्षेप नहीं करती',
      workL7: 'किसी प्रकार की निगरानी या नियंत्रण संस्था नहीं है',
      workP4: 'हमारा दृष्टिकोण Neutral, Ethical और Responsible है।',
      legalTitle: 'वैधानिक स्थिति और पारदर्शिता',
      legalP1: 'AISSA एक पंजीकृत ट्रस्ट है और इसके पास सभी आवश्यक वैधानिक पहचान उपलब्ध हैं:',
      legalL1: 'Registered under Indian Trusts Act',
      legalL2: 'पंजीकरण नम्बर: 290/24 (IV)',
      legalL3: 'NGO Darpan ID: UP/2025/0625395',
      legalL4: 'Registered Main Office: Prayagraj, Uttar Pradesh',
      legalL5: 'ईमेल: SatrangiSalamSS@gmail.com',
      legalP2: 'अन्य जानकारी Need-to-Know Principle के आधार पर साझा की जाती है।',
      legalQuote: 'हर जानकारी सार्वजनिक करने योग्य नहीं होती।',
      legalP3:
        'संस्था Need-to-Know Principle का पालन करती है ताकि संस्था की सुरक्षा, सदस्यों की गोपनीयता और दुरुपयोग से बचाव सुनिश्चित हो सके।',
      govTitle: 'शासन, नैतिकता और जवाबदेही',
      govP1:
        'AISSA व्यक्ति-केंद्रित संस्था नहीं है। इसका संचालन Trust Deed, संशोधित नियमावली, आंतरिक संहिता, समितियों और सामूहिक निर्णय-प्रक्रिया के माध्यम से होता है।',
      govP2: 'हर पद, जिम्मेदारी और निर्णय संस्थागत ढाँचे के अंतर्गत आता है।',
      discTitle: 'अस्वीकरण',
      discP1:
        'AISSA की वेबसाइट और डिजिटल प्लेटफॉर्म पर प्रकाशित सामग्री संस्था के आधिकारिक विचारों को प्रतिबिंबित कर भी सकती है और नहीं भी; यह लेखकों/योगदानकर्ताओं की व्यक्तिगत राय भी हो सकती है।',
      discP2: 'AISSA किसी भी प्रकार की घृणा, भेदभाव, या अवैधानिक/अनैतिक आचरण का समर्थन नहीं करती।',
      discP3: 'संस्था सभी उपयोगकर्ताओं से सम्मानजनक और जिम्मेदार व्यवहार की अपेक्षा रखती है।'
    }
  };

  let isHindi = false;

  function applyLanguage(lang) {
    const dict = translations[lang];
    if (!dict) return;

    document.documentElement.lang = lang === 'hi' ? 'hi' : 'en';

    document.querySelectorAll('[data-i18n]').forEach((el) => {
      const key = el.getAttribute('data-i18n');
      const value = dict[key];
      if (typeof value === 'string') {
        el.textContent = value;
      }
    });

    const btn = document.querySelector('.translate-btn');
    if (btn) btn.textContent = lang === 'hi' ? 'English' : 'हिंदी';

    document.body.classList.toggle('hindi', lang === 'hi');
  }

  // Expose for inline onclick in nav.php
  window.toggleLanguage = function () {
    isHindi = !isHindi;
    applyLanguage(isHindi ? 'hi' : 'en');
  };

  // Initial state
  applyLanguage('en');
})();
