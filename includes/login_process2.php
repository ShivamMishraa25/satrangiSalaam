<?php
session_start();
require 'db.php'; // Include the correct db connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepared statement to fetch the moderator's data
    $stmt = $conn->prepare("SELECT * FROM moderators WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $moderator = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $moderator['password'])) {
            // Store session data
            $_SESSION['moderator_logged_in'] = true;
            $_SESSION['moderator_id'] = $moderator['id'];
            
            // Add debug message
            echo "Login successful, redirecting to moderator panel...";

            // Redirect to moderator.php
            header("Location: moderator2");
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "Invalid username!";
    }
}
?>
