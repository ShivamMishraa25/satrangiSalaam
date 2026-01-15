<?php
// db.php: Include your database connection file
include 'db.php';

// Check if the moderator is logged in
session_start();
if (!isset($_SESSION['moderator_logged_in'])) {
    header("Location: moderator_login");
    exit;
}

// Handle form approval and rejection for Donation Certificate
if (isset($_POST['approve_donation'])) {
    $donation_id = $_POST['donation_id'];
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $email = $_POST['email'];
    $whatsapp = $_POST['whatsapp'];

    // Update the donation information in the database
    $query = "UPDATE donations SET name = '$name', amount = '$amount', email = '$email', whatsapp = '$whatsapp' WHERE id = '$donation_id'";
    mysqli_query($conn, $query);

    // Redirect to generate_certificate.php to create and download the PDF
    header("Location: generate_donation?donation_id=$donation_id");
    exit;
}
if (isset($_POST['reject_donation'])) {
    $donation_id = $_POST['donation_id'];
    
    // Delete the record from the database
    $query = "DELETE FROM donations WHERE id = '$donation_id'";
    mysqli_query($conn, $query);
    header("Location: certificates");
    exit;
}

// Handle form approval and rejection for Experience Certificate
if (isset($_POST['approve_experience'])) {
    $experience_id = $_POST['experience_id'];
    $name = $_POST['name'];
    $post = $_POST['post'];
    $period = $_POST['period'];
    $email = $_POST['email'];
    $whatsapp = $_POST['whatsapp'];

    // Update the experience information in the database
    $query = "UPDATE experience SET name = '$name', post = '$post', period = '$period', email = '$email', whatsapp = '$whatsapp' WHERE id = '$experience_id'";
    mysqli_query($conn, $query);

    // Redirect to generate_experience_certificate.php to create and download the PDF
    header("Location: generate_experience?experience_id=$experience_id");
    exit;
}
if (isset($_POST['reject_experience'])) {
    $experience_id = $_POST['experience_id'];
    
    // Delete the record from the database
    $query = "DELETE FROM experience WHERE id = '$experience_id'";
    mysqli_query($conn, $query);
    header("Location: certificates");
    exit;
}

// Handle form approval for participation Certificate
if (isset($_POST['approve_participation'])) {
    $participation_id = $_POST['participation_id'];
    $name = trim($_POST['name']);
    $event_name = trim($_POST['event_name']);
    $event_date = trim($_POST['event_date']);
    $collaborators = isset($_POST['collaborators']) ? trim($_POST['collaborators']) : null;
    $email = trim($_POST['email']);
    $whatsapp = trim($_POST['whatsapp']);

    // Debugging: Check if data is received
    error_log("Updating Participation ID: $participation_id, Name: $name, Collaborators: $collaborators");

    // Update the participation information in the database
    $query = "UPDATE participation SET 
                name = ?, 
                event_name = ?, 
                event_date = ?, 
                collaborators = ?, 
                email = ?, 
                whatsapp = ? 
              WHERE id = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssssi", $name, $event_name, $event_date, $collaborators, $email, $whatsapp, $participation_id);

    if (!mysqli_stmt_execute($stmt)) {
        die("Database update failed: " . mysqli_error($conn));
    }

    mysqli_stmt_close($stmt);

    // Debugging: Ensure data is saved before generating the PDF
    error_log("Participation ID $participation_id updated successfully!");

    // Redirect to generate_participation_certificate.php to create and download the PDF
    header("Location: generate_participation.php?participation_id=$participation_id");
    exit;
}
// Handle rejection for participation Certificate
if (isset($_POST['reject_participation'])) {
    $participation_id = $_POST['participation_id'];

    // Delete the record from the database
    $query = "DELETE FROM participation WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $participation_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: certificates.php");
    exit;
}

// Handle form submission for Collaboration Certificate
if (isset($_POST['generate_collaboration_certificate'])) {
    $name = $_POST['name'];
    $works = $_POST['works'];

    // Get the latest certificate number
    $query = "SELECT IFNULL(MAX(certificate_no), 0) + 1 AS new_certificate_no FROM collaboration_certificates";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $certificate_no = $row['new_certificate_no'];

    // Insert the new certificate into the database
    $query = "INSERT INTO collaboration_certificates (name, works, certificate_no) VALUES ('$name', '$works', '$certificate_no')";
    mysqli_query($conn, $query);

    // Redirect to generate_collaboration_certificate.php to create and download the PDF
    header("Location: generate_collaboration_certificate?certificate_no=$certificate_no");
    exit;
}

// Handle form submission for Outstanding Performance Certificate
if (isset($_POST['generate_performance'])) {
    $name = $_POST['name'];
    $works = $_POST['works'];

    // Get the latest certificate number for Outstanding Performance
    $query = "SELECT IFNULL(MAX(certificate_no), 0) + 1 AS new_certificate_no FROM performance";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $certificate_no = $row['new_certificate_no'];

    // Insert the new certificate into the database
    $query = "INSERT INTO performance (name, works, certificate_no) VALUES ('$name', '$works', '$certificate_no')";
    mysqli_query($conn, $query);

    // Redirect to generate_outstanding_certificate.php to create and download the PDF
    header("Location: generate_performance?certificate_no=$certificate_no");
    exit;
}


// Fetch the donations for moderation
$donations_query = "SELECT * FROM donations WHERE status = 'pending'";
$donations_result = mysqli_query($conn, $donations_query);

// Fetch the experience records for moderation
$experience_query = "SELECT * FROM experience WHERE status = 'pending'";
$experience_result = mysqli_query($conn, $experience_query);

// Fetch the participation records for moderation
$participation_query = "SELECT * FROM participation WHERE status = 'pending'";
$participation_result = mysqli_query($conn, $participation_query);
?>
<!DOCTYPE html>
<html lang="hi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Certificates Panel</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Rozha+One&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../css/pages.css">
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1562008429602585"
     crossorigin="anonymous"></script>
     
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
          <h1>Pending Certificates</h2>

<h3 class="section-title">Pending Donation Certificates</h3>
<table class="container" border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Amount</th>
        <th>Email</th>
        <th>WhatsApp Number</th>
        <th>Action</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($donations_result)): ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><form method="post" action="">
            <input type="text" name="name" value="<?php echo $row['name']; ?>"></td>
        <td><input type="text" name="amount" value="<?php echo $row['amount']; ?>"></td>
        <td><input type="text" name="email" value="<?php echo $row['email']; ?>"></td>
        <td><input type="text" name="whatsapp" value="<?php echo $row['whatsapp']; ?>"></td>
        <td>
            <!-- Edit and submit the donation info -->
            <input type="hidden" name="donation_id" value="<?php echo $row['id']; ?>">
            <button type="submit" name="approve_donation">Approve</button>
            <button type="submit" name="reject_donation">Reject</button>
        </form></td>
    </tr>
    <?php endwhile; ?>
</table>
<hr/>
<h3 class="section-title">Pending Experience Certificates</h3>
<table class="container" border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Post</th>
        <th>Period</th>
        <th>email</th>
        <th>whatsapp no</th>
        <th>Action</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($experience_result)): ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><form method="post" action="">
            <input type="text" name="name" value="<?php echo $row['name']; ?>"></td>
        <td><input type="text" name="post" value="<?php echo $row['post']; ?>"></td>
        <td><input type="text" name="period" value="<?php echo $row['period']; ?>"></td>
        <td><input type="text" name="email" value="<?php echo $row['email']; ?>"></td>
        <td><input type="text" name="whatsapp" value="<?php echo $row['whatsapp']; ?>"></td>
        <td>
            <!-- Edit and submit the experience info -->
            <input type="hidden" name="experience_id" value="<?php echo $row['id']; ?>">
            <button type="submit" name="approve_experience">Approve</button>
            <button type="submit" name="reject_experience">Reject</button>
        </form></td>
    </tr>
    <?php endwhile; ?>
</table>
<br/>


<!-- participation certificate -->
<hr/>
<h3 class="section-title">Pending Participation Certificates</h3>
<table class="container" border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Event Name</th>
        <th>Event Date</th>
        <th>Collaborators</th>
        <th>Email</th>
        <th>WhatsApp No</th>
        <th>Action</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($participation_result)): ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td>
            <form method="post" action="">
                <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>">
        </td>
        <td><input type="text" name="event_name" value="<?php echo htmlspecialchars($row['event_name']); ?>"></td>
        <td><input type="text" name="event_date" value="<?php echo htmlspecialchars($row['event_date']); ?>"></td>
        <td><input type="text" name="collaborators" value="<?php echo htmlspecialchars($row['collaborators']); ?>"></td>
        <td><input type="text" name="email" value="<?php echo htmlspecialchars($row['email']); ?>"></td>
        <td><input type="text" name="whatsapp" value="<?php echo htmlspecialchars($row['whatsapp']); ?>"></td>
        <td>
            <input type="hidden" name="participation_id" value="<?php echo $row['id']; ?>">
            <button type="submit" name="approve_participation">Approve</button>
            <button type="submit" name="reject_participation">Reject</button>
        </td>
        </form>
    </tr>
    <?php endwhile; ?>
</table>
<br/>


<hr/>
<h3 class="section-title">Generate Collaboration Certificate</h3>
<!-- Collaboration Certificate Form -->

<form class="container" method="post" action="">
    <label for="name">Name of Collaborator:</label>
    <input type="text" name="name" id="name" required><br><br>

    <label for="works">Works Done by Collaborator (Do NOT change paragraphs):</label><br>
    <textarea name="works" id="works" rows="5" cols="50" maxlength="450" required>450 chars max</textarea><br><br>

    <button type="submit" name="generate_collaboration_certificate">Generate Certificate</button>
</form>
<br/><br>
<hr/>
<h3 class="section-title">Generate Performance Certificate</h3>
<!-- Outstanding Performance Certificate Form -->
<form class="container" method="post" action="">
    <label for="name">Name of Performer:</label>
    <input type="text" name="name" id="name" required><br><br>

    <label for="works">Works Done (Do NOT change paragraphs):</label><br>
    <textarea name="works" id="works" rows="5" cols="50" maxlength="450" required>450 chars max</textarea><br><br>

    <button type="submit" name="generate_performance">Generate Certificate</button>
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
