<?php
// This logic automatically detects if you are on localhost (with or without port) or a live server.
$host = $_SERVER['HTTP_HOST'] ?? '';
$isLocal = (stripos($host, 'localhost') === 0) || (stripos($host, '127.0.0.1') === 0);

if ($isLocal) {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    define('BASE_URL', $scheme . '://' . $host . '/satrangisalaam/');
} else {
    define('BASE_URL', 'https://satrangisalaam.in/');
}
?>