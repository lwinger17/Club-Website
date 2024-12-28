<?php

ob_start(); // Start output buffering
session_start();

// Error reporting settings
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    // Input sanitization and validation
    $eventId = filter_input(INPUT_POST, 'event_id', FILTER_SANITIZE_NUMBER_INT);
    if (!is_numeric($eventId) || $eventId <= 0) {
        error_log("Invalid event ID: " . $eventId); // Log to PHP error log
        $response = ["status" => "error", "message" => "Invalid event ID."];
        echo json_encode($response);
        exit;
    }

    $eventTitle = filter_input(INPUT_POST, 'event_title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $eventDesc = filter_input(INPUT_POST, 'event_desc', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $eventDetailedDesc = filter_input(INPUT_POST, 'event_detailed_desc', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $eventTime = filter_input(INPUT_POST, 'event_time', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $eventType = filter_input(INPUT_POST, 'event_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $eventDate = filter_input(INPUT_POST, 'event_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


    // Handle image upload (optional)
    $target_dir = "scheduleMEDIA/";
    $eventImg = null;

    if (isset($_FILES["event_img"])) {
        $uploadError = $_FILES["event_img"]["error"];
        if ($uploadError === UPLOAD_ERR_OK) {
            $target_file = $target_dir . basename($_FILES["event_img"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Image validation
            $check = getimagesize($_FILES["event_img"]["tmp_name"]);
            if ($check === false) {
                error_log("File is not an image: " . $_FILES["event_img"]["name"]); // Log to PHP error log
                $response = ["status" => "error", "message" => "File is not an image."];
                echo json_encode($response);
                exit;
            }

            // File size check
            if ($_FILES["event_img"]["size"] > 5000000) {
                error_log("File too large: " . $_FILES["event_img"]["name"] . " (" . $_FILES["event_img"]["size"] . " bytes)"); // Log to PHP error log
                $response = ["status" => "error", "message" => "File is too large."];
                echo json_encode($response);
                exit;
            }

            // File type check
            $allowedTypes = ["jpg", "png", "jpeg", "gif"];
            if (!in_array($imageFileType, $allowedTypes)) {
                error_log("Invalid file type: " . $imageFileType . " for file: " . $_FILES["event_img"]["name"]); // Log to PHP error log
                $response = ["status" => "error", "message" => "Invalid file type."];
                echo json_encode($response);
                exit;
            }

            // Move uploaded file
            if (!move_uploaded_file($_FILES["event_img"]["tmp_name"], $target_file)) {
                error_log("Error uploading file: " . $_FILES["event_img"]["name"] . " - " . error_get_last()['message']); // Log to PHP error log
                $response = ["status" => "error", "message" => "Error uploading file."];
                echo json_encode($response);
                exit;
            }
            $eventImg = $target_file;
        } else {

                // Image deletion handling
                $eventId = filter_input(INPUT_POST, 'event_id', FILTER_SANITIZE_NUMBER_INT);
                $initialImgPath = filter_input(INPUT_POST, 'initial_img_path', FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Get initial image path

                if ($eventId && $initialImgPath !== '') {
                    $target_dir = "scheduleMEDIA/"; //Make sure this path is correct
                    $imagePathToDelete = $initialImgPath; //Use the initial image path
            
                    if (file_exists($imagePathToDelete)) {
                        if (unlink($imagePathToDelete)) {
                            error_log("Image deleted successfully: " . $imagePathToDelete);
                            $eventImg = null; // Set eventImg to null to update the database
                        } else {
                            error_log("Error deleting image: " . $imagePathToDelete . " - " . error_get_last()['message']);
                            $response = ["status" => "error", "message" => "Error deleting image."];
                            echo json_encode($response);
                            exit;
                        }
                    } else {
                        error_log("Image file not found: " . $imagePathToDelete);
                        //It's okay if the file doesn't exist; it might have been deleted already.
                    }
                }

         
        }
    } else if (isset($_POST['event_img']) && $_POST['event_img'] === 'null') {
        // Image deletion handling
        $eventId = filter_input(INPUT_POST, 'event_id', FILTER_SANITIZE_NUMBER_INT);
        $initialImgPath = filter_input(INPUT_POST, 'initial_img_path', FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Get initial image path
    
        if ($eventId && $initialImgPath) {
            //Check if the image file exists before attempting to delete it.
            if (file_exists($initialImgPath)) {
                if (unlink($initialImgPath)) {
                    error_log("Image deleted successfully: " . $initialImgPath);
                    $eventImg = null; // Set eventImg to null to update the database
                } else {
                    error_log("Error deleting image: " . $initialImgPath . " - " . error_get_last()['message']);
                    $response = ["status" => "error", "message" => "Error deleting image."];
                    echo json_encode($response);
                    exit;
                }
            } else {
                error_log("Image file not found: " . $initialImgPath);
                $response = ["status" => "error", "message" => "Image file not found."];
                echo json_encode($response);
                exit;
            }
        } else {
            error_log("Error deleting image: Missing event ID or initial image path.");
            $response = ["status" => "error", "message" => "Error deleting image."];
            echo json_encode($response);
            exit;
        }
    }

    // Database connection
    require_once('db_config.php');
    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        error_log("Database connection failed: " . $conn->connect_error); // Log to PHP error log
        $response = ["status" => "error", "message" => "Database connection failed."];
        echo json_encode($response);
        exit;
    }

    try {
        // Update query (prepared statement)
        $sql = "UPDATE events SET event_title=?, event_desc=?, event_detailed_desc=?, event_time=?, event_type=?, event_date=?, event_img=? WHERE ID=?";
    
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Error preparing statement: " . $conn->error);
        }
    
        // Handle null values gracefully using the null coalescing operator
        $eventImg = $eventImg ?? null;
    
        $stmt->bind_param("sssssssi", $eventTitle, $eventDesc, $eventDetailedDesc, $eventTime, $eventType, $eventDate, $eventImg, $eventId);
    
        $stmt->execute();
    
        if ($stmt->affected_rows > 0) {
            $response = ["status" => "success", "message" => "Event updated successfully."];
        } else {
            error_log("Error updating event: " . $stmt->error . " - Affected rows: " . $stmt->affected_rows);
            $response = ["status" => "error", "message" => "Error updating event."];
        }
    } catch (Exception $e) {
        error_log("Database error: " . $e->getMessage());
        $response = ["status" => "error", "message" => "An error occurred."];
    } finally {
        if ($stmt) $stmt->close();
        if ($conn) $conn->close();
    }

    echo json_encode($response);
}

?>