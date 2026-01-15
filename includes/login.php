<?php

session_start();

require 'db.php'; // Include the correct db connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepared statement to fetch the moderator's data
    $stmt = $conn->prepare("SELECT * FROM moderators WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $moderator = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $moderator['password'])) {
            // Store session data
            $_SESSION['moderator_logged_in'] = true;
            $_SESSION['moderator_id'] = $moderator['id'];

            // Redirect to moderator.php
            header("Location: moderator");
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "Invalid username!";
    }
}
?>

<!DOCTYPE html>
<html lang="hi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Moderator Panel Login</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Rozha+One&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../css/pages.css">
        <style>
        .container {
                background-color: white;
                border-radius: 7px;
                color: black;
                display: inline-block;
                padding: 5px;
            }
        </style>
    </head>
    <body>
        <header>
            <!-- Logo or homepage anchor -->
            <a href="../" class="logo">
            <img src="../img/satrangiSalaamLogo.png"
                alt="satrangi Salaam Logo">
            </a>
            <!-- Burger Icon -->
            <div class="menu-icon" id="menu-icon"></div>
            <!-- Slide-in Menu -->
            <nav id="nav-menu">
            <ul>
            <li><a href="../about">हमारे बारे में</a></li>
                <li><a href="../public/announcements">सूचनाएं (अपडेट्स)</a></li>
                <li><a href="../public/gallery">गैलरी</a></li>   
                <li><a href="../public/events">हमारे कार्यक्रम</a></li>
                <li><a href="../public/joinUs">हम से जुड़े (सदस्यता)</a></li>
                <li><a href="../donate">सहयोग करें (donate)</a></li>
                <li><a href="../public/careers">करियर (पेशेवर) विकास के अवसर</a></li>
                <li><a href="../public/officers">हमारे पदाधिकारी</a></li>
                <li><a href="../public/writeArticle">आर्टिकल लिखें</a></li>
                <li><a href="../public/inTheNews">खबरों में हम</a></li>               
                <li><a href="../public/collaboratorsAndSponsors">हमारे सहयोगी</a></li>
                <li><a href="../public/impact">बदलाव (असर)</a></li>               
                <li><a href="../public/affiliates">हमारे स्वायत्त व अधीनस्थ संस्थाएं</a></li>
                <li><a href="../public/reach">प्रदेश व विभिन्न स्तर पर हम</a></li>                                
                <li><a href="../public/other">अन्य</a></li>
            </ul>
            <div class="social-icons">
                <a href="https://youtube.com/@satrangisalaam/"><img src="https://cdn1.iconfinder.com/data/icons/logotypes/32/youtube-512.png"></a>
                <a href="https://www.instagram.com/satrangisalaam/"><img src="https://cdn2.iconfinder.com/data/icons/social-media-2285/512/1_Instagram_colored_svg_1-1024.png" alt="Instagram"></a>
                <a href="https://www.facebook.com/SatrangiSalaam/"><img src="https://cdn2.iconfinder.com/data/icons/social-media-2285/512/1_Facebook_colored_svg_copy-1024.png" alt="Facebook"></a>
                <a href="mailto:satrangisalamss@gmail.com"><img src="https://cdn4.iconfinder.com/data/icons/logos-brands-in-colors/48/google-gmail-512.png" alt="Email"></a>
                <a href="https://x.com/SatrangiSalamSS"><img src="https://cdn2.iconfinder.com/data/icons/threads-by-instagram/24/x-logo-twitter-new-brand-contained-512.png" alt="Twitter"></a>
        </header>
        <!-- Bird Image for animation -->
        <img id="bird" src="../img/bird.png" alt="Flying Bird">
        <img id="bird2" src="../img/bird2.png" alt="flying bird">
          <section class="content">
                <form class="container" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br/>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br/>
    <button type="submit">Login</button>
</form>
          </section>
        <footer>
            <ul>
                <li><a href="https://youtube.com/@satrangisalaam/"><img src="https://cdn1.iconfinder.com/data/icons/logotypes/32/youtube-512.png"></a></li>
                <li><a href="https://www.instagram.com/satrangisalaam/"><img src="https://cdn2.iconfinder.com/data/icons/social-media-2285/512/1_Instagram_colored_svg_1-1024.png" alt="Instagram"></a></li>
                <li><a href="https://www.facebook.com/SatrangiSalaam/"><img src="https://cdn2.iconfinder.com/data/icons/social-media-2285/512/1_Facebook_colored_svg_copy-1024.png" alt="Facebook"></a></li>
                <li><a href="https://satrangisalaam.wordpress.com/"><img src="https://cdn4.iconfinder.com/data/icons/iconsimple-logotypes/512/wordpress-512.png" alt="WordPress"></a></li>
                <li><a href="https://chat.whatsapp.com/Jifh0MGROxAJQD4bueRFN0"><img src="https://cdn3.iconfinder.com/data/icons/2018-social-media-logotypes/1000/2018_social_media_popular_app_logo-whatsapp-512.png"></a></li>
                <li><a href="https://t.me/SatrangiSalam"><img src="https://cdn4.iconfinder.com/data/icons/logos-and-brands/512/335_Telegram_logo-512.png"></a></li>
                <li><a href="https://x.com/SatrangiSalamSS"><img src="https://cdn2.iconfinder.com/data/icons/social-media-2285/512/1_Twitter_colored_svg-256.png" alt="Twitter"></a></li>
            </ul>
            <div class="darkmode">
              <span>switch to dark/light mode</span>
            <button id="toggle-button" class="toggle-btn"></button>
            </div>
        </footer>
        <script type="text/javascript" src="../js/pages.js"></script>
    </body>
</html>
