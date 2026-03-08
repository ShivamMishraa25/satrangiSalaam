<?php
session_start();

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/password_reset.php';

$token = trim($_GET['token'] ?? ($_POST['token'] ?? ''));
$token = preg_replace('/\s+/', '', $token);

$error = '';
$notice = '';

$resetRow = null;
if ($token !== '') {
    try {
        $resetRow = satrangi_find_valid_reset($conn, $token);
    } catch (Throwable $e) {
        error_log('Reset lookup error: ' . $e->getMessage());
        $resetRow = null;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = (string)($_POST['password'] ?? '');
    $confirm = (string)($_POST['confirm_password'] ?? '');

    if ($token === '' || !$resetRow) {
        $error = 'This reset link is invalid or has expired.';
    } elseif (strlen($newPassword) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif ($newPassword !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        try {
            satrangi_consume_reset_and_update_password($conn, (int)$resetRow['reset_id'], (int)$resetRow['user_id'], $newPassword);
            $_SESSION = [];
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
            }
            session_destroy();

            $notice = 'Your password has been reset. You can now log in.';
        } catch (Throwable $e) {
            error_log('Password reset consume error: ' . $e->getMessage());
            $error = 'Something went wrong. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reset Password</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rozha+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/pages.css">
    <style>
        button { background-color: green; color: white; }
        .msg { background: #fff; color: #000; padding: 10px; border-radius: 6px; display: inline-block; }
        .msg.error { border: 1px solid #c0392b; }
        .msg.notice { border: 1px solid #27ae60; }

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
        <h2 class="section-title">Reset Password</h2>

        <?php if ($error): ?>
            <div class="msg error"><?php echo htmlspecialchars($error); ?></div>
            <br><br>
        <?php endif; ?>

        <?php if ($notice): ?>
            <div class="msg notice"><?php echo htmlspecialchars($notice); ?></div>
            <br><br>
        <?php else: ?>
            <?php if (!$resetRow): ?>
                <div class="msg error">This reset link is invalid or has expired.</div>
                <br><br>
                <a href="forgot_password" class="btn-highlight">Request a new link</a>
            <?php else: ?>
                <form method="POST" autocomplete="off">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">
                    <label>New Password: 
                        <span class="pw-field">
                            <input id="new-password" type="password" name="password" required>
                            <button type="button" class="pw-toggle" data-toggle="new-password" aria-label="Show password" title="Show password">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path d="M2 12C3.8 7.5 7.3 5 12 5C16.7 5 20.2 7.5 22 12C20.2 16.5 16.7 19 12 19C7.3 19 3.8 16.5 2 12Z" stroke="currentColor" stroke-width="1.8"/>
                                    <path d="M12 15.2C10.2 15.2 8.8 13.8 8.8 12C8.8 10.2 10.2 8.8 12 8.8C13.8 8.8 15.2 10.2 15.2 12C15.2 13.8 13.8 15.2 12 15.2Z" stroke="currentColor" stroke-width="1.8"/>
                                </svg>
                            </button>
                        </span>
                    </label><br>
                    <label>Confirm Password: 
                        <span class="pw-field">
                            <input id="confirm-password" type="password" name="confirm_password" required>
                            <button type="button" class="pw-toggle" data-toggle="confirm-password" aria-label="Show password" title="Show password">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path d="M2 12C3.8 7.5 7.3 5 12 5C16.7 5 20.2 7.5 22 12C20.2 16.5 16.7 19 12 19C7.3 19 3.8 16.5 2 12Z" stroke="currentColor" stroke-width="1.8"/>
                                    <path d="M12 15.2C10.2 15.2 8.8 13.8 8.8 12C8.8 10.2 10.2 8.8 12 8.8C13.8 8.8 15.2 10.2 15.2 12C15.2 13.8 13.8 15.2 12 15.2Z" stroke="currentColor" stroke-width="1.8"/>
                                </svg>
                            </button>
                        </span>
                    </label><br>
                    <button type="submit">Reset password</button>
                </form>
            <?php endif; ?>
        <?php endif; ?>

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
    <script>
        (function() {
            var buttons = document.querySelectorAll('.pw-toggle[data-toggle]');
            if (!buttons || !buttons.length) return;
            buttons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var id = btn.getAttribute('data-toggle');
                    var input = document.getElementById(id);
                    if (!input) return;
                    var showing = input.type === 'text';
                    input.type = showing ? 'password' : 'text';
                    btn.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
                    btn.title = showing ? 'Show password' : 'Hide password';
                });
            });
        })();
    </script>
</body>
</html>
<?php $conn->close(); ?>
