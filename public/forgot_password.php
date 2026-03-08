<?php
session_start();

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/password_reset.php';

$notice = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $debug = !empty($_GET['debug']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        try {
            $payload = satrangi_create_password_reset_for_email($conn, $email);
            if (!$payload) {
                $error = 'No account found with that email.';
            } else {
                try {
                    satrangi_send_password_reset_email($payload['email'], $payload['token'], $debug);
                } catch (Throwable $mailError) {
                    // Token was created, but the email did not send; invalidate it to avoid clutter.
                    satrangi_invalidate_password_reset_token($conn, $payload['token']);
                    throw $mailError;
                }
                $_SESSION['forgot_password_notice'] = 'Password reset link sent. Please check your inbox (and Spam).';
                header('Location: forgot_password?sent=1');
                exit();
            }
        } catch (Throwable $e) {
            error_log('Forgot password error: ' . $e->getMessage());
            $error = 'Email could not be sent. Please verify your Safe email credentials and Gmail App Password.';
            if (!empty($_GET['debug'])) {
                $error .= ' Technical: ' . $e->getMessage();
            }
        }
    }
}

if (!empty($_SESSION['forgot_password_notice'])) {
    $notice = $_SESSION['forgot_password_notice'];
    unset($_SESSION['forgot_password_notice']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Forgot Password</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rozha+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/pages.css">
    <style>
        button { background-color: green; color: white; }
        .msg { background: #fff; color: #000; padding: 10px; border-radius: 6px; display: inline-block; }
        .msg.error { border: 1px solid #c0392b; }
        .msg.notice { border: 1px solid #27ae60; }
    </style>
</head>
<body>
    <header>
        <a href="../" class="logo"><img src="../img/satrangiSalaamLogo.png" alt="satrangi Salaam Logo"></a>
        <div class="menu-icon" id="menu-icon"></div>
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
        </nav>
    </header>

    <img id="bird" src="../img/bird.png" alt="Flying Bird">
    <img id="bird2" src="../img/bird2.png" alt="flying bird">

    <section class="content">
        <h2 class="section-title">Forgot Password</h2>

        <?php if ($error): ?>
            <div class="msg error"><?php echo htmlspecialchars($error); ?></div>
            <br><br>
        <?php endif; ?>

        <?php if ($notice): ?>
            <div class="msg notice"><?php echo htmlspecialchars($notice); ?></div>
            <br><br>
        <?php endif; ?>

        <form method="POST" autocomplete="off">
            <label>Email: <input type="email" name="email" required></label><br>
            <button type="submit">Send reset link</button>
        </form>

        <br><br>
        <a href="user_login" class="btn-highlight">Back to login</a>
    </section>

    <footer>
        <div class="darkmode">
            <span>switch to dark/light mode</span>
            <button id="toggle-button" class="toggle-btn"></button>
        </div>
    </footer>

    <script type="text/javascript" src="../js/pages.js"></script>
</body>
</html>
<?php $conn->close(); ?>
