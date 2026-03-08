<?php
session_start();
require '../includes/db.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user exists in the users table
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            header("Location: user_dashboard");
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "Invalid email!";
    }
}
?>
<!DOCTYPE html>
<html lang="hi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta property="og:site_name" content="Satrangi Salaam">
<meta property="og:title" content="Member Login">
<meta property="og:description" content="Login to download your Satrangi Salaam Membership ID Card.">

<meta property="og:image" itemprop="image" content="https://i.ibb.co/nz0bLWN/Satrangi-Salaam-300.jpg">
<meta property="og:type" content="website" />
<meta property="og:image:type" content="image/jpg">

<meta property="og:image:width" content="300">
<meta property="og:image:height" content="300">

<link rel="icon" type="image/x-icon" href="favicon.ico">
<link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">

<!--  <meta property="og:url" content="http://satrangisalaam.in">  -->
        <title>Member Login</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Rozha+One&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../css/pages.css">
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1562008429602585"
     crossorigin="anonymous"></script>
     
        <style>
            button {
                background-color: green;
                color:white;
            }

            .login-actions {
                display: inline-flex;
                align-items: center;
                gap: 12px;
                margin-top: 6px;
            }

            .forgot-link {
                font-size: 14px;
                text-decoration: underline;
            }

            .pw-field {
                position: relative;
                display: inline-block;
            }

            .pw-field input {
                padding-right: 42px;
            }

            .pw-toggle {
                position: absolute;
                top: 50%;
                right: 8px;
                transform: translateY(-50%);
                width: 30px;
                height: 30px;
                border: none;
                background: transparent;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0;
                color: rgba(0,0,0,0.75);
            }

            .pw-toggle:hover {
                color: rgba(0,0,0,1);
            }

            .pw-toggle svg {
                width: 18px;
                height: 18px;
                display: block;
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
                <li><a href="announcements">सूचनाएं (अपडेट्स)</a></li>
                <li><a href="gallery">गैलरी</a></li>   
                <li><a href="events">हमारे कार्यक्रम</a></li>
                <li><a href="joinUs">हम से जुड़े (सदस्यता)</a></li>
                <li><a href="../donate">सहयोग करें (donate)</a></li>
                <li><a href="careers">करियर (पेशेवर) विकास के अवसर</a></li>
                <li><a href="officers">हमारे पदाधिकारी</a></li>
                <li><a href="writeArticle">आर्टिकल लिखें</a></li>
                <li><a href="inTheNews">खबरों में हम</a></li>               
                <li><a href="collaboratorsAndSponsors">हमारे सहयोगी</a></li>
                <li><a href="impact">बदलाव (असर)</a></li>               
                <li><a href="affiliates">हमारे स्वायत्त व अधीनस्थ संस्थाएं</a></li>
                <li><a href="reach">प्रदेश व विभिन्न स्तर पर हम</a></li>                                
                <li><a href="other">अन्य</a></li>
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
                <h2 class="section-title">Member Login</h2>
    <form method="POST">
        <label>Email: <input type="email" name="email" required></label><br>
        <label>Password:
            <span class="pw-field">
                <input id="login-password" type="password" name="password" required>
                <button type="button" class="pw-toggle" id="toggle-login-password" aria-label="Show password" title="Show password">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M2 12C3.8 7.5 7.3 5 12 5C16.7 5 20.2 7.5 22 12C20.2 16.5 16.7 19 12 19C7.3 19 3.8 16.5 2 12Z" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M12 15.2C10.2 15.2 8.8 13.8 8.8 12C8.8 10.2 10.2 8.8 12 8.8C13.8 8.8 15.2 10.2 15.2 12C15.2 13.8 13.8 15.2 12 15.2Z" stroke="currentColor" stroke-width="1.8"/>
                    </svg>
                </button>
            </span>
        </label><br>
        <div class="login-actions">
            <button type="submit">Login</button>
            <a href="forgot_password" class="forgot-link">Forgot password?</a>
        </div>
    </form>
    <br><br>
    <p>यदि आपने फॉर्म नहीं भरा है तो आप निचे दिए गये बटन पे क्लिक करके भर सकते हैं|</p>
    <a href="form" class="btn-highlight">रजिस्टर</a>
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
        <script>
            (function() {
                var input = document.getElementById('login-password');
                var btn = document.getElementById('toggle-login-password');
                if (!input || !btn) return;

                btn.addEventListener('click', function() {
                    var showing = input.type === 'text';
                    input.type = showing ? 'password' : 'text';
                    btn.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
                    btn.title = showing ? 'Show password' : 'Hide password';
                });
            })();
        </script>
    </body>
</html>
