<?php
include 'db.php';
require('fpdf/fpdf.php');

// Check if certificate_no is provided
if (isset($_GET['certificate_no'])) {
    $certificate_no = $_GET['certificate_no'];

    // Fetch the Outstanding Performance certificate details
    $query = "SELECT * FROM performance WHERE certificate_no = '$certificate_no'";
    $result = mysqli_query($conn, $query);
    $certificate = mysqli_fetch_assoc($result);

    if ($certificate) {
        // Step 1: Generate the certificate PDF
        $pdf = new FPDF('L');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // Add the bg
        $pdf->Image('../img/cert/performanceCert.png', 0, 0, 297, 210, '', '');

        // Add the performer's name
        $pdf->SetFont('Arial', '', 15);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetXY(96, 69);
        $pdf->Cell(169, 10, $certificate['name'], 0, 1, 'C');

        // Add the performer's work
        $pdf->SetFont('Arial', '', 14);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetXY(26, 91);
        $pdf->MultiCell(250, 8, $certificate['works'], 0, 'L');

        // ID Number
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(60, 50);
        $pdf->Cell(40, 5, 'PER-' . $certificate['id'], 0, 1, 'L');

        // date
        $pdf->SetFont('Arial', '', 12);
        $formatted_date = date('d-m-Y');
        $pdf->SetXY(40, 60.5);
        $pdf->Cell(40, 5, $formatted_date, 0, 1, 'L');
        
        // Output the PDF and force download
        $pdf->Output('D', 'Outstanding_Performance_Certificate_' . $certificate_no . '.pdf');
        exit;
    } else {
        echo "Invalid certificate number!";
    }
} else {
    echo "No certificate number provided!";
}
?>
