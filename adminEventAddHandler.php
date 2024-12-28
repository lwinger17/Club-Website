<?php

require_once('db_config.php');

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $desc = $conn->real_escape_string($_POST['desc']);
    $detailedDesc = $conn->real_escape_string($_POST['detailedDesc']);
    $time = $conn->real_escape_string($_POST['time']);
    $eventType = $conn->real_escape_string($_POST['eventType']);
    $date = $conn->real_escape_string($_POST['date']);
    $imagePath = null; // Initialize imagePath to NULL

    // Handle image upload (only if a file was uploaded)
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "scheduleMEDIA/"; 
        $tempFile = $_FILES['photo']['tmp_name'];
        $targetFile = $uploadDir . basename($_FILES['photo']['name']);

        $check = getimagesize($tempFile);
        if ($check !== false) {
            if (move_uploaded_file($tempFile, $targetFile)) {
                $imagePath = $targetFile;
            } else {
                echo "Error moving uploaded file.";
                exit;
            }
        } else {
            echo "File is not an image.";
            exit;
        }
    }

    // Use prepared statement
    $stmt = $conn->prepare("INSERT INTO events (event_title, event_desc, event_detailed_desc, event_time, event_type, event_img, event_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    $stmt->bind_param("sssssss", $title, $desc, $detailedDesc, $time, $eventType, $imagePath, $date);

    if ($stmt->execute()) {
        echo "New event added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();

?>