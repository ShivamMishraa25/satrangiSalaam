<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">
            <img src="<?php echo defined('BASE_URL') ? BASE_URL : '/'; ?>img/satrangiSalaamLogoHome.png" alt="Satrangi Salaam Logo">
            <h2 id="nav-title">Satrangi Salaam</h2>
        </div>
        <button class="translate-btn" onclick="toggleLanguage()">
            हिंदी
        </button>
        <ul class="nav-menu">
            <li><a href="<?php echo defined('BASE_URL') ? BASE_URL : '/'; ?>about">About</a></li>
            <li><a href="<?php echo defined('BASE_URL') ? BASE_URL : '/'; ?>public/events">Events</a></li>
            <li><a href="<?php echo defined('BASE_URL') ? BASE_URL : '/'; ?>public/announcements">News</a></li>
            <li><a href="<?php echo defined('BASE_URL') ? BASE_URL : '/'; ?>public/joinUs">Join Us</a></li>
            <li><a href="<?php echo defined('BASE_URL') ? BASE_URL : '/'; ?>public/user_login">Log In</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="<?php echo defined('BASE_URL') ? BASE_URL : '/'; ?>donate" class="donate-nav">Donate</a></li>
        </ul>
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</nav>