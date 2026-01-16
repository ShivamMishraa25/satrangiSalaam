<?php
// Central URL config.
// BASE_URL is the full absolute URL (used for assets, canonical URLs, etc.).
// BASE_PATH is the web-root relative path (used for internal links).

$host = $_SERVER['HTTP_HOST'] ?? '';
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

// Auto-detect if app runs in a subfolder like /satrangisalaam on localhost.
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$baseDir = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');

// If the current script is under /satrangisalaam, set that as the base path.
// Otherwise fall back to root.
$basePath = '/';
if (preg_match('#^/satrangisalaam(/|$)#i', $baseDir . '/')) {
    $basePath = '/satrangisalaam/';
}

if (!defined('BASE_PATH')) {
    define('BASE_PATH', $basePath);
}

if (!defined('BASE_URL')) {
    // Prefer production domain when not on localhost.
    $isLocal = (stripos($host, 'localhost') === 0) || (stripos($host, '127.0.0.1') === 0);
    if ($isLocal) {
        define('BASE_URL', $scheme . '://' . $host . BASE_PATH);
    } else {
        define('BASE_URL', 'https://satrangisalaam.in/');
    }
}

?>