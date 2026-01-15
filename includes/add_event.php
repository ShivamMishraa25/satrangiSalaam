<?php
// Include database connection
include dirname(includes) . '/db.php'; // Adjust the path if db.php is elsewhere

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $title = $conn->real_escape_string($_POST['title']);
    $event_date = $conn->real_escape_string($_POST['event_date']);
    $location = $conn->real_escape_string($_POST['location']);
    $description = $conn->real_escape_string($_POST['description']);

    // Validate required fields
    if (empty($title) || empty($event_date) || empty($location) || empty($description)) {
        die("Please fill in all required fields.");
    }

    // Insert event data into the database
    $query = "INSERT INTO events (title, event_date, location, description) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $title, $event_date, $location, $description);

    if ($stmt->execute()) {
        $event_id = $stmt->insert_id; // Get the ID of the newly inserted event

        // Handle image uploads
        if (!empty($_FILES['images']['name'][0])) {
            $upload_dir = 'uploads/events/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                $file_name = basename($_FILES['images']['name'][$key]);
                $target_path = $upload_dir . uniqid() . '_' . $file_name;

                // Validate and move image
                $check = getimagesize($tmp_name);
                if ($check !== false) {
                    if (move_uploaded_file($tmp_name, $target_path)) {
                        // Insert image path into the event_images table
                        $conn->query("INSERT INTO event_images (event_id, image_path) VALUES ('$event_id', '$target_path')");
                    } else {
                        echo "Failed to upload $file_name.<br>";
                    }
                } else {
                    echo "$file_name is not a valid image.<br>";
                }
            }
        }

        echo "Event added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>