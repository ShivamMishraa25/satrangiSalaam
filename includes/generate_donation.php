<?php
include 'db.php';
require('fpdf/fpdf.php');

if (isset($_GET['donation_id'])) {
    $donation_id = $_GET['donation_id'];

    // Fetch the donation details
    $query = "SELECT * FROM donations WHERE id = '$donation_id'";
    $result = mysqli_query($conn, $query);
    $donation = mysqli_fetch_assoc($result);

    if ($donation) {
        // Step 1: Update the donation status and assign a certificate number
        $query = "UPDATE donations SET status = 'approved', certificate_no = (SELECT IFNULL(MAX(certificate_no) + 1, 1) FROM donations WHERE status = 'approved') WHERE id = '$donation_id'";
        mysqli_query($conn, $query);

        // Step 2: Fetch the updated certificate number from the database
        $query = "SELECT certificate_no FROM donations WHERE id = '$donation_id'";
        $result = mysqli_query($conn, $query);
        $updated_donation = mysqli_fetch_assoc($result);
        $certificate_no = $updated_donation['certificate_no'];

        // Step 3: Generate the certificate PDF
        $pdf = new FPDF('L');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        
        // Add the bg
        $pdf->Image('../img/cert/donationCert.png', 0, 0, 297, 210, '', '');

        // Add the name
        $pdf->SetFont('Arial', '', 18);
        $pdf->SetXY(100, 84.5);
        $pdf->Cell(160, 10, $donation['name'], 0, 1, 'C');

        // Add the amount
        $pdf->SetFont('Arial', '', 17);
        $pdf->SetXY(89.7, 95.5);
        $pdf->Cell(29, 8, $donation['amount'], 0, 0, 'C');
        
        // Add the certificate number
        $pdf->SetFont('Arial', '', 14);
        $pdf->SetXY(54, 45.2);
        $pdf->Cell(40, 5, 'DON-' . $certificate_no, 0, 1, 'L');

        // todays date
        $pdf->SetFont('Arial', '', 14);
        $formatted_date = date('d-m-Y');
        $pdf->SetXY(35, 55);
        $pdf->Cell(40, 5, $formatted_date, 0, 1, 'L');

        // Format the date to 'd-m-Y'
        $pdf->SetFont('Arial', '', 17);
        $pdf->SetXY(204, 94.5);
        $formatted_date = date('d-m-Y', strtotime($donation['created_at']));
        $pdf->Cell(35, 10, $formatted_date, 0, 1, 'L');
        
        // Output the PDF and force download
        $pdf->Output('D', 'Donation_Certificate_' . $certificate_no . '.pdf');
        exit;
    } else {
        echo "Invalid donation ID!";
    }
} else {
    echo "No donation ID provided!";
}
?>
