<?php
include '../config.php';

$lang = $_GET['lang'] ?? ($_COOKIE['lang'] ?? 'en');
$lang = ($lang === 'hi' || $lang === 'hindi') ? 'hi' : 'en';

$rootDir = dirname(__DIR__);
$staticDir = $rootDir . '/img/gallery';
$uploadDir = $rootDir . '/uploads/gallery';

function buildGalleryItems($dirPath, $publicPrefix, $source)
{
    $items = [];

    if (!is_dir($dirPath)) {
        return $items;
    }

    $files = scandir($dirPath);
    if ($files === false) {
        return $items;
    }

    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }

        $fullPath = $dirPath . '/' . $file;
        if (!is_file($fullPath)) {
            continue;
        }

        if (!preg_match('/\.(jpg|jpeg|png|webp|gif)$/i', $file)) {
            continue;
        }

        $filenameWithoutExt = pathinfo($file, PATHINFO_FILENAME);
        $cleanName = trim(preg_replace('/[_-]+/', ' ', $filenameWithoutExt));
        if ($cleanName === '') {
            $cleanName = 'Photo';
        }

        $fileTimestamp = filemtime($fullPath);
        if ($fileTimestamp === false) {
            $fileTimestamp = time();
        }

        $fullSrc = BASE_URL . ltrim($publicPrefix . '/' . rawurlencode($file), '/');
        $thumbSrc = BASE_URL . 'public/gallery_image.php?source=' . rawurlencode($source) . '&file=' . rawurlencode($file) . '&w=720&q=72';

        $items[] = [
            'src' => $fullSrc,
            'thumbSrc' => $thumbSrc,
            'title' => ucwords($cleanName),
            'source' => $source,
            'uploadedAt' => date('F d, Y', $fileTimestamp),
            'sortKey' => $fileTimestamp,
        ];
    }

    usort($items, function ($a, $b) {
        return $b['sortKey'] <=> $a['sortKey'];
    });

    return $items;
}

$uploadedItems = buildGalleryItems($uploadDir, 'uploads/gallery', 'recent');
$archiveItems = buildGalleryItems($staticDir, 'img/gallery', 'archive');

$allItems = [];
$seen = [];

foreach (array_merge($uploadedItems, $archiveItems) as $item) {
    if (isset($seen[$item['src']])) {
        continue;
    }
    $seen[$item['src']] = true;
    $allItems[] = $item;
}

$totalCount = count($allItems);
$recentCount = count($uploadedItems);
$archiveCount = count($archiveItems);
?>
<!DOCTYPE html>
<html lang="<?php echo $lang === 'hi' ? 'hi' : 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta property="og:site_name" content="Satrangi Salaam">
    <meta property="og:title" content="Gallery | All India Satrangi Salaam Association">
    <meta property="og:description" content="Explore activity moments, outreach drives, and community impact in the AISSA gallery.">
    <meta property="og:type" content="website">

    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo BASE_URL; ?>apple-touch-icon.png">

    <title>Gallery | AISSA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/gallery.css">

    <script>
        window.__INITIAL_LANG__ = <?php echo json_encode($lang); ?>;
    </script>
</head>
<body class="<?php echo $lang === 'hi' ? 'hindi' : ''; ?>">
    <?php include '../includes/nav.php'; ?>

    <main class="gallery-page">
        <section class="gallery-hero">
            <div class="gallery-hero__bg" aria-hidden="true"></div>
            <div class="gallery-container gallery-hero__inner">
                <div class="gallery-hero__content">
                    <p class="gallery-hero__badge" data-i18n="badge">AISSA Moments</p>
                    <h1 class="gallery-hero__title" data-i18n="title">Gallery</h1>
                    <p class="gallery-hero__lead" data-i18n="lead">A living archive of community actions, awareness drives, collaborations, and impact stories across India.</p>

                    <div class="gallery-stats" aria-label="Gallery statistics">
                        <div class="gallery-stat">
                            <span class="gallery-stat__value"><?php echo $totalCount; ?></span>
                            <span class="gallery-stat__label" data-i18n="statTotal">Total Photos</span>
                        </div>
                        <div class="gallery-stat">
                            <span class="gallery-stat__value"><?php echo $recentCount; ?></span>
                            <span class="gallery-stat__label" data-i18n="statRecent">Recent Uploads</span>
                        </div>
                        <div class="gallery-stat">
                            <span class="gallery-stat__value"><?php echo $archiveCount; ?></span>
                            <span class="gallery-stat__label" data-i18n="statArchive">Archive Photos</span>
                        </div>
                    </div>
                </div>

                <aside class="gallery-panel" aria-label="Filter panel">
                    <h2 class="gallery-panel__title" data-i18n="panelTitle">Browse By</h2>
                    <div class="gallery-filters" role="tablist" aria-label="Gallery filters">
                        <button class="gallery-filter is-active" type="button" data-filter="all" data-i18n="filterAll">All</button>
                        <button class="gallery-filter" type="button" data-filter="recent" data-i18n="filterRecent">Recent</button>
                        <button class="gallery-filter" type="button" data-filter="archive" data-i18n="filterArchive">Archive</button>
                    </div>
                    <p class="gallery-panel__hint" data-i18n="panelHint">Click any photo to view in full screen. Use keyboard arrows for navigation.</p>
                </aside>
            </div>
        </section>

        <section class="gallery-section">
            <div class="gallery-container">
                <?php if ($totalCount > 0): ?>
                    <div class="gallery-grid" id="galleryGrid">
                        <?php foreach ($allItems as $index => $item): ?>
                            <article class="gallery-card reveal" data-source="<?php echo htmlspecialchars($item['source']); ?>">
                                <button
                                    class="gallery-card__media"
                                    type="button"
                                    data-lightbox-index="<?php echo $index; ?>"
                                    data-full-src="<?php echo htmlspecialchars($item['src']); ?>"
                                    data-uploaded-at="<?php echo htmlspecialchars($item['uploadedAt']); ?>"
                                    aria-label="Open image <?php echo htmlspecialchars($item['title']); ?>"
                                >
                                    <img
                                        src="<?php echo htmlspecialchars($item['thumbSrc']); ?>"
                                        alt="<?php echo htmlspecialchars($item['title']); ?>"
                                        loading="lazy"
                                        decoding="async"
                                    >
                                    <span class="gallery-card__chip" data-i18n="<?php echo $item['source'] === 'recent' ? 'chipRecent' : 'chipArchive'; ?>">
                                        <?php echo $item['source'] === 'recent' ? 'Recent Upload' : 'Archive'; ?>
                                    </span>
                                </button>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="gallery-empty">
                        <h2 data-i18n="emptyTitle">No Photos Yet</h2>
                        <p data-i18n="emptyText">Gallery images will appear here as soon as new uploads are available.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <div class="gallery-lightbox" id="galleryLightbox" aria-hidden="true">
        <a class="gallery-lightbox__download" id="lightboxDownload" href="#" download aria-label="Download image">
            <i class="fas fa-download"></i>
        </a>
        <button class="gallery-lightbox__close" id="lightboxClose" type="button" aria-label="Close lightbox">
            <i class="fas fa-times"></i>
        </button>
        <button class="gallery-lightbox__nav gallery-lightbox__nav--prev" id="lightboxPrev" type="button" aria-label="Previous image">
            <i class="fas fa-chevron-left"></i>
        </button>
        <figure class="gallery-lightbox__figure">
            <img id="lightboxImage" src="" alt="Expanded gallery photo">
            <figcaption id="lightboxCaption"></figcaption>
        </figure>
        <button class="gallery-lightbox__nav gallery-lightbox__nav--next" id="lightboxNext" type="button" aria-label="Next image">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="<?php echo BASE_URL; ?>js/shared-layout.js"></script>
    <script src="<?php echo BASE_URL; ?>js/gallery.js"></script>
</body>
</html>
