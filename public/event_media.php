<?php
include '../config.php';

$rootDir = dirname(__DIR__);
$path = $_GET['path'] ?? '';
$targetWidth = isset($_GET['w']) ? (int) $_GET['w'] : 780;
$quality = isset($_GET['q']) ? (int) $_GET['q'] : 70;

$targetWidth = max(280, min(1200, $targetWidth));
$quality = max(40, min(85, $quality));

$path = str_replace('\\', '/', trim((string) $path));
$path = preg_replace('#^\.{1,2}/+#', '', $path);
$path = ltrim($path, '/');

if ($path === '') {
    http_response_code(400);
    exit('Invalid media request');
}

$allowedPrefixes = [
    'img/gallery/',
    'uploads/',
    'uploads/events/',
    'uploads/gallery/',
];

$isAllowed = false;
foreach ($allowedPrefixes as $prefix) {
    if (strpos($path, $prefix) === 0) {
        $isAllowed = true;
        break;
    }
}

if (!$isAllowed) {
    http_response_code(403);
    exit('Path not allowed');
}

$fullPath = $rootDir . '/' . $path;
if (!is_file($fullPath)) {
    http_response_code(404);
    exit('Image not found');
}

$realRoot = realpath($rootDir);
$realFile = realpath($fullPath);
if ($realRoot === false || $realFile === false || strpos($realFile, $realRoot) !== 0) {
    http_response_code(403);
    exit('Access denied');
}

$info = @getimagesize($realFile);
if ($info === false || !isset($info['mime'])) {
    http_response_code(415);
    exit('Unsupported image');
}

$mime = $info['mime'];
$width = (int) ($info[0] ?? 0);
$height = (int) ($info[1] ?? 0);
$fileSize = (int) filesize($realFile);

$cacheDir = $rootDir . '/uploads/cache/events';
if (!is_dir($cacheDir)) {
    @mkdir($cacheDir, 0775, true);
}

$fileMTime = filemtime($realFile);
if ($fileMTime === false) {
    $fileMTime = time();
}

$cacheKey = sha1($realFile . '|' . $targetWidth . '|' . $quality . '|' . $fileMTime);
$cachePath = $cacheDir . '/' . $cacheKey . '.jpg';

$serveOriginal = ($width > 0 && $width <= $targetWidth) || $fileSize <= 320000;

$lastModified = $fileMTime;
header('Cache-Control: public, max-age=604800');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastModified) . ' GMT');

if (!$serveOriginal && is_file($cachePath)) {
    $cachedMTime = filemtime($cachePath);
    if ($cachedMTime !== false) {
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $cachedMTime) . ' GMT');
    }
    header('Content-Type: image/jpeg');
    header('Content-Length: ' . (string) filesize($cachePath));
    readfile($cachePath);
    exit;
}

if ($serveOriginal || !function_exists('imagecreatetruecolor')) {
    header('Content-Type: ' . $mime);
    header('Content-Length: ' . (string) filesize($realFile));
    readfile($realFile);
    exit;
}

switch ($mime) {
    case 'image/jpeg':
        $sourceImage = @imagecreatefromjpeg($realFile);
        break;
    case 'image/png':
        $sourceImage = @imagecreatefrompng($realFile);
        break;
    case 'image/webp':
        $sourceImage = function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($realFile) : false;
        break;
    case 'image/gif':
        $sourceImage = @imagecreatefromgif($realFile);
        break;
    default:
        $sourceImage = false;
        break;
}

if ($sourceImage === false) {
    header('Content-Type: ' . $mime);
    readfile($realFile);
    exit;
}

$newWidth = $targetWidth;
$newHeight = max(1, (int) round(($height / max(1, $width)) * $newWidth));
$resizedImage = imagecreatetruecolor($newWidth, $newHeight);

if ($resizedImage === false) {
    imagedestroy($sourceImage);
    header('Content-Type: ' . $mime);
    readfile($realFile);
    exit;
}

if ($mime === 'image/png' || $mime === 'image/gif') {
    imagealphablending($resizedImage, false);
    imagesavealpha($resizedImage, true);
    $transparent = imagecolorallocatealpha($resizedImage, 0, 0, 0, 127);
    imagefilledrectangle($resizedImage, 0, 0, $newWidth, $newHeight, $transparent);
}

imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

// Cache all resized thumbnails as JPEG for faster repeat loads.
@imagejpeg($resizedImage, $cachePath, $quality);

if (is_file($cachePath)) {
    header('Content-Type: image/jpeg');
    header('Content-Length: ' . (string) filesize($cachePath));
    readfile($cachePath);
} else {
    header('Content-Type: image/jpeg');
    imagejpeg($resizedImage, null, $quality);
}

imagedestroy($sourceImage);
imagedestroy($resizedImage);
