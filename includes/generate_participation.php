<?php
require_once __DIR__ . '/vendor/autoload.php';
include 'db.php'; // Ensure database connection

if (isset($_GET['participation_id'])) {
    $participation_id = $_GET['participation_id'];

    // Fetch participation details
    $query = "SELECT * FROM participation WHERE id = '$participation_id'";
    $result = mysqli_query($conn, $query);
    $participation = mysqli_fetch_assoc($result);

    if ($participation) {
        // Step 1: Update status and assign a certificate number
        $query = "UPDATE participation SET status = 'approved', 
                  certificate_no = (SELECT IFNULL(MAX(certificate_no) + 1, 1) 
                                    FROM participation WHERE status = 'approved') 
                  WHERE id = '$participation_id'";
        mysqli_query($conn, $query);

        // Step 2: Fetch updated certificate number
        $query = "SELECT certificate_no FROM participation WHERE id = '$participation_id'";
        $result = mysqli_query($conn, $query);
        $updated_participation = mysqli_fetch_assoc($result);
        $certificate_no = $updated_participation['certificate_no'];

        // Step 3: Generate PDF with dynamic data
        $name = $participation['name'];
        $event_name = $participation['event_name'] ?? "Not Specified";

        // collaborators
        $collaborators = $participation['collaborators'] ?? "None"; // Fetch collaborators field
        
        // Format event date as dd/mm/yyyy
        $event_date = (!empty($participation['event_date']) && $participation['event_date'] != "Unknown Date") 
                      ? date("d/m/Y", strtotime($participation['event_date'])) 
                      : "Unknown Date";

        // Generate current date in ddmmyyyy format
        $current_date = date("d/m/Y");

        // Custom Page Size (Landscape)
        $width_mm = (1200 / 96) * 25.4; // ≈ 317.5 mm
        $height_mm = (926 / 96) * 25.4; // ≈ 244.2 mm

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => [$width_mm, $height_mm], // Custom Landscape Size
            'margin_top' => 0,
            'margin_right' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
        ]);

        // CSS for Background and Bottom-Half Positioning
        $stylesheet = "
            body { margin: 0; padding: 0; box-sizing: border-box; display: flex; justify-content: center; align-items: center; height: 100vh; background-image: url('../img/cert/participationCert.png'); background-size: cover; }
            .container { position: relative; width: 100%; height: 100%; color: #333; font-family: Arial; font-size:24px; font-weight: bold;}

            .container div { position: absolute;}

            .cert_no { margin: 72px 69px; width:150px; float: right; font-weight: normal; line-height:1.4; font-size:18; text-align:center;}

            .name { margin:1px auto 0 auto; text-align:center; font-size:38; }
            .event {margin:33px 160px 0 173px; width:865px; display: flex; line-height:1.1;}
            .date { width:680px; margin: 0 0 0 290px; text-align: center; }
            .collaborators { width:865px; margin:28px 0 0 170px; text-align: center;}
        ";

        // HTML with Dynamic Data
        $html = "
        <div class='container'><br>
            <div class='cert_no'>PAR$certificate_no<br>$current_date</div>
            <br><br><br><br><br><br><br><br><br><br><br>
            <div class='name'>$name</div>
            <div class='event'>$event_name</div>
            <div class='date'>$event_date</div>
            <div class='collaborators'>$collaborators</div>
        </div>
        ";

        // Apply CSS and HTML
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($html, 2);

        // Output the PDF
        $mpdf->Output("Participation_Certificate_$certificate_no.pdf", "I");
        exit;
    } else {
        echo "Invalid participation ID!";
    }
} else {
    echo "No participation ID provided!";
}
?>
