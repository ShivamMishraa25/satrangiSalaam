<?php
require_once __DIR__ . '/vendor/autoload.php';
require 'phpqr1/qrlib.php'; // QR Code Library
include 'db.php'; // Database Connection

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Fetch user details
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => [50, 85], // ID Card Size: 50mm x 85mm (1.97 x 3.35 in)
            'margin_top' => 0,
            'margin_right' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
        ]);

        // Generate QR Code
        $qr_content = "https://satrangisalaam.in/includes/member?id=" . $user_id;
        $qr_dir = "qr_codes/";
        if (!is_dir($qr_dir)) {
            mkdir($qr_dir, 0777, true);
        }
        $qr_filename = $qr_dir . "qr_user_" . $user_id . ".png";
        QRcode::png($qr_content, $qr_filename, QR_ECLEVEL_L, 4);

        // CSS Styles
        $stylesheet = "
            * { padding: 0; margin: 0; box-sizing: border-box; line-height: 1;}
            body { font-family: Arial, sans-serif; text-align: center; }
            .id-card { position: relative; width: 100%; height: 100%; background-size: cover; display: flex; flex-direction: column; align-items: center; justify-content: center; }
            .photo { width: auto; height: 87.5px; margin: 60.5px 0 0 27.5px; }
            .name { margin: 8px 0 0 5px; font-size: 14px;}
            .post { font-size:12px; margin: 0 0 0 5px;}
            .id-number { font-weight: bold; font-size: 9px; margin: 8px 0 0 72px; text-align: left; }
            .phone { font-size: 8px; margin: 2px 0 0 45px; text-align: left;}
            .email { font-size: 8px; margin: 3px 0 0 45px; text-align: left;}
            .city { font-size: 10px; margin: 3.5px 7.5px 0 0; font-weight: bold;}
            .qr-code { width: 53px; height: 53px; margin: 0 7.5px 0 0; }
            
            .id2 { font-size: 5px; margin: 12px 0 0 98px; color: rgb(41, 41, 41); text-align: left; }
            .name2 { font-size: 6px; margin: 0 0 0 37px; color: rgb(41, 41, 41); text-align: left; }
            .post2 { font-size: 6px; margin: 0 0 0 32px; color: rgb(41, 41, 41); text-align: left; }
            .date2 { font-size: 6px; margin: 0 0 0 32px; color: rgb(41, 41, 41); text-align: left; }
            .mob2 { font-size: 6px; margin: 1px 0 0 40px; color: rgb(41, 41, 41); text-align: left; }
            .qr2 { width:26px; height: 26px; margin: 38px 123px 0 0; }
            .date3 { font-size: 7px; font-weight: bold; margin: 150px 5px 0 0; color: white; }
            ";


        // HTML Content for ID Card Front Side
        $html_front = "
        <div class='id-card' style='background-image: url(../img/idPage1.png);'>
            <img src='../" . $user['photo'] . "' class='photo'>
            <h1 class='name'>" . $user['name'] . "</h1>
            <p class='post'>" . $user['post'] . "</p>
            <p class='id-number'>" . $user['id'] . "</p>
            <p class='phone'>+91 ".$user['mobile_no']."</p>
            <p class='email'>".$user['email']."</p>
            <p class='city'>".$user['city']."</p>
            <img src='$qr_filename' class='qr-code' >
        </div>
        ";

        // HTML Content for ID Card Back Side
        $html_back = "
        <div class='id-card' style='background-image: url(../img/idPage2.png); padding: 10px;'>
            <p class='id2'>".$user['id']."</p>
            <p class='name2'>".$user['name']."</p>
            <p class='post2'>".$user['post']."</p>
            <p class='date2'>". date("d-m-Y") . "</p>
            <p class='mob2'>".$user['mobile_no']."</p>
            <img src='$qr_filename' class='qr2'>
            <p class='date3'>". date("d/m/Y") . "</p>
        </div>
        ";

        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($html_front, 2);
        $mpdf->AddPage();
        $mpdf->WriteHTML($html_back, 2);
        
        $mpdf->Output("ID_Card_" . $user['name'] . ".pdf", "D");
    } else {
        echo "User not found!";
    }
} else {
    echo "Invalid request!";
}
?>
