<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
</head>
<body>
    <h1>Add Event</h1>
    <form action="add_event.php" method="POST" enctype="multipart/form-data">
        <label for="title">Event Title:</label><br>
        <input type="text" name="title" id="title" required><br><br>

        <label for="description">Description:</label><br>
        <textarea name="description" id="description" required></textarea><br><br>

        <label for="date">Event Date:</label><br>
        <input type="date" name="date" id="date"><br><br>

        <label for="location">Event Location:</label><br>
        <input type="text" name="location" id="location"><br><br>

        <label for="images">Upload Images (Optional):</label><br>
        <input type="file" name="images[]" id="images" multiple accept="image/*"><br><br>

        <button type="submit">Submit</button>
    </form>
</body>
</html>