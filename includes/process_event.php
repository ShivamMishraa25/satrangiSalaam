<?php
session_start();
if (!isset($_SESSION['moderator_logged_in'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize input data
    $event_name = trim($_POST['event_name']);
    $event_date = trim($_POST['event_date']);
    $event_location = trim($_POST['event_location']);
    $event_description = trim($_POST['event_description']);
    
    // Basic validation
    $errors = [];
    
    if (empty($event_name)) {
        $errors[] = "Event name is required.";
    } elseif (strlen($event_name) > 255) {
        $errors[] = "Event name must be less than 255 characters.";
    }
    
    if (empty($event_date)) {
        $errors[] = "Event date is required.";
    } elseif (!DateTime::createFromFormat('Y-m-d', $event_date)) {
        $errors[] = "Invalid date format.";
    }
    
    if (empty($event_location)) {
        $errors[] = "Event location is required.";
    } elseif (strlen($event_location) > 255) {
        $errors[] = "Event location must be less than 255 characters.";
    }
    
    if (empty($event_description)) {
        $errors[] = "Event description is required.";
    } elseif (strlen($event_description) > 5000) {
        $errors[] = "Event description must be less than 5000 characters.";
    }
    
    // Handle image uploads
    $uploaded_images = [];
    if (isset($_FILES['event_image']) && !empty($_FILES['event_image']['name'][0])) {
        $target_dir = "../uploads/events/";
        
        // Create directory if it doesn't exist
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $max_file_size = 5 * 1024 * 1024; // 5MB
        
        foreach ($_FILES['event_image']['tmp_name'] as $key => $tmp_name) {
            if (!empty($tmp_name)) {
                $file_name = $_FILES['event_image']['name'][$key];
                $file_size = $_FILES['event_image']['size'][$key];
                $file_type = $_FILES['event_image']['type'][$key];
                $file_error = $_FILES['event_image']['error'][$key];
                
                // Validate file
                if ($file_error !== UPLOAD_ERR_OK) {
                    $errors[] = "Error uploading file: " . htmlspecialchars($file_name);
                    continue;
                }
                
                if (!in_array($file_type, $allowed_types)) {
                    $errors[] = "Invalid file type for: " . htmlspecialchars($file_name) . ". Only JPEG, PNG, and GIF allowed.";
                    continue;
                }
                
                if ($file_size > $max_file_size) {
                    $errors[] = "File too large: " . htmlspecialchars($file_name) . ". Maximum size is 5MB.";
                    continue;
                }
                
                // Generate unique filename
                $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $unique_name = uniqid() . "_" . time() . "." . $file_extension;
                $target_file = $target_dir . $unique_name;
                
                if (move_uploaded_file($tmp_name, $target_file)) {
                    $uploaded_images[] = "../uploads/events/" . $unique_name;
                } else {
                    $errors[] = "Failed to upload file: " . htmlspecialchars($file_name);
                }
            }
        }
    }
    
    // If no errors, insert into database
    if (empty($errors)) {
        try {
            // Prepare the image string
            $image_string = implode(',', $uploaded_images);
            
            // Use prepared statement to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO events (event_name, event_date, event_location, event_description, event_image) VALUES (?, ?, ?, ?, ?)");
            
            if ($stmt) {
                $stmt->bind_param("sssss", $event_name, $event_date, $event_location, $event_description, $image_string);
                
                if ($stmt->execute()) {
                    $_SESSION['success_message'] = "Event added successfully!";
                    header("Location: moderator.php");
                    exit();
                } else {
                    $_SESSION['error_message'] = "Database error: " . $stmt->error;
                }
                
                $stmt->close();
            } else {
                $_SESSION['error_message'] = "Database preparation error: " . $conn->error;
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Error: " . $e->getMessage();
        }
    } else {
        $_SESSION['error_message'] = implode('<br>', $errors);
    }
    
    header("Location: moderator.php");
    exit();
} else {
    header("Location: moderator.php");
    exit();
}
?>