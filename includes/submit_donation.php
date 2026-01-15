<!DOCTYPE html>
<html lang="hi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Thank You For Donating</title>
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
            .button {
                background-color: white;                
                padding: 8px 8px;
                border-radius: 5px;
                font-weight: bold;
                margin-top: 5px;
                box-shadow: 5px 5px 10px grey;
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
            <h1>धन्यवाद</h1>
                <?php
// db.php: Include your database connection file
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $email = $_POST['email'];
    $whatsapp = $_POST['whatsapp'];
    $timestamp = date('Y-m-d H:i:s'); // Get current timestamp

    // Insert the form data into the donations table
    $query = "INSERT INTO donations (name, amount, email, whatsapp, created_at, status) VALUES ('$name', '$amount', '$email', '$whatsapp', '$timestamp', 'pending')";
    if (mysqli_query($conn, $query)) {
        echo "<div class='container'><p><span class='highlight'>आपने सतरंगी सलाम के उद्देश्य में विश्वास दिखा करके आर्थिक मदद किया है, इसके लिए बहुत धन्यवाद|</span><br><br>हम आपके विश्वास पर पूरी तरीके से खरा उतरने का विश्वास दिलाते हैं|<br>हम पूरी तरीके से पारदर्शी व्यवस्था रखतें हैं, आप बेझिझक अपने दान का हिसाब देख सकते हैं| <br><br><b>आपको डोनेशन का सर्टिफ़िकेट आपके व्हाट्सैप नंबर या ईमेल पर भेज दिया जायगा| धन्यवाद|</b></p></div><br><br>";
        echo "<a class='button' href='../'>होमपेज पर जेएं (HomePage)</a>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
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
