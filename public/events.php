<?php
include '../config.php';
include '../includes/db.php';

$lang = $_GET['lang'] ?? ($_COOKIE['lang'] ?? 'en');
$lang = ($lang === 'hi' || $lang === 'hindi') ? 'hi' : 'en';

$sql = "SELECT * FROM events ORDER BY event_date DESC";
$result = $conn->query($sql);

$hindiMonths = [
    'January' => 'जनवरी',
    'February' => 'फ़रवरी',
    'March' => 'मार्च',
    'April' => 'अप्रैल',
    'May' => 'मई',
    'June' => 'जून',
    'July' => 'जुलाई',
    'August' => 'अगस्त',
    'September' => 'सितंबर',
    'October' => 'अक्टूबर',
    'November' => 'नवंबर',
    'December' => 'दिसंबर',
];

function normalizeLocalMediaPath($path)
{
    $clean = trim((string) $path);
    $clean = preg_replace('#^\.{1,2}/+#', '', $clean);
    $clean = ltrim(str_replace('\\', '/', $clean), '/');
    return $clean;
}

function eventThumbUrl($path)
{
    if (preg_match('~^https?://~i', $path)) {
        return $path;
    }

    $clean = normalizeLocalMediaPath($path);
    if ($clean === '') {
        return '';
    }

    return BASE_URL . 'public/event_media.php?path=' . rawurlencode($clean) . '&w=560&q=62';
}

function eventFullUrl($path)
{
    if (preg_match('~^https?://~i', $path)) {
        return $path;
    }

    $clean = normalizeLocalMediaPath($path);
    if ($clean === '') {
        return '';
    }

    return BASE_URL . ltrim($clean, '/');
}

function formatEventDateLocalized($dateValue, $lang, $hindiMonths)
{
    $trimmed = trim((string) $dateValue);
    if ($trimmed === '') {
        return '';
    }

    $date = DateTime::createFromFormat('Y-m-d', $trimmed);
    if (!$date) {
        return $trimmed;
    }

    if ($lang === 'hi') {
        $monthEnglish = $date->format('F');
        $monthHindi = $hindiMonths[$monthEnglish] ?? $monthEnglish;
        return $date->format('d') . ' ' . $monthHindi . ' ' . $date->format('Y');
    }

    return $date->format('F d, Y');
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang === 'hi' ? 'hi' : 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta property="og:site_name" content="Satrangi Salaam">
    <meta property="og:title" content="Events | All India Satrangi Salaam Association">
    <meta property="og:description" content="Explore AISSA events, workshops, awareness drives, and community activities.">
    <meta property="og:type" content="website">

    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo BASE_URL; ?>apple-touch-icon.png">

    <title>Events | AISSA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/events.css">

    <script>
        window.__INITIAL_LANG__ = <?php echo json_encode($lang); ?>;
        window.__BASE_URL__ = <?php echo json_encode(BASE_URL); ?>;
        window.__BASE_PATH__ = <?php echo json_encode(BASE_PATH); ?>;
    </script>
</head>
<body class="<?php echo $lang === 'hi' ? 'hindi' : ''; ?>">
    <?php include '../includes/nav.php'; ?>

    <main class="events-page">
        <section class="events-hero">
            <div class="events-hero__bg" aria-hidden="true"></div>
            <div class="events-container events-hero__inner">
                <div class="events-hero__content">
                    <p class="events-hero__badge" data-i18n="badge">AISSA Activities</p>
                    <h1 class="events-hero__title" data-i18n="title">Our Events</h1>
                    <p class="events-hero__lead" data-i18n="lead">Workshops, campaigns, celebrations, and social actions that shape our collective impact.</p>
                </div>
            </div>
        </section>

        <section class="events-section">
            <div class="events-container">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <?php $formattedDate = formatEventDateLocalized($row['event_date'] ?? '', $lang, $hindiMonths); ?>
                        <article class="event-card reveal">
                            <header class="event-card__header">
                                <h2><?php echo htmlspecialchars($row['event_name'] ?? 'Event'); ?></h2>
                                <p class="event-date"><?php echo htmlspecialchars($formattedDate); ?></p>
                                <?php if (!empty($row['event_location'])): ?>
                                    <p class="event-location"><?php echo htmlspecialchars($row['event_location']); ?></p>
                                <?php endif; ?>
                            </header>
                            <?php if (!empty($row['event_description'])): ?>
                                <p class="event-description"><?php echo nl2br(htmlspecialchars($row['event_description'])); ?></p>
                            <?php endif; ?>
                            <div class="event-images" data-event-date="<?php echo htmlspecialchars($formattedDate); ?>">
                                <?php
                                $images = explode(',', $row['event_image'] ?? '');
                                foreach ($images as $image) {
                                    $image = trim($image);
                                    if ($image === '') {
                                        continue;
                                    }

                                    $fullUrl = eventFullUrl($image);
                                    $thumbUrl = eventThumbUrl($image);
                                    if ($fullUrl === '' || $thumbUrl === '') {
                                        continue;
                                    }
                                    ?>
                                    <button class="event-image-btn" type="button" aria-label="Open event image">
                                        <img
                                            src="<?php echo htmlspecialchars($thumbUrl); ?>"
                                            data-full-src="<?php echo htmlspecialchars($fullUrl); ?>"
                                            alt="Event Image"
                                            loading="lazy"
                                            decoding="async"
                                        >
                                    </button>
                                <?php } ?>
                            </div>
                        </article>
                    <?php endwhile; ?>
                <?php endif; ?>

                <article class="event-card reveal" id="event1">
                    <header class="event-card__header">
                        <h2>Pride Month: Potluck Event</h2>
                        <p class="event-date">16 जून 2024</p>
                        <p class="event-location">कम्पनी गार्डन, गेट 03</p>
                    </header>
                    <p class="event-description">16 जून को PQP का पहला मीटिंग व Potluck Event का आयोजन कंपनी गार्डन में हुआ, जिसमें हम सभी लोगो ने
                        Gender Equality, LGBTQ+ Rights व अन्य Human Rights के मुद्दों पर विचार विमर्श किया !
                        इस मीटिंग इवेंट में LGBTQ+ Individuals के साथ साथ Hetero, Supporters, Allies भी उपस्थित रहें !
                        पहले मीटिंग इवेंट में ही 70 + लोगो का आना इलाहाबाद के उत्साह व उमंग को दर्शाता है ।
                        यहाँ हम सभी को बहुत कुछ जानने व समझने को मिला, साथ ही हम सब ने यहाँ Fun Activities भी किये !
                        ये तो बस शुरुआत है, हम आगे भी ऐसे ढेरों इवेंट, वर्कशॉप आदि करते रहेंगे .. जिसकी सूचना आपको PQP
                        के Social Media Sites के द्वारा प्राप्त होती रहेगी !</p>
                    <div class="event-images" data-event-date="16 जून 2024">
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/5.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/5.jpg'); ?>" alt="Pride Month Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/6.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/6.jpg'); ?>" alt="Pride Month Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/10.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/10.jpg'); ?>" alt="Pride Month Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/24.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/24.jpg'); ?>" alt="Pride Month Event" loading="lazy" decoding="async"></button>
                    </div>
                </article>

                <article class="event-card reveal">
                    <header class="event-card__header">
                        <h2>पहला कपड़ा डोनेशन कार्यक्रम</h2>
                        <p class="event-date">07 August 2024, Wednesday</p>
                        <p class="event-location">गऊ घाट</p>
                    </header>
                    <p class="event-description">कोई भी ना तरसे कपड़ो के लिए इसीलिए दान करो अपने ऐसे कपड़े जो आपके लिए हो पुराने लेकिन किसी और के
                        लिए हो एकदम नए जैसे</p>
                    <div class="event-images" data-event-date="07 August 2024, Wednesday">
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/12.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/12.jpg'); ?>" alt="Clothes Donation Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/4.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/4.jpg'); ?>" alt="Clothes Donation Event" loading="lazy" decoding="async"></button>
                    </div>
                </article>

                <article class="event-card reveal" id="event3">
                    <header class="event-card__header">
                        <h2>SAGA: Connecting The Dots</h2>
                        <p class="event-date">11 अगस्त 2024</p>
                        <p class="event-location">महेवा, नैनी</p>
                    </header>
                    <p class="event-description">हमे हर प्रकार के Sexuality व Gender को स्वीकार करना चाहिए और इनके आधार पर होने वाले भेदभावों व
                        बाकी चीजों को खत्म करना चाहिए ।
                        विभिन्न प्रकार के मुद्दों को एक साथ जोड़ करके चलने से ही समस्याओं का अंत होगा और समाधान मिलेगा ।
                        जाति, धर्म, लिंग, रंग आदि के आधार पर होने वाले भेदभावों का अंत होना चाहिए ।</p>
                    <div class="event-images" data-event-date="11 अगस्त 2024">
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/8.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/8.jpg'); ?>" alt="SAGA Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/13.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/13.jpg'); ?>" alt="SAGA Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/18.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/18.jpg'); ?>" alt="SAGA Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/16.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/16.jpg'); ?>" alt="SAGA Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/2.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/2.jpg'); ?>" alt="SAGA Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/9.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/9.jpg'); ?>" alt="SAGA Event" loading="lazy" decoding="async"></button>
                    </div>
                </article>

                <article class="event-card reveal">
                    <header class="event-card__header">
                        <h2>स्वतन्त्रता दिवस समारोह</h2>
                        <p class="event-date">15 August 2024 Thursday</p>
                        <p class="event-location">कम्पनी गार्डन</p>
                    </header>
                    <p class="event-description">किसी को भी किसी का गुलाम नहीं होना चाहिए । भारत भी आजाद हुआ क्योंकि आजादी सभी का हक है । हम भी इस
                        आजाद देश में आजादी माँगते है तो कौन सा गुनाह कर देते है ?</p>
                    <div class="event-images" data-event-date="15 August 2024 Thursday">
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/11.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/11.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                    </div>
                </article>

                <article class="event-card reveal" id="event5">
                    <header class="event-card__header">
                        <h2>AISSA प्रथम राष्ट्रीय सम्मेलन</h2>
                        <p class="event-date">25 अगस्त</p>
                    </header>
                    <p class="event-description">25 अगस्त रविवार को 4 PM बजे से All India Satrangi Salaam Association का पहला राष्ट्रीय सम्मेलन
                        हुआ, जिसमें संस्था से जुड़े विभिन्न पहलुओं पर विचार विमर्श हुआ ।
                        सतरंगी सलाम हर कीमत पर अपने उद्देश्यों को प्राप्त करने के लिए समर्पित रहेगा ।</p>
                    <div class="event-images" data-event-date="25 अगस्त">
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/1.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/1.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                    </div>
                </article>

                <article class="event-card reveal" id="event6">
                    <header class="event-card__header">
                        <h2>Anti Rape Protest ( बलात्कार विरोधी अभियान )</h2>
                        <p class="event-date">01 September 2024 Sunday</p>
                        <p class="event-location">हनुमान मंदिर, सिविल लाइंस से सुभाष चौराहा होते हुए धरना स्थल, पत्थर गिरजाघर तक</p>
                    </header>
                    <p class="event-description">ना मतलब ना होता है, आखिर इतनी छोटी सी बात किसी को समझ क्यो नहीं आता है ?
                        रेप जैसे अपराधों को भी लिंग, जाति, धर्म, Sexual Orientation के आधार पर नहीं देखना चाहिए ।
                        हर हाल में रेप जैसे घिनौने अपराध बंद होने चाहिए ।</p>
                    <div class="event-images" data-event-date="01 September 2024 Sunday">
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/14.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/14.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/15.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/15.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/22.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/22.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/19.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/19.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/20.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/20.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/17.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/17.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                    </div>
                </article>

                <article class="event-card reveal">
                    <header class="event-card__header">
                        <h2>धारा 377 हटने व समलैंगिकों के आंशिक आजादी का सेलिब्रेशन</h2>
                        <p class="event-date">06 September 2024 Friday</p>
                    </header>
                    <p class="event-description">06 सितंबर 2018 को IPC धारा 377 को आंशिक रूप से हटा दिया गया था, जिससे कि समलैंगिकों को आंशिक रूप
                        से आजादी मिली थी इसीलिए 06 सितंबर LGBTQIA+ Community के लिए एक महत्वपूर्ण दिन है
                        इसी क्रम में हमने Interactive Potluck Session का आयोजन करके 06 सितंबर के आंशिक आजादी का उत्सव मनाया ।</p>
                    <div class="event-images" data-event-date="06 September 2024 Friday">
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/21.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/21.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/23.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/23.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                    </div>
                </article>

                <article class="event-card reveal">
                    <header class="event-card__header">
                        <h2>दूसरा कपड़ा दान कार्यक्रम</h2>
                        <p class="event-date">08 September 2024 Sunday</p>
                    </header>
                    <p class="event-description">हम निरतंर कपड़ा व भोजन दान अभियान का संचालन करते रहते है ।</p>
                    <div class="event-images" data-event-date="08 September 2024 Sunday">
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/25.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/25.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/3.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/3.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/28.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/28.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/27.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/27.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                    </div>
                </article>

                <article class="event-card reveal" id="event9">
                    <header class="event-card__header">
                        <h2>Discover & Accept Yourself</h2>
                        <p class="event-date">21 September 2024 Saturday</p>
                        <p class="event-location">जगत तारन गोल्डन जुबली इंटर कॉलेज</p>
                    </header>
                    <p class="event-description">जब तक हम खुद को नहीं खोजते है और खुद को नहीं स्वीकारते है, तब तक हम किसी और से उमीद कैसे लगा सकते है ?
                        अधिकारियों व सरकार का कुछ अनछुए पहलुओं पर ध्यान आकर्षित करने में यह वर्कशॉप बहुत ही सफल रहा ।
                        इसमे विद्यार्थियों को भी शामिल किया गया था ।</p>
                    <div class="event-images" data-event-date="21 September 2024 Saturday">
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/29.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/29.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/31.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/31.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/32.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/32.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/33.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/33.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/34.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/34.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/38.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/38.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/26.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/26.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                    </div>
                </article>

                <article class="event-card reveal" id="event10">
                    <header class="event-card__header">
                        <h2>फ्री नेत्र जाँच शिविर</h2>
                        <p class="event-date">01 October 2024 Tuesday</p>
                        <p class="event-location">मन्ना का पुरवा, दांडी, नैनी</p>
                    </header>
                    <p class="event-description">मोतियाबिंद एक गम्भीर समस्या है लेकिन लोग जागरूकता के कमी के कारण व आर्थिक तंगी के कारण इस बीमारी के साथ जीने को मजबूर है ।
                        सतरंगी सलाम निरंतर स्वास्थ्य लाभ के दिशा में कार्यरत है ।</p>
                    <div class="event-images" data-event-date="01 October 2024 Tuesday">
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/30.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/30.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/35.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/35.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/7.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/7.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/45.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/45.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/37.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/37.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/39.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/39.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                    </div>
                </article>

                <article class="event-card reveal">
                    <header class="event-card__header">
                        <h2>फ्री नेत्र जाँच शिविर</h2>
                        <p class="event-date">08 October 2024 Tuesday</p>
                        <p class="event-location">सृष्टि गुप्ता का आवास, मेंहदौरी, तेलियरगंज</p>
                    </header>
                    <p class="event-description">लोग सेहतमंद रहे, इसके लिए हम लगातार कार्यरत है ।</p>
                    <div class="event-images" data-event-date="08 October 2024 Tuesday">
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/48.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/48.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/43.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/43.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/46.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/46.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/36.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/36.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/40.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/40.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/49.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/49.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                    </div>
                </article>

                <article class="event-card reveal" id="event12">
                    <header class="event-card__header">
                        <h2>डांडिया नाईट</h2>
                        <p class="event-date">18 October 2024 Friday</p>
                        <p class="event-location">RunWay 70 Restaurant, नया सिविल एयरपोर्ट</p>
                    </header>
                    <p class="event-description">SRA Entertainment के सहयोग से हमने डांडिया नाईट का आयोजन किया ।</p>
                    <div class="event-images" data-event-date="18 October 2024 Friday">
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/47.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/47.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/42.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/42.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/41.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/41.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/44.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/44.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                    </div>
                </article>

                <article class="event-card reveal" id="event13">
                    <header class="event-card__header">
                        <h2>कपड़ा दान कार्यक्रम</h2>
                        <p class="event-date">22 October 2024 Tuesday</p>
                        <p class="event-location">संगम क्षेत्र</p>
                    </header>
                    <p class="event-description">हम लगातार वस्त्र व भोजन दान कार्यक्रम संचालित करते रहते है ।</p>
                    <div class="event-images" data-event-date="22 October 2024 Tuesday">
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/50.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/50.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/52.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/52.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/53.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/53.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/54.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/54.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                    </div>
                </article>

                <article class="event-card reveal">
                    <header class="event-card__header">
                        <h2>Yoga : Blissful Beginnings</h2>
                        <p class="event-date">27 October Sunday</p>
                        <p class="event-location">Company Garden</p>
                    </header>
                    <p class="event-description">आयुष के महत्व को जानना जरूरी है । सभी को योग का महत्व जानना चाहिए और इसे अपनाना चाहिए ।
                        इसके अलावा हम लोग निरतंर विभिन्न तरह के कार्यक्रम करते रहते है ।</p>
                    <div class="event-images" data-event-date="27 October Sunday">
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/51.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/51.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/55.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/55.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                        <button class="event-image-btn" type="button"><img src="<?php echo eventThumbUrl('img/gallery/56.jpg'); ?>" data-full-src="<?php echo eventFullUrl('img/gallery/56.jpg'); ?>" alt="Event" loading="lazy" decoding="async"></button>
                    </div>
                </article>
            </div>
        </section>
    </main>

    <div class="event-lightbox" id="eventLightbox" aria-hidden="true">
        <a class="event-lightbox__download" id="eventLightboxDownload" href="#" download aria-label="Download image">
            <i class="fas fa-download"></i>
        </a>
        <button class="event-lightbox__close" id="eventLightboxClose" type="button" aria-label="Close image">
            <i class="fas fa-times"></i>
        </button>
        <button class="event-lightbox__nav event-lightbox__nav--prev" id="eventLightboxPrev" type="button" aria-label="Previous image">
            <i class="fas fa-chevron-left"></i>
        </button>
        <figure class="event-lightbox__figure">
            <img id="eventLightboxImage" src="" alt="Expanded event image">
            <figcaption id="eventLightboxCaption"></figcaption>
        </figure>
        <button class="event-lightbox__nav event-lightbox__nav--next" id="eventLightboxNext" type="button" aria-label="Next image">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="<?php echo BASE_URL; ?>js/shared-layout.js"></script>
    <script src="<?php echo BASE_URL; ?>js/events.js"></script>
</body>
</html>
