<?php
// Database credentials
$host = "localhost";
$username = "root";
$password = "";
$dbname = "ClubDatabase";

// Create a database connection
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    $response = array("status" => "error", "message" => "Database connection failed: " . $conn->connect_error);
    echo json_encode($response);
    exit();
}


// Get data from the POST request (using JSON)
$data = json_decode(file_get_contents('php://input'), true);

//Sanitize and validate data (crucial for security!)  Example:
$eventId = isset($data['id']) ? filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT) : null;
$eventTitle = isset($data['title']) ? filter_var($data['title'], FILTER_SANITIZE_STRING) : null;
$eventDesc = isset($data['shortDesc']) ? filter_var($data['shortDesc'], FILTER_SANITIZE_STRING) : null;
$eventDetailedDesc = isset($data['detailedDesc']) ? filter_var($data['detailedDesc'], FILTER_SANITIZE_STRING) : null;
$eventTime = isset($data['time']) ? filter_var($data['time'], FILTER_SANITIZE_STRING) : null;
$eventType = isset($data['type']) ? filter_var($data['type'], FILTER_SANITIZE_STRING) : null;
$eventDate = isset($data['date']) ? filter_var($data['date'], FILTER_SANITIZE_STRING) : null;


// Handle image upload (Improved security and error handling, handles optional image)
$eventImg = null;
$target_dir = "scheduleMEDIA/";

if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Basic image validation
    $check = getimagesize($_FILES["photo"]["tmp_name"]);
    if ($check !== false) {
        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $response = array("status" => "error", "message" => "Only JPG, JPEG, PNG & GIF files are allowed.");
            echo json_encode($response);
            exit();
        } else {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                $eventImg = $target_file;
            } else {
                $response = array("status" => "error", "message" => "Error uploading your file.");
                echo json_encode($response);
                exit();
            }
        }
    } else {
        $response = array("status" => "error", "message" => "File is not an image.");
        echo json_encode($response);
        exit();
    }
}


// Prepare and execute the SQL UPDATE query (using prepared statements)
$sql = "UPDATE events SET event_title=?, event_desc=?, event_detailed_desc=?, event_time=?, event_type=?, event_date=?";
if ($eventImg !== null) {
    $sql .= ", event_img=?";
}
$sql .= " WHERE ID=?";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    $response = array("status" => "error", "message" => "Error preparing statement: " . $conn->error);
    echo json_encode($response);
    exit();
}

if ($eventImg !== null) {
    $stmt->bind_param("sssssssi", $eventTitle, $eventDesc, $eventDetailedDesc, $eventTime, $eventType, $eventDate, $eventImg, $eventId);
} else {
    $stmt->bind_param("sssssss", $eventTitle, $eventDesc, $eventDetailedDesc, $eventTime, $eventType, $eventDate, $eventId);
}

if ($stmt->execute()) {
    $response = array("status" => "success", "message" => "Event updated successfully.");
} else {
    $response = array("status" => "error", "message" => "Error updating event: " . $stmt->error);
}

echo json_encode($response);

$stmt->close();
$conn->close();
?>
