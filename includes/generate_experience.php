<?php
include 'db.php';
require('fpdf/fpdf.php');

if (isset($_GET['experience_id'])) {
    $experience_id = $_GET['experience_id'];

    // Fetch the experience details
    $query = "SELECT * FROM experience WHERE id = '$experience_id'";
    $result = mysqli_query($conn, $query);
    $experience = mysqli_fetch_assoc($result);

    if ($experience) {
        // Step 1: Update the experience status and assign a certificate number
        $query = "UPDATE experience SET status = 'approved', certificate_no = (SELECT IFNULL(MAX(certificate_no) + 1, 1) FROM experience WHERE status = 'approved') WHERE id = '$experience_id'";
        mysqli_query($conn, $query);

        // Step 2: Fetch the updated certificate number from the database
        $query = "SELECT certificate_no FROM experience WHERE id = '$experience_id'";
        $result = mysqli_query($conn, $query);
        $updated_experience = mysqli_fetch_assoc($result);
        $certificate_no = $updated_experience['certificate_no'];

        // Step 3: Generate the certificate PDF
        $pdf = new FPDF('L');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // Add the bg
        $pdf->Image('../img/cert/experienceCert.png', 0, 0, 297, 210, '', '');

        // Add the performer's name
        $pdf->SetFont('Arial', '', 17);
        $pdf->SetXY(81, 74);
        $pdf->Cell(190, 7, $experience['name'], 0, 1, 'C');

        // Add the period
        $pdf->SetFont('Arial', '', 17);
        $pdf->SetXY(173.5, 85);
        $pdf->Cell(99, 8, $experience['period'], 0, 0, 'C');

        // Add the post
        $pdf->SetFont('Arial', '', 17);
        $pdf->SetXY(31, 97);
        $pdf->Cell(90, 8, $experience['post'], 0, 0, 'C');

        // ID Number
        $pdf->SetFont('Arial', '', 14);
        $pdf->SetXY(58, 46.5);
        $pdf->Cell(40, 5, 'EXP-' . $experience['id'], 0, 1, 'L');

        // date
        $pdf->SetFont('Arial', '', 14);
        $formatted_date = date('d-m-Y');
        $pdf->SetXY(36, 55.2);
        $pdf->Cell(40, 5, $formatted_date, 0, 1, 'L');
        
        // Output the PDF and force download
        $pdf->Output('D', 'Experience_Certificate_' . $certificate_no . '.pdf');
        exit;
    } else {
        echo "Invalid experience ID!";
    }
} else {
    echo "No experience ID provided!";
}
?>
