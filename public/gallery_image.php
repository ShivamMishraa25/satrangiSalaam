<?php
include '../config.php';

$rootDir = dirname(__DIR__);
$source = $_GET['source'] ?? '';
$file = $_GET['file'] ?? '';
$targetWidth = isset($_GET['w']) ? (int)$_GET['w'] : 720;
$quality = isset($_GET['q']) ? (int)$_GET['q'] : 72;

$targetWidth = max(240, min(900, $targetWidth));
$quality = max(40, min(85, $quality));

if ($file === '' || basename($file) !== $file) {
    http_response_code(400);
    exit('Invalid image request');
}

$sourceMap = [
    'recent' => $rootDir . '/uploads/gallery',
    'archive' => $rootDir . '/img/gallery',
];

if (!isset($sourceMap[$source])) {
    http_response_code(400);
    exit('Invalid source');
}

$baseDir = $sourceMap[$source];
$fullPath = $baseDir . '/' . $file;

if (!is_file($fullPath)) {
    http_response_code(404);
    exit('Image not found');
}

$realBase = realpath($baseDir);
$realFile = realpath($fullPath);
if ($realBase === false || $realFile === false || strpos($realFile, $realBase) !== 0) {
    http_response_code(403);
    exit('Access denied');
}

$imageInfo = @getimagesize($realFile);
if ($imageInfo === false || !isset($imageInfo['mime'])) {
    http_response_code(415);
    exit('Unsupported image');
}

$mime = $imageInfo['mime'];
$width = (int)($imageInfo[0] ?? 0);
$height = (int)($imageInfo[1] ?? 0);
$fileSize = (int)filesize($realFile);

$serveOriginal = ($width > 0 && $width <= $targetWidth) || $fileSize <= 300000;

$lastModified = filemtime($realFile);
if ($lastModified === false) {
    $lastModified = time();
}
header('Cache-Control: public, max-age=604800');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastModified) . ' GMT');

if ($serveOriginal || !function_exists('imagecreatetruecolor')) {
    header('Content-Type: ' . $mime);
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
$newHeight = max(1, (int)round(($height / max(1, $width)) * $newWidth));

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

if ($mime === 'image/jpeg') {
    header('Content-Type: image/jpeg');
    imagejpeg($resizedImage, null, $quality);
} elseif ($mime === 'image/webp' && function_exists('imagewebp')) {
    header('Content-Type: image/webp');
    imagewebp($resizedImage, null, $quality);
} elseif ($mime === 'image/png') {
    header('Content-Type: image/png');
    imagepng($resizedImage, null, 7);
} elseif ($mime === 'image/gif') {
    header('Content-Type: image/gif');
    imagegif($resizedImage);
} else {
    header('Content-Type: image/jpeg');
    imagejpeg($resizedImage, null, $quality);
}

imagedestroy($sourceImage);
imagedestroy($resizedImage);
