<?php
    include '../includes/db.php'; // Include database connection

    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $preferredName = $_POST['preferred_name'];
        $pronouns = $_POST['pronouns'];
        $fatherName = $_POST['father_name'];
        $post = $_POST['post'];
        $reference = $_POST['reference'];
        $address = $_POST['address'];
        $occupation = $_POST['occupation'];
        $mobileNo = $_POST['mobile_no'];
        $email = $_POST['email'];
        $city = $_POST['city'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        $photo = NULL;
        if (!empty($_POST['cropped_image'])) {
            $targetDir = "../uploads/members/";
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $fileName = "user_" . time() . ".jpg";
            $targetFilePath = $targetDir . $fileName;
            
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_POST['cropped_image']));
            
            if (!file_put_contents($targetFilePath, $imageData)) {
                die("Error saving image file.");
            }
            
            $photo = "uploads/members/" . $fileName;
        }

        $query = "INSERT INTO submissions (name, preferred_name, pronouns, father_name, post, reference, address, occupation, mobile_no, email, city, photo, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssssssss", $name, $preferredName, $pronouns, $fatherName, $post, $reference, $address, $occupation, $mobileNo, $email, $city, $photo, $password);
    
        if ($stmt->execute()) {
            header("Location: ../includes/thankYou.php");
        } else {
            echo "Submission failed.";
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
<meta property="og:title" content="Membership Form">
<meta property="og:description" content="Become a Member or Senior Member of Satrangi Salaam by applying through this Form.">

<meta property="og:image" itemprop="image" content="https://i.ibb.co/nz0bLWN/Satrangi-Salaam-300.jpg">
<meta property="og:type" content="website" />
<meta property="og:image:type" content="image/jpg">

<meta property="og:image:width" content="300">
<meta property="og:image:height" content="300">

<link rel="icon" type="image/x-icon" href="favicon.ico">
<link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">

<!--  <meta property="og:url" content="http://satrangisalaam.in">  -->
        <title>Membership Form</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Rozha+One&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../css/pages.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
        <style>
            #container {
                background-color: white;
                border-radius: 5px;
                display: inline-block;
                padding: 4px;
                color: black;
            }  
            button {
                background-color: rgb(245, 245, 245);
                border-top: 2px solid lightgrey;
                border-bottom: 2px solid grey;
                border-right: 2px solid grey;
                border-left: 2px solid lightgrey;
                padding: 2px;
                border-radius: 1px;
                font-weight: bold;
            }
            .button {
                background-color: white;                
                padding: 4px 8px;
                border-radius: 5px;
                font-weight: bold;
                margin-top: 5px;
            }
            #preview {
            max-width: 100%;
            max-height: 300px;
            }
            </style>
    </head>
    <body>
        <header>
            <!-- Logo or homepage anchor -->
            <a href="../index.php" class="logo">
            <img src="../img/satrangiSalaamLogo.png"
                alt="satrangi Salaam Logo">
            </a>
            <!-- Burger Icon -->
            <div class="menu-icon" id="menu-icon"></div>
            <!-- Slide-in Menu -->
            <nav id="nav-menu">
            <ul>
                <li><a href="aboutUs.php">हमारे बारे में</a></li>
                <li><a href="announcements.php">सूचनाएं (अपडेट्स)</a></li>
                <li><a href="gallery.php">गैलरी</a></li>   
                <li><a href="events.php">हमारे कार्यक्रम</a></li>
                <li><a href="joinUs.php">हम से जुड़े (सदस्यता)</a></li>
                <li><a href="donate.php">सहयोग करें (donate)</a></li>
                <li><a href="careers.php">करियर (पेशेवर) विकास के अवसर</a></li>
                <li><a href="officers.php">हमारे पदाधिकारी</a></li>
                <li><a href="writeArticle.php">आर्टिकल लिखें</a></li>
                <li><a href="inTheNews.php">खबरों में हम</a></li>               
                <li><a href="collaboratorsAndSponsors.php">हमारे सहयोगी</a></li>
                <li><a href="impact.php">बदलाव (असर)</a></li>               
                <li><a href="affiliates.php">हमारे स्वायत्त व अधीनस्थ संस्थाएं</a></li>
                <li><a href="reach.php">प्रदेश व विभिन्न स्तर पर हम</a></li>                                
                <li><a href="other.php">अन्य</a></li>
            </ul>
            <div class="social-icons">
                <a href="https://youtube.com/@satrangisalaam/"><img src="https://cdn1.iconfinder.com/data/icons/logotypes/32/youtube-512.png"></a>
                <a href="https://www.instagram.com/satrangisalaam/"><img src="https://cdn2.iconfinder.com/data/icons/social-media-2285/512/1_Instagram_colored_svg_1-1024.png" alt="Instagram"></a>
                <a href="https://www.facebook.com/SatrangiSalaam/"><img src="https://cdn2.iconfinder.com/data/icons/social-media-2285/512/1_Facebook_colored_svg_copy-1024.png" alt="Facebook"></a>
                <a href="satrangisalamss@gmail.com"><img src="https://cdn4.iconfinder.com/data/icons/logos-brands-in-colors/48/google-gmail-512.png" alt="Email"></a>
                <a href="https://x.com/SatrangiSalamSS/"><img src="https://cdn2.iconfinder.com/data/icons/threads-by-instagram/24/x-logo-twitter-new-brand-contained-512.png" alt="Twitter"></a>
        </header>
        <!-- Bird Image for animation -->
        <img id="bird" src="../img/bird.png" alt="Flying Bird">
        <img id="bird2" src="../img/bird2.png" alt="flying bird">
          <section class="content">
                <h1>Join Our NGO</h1>
                <h2>Membership Form</h2>
<br>

<form class="left" id="container" method="post" enctype="multipart/form-data">
    <label>Name:<input type="text" name="name" required></label><br><br>
    <label>Preferred Name:<input type="text" name="preferred_name" required></label><br><br>
    <label>Pronouns:<input type="text" name="pronouns" required></label><br><br>
    <label>Father’s Name:<input type="text" name="father_name" required></label><br><br>
    <label>Post:<select name="post" required>
        <option value="Member">Member</option>
        <option value="Senior Member">Senior Member</option>
    </select></label><br><br>
    <label>Reference:<input type="text" name="reference" required></label><br><br>
    <label>Address:<input type="text" name="address" required></label><br><br>
    <label>City:<input type="text" name="city" required></label><br><br>
    <label>Occupation:<input type="text" name="occupation" required></label><br><br>
    <label>Mobile Number:<input type="tel" name="mobile_no" required></label><br><br>
    <label>Email:<input type="email" name="email" required></label><br><br>
    <label>Password:<input type="password" name="password" required></label><br><br>
    <label>Photo:<input type="file" id="photoInput" accept="image/*" required></label><br><br>
    <div><img id="preview"></div>
    <input type="hidden" name="cropped_image" id="cropped_image">
    <button type="submit" name="submit">Submit</button>
</form>

    <br><br>
    <p>कृपया अपना <span class="highlight">पासवर्ड याद रक्खें|</span> सत्यापन के लिए आप को संपर्क किया जायगा|</p>
    <p>अपना <b>मेम्बरशिप कार्ड डाउनलोड</b> करने के लिए यहाँ <span class="highlight">लॉग इन करें</span> => <a class="button" href="user_login.php">Log In</a></p>
          </section>
        <footer>
            <ul>
                <li><a href="https://youtube.com/@satrangisalaam/"><img src="https://cdn1.iconfinder.com/data/icons/logotypes/32/youtube-512.png"></a></li>
                <li><a href="https://www.instagram.com/satrangisalaam/"><img src="https://cdn2.iconfinder.com/data/icons/social-media-2285/512/1_Instagram_colored_svg_1-1024.png" alt="Instagram"></a></li>
                <li><a href="https://www.facebook.com/SatrangiSalaam/"><img src="https://cdn2.iconfinder.com/data/icons/social-media-2285/512/1_Facebook_colored_svg_copy-1024.png" alt="Facebook"></a></li>
                <li><a href="https://satrangisalaam.wordpress.com/"><img src="https://cdn4.iconfinder.com/data/icons/iconsimple-logotypes/512/wordpress-512.png" alt="WordPress"></a></li>
                <li><a href="https://chat.whatsapp.com/Jifh0MGROxAJQD4bueRFN0"><img src="https://cdn3.iconfinder.com/data/icons/2018-social-media-logotypes/1000/2018_social_media_popular_app_logo-whatsapp-512.png"></a></li>
                <li><a href="https://t.me/SatrangiSalam"><img src="https://cdn4.iconfinder.com/data/icons/logos-and-brands/512/335_Telegram_logo-512.png"></a></li>
                <li><a href="https://x.com/SatrangiSalamSS/"><img src="https://cdn2.iconfinder.com/data/icons/social-media-2285/512/1_Twitter_colored_svg-256.png" alt="Twitter"></a></li>
            </ul>
            <div class="darkmode">
              <span>switch to dark/light mode</span>
            <button id="toggle-button" class="toggle-btn"></button>
            </div>
        </footer>
        <script type="text/javascript" src="../js/pages.js"></script>
        <script>
    let cropper;
    const photoInput = document.getElementById('photoInput');
    const preview = document.getElementById('preview');
    const croppedImageInput = document.getElementById('cropped_image');
    const form = document.querySelector('form');

    photoInput.addEventListener('change', function(event) {
        let file = event.target.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                if (cropper) cropper.destroy();
                cropper = new Cropper(preview, { aspectRatio: 5/6 });
            };
            reader.readAsDataURL(file);
        }
    });

    form.addEventListener('submit', function(event) {
        if (cropper) {
            let croppedCanvas = cropper.getCroppedCanvas({ width: 250, height: 300 });
            if (!croppedCanvas) {
                alert("Please crop the image before submitting.");
                event.preventDefault(); // Stop form submission
                return;
            }
            croppedImageInput.value = croppedCanvas.toDataURL('image/jpeg');
        } else {
            alert("Please select and crop an image before submitting.");
            event.preventDefault(); // Stop form submission
        }
    });
</script>

    </body>
</html>
