<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $email = $_POST['email'];
    $whatsapp = $_POST['whatsapp'];

    // Check the database connection
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    $stmt = $conn->prepare("INSERT INTO participation (name, event_name, event_date, email, whatsapp, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $event_name, $event_date, $email, $whatsapp, $status); // "s" for string

    $status = 'pending'; // Set the status

    if (!$stmt->execute()) {
        die("Error inserting record: " . $stmt->error); // Display the specific error
    }

    $stmt->close();
    $conn->close();

    header("Location: thankYou_form");
    exit;
}
?>
