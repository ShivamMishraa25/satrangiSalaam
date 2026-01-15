<?php
include 'includes/db.php';

// Fetch latest announcement
$sql = "SELECT * FROM announcements ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

// Fetch latest 3 events
$events_sql = "SELECT * FROM events ORDER BY event_date DESC LIMIT 3";
$events_result = $conn->query($events_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta property="og:site_name" content="Satrangi Salaam">
    <meta property="og:title" content="All India Satrangi Salaam Association">
    <meta property="og:description" content="Satrangi Salaam Home Page">

    <meta property="og:image" itemprop="image" content="https://i.ibb.co/nz0bLWN/Satrangi-Salaam-300.jpg">
    <meta property="og:type" content="website" />
    <meta property="og:image:type" content="image/jpg">

    <meta property="og:image:width" content="300">
    <meta property="og:image:height" content="300">

    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">

    <meta property="og:url" content="https://satrangisalaam.in">
    <title>All India Satrangi Salaam Association | Prayagraj</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
    <?php
    include 'includes/nav.php';
    ?>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
            <h1 class="hero-title">All India Satrangi Salaam Association</h1>
            <p class="hero-subtitle">Promoting Unity, Harmony & Social Justice across India</p>
            <p class="hero-location">Based in Prayagraj, Uttar Pradesh</p>
            <div class="hero-buttons">
                <a href="donate" class="btn btn-primary">Donate Now</a>
                <a href="#about" class="btn btn-secondary">Learn More</a>
            </div>
        </div>
        <div class="hero-overlay"></div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <div class="container">
            <div class="section-header">
                <h2>About Our Mission</h2>
                <p>Working towards a harmonious and inclusive society</p>
            </div>
            <div class="about-grid">
                <div class="about-text">
                    <h3>Our Vision</h3>
                    <p>All India Satrangi Salaam Association is dedicated to fostering unity, promoting social harmony, and ensuring equal opportunities for all communities across India. Based in the historic city of Prayagraj, we work tirelessly to bridge divides and create a more inclusive society.</p>
                    <div class="mission-points">
                        <div class="point">
                            <i class="fas fa-hands-helping"></i>
                            <div>
                                <h4>Community Service</h4>
                                <p>Providing essential services to underserved communities</p>
                            </div>
                        </div>
                        <div class="point">
                            <i class="fas fa-graduation-cap"></i>
                            <div>
                                <h4>Education</h4>
                                <p>Promoting education and skill development programs</p>
                            </div>
                        </div>
                        <div class="point">
                            <i class="fas fa-heart"></i>
                            <div>
                                <h4>Healthcare</h4>
                                <p>Ensuring access to quality healthcare for all</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="about-image">
                    <img src="https://satrangisalaam.in/img/gallery/2.jpg" alt="Community Unity and Diversity">
                </div>
            </div>
        </div>
    </section>

    <!-- Dynamic Latest Announcement Section -->
    <?php if ($result->num_rows > 0): ?>
        <?php $row = $result->fetch_assoc(); ?>
        <section class="latest-announcement">
            <div class="container">
                <div class="announcement-spotlight">
                    <div class="section-header">
                        <h2 id="latest-announcement-title">Latest Announcement</h2>
                    </div>
                    <div class="announcement-content-grid">
                        <div class="announcement-text">
                            <div class="announcement-meta">
                                <div class="announcement-date">
                                    <i class="fas fa-calendar-alt"></i>
                                    <?php
                                    $date = DateTime::createFromFormat('Y-m-d H:i:s', $row['created_at']);
                                    echo $date->format('F d, Y');
                                    ?>
                                </div>
                            </div>
                            <h3 class="announcement-title"><?= htmlspecialchars($row['title']) ?></h3>
                            <div class="announcement-excerpt">
                                <?= nl2br(htmlspecialchars($row['content'])) ?>
                            </div>
                            <div class="announcement-actions">
                                <a href="public/announcements" class="btn btn-primary">Read Full Article</a>
                                <a href="public/announcements" class="btn btn-outline">View All News</a>
                            </div>
                        </div>
                        <div class="announcement-images">
                            <?php
                            if (!empty($row['images'])) {
                                $images = explode(',', $row['images']);
                                $imageCount = 0;
                                foreach ($images as $image) {
                                    $image = trim($image);
                                    if (!empty($image) && $imageCount < 3) {
                                        $image = preg_replace('/^\.\.\//', '', $image);
                                        echo "<div class='announcement-image'>
                                                <img src='" . htmlspecialchars($image) . "' alt='Announcement Image'>
                                              </div>";
                                        $imageCount++;
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Donate Section -->
    <section id="donate" class="donate">
        <div class="container">
            <div class="donate-content">
                <h2>Support Our Cause</h2>
                <p>Your contribution helps us create positive change in communities across India</p>
                <div class="donate-options">
                    <div class="donate-card">
                        <h3>₹51</h3>
                        <p>Spread Awareness</p>
                        <a href="donate" class="btn btn-primary">Donate</a>
                    </div>
                    <div class="donate-card featured">
                        <h3>₹101</h3>
                        <p>Cleaner Environment</p>
                        <a href="donate" class="btn btn-primary">Donate</a>
                    </div>
                    <div class="donate-card">
                        <h3>₹501</h3>
                        <p>Make a Difference</p>
                        <a href="donate" class="btn btn-primary">Donate</a>
                    </div>
                </div>
                <div class="custom-amount">
                    <input type="number" placeholder="Enter custom amount" id="customAmount">
                    <a href="donate" class="btn btn-secondary">Donate Custom Amount</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Preview Section -->
    <section class="gallery-preview">
        <div class="container">
            <div class="section-header">
                <h2 id="gallery-title">Our Impact in Pictures</h2>
                <p>See how we're making a difference in communities across India</p>
            </div>
            <div class="gallery-showcase">
                <div class="gallery-main">
                    <div class="slideshow-container">
                        <div class="gallery-slide active">
                            <img src="img/banner1.jpg" alt="Food Distribution">
                            <div class="slide-caption">
                                <h4>Gender Sensitization Workshop</h4>
                            </div>
                        </div>
                        <div class="gallery-slide">
                            <img src="img/banner3.jpg" alt="Health Camp">
                            <div class="slide-caption">
                                <h4>Go-Green Workshop</h4>
                            </div>
                        </div>
                        <div class="gallery-slide">
                            <img src="img/banner2.jpg" alt="Education Program">
                            <div class="slide-caption">
                                <h4>Gender Sensitization Workshop</h4>
                            </div>
                        </div>
                        <button class="slide-nav prev" onclick="changeSlide(-1)">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="slide-nav next" onclick="changeSlide(1)">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <div class="gallery-thumbnails">
                    <div class="thumbnail-item">
                        <img src="img/gallery/19.jpg" alt="Community Event">
                        <div class="thumbnail-overlay">
                            <span>Community Unity Rally</span>
                        </div>
                    </div>
                    <div class="thumbnail-item">
                        <img src="img/gallery/3.jpg" alt="Women Empowerment">
                        <div class="thumbnail-overlay">
                            <span>Women Empowerment Workshop</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="gallery-action">
                <a href="public/gallery" class="btn btn-primary">
                    <i class="fas fa-images"></i> View Full Gallery
                </a>
            </div>
        </div>
    </section>

    <!-- Events Section -->
    <section id="events" class="events">
        <div class="container">
            <div class="section-header">
                <h2>Recent Events</h2>
                <p>See our latest activities and community initiatives</p>
            </div>
            <div class="events-grid">
                <?php if ($events_result->num_rows > 0): ?>
                    <?php while($event = $events_result->fetch_assoc()): ?>
                        <div class="event-card">
                            <div class="event-image">
                                <?php
                                $images = explode(',', $event['event_image']);
                                $first_image = !empty($images[0]) ? trim($images[0]) : '';
                                if (!empty($first_image)) {
                                    // Remove "../" prefix if it exists
                                    $first_image = preg_replace('/^\.\.\//', '', $first_image);
                                    echo '<img src="' . htmlspecialchars($first_image) . '" alt="' . htmlspecialchars($event['event_name']) . '">';
                                } else {
                                    echo '<img src="https://satrangisalaam.in/img/xsatrangiSalaamLogo.png.pagespeed.ic.aPMdLDId15.png" alt="Satrangi Salaam Logo">';
                                }
                                ?>
                            </div>
                            <div class="event-content">
                                <div class="event-date">
                                    <?php
                                    $date = DateTime::createFromFormat('Y-m-d', $event['event_date']);
                                    echo $date ? $date->format('F d, Y') : $event['event_date'];
                                    ?>
                                </div>
                                <h3><?= htmlspecialchars($event['event_name']) ?></h3>
                                <p>
                                    <?php
                                    $description = htmlspecialchars($event['event_description']);
                                    echo strlen($description) > 63 ? substr($description, 0, 63) . '...' : $description;
                                    ?>
                                </p>
                                <div class="event-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?= htmlspecialchars($event['event_location']) ?>
                                </div>
                                <a href="public/events" class="btn btn-outline">Read More</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <!-- Fallback content if no events in database -->
                    
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Join Us Section --> <hr />
    <section id="join" class="join-us">
        <div class="container">
            <div class="join-content">
                <h2>Join Our Movement</h2>
                <p>Be part of the change you want to see in society</p>
                <div class="join-options">
                    <div class="join-card">
                        <i class="fas fa-users"></i>
                        <h3>Become a Member</h3>
                        <p>Join our association and contribute to community development</p>
                        <button class="btn btn-primary">Apply for Membership</button>
                    </div>
                    <div class="join-card">
                        <i class="fas fa-hand-holding-heart"></i>
                        <h3>Volunteer</h3>
                        <p>Dedicate your time and skills to help those in need</p>
                        <button class="btn btn-primary">Become a Volunteer</button>
                    </div>
                    <div class="join-card">
                        <i class="fas fa-handshake"></i>
                        <h3>Partner with Us</h3>
                        <p>Collaborate with us to amplify our impact</p>
                        <button class="btn btn-primary">Partnership Inquiry</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact">
        <div class="container">
            <div class="contact-grid">
                <div class="contact-info">
                    <h2>Get in Touch</h2>
                    <p>Reach out to us for any inquiries or support</p>
                    <div class="contact-details">
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <h4>Address</h4>
                                <p>915 Shahpur urf Peepalgaon, Jhalwa, Sadar<br>Prayagraj, Uttar Pradesh, India - 211012</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <div>
                                <h4>Phone</h4>
                                <p>+91 94554 39320<br>+91 87653 72798</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <h4>Email</h4>
                                <p>satrangisalamss@gmail.com<br><a href='https://chat.whatsapp.com/Jifh0MGROxAJQD4bueRFN0'>whatsapp</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="contact-form">
                    <form action="includes/contactUs_process.php" method="post">
                        <div class="form-group">
                            <input type="text" name="name" placeholder="Your Name" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" placeholder="Your Email" required>
                        </div>
                        <div class="form-group">
                            <input type="tel" name="phone" placeholder="Your Phone">
                        </div>
                        <div class="form-group">
                            <select name="subject">
                                <option value="">Select Subject</option>
                                <option value="general">General Inquiry</option>
                                <option value="volunteer">Volunteer Opportunity</option>
                                <option value="donation">Donation</option>
                                <option value="partnership">Partnership</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <textarea name="message" placeholder="Your Message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="container">
            <div class="section-header">
                <h2>Find Us</h2>
            </div>
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3601.9987654321!2d81.85123456789!3d25.456789012345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x398534c9b20bd49f%3A0xa2237856ad4041a!2s915%20Shahpur%20urf%20Peepalgaon%2C%20Jhalwa%2C%20Sadar%2C%20Prayagraj%2C%20Uttar%20Pradesh%20211012%2C%20India!5e0!3m2!1sen!2sin!4v1703089234567!5m2!1sen!2sin" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php
    include "includes/footer.php"
    ?>

    <script src="js/script.js"></script>
</body>
</html>
<?php $conn->close(); ?>
