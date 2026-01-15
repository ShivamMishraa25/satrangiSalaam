<?php
include 'db.php';
require('fpdf/fpdf.php');

// Check if certificate_no is provided
if (isset($_GET['certificate_no'])) {
    $certificate_no = $_GET['certificate_no'];

    // Fetch the collaboration certificate details
    $query = "SELECT * FROM collaboration_certificates WHERE certificate_no = '$certificate_no'";
    $result = mysqli_query($conn, $query);
    $certificate = mysqli_fetch_assoc($result);

    if ($certificate) {
        // Step 1: Generate the certificate PDF
        $pdf = new FPDF('L');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // Add the bg
        $pdf->Image('../img/cert/collaborationCert.png', 0, 0, 297, 210, '', '');
        
        // Add the name
        $pdf->SetFont('Arial', '', 15);
        $pdf->SetXY(76, 66);
        $pdf->Cell(190, 10, $certificate['name'], 0, 1, 'C');

        // Add the works
        $pdf->SetFont('Arial', '', 14);
        $pdf->SetXY(21.7, 81.8);
        $pdf->MultiCell(250, 8, $certificate['works'], 0, 'L');
        
        // ID Number
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(42, 55.2);
        $pdf->Cell(40, 5, 'COL-' . $certificate['id'], 0, 1, 'L');

        // date
        $pdf->SetFont('Arial', '', 12);
        $formatted_date = date('d-m-Y');
        $pdf->SetXY(30, 61.7);
        $pdf->Cell(40, 5, $formatted_date, 0, 1, 'L');
        
        // Output the PDF and force download
        $pdf->Output('D', 'Collaboration_Certificate_' . $certificate_no . '.pdf');
        exit;
    } else {
        echo "Invalid certificate number!";
    }
} else {
    echo "No certificate number provided!";
}
?>
