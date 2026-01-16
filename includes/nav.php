<?php
// Ensure BASE_PATH / BASE_URL are available regardless of include location.
if (!defined('BASE_URL') || !defined('BASE_PATH')) {
    $configPath = __DIR__ . '/../config.php';
    if (file_exists($configPath)) {
        include_once $configPath;
    }
}

$currentLang = $_GET['lang'] ?? ($_COOKIE['lang'] ?? 'en');
$currentLang = ($currentLang === 'hi' || $currentLang === 'hindi') ? 'hi' : 'en';
$langQuery = '?lang=' . $currentLang;

$basePath = defined('BASE_PATH') ? BASE_PATH : '/';
$baseUrl = defined('BASE_URL') ? BASE_URL : $basePath;
?>

<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">
            <a href="<?php echo $basePath; ?><?php echo $langQuery; ?>">
                <img src="<?php echo $baseUrl; ?>img/satrangiSalaamLogoHome.png" alt="Satrangi Salaam Logo">
            </a>
            <h2 id="nav-title">Satrangi Salaam</h2>
        </div>
        <button class="translate-btn" onclick="toggleLanguage()">
            हिंदी
        </button>
        <ul class="nav-menu">
            <li><a href="<?php echo $basePath; ?>about<?php echo $langQuery; ?>">About</a></li>
            <li><a href="<?php echo $basePath; ?>public/events<?php echo $langQuery; ?>">Events</a></li>
            <li><a href="<?php echo $basePath; ?>public/announcements<?php echo $langQuery; ?>">News</a></li>
            <li><a href="<?php echo $basePath; ?>public/joinUs<?php echo $langQuery; ?>">Join Us</a></li>
            <li><a href="<?php echo $basePath; ?>public/user_login<?php echo $langQuery; ?>">Log In</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="<?php echo $basePath; ?>donate<?php echo $langQuery; ?>" class="donate-nav">Donate</a></li>
        </ul>
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</nav>

<script>
    // Ensure language preference persists across navigation.
    (function() {
        try {
            var lang = <?php echo json_encode($currentLang); ?>;
            document.cookie = 'lang=' + encodeURIComponent(lang) + '; path=/; SameSite=Lax';
        } catch (e) {}
    })();
</script>