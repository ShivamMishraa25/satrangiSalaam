<?php
session_start();
if (!isset($_SESSION['moderator_logged_in'])) {
    header("Location: login.php");
    exit();
}

include 'db.php'; // Include database connection

// Display success/error messages
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['success_message'], $_SESSION['error_message']);

// Handle Gallery Image Upload
if (isset($_POST['upload_gallery_images'])) {
    $target_dir = "../uploads/gallery/";
    $uploaded_images = [];

    foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name) {
        $file_name = basename($_FILES['gallery_images']['name'][$key]);
        $unique_name = uniqid() . "_" . $file_name;
        $target_file = $target_dir . $unique_name;

        if (move_uploaded_file($tmp_name, $target_file)) {
            $uploaded_images[] = $unique_name;
        }
    }

    if (!empty($uploaded_images)) {
        $insert_query = "INSERT INTO gallery (image_path) VALUES (?)";
        $stmt = $conn->prepare($insert_query);

        foreach ($uploaded_images as $image) {
            $stmt->bind_param("s", $image);
            $stmt->execute();
        }

        header("Location: ../public/gallery");
        exit();
    } else {
        echo "<p>Error: Unable to upload images.</p>";
    }
}

// Handle Gallery Image Delete
if (isset($_POST['delete_gallery_images'])) {
    $selected_images = isset($_POST['selected_images']) && is_array($_POST['selected_images']) ? $_POST['selected_images'] : [];

    if (!empty($selected_images)) {
        $ids = array_map('intval', $selected_images);
        $ids = array_filter($ids, function ($id) {
            return $id > 0;
        });

        if (!empty($ids)) {
            $id_list = implode(',', $ids);
            $fetch_query = "SELECT id, image_path FROM gallery WHERE id IN ($id_list)";
            $fetch_result = mysqli_query($conn, $fetch_query);

            if ($fetch_result && mysqli_num_rows($fetch_result) > 0) {
                while ($img = mysqli_fetch_assoc($fetch_result)) {
                    $file_path = "../uploads/gallery/" . $img['image_path'];
                    if (is_file($file_path)) {
                        unlink($file_path);
                    }
                }

                $delete_query = "DELETE FROM gallery WHERE id IN ($id_list)";
                mysqli_query($conn, $delete_query);
            }
        }

        header("Location: moderator.php");
        exit();
    }
}

$gallery_images_result = mysqli_query($conn, "SELECT id, image_path FROM gallery ORDER BY id DESC");
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
            .message {
                padding: 10px;
                margin: 10px 0;
                border-radius: 5px;
            }
            .success {
                background-color: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
            }
            .error {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }
            .form-group {
                margin-bottom: 15px;
            }
            .form-group label {
                display: block;
                font-weight: bold;
                margin-bottom: 5px;
            }
            .form-group input,
            .form-group textarea {
                width: 100%;
                padding: 8px;
                border: 1px solid #ddd;
                border-radius: 4px;
                font-size: 14px;
            }
            .form-group textarea {
                resize: vertical;
                min-height: 100px;
            }
            .btn {
                background-color: #007bff;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 16px;
            }
            .btn:hover {
                background-color: #0056b3;
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
                <li><a href="../public/aboutUs.php">हमारे बारे में</a></li>
                <li><a href="../public/announcements.php">सूचनाएं (अपडेट्स)</a></li>
                <li><a href="../public/gallery.php">गैलरी</a></li>   
                <li><a href="../public/events.php">हमारे कार्यक्रम</a></li>
                <li><a href="../public/joinUs.php">हम से जुड़े (सदस्यता)</a></li>
                <li><a href="../public/donate.php">सहयोग करें (donate)</a></li>
                <li><a href="../public/careers.php">करियर (पेशेवर) विकास के अवसर</a></li>
                <li><a href="../public/officers.php">हमारे पदाधिकारी</a></li>
                <li><a href="../public/writeArticle.php">आर्टिकल लिखें</a></li>
                <li><a href="../public/inTheNews.php">खबरों में हम</a></li>               
                <li><a href="../public/collaboratorsAndSponsors.php">हमारे सहयोगी</a></li>
                <li><a href="../public/impact.php">बदलाव (असर)</a></li>               
                <li><a href="../public/affiliates.php">हमारे स्वायत्त व अधीनस्थ संस्थाएं</a></li>
                <li><a href="../public/reach.php">प्रदेश व विभिन्न स्तर पर हम</a></li>                                
                <li><a href="../public/other.php">अन्य</a></li>
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

            <!-- Display Messages -->
            <?php if (!empty($success_message)): ?>
                <div class="message success"><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
                <div class="message error"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <!-- write new announcements/updates -->
            <h2 class="section-title">Announcement</h2>
            <form action="upload_announcement.php" method="POST" enctype="multipart/form-data">
                <label for="title">Announcement Title:</label><br>
                <input type="text" name="title" id="title" required><br><br>

                <label for="content">Announcement:</label><br>
                <textarea name="content" id="content" rows="5" required></textarea><br><br>

                <label for="images">Upload Images (optional):</label><br>
                <input type="file" name="images[]" id="images" multiple><br><br>

                <button type="submit">Post Announcement</button>
            </form>
            <br><br>
            <hr>
            <!-- approve articles -->
            <h2 class="section-title">Approve Submitted Articles</h2>
            <?php
            // moderator.php

            // Connect to the database
            include('db.php');

            // Fetch and display pending articles
            $sql = "SELECT * FROM articles WHERE status = 'pending' ORDER BY created_at DESC";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div id='container'>";
                echo "<h4>Title: " . $row['title'] . "</h2>";
                echo "<p>By: " . $row['name'] . " (" . $row['contact'] . ")</p>";
                echo "<p class='left'>" . $row['content'] . "</p>";

                // Approve and dismiss buttons
                echo "<form action='' method='post'>";
                echo "<input type='hidden' name='article_id' value='" . $row['id'] . "'>";
                echo "<button type='submit' name='approve2'>Approve</button>";
                echo "<button type='submit' name='dismiss2'>Dismiss</button>";
                echo "</form>";
                echo "</div><br><br>";
            }
            } else {
            echo "<p>No pending articles.</p>";
            }

            // Handle approve or dismiss actions
            if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['approve2']) || isset($_POST['dismiss2']))) {
            $article_id = mysqli_real_escape_string($conn, $_POST['article_id']);

            if (isset($_POST['approve2'])) {
                // Update the article's status to 'approved'
                $sql = "UPDATE articles SET status = 'approved' WHERE id = $article_id";
                if (mysqli_query($conn, $sql)) {
                    echo "<p>Article approved successfully.</p>";
                } else {
                    echo "<p>Error: " . mysqli_error($conn) . "</p>";
                }
            } elseif (isset($_POST['dismiss2'])) {
                // Delete the article
                $sql = "DELETE FROM articles WHERE id = $article_id";
                if (mysqli_query($conn, $sql)) {
                    echo "<p>Article dismissed successfully.</p>";
                } else {
                    echo "<p>Error: " . mysqli_error($conn) . "</p>";
                }
            }

            // Refresh the page after action
            header("Location: moderator.php");
            exit();
            }
            ?>

            <br>
            <hr>

            <!-- events update form with improved validation -->
            <h2 class="section-title">Add New Events:</h2>
            <form action="process_event.php" method="POST" enctype="multipart/form-data" id="eventForm">
                <div class="form-group">
                    <label for="event_name">Event Name: *</label>
                    <input type="text" name="event_name" id="event_name" maxlength="255" required>
                    <small>Maximum 255 characters</small>
                </div>

                <div class="form-group">
                    <label for="event_date">Event Date: *</label>
                    <input type="date" name="event_date" id="event_date" required>
                </div>

                <div class="form-group">
                    <label for="event_location">Event Location: *</label>
                    <input type="text" name="event_location" id="event_location" maxlength="255" required>
                    <small>Maximum 255 characters</small>
                </div>

                <div class="form-group">
                    <label for="event_description">Event Description: *</label>
                    <textarea name="event_description" id="event_description" maxlength="5000" required></textarea>
                    <small>Maximum 5000 characters</small>
                </div>

                <div class="form-group">
                    <label for="event_image">Event Images:</label>
                    <input type="file" name="event_image[]" id="event_image" accept="image/jpeg,image/jpg,image/png,image/gif" multiple>
                    <small>Maximum 5MB per file. Allowed formats: JPEG, PNG, GIF</small>
                </div>

                <button type="submit" class="btn">Submit Event</button>
            </form>

            <script>
                // Client-side validation for event form
                document.getElementById('eventForm').addEventListener('submit', function(e) {
                    const eventName = document.getElementById('event_name').value.trim();
                    const eventDate = document.getElementById('event_date').value;
                    const eventLocation = document.getElementById('event_location').value.trim();
                    const eventDescription = document.getElementById('event_description').value.trim();
                    
                    let errors = [];
                    
                    if (eventName.length === 0) {
                        errors.push('Event name is required');
                    } else if (eventName.length > 255) {
                        errors.push('Event name must be less than 255 characters');
                    }
                    
                    if (!eventDate) {
                        errors.push('Event date is required');
                    }
                    
                    if (eventLocation.length === 0) {
                        errors.push('Event location is required');
                    } else if (eventLocation.length > 255) {
                        errors.push('Event location must be less than 255 characters');
                    }
                    
                    if (eventDescription.length === 0) {
                        errors.push('Event description is required');
                    } else if (eventDescription.length > 5000) {
                        errors.push('Event description must be less than 5000 characters');
                    }
                    
                    // Validate file uploads
                    const fileInput = document.getElementById('event_image');
                    if (fileInput.files.length > 0) {
                        for (let i = 0; i < fileInput.files.length; i++) {
                            const file = fileInput.files[i];
                            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                            const maxSize = 5 * 1024 * 1024; // 5MB
                            
                            if (!allowedTypes.includes(file.type)) {
                                errors.push('Invalid file type: ' + file.name + '. Only JPEG, PNG, and GIF allowed.');
                            }
                            
                            if (file.size > maxSize) {
                                errors.push('File too large: ' + file.name + '. Maximum size is 5MB.');
                            }
                        }
                    }
                    
                    if (errors.length > 0) {
                        e.preventDefault();
                        alert('Please fix the following errors:\n\n' + errors.join('\n'));
                        return false;
                    }
                });
            </script>

            <br>
            <hr>
            <!-- Gallery Image Upload Form -->
            <h2 class="section-title">Upload Gallery Images</h2>
            <form action="moderator.php" method="POST" enctype="multipart/form-data">
                <label for="gallery_images">Select Images:</label><br>
                <input type="file" name="gallery_images[]" id="gallery_images" multiple required><br><br>
                <button type="submit" name="upload_gallery_images">Upload Images</button>
            </form>

            <br>
            <h2 class="section-title">Delete Gallery Images</h2>
            <form action="moderator.php" method="POST">
                <?php if ($gallery_images_result && mysqli_num_rows($gallery_images_result) > 0): ?>
                    <?php while ($gallery_image = mysqli_fetch_assoc($gallery_images_result)): ?>
                        <label style="display:inline-block; margin:8px; text-align:center;">
                            <input type="checkbox" name="selected_images[]" value="<?php echo (int)$gallery_image['id']; ?>"><br>
                            <img src="../uploads/gallery/<?php echo rawurlencode($gallery_image['image_path']); ?>" alt="Gallery image" style="width:120px; height:120px; object-fit:cover; border:1px solid #ccc;">
                        </label>
                    <?php endwhile; ?>
                    <br>
                    <button type="submit" name="delete_gallery_images">Delete Selected Images</button>
                <?php else: ?>
                    <p>No gallery images found.</p>
                <?php endif; ?>
            </form>

            <a href="logout.php">Logout</a>
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
