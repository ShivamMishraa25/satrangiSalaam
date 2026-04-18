<?php
include '../config.php';
include '../includes/db.php';

$lang = $_GET['lang'] ?? ($_COOKIE['lang'] ?? 'en');
$lang = ($lang === 'hi' || $lang === 'hindi') ? 'hi' : 'en';

$sql = "SELECT * FROM announcements ORDER BY id DESC";
$result = $conn->query($sql);

$hindiMonths = ['जनवरी', 'फ़रवरी', 'मार्च', 'अप्रैल', 'मई', 'जून', 'जुलाई', 'अगस्त', 'सितंबर', 'अक्टूबर', 'नवंबर', 'दिसंबर'];

function normalizeAnnouncementMediaPath($path)
{
    $clean = trim((string) $path);
    $clean = preg_replace('#^\.{1,2}/+#', '', $clean);
    $clean = ltrim(str_replace('\\', '/', $clean), '/');
    return $clean;
}

function announcementThumbUrl($path)
{
    if (preg_match('~^https?://~i', $path)) {
        return $path;
    }

    $clean = normalizeAnnouncementMediaPath($path);
    if ($clean === '') {
        return '';
    }

    return BASE_URL . 'public/event_media.php?path=' . rawurlencode($clean) . '&w=560&q=62';
}

function announcementFullUrl($path)
{
    if (preg_match('~^https?://~i', $path)) {
        return $path;
    }

    $clean = normalizeAnnouncementMediaPath($path);
    if ($clean === '') {
        return '';
    }

    return BASE_URL . $clean;
}

function formatAnnouncementDateLocalized($dateValue, $lang, $hindiMonths)
{
    $date = DateTime::createFromFormat('Y-m-d H:i:s', (string) $dateValue);
    if (!$date) {
        return (string) $dateValue;
    }

    if ($lang === 'hi') {
        $monthIndex = ((int) $date->format('n')) - 1;
        $month = $hindiMonths[$monthIndex] ?? $date->format('F');
        return $date->format('d') . ' ' . $month . ' ' . $date->format('Y');
    }

    return $date->format('F d, Y');
}

$allAnnouncements = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $titleEn = trim((string) ($row['title'] ?? ''));
        $titleHi = trim((string) ($row['title_hi'] ?? ''));
        $contentEn = trim((string) ($row['content'] ?? ''));
        $contentHi = trim((string) ($row['content_hi'] ?? ''));

        $title = $lang === 'hi' && $titleHi !== '' ? $titleHi : ($titleEn !== '' ? $titleEn : 'Announcement');
        $content = $lang === 'hi' && $contentHi !== '' ? $contentHi : $contentEn;

        $allAnnouncements[] = [
            'title' => $title,
            'date' => formatAnnouncementDateLocalized($row['created_at'] ?? '', $lang, $hindiMonths),
            'content' => $content,
            'images' => array_filter(array_map('trim', explode(',', (string) ($row['images'] ?? '')))),
        ];
    }
}

$legacyNotices = [
    [
        'title' => '11, September, Wednesday.',
        'date' => '11 September',
        'content' => 'अखिलेश तिवारी व नितिन शुक्ला का त्यागपत्र मंजूर कर लिया गया है। ये पुनः संस्था में आ सकते है लेकिन पद ग्रहण ये संस्था में 6 महीने रहने के बाद ही कर पाएंगे।\n\nविजयलक्ष्मी जी का त्यागपत्र मंजूर कर लिया गया है। इन्हें संस्था से तीन साल के लिए प्रतिबंधित किया गया है।',
        'images' => [],
    ],
    [
        'title' => '12, September, Thursday',
        'date' => '12 September',
        'content' => 'अब केवल 10 रुपया वाले सदस्य या फिर 30 रुपये वाले वरिष्ठ सदस्यों की नियुक्ति ही डायरेक्ट किया जाएगा, बाकी किसी भी नियुक्ति के लिए मुख्य ट्रस्टी, Board Of Trustees व विशेष सभा की अनुमति आवश्यक होगी...',
        'images' => [],
    ],
    [
        'title' => '21, September, Saturday',
        'date' => '21 September',
        'content' => '*जो लोग बिना सूचना के लगातार किसी भी इवेंट में नहीं आ रहे है, जब तक वे मुझे व दीप को (दोनों को) संतोषजनक जवाब नहीं देते है; तब तक के लिए उनके सभी अधिकार, पद मान्यता सब कुछ सस्पेंड रहेगा; तब तक वे संस्था के मात्र साधारण सदस्य होंगे। जवाब देने के लिए मात्र 7 दिनों का समय है।*',
        'images' => [],
    ],
    [
        'title' => '24, September, Tuesday',
        'date' => '24 September',
        'content' => '*आकाश (सक्सेना) ने अपना त्यागपत्र दिया था* और वो फिर 9 दिनों के बाद सभी ग्रुप से हट चुके हैं... अतः *उनका त्यागपत्र मंजूर किया था।*',
        'images' => [],
    ],
    [
        'title' => '8, October, Tuesday',
        'date' => '8 October',
        'content' => 'NGO के किसी भी सदस्य या पदाधिकारी को किसी भी प्रकार की सैलरी देने का वादा नहीं किया गया है :-\n\nNGO का मतलब होता है Non-Government Organization और AISSA का रजिस्ट्रेशन अभी इसी साल अगस्त में हुआ है।\n\nहम बस आपके काम के लिए उचित मान-सम्मान व क्रेडिट दे सकते हैं, जो कि हम देते ही हैं।\n\nआप को अपने विभिन्न टैलेंट को दिखाने का बेहतरीन प्लेटफार्म उपलब्ध करवा सकते हैं, किंतु अभी हम किसी को भी उनके सेवाओं के लिए भुगतान नहीं कर सकते हैं।\n\nहम कब भुगतान करने की स्थिति में होंगे, हमें यह भी नहीं पता है।\n\nहम में से किसी ने भी किसी को यह नहीं कहा है कि आपको सैलरी दी जाएगी और आशा है कि इंटरनेट के माध्यम से आप सभी ने जरूर पता किया होगा कि NGO का मतलब क्या होता है।\n\nऔर रही बात मेरी तो मेरा खुद का पैसा खर्च होता है - कपड़े, दान कलेक्शन, ऑय कैम्प और हर जगह का किराया, छोटे-छोटे पेमेंट।\n\nबाकी एक्टिव सदस्य भी खुद का ही पैसा खर्च करते हैं।\n\nइसीलिए हम लोग सब कुछ पहले ही साफ-साफ बता देते हैं। पारदर्शी व्यवस्था एकदम।\n\nअगर हमें गवर्नमेंट प्रोजेक्ट मिलता है या फिर कोई बड़ा स्पॉन्सरशिप मिलता है, तो फिर भुगतान किया जा सकेगा लेकिन केवल उन्ही को, जो आज बिना किसी भुगतान के NGO के लिए लगे हुए हैं।\n\nकृपया सैलरी की बात करके शर्मिंदा ना करें।',
        'images' => [],
    ],
];

$allAnnouncements = array_merge($allAnnouncements, $legacyNotices);
?>
<!DOCTYPE html>
<html lang="<?php echo $lang === 'hi' ? 'hi' : 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta property="og:site_name" content="Satrangi Salaam">
    <meta property="og:title" content="Announcements | All India Satrangi Salaam Association">
    <meta property="og:description" content="Latest AISSA announcements, notices, and official updates.">
    <meta property="og:type" content="website">

    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo BASE_URL; ?>apple-touch-icon.png">

    <title>Announcements | AISSA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/announcements.css">

    <script>
        window.__INITIAL_LANG__ = <?php echo json_encode($lang); ?>;
    </script>
</head>
<body class="<?php echo $lang === 'hi' ? 'hindi' : ''; ?>">
    <?php include '../includes/nav.php'; ?>

    <main class="bulletin-page">
        <section class="bulletin-hero">
            <div class="bulletin-shell">
                <div class="bulletin-hero__panel">
                    <p class="bulletin-hero__eyebrow" data-i18n="eyebrow">Official Bulletin</p>
                    <h1 data-i18n="title">Announcements</h1>
                    <p data-i18n="lead">Decisions, notices, and communication updates presented in one clear stream.</p>
                </div>
            </div>
        </section>

        <section class="bulletin-stream-wrap">
            <div class="bulletin-shell bulletin-stream">
                <?php if (!empty($allAnnouncements)): ?>
                    <?php foreach ($allAnnouncements as $index => $entry): ?>
                        <?php
                        $title = htmlspecialchars($entry['title'] ?? 'Announcement');
                        $date = htmlspecialchars($entry['date'] ?? '');
                        $content = (string) ($entry['content'] ?? '');
                        $images = $entry['images'] ?? [];

                        $chipLabel = '';
                        if ($index === 0) {
                            $chipLabel = 'Latest';
                        } elseif ($index > 0 && $index <= 4) {
                            $chipLabel = 'Recent';
                        }
                        ?>
                        <article class="bulletin-card reveal">
                            <header class="bulletin-card__header">
                                <p class="bulletin-card__date"><?php echo $date; ?></p>
                                <?php if ($chipLabel !== ''): ?>
                                    <span class="bulletin-chip"><?php echo $chipLabel; ?></span>
                                <?php endif; ?>
                            </header>
                            <h2><?php echo $title; ?></h2>

                            <?php if ($content !== ''): ?>
                                <p class="bulletin-card__content"><?php echo nl2br(htmlspecialchars($content)); ?></p>
                            <?php endif; ?>

                            <?php if (!empty($images)): ?>
                                <div class="bulletin-gallery" data-announcement-date="<?php echo $date; ?>">
                                    <?php foreach ($images as $image): ?>
                                        <?php
                                        $image = trim((string) $image);
                                        if ($image === '') {
                                            continue;
                                        }
                                        $thumb = announcementThumbUrl($image);
                                        $full = announcementFullUrl($image);
                                        if ($thumb === '' || $full === '') {
                                            continue;
                                        }
                                        ?>
                                        <button class="bulletin-photo" type="button" aria-label="Open announcement image">
                                            <img src="<?php echo htmlspecialchars($thumb); ?>" data-full-src="<?php echo htmlspecialchars($full); ?>" alt="Announcement Image" loading="lazy" decoding="async">
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="bulletin-empty is-visible" data-i18n="noAnnouncements">No announcements available.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <div class="bulletin-lightbox" id="bulletinLightbox" aria-hidden="true">
        <div class="bulletin-lightbox__top">
            <span id="bulletinLightboxMeta"></span>
            <div class="bulletin-lightbox__actions">
                <a id="bulletinDownload" href="#" download aria-label="Download image"><i class="fas fa-download"></i></a>
                <button id="bulletinClose" type="button" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <button id="bulletinPrev" class="bulletin-lightbox__nav bulletin-lightbox__nav--prev" type="button" aria-label="Previous image"><i class="fas fa-chevron-left"></i></button>
        <figure class="bulletin-lightbox__figure">
            <img id="bulletinImage" src="" alt="Expanded announcement image">
            <figcaption id="bulletinCaption"></figcaption>
        </figure>
        <button id="bulletinNext" class="bulletin-lightbox__nav bulletin-lightbox__nav--next" type="button" aria-label="Next image"><i class="fas fa-chevron-right"></i></button>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="<?php echo BASE_URL; ?>js/shared-layout.js"></script>
    <script src="<?php echo BASE_URL; ?>js/announcements.js"></script>
</body>
</html>
<?php $conn->close(); ?>
