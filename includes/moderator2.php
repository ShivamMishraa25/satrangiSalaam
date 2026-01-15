<?php
session_start();
if (!isset($_SESSION['moderator_logged_in'])) {
    header("Location: login2");
    exit();
}

include 'db.php'; // Include database connection

// Handle Approve User
if (isset($_POST['approve'])) {
    $submission_id = $_POST['submission_id'];

    // Get updated data entered by the moderator
    $updated_name = $_POST['name'];
    $updated_preferred_name = $_POST['preferred_name'];
    $updated_pronouns = $_POST['pronouns'];
    $updated_father_name = $_POST['father_name'];
    $updated_post = $_POST['post'];
    $updated_reference = $_POST['reference'];
    $updated_address = $_POST['address'];
    $updated_occupation = $_POST['occupation'];
    $updated_mobile_no = $_POST['mobile_no'];
    $updated_email = $_POST['email'];
    $updated_city = $_POST['city'];

    // Step 1: Update the submissions table with the edited data
    $update_submission_query = "UPDATE submissions SET name = ?, preferred_name = ?, pronouns = ?, father_name = ?, post = ?, reference = ?, address = ?, occupation = ?, mobile_no = ?, email = ?, city = ? WHERE id = ?";
    $update_submission_stmt = $conn->prepare($update_submission_query);
    $update_submission_stmt->bind_param("sssssssssssi", $updated_name, $updated_preferred_name, $updated_pronouns, $updated_father_name, $updated_post, $updated_reference, $updated_address, $updated_occupation, $updated_mobile_no, $updated_email, $updated_city, $submission_id);

    if ($update_submission_stmt->execute()) {
        // Step 2: Insert the updated data into the users table, but only if the user does not exist yet
        $check_user_query = "SELECT * FROM users WHERE email = ?";
        $check_user_stmt = $conn->prepare($check_user_query);
        $check_user_stmt->bind_param("s", $updated_email);
        $check_user_stmt->execute();
        $result = $check_user_stmt->get_result();

        if ($result->num_rows == 0) {
            // If user does not exist, insert the data
            $insert_query = "INSERT INTO users (name, preferred_name, pronouns, father_name, post, reference, address, occupation, mobile_no, email, city, photo, password) SELECT name, preferred_name, pronouns, father_name, post, reference, address, occupation, mobile_no, email, city, photo, password FROM submissions WHERE id = ?";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("i", $submission_id);

            if ($insert_stmt->execute()) {
                // Step 3: Update the status of the submission to 'approved'
                $update_status_query = "UPDATE submissions SET status = 'approved' WHERE id = ?";
                $update_status_stmt = $conn->prepare($update_status_query);
                $update_status_stmt->bind_param("i", $submission_id);
                $update_status_stmt->execute();

                // Redirect after processing to prevent form resubmission
                header("Location: moderator2?success=approved");
                exit();
            } else {
                echo "<p>Error: Unable to insert user data.</p>";
            }
        } else {
            echo "<p>Error: User with this email already exists.</p>";
        }
    } else {
        echo "<p>Error: Unable to update the submission data.</p>";
    }
}

// Handle Dismiss User
if (isset($_POST['dismiss'])) {
    $submission_id = $_POST['submission_id'];
    // Update the status of the submission to 'dismissed'
    $update_query = "UPDATE submissions SET status = 'dismissed' WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("i", $submission_id);
    $update_stmt->execute();

    // Redirect after dismissing to prevent form resubmission
    header("Location: moderator2?success=dismissed");
    exit();
}
 
// Handle Change Post and Move to exMembers
if (isset($_POST['change_post'])) {
    $user_id = $_POST['user_id'];
    $new_post = $_POST['new_post'];

    // Move to exMembers if post changed to resigned, removed, etc.
    if (in_array($new_post, ['resigned', 'removed', 'promoted', 'demoted'])) {
        // Insert into exMembers with the new post
        $query = "INSERT INTO exMembers (name, email, post) SELECT name, email, ? FROM users WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $new_post, $user_id);
        $stmt->execute();

        // Remove from users table
        $delete_query = "DELETE FROM users WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $user_id);
        $delete_stmt->execute();

        // Redirect after changing the post to prevent form resubmission
        header("Location: moderator2?success=post_changed");
        exit();
    }
}

// Fetch pending submissions
$pending_query = "SELECT * FROM submissions WHERE status = 'pending'";
$pending_result = $conn->query($pending_query);

// Fetch dismissed submissions
$dismissed_query = "SELECT * FROM submissions WHERE status = 'dismissed'";
$dismissed_result = $conn->query($dismissed_query);

// Fetch approved users
$users_query = "SELECT * FROM users";
$users_result = $conn->query($users_query);
?>

<!DOCTYPE html>
<html lang="hi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Moderator Panel</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Rozha+One&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../css/pages.css">
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1562008429602585"
     crossorigin="anonymous"></script>
     
        <style>
            #container {
                background-color: white;
                border-radius: 7px;
                color: black;
                display: inline-block;
                padding: 5px;
            }
            .updates{
                width: 300px;
            }
            </style>
    </head>
    <body>
        <header>
            <!-- Logo or homepage anchor -->
            <a href="../index" class="logo">
            <img src="../img/satrangiSalaamLogo.png"
                alt="satrangi Salaam Logo">
            </a>
            <!-- Burger Icon -->
            <div class="menu-icon" id="menu-icon"></div>
            <!-- Slide-in Menu -->
            <nav id="nav-menu">
            <ul>
                <li><a href="../public/aboutUs">हमारे बारे में</a></li>
                <li><a href="../public/announcements">सूचनाएं (अपडेट्स)</a></li>
                <li><a href="../public/gallery">गैलरी</a></li>   
                <li><a href="../public/events">हमारे कार्यक्रम</a></li>
                <li><a href="../public/joinUs">हम से जुड़े (सदस्यता)</a></li>
                <li><a href="../public/donate">सहयोग करें (donate)</a></li>
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
                <a href="satrangisalamss@gmail.com"><img src="https://cdn4.iconfinder.com/data/icons/logos-brands-in-colors/48/google-gmail-512.png" alt="Email"></a>
                <a href="https://x.com/SatrangiSalamSS/"><img src="https://cdn2.iconfinder.com/data/icons/threads-by-instagram/24/x-logo-twitter-new-brand-contained-512.png" alt="Twitter"></a>
        </header>
        <!-- Bird Image for animation -->
        <img id="bird" src="../img/bird.png" alt="Flying Bird">
        <img id="bird2" src="../img/bird2.png" alt="flying bird">
          <section class="content">
          <h1>Moderator Approval Panel</h1>

<!-- Pending Submissions -->
<?php if ($pending_result->num_rows > 0): ?>
    <h2 class="section-title">Pending Submissions</h2>
    <?php while ($row = $pending_result->fetch_assoc()): ?>
        <div>
        <form class="left" id="container" method="post">
<input type="hidden" name="submission_id" value="<?= $row['id'] ?>">
<label>Name: <input type="text" name="name" value="<?= $row['name'] ?>"></label><br>
<label>Preferred Name: <input type="text" name="preferred_name" value="<?= $row['preferred_name'] ?>"></label><br>
<label>pronouns: <input type="text" name="pronouns" value="<?= $row['pronouns'] ?>"></label><br>
<label>father_name: <input type="text" name="father_name" value="<?= $row['father_name'] ?>"></label><br>
<label>post: <input type="text" name="post" value="<?= $row['post'] ?>"></label><br>
<label>reference: <input type="text" name="reference" value="<?= $row['reference'] ?>"></label><br>
<label>address: <input type="text" name="address" value="<?= $row['address'] ?>"></label><br>
<label>occupation: <input type="text" name="occupation" value="<?= $row['occupation'] ?>"></label><br>
<label>mobile_no: <input type="text" name="mobile_no" value="<?= $row['mobile_no'] ?>"></label><br>
<label>Email: <input type="email" name="email" value="<?= $row['email'] ?>"></label><br>
<label>City: <input type="text" name="city" value="<?= $row['city'] ?>"></label><br>
<label>Photo:</label><br>
<img src="../<?= $row['photo'] ?>" alt="User Photo" style="width:100px;height:120px;"><br>
<button type="submit" name="approve">Approve</button>
<button type="submit" name="dismiss">Dismiss</button>
</form>

        </div><br>
    <?php endwhile; ?>
<?php else: ?>
    <p>No pending submissions.</p>
<?php endif; ?>
<hr>


<!-- List of Users with post Update -->
<h2 class="section-title">List of Approved Users</h2>
<?php if ($users_result->num_rows > 0): ?>
    <?php while ($row = $users_result->fetch_assoc()): ?>
        <div id="container">
            <strong>Name:</strong> <?= $row['name'] ?><br>
            <form method="post">
                <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                <label>Change post:</label>
                <select name="new_post">
                    <option value="resigned">Resigned</option>
                    <option value="removed">Removed</option>
                    <option value="promoted">Promoted</option>
                    <option value="demoted">Demoted</option>
                </select>
                <button type="submit" name="change_post">Change post</button>
            </form>
        </div><br><br/>
    <?php endwhile; ?>
<?php else: ?>
    <p>No approved users found.</p>
<?php endif; ?>
<hr>

<!-- Dismissed Forms -->
<?php if ($dismissed_result->num_rows > 0): ?>
    <h2  class="section-title">Dismissed Forms</h2>
    <?php while ($row = $dismissed_result->fetch_assoc()): ?>
        <div id="container">
            <strong>Name:</strong> <?= $row['name'] ?><br>
            <strong>Email:</strong> <?= $row['email'] ?><br>
        </div><br><br>
    <?php endwhile; ?>
<?php else: ?>
    <p>No dismissed forms.</p>
<?php endif; ?>


<a href="logout">Logout</a>
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
    </body>
</html>