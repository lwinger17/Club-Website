<?php
header('Content-Type: application/json');

//database connection
require_once('db_config.php');

//establish connection to database
$conn = new mysqli($host, $username, $password, $dbname);
//check for errors
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed."]);
    exit();
}

// decode the incoming JSON request
$data = json_decode(file_get_contents("php://input"), true);
// validate data
if (!$data || !is_array($data)) {
    echo json_encode(["success" => false, "message" => "Invalid request data."]);
    exit();
}

try {
    // begin a transaction
    $conn->begin_transaction();

    // set all `display_order` values to negatives
    // this makes sure there are no duplicates trying to override each other when saved
    foreach ($data as $item) {
        // escape the `member_id` to prevent sql injection
        $member_id = $conn->real_escape_string($item['member_id']);
         // set `display_order` to a negative value
        $temporary_order = -($item['display_order']); 
        // prepare sql
        $sql = "UPDATE memberOrder SET display_order = $temporary_order WHERE member_id = $member_id";
        // execute sql and check for errors
        if (!$conn->query($sql)) {
            throw new Exception("Error during temporary update: " . $conn->error);
        }
    }

    // update with new `display_order` values
    foreach ($data as $item) {
        // escape the `member_id` to prevent sql injection
        $member_id = $conn->real_escape_string($item['member_id']);
        $display_order = $conn->real_escape_string($item['display_order']);
        // prepare query
        $sql = "UPDATE memberOrder SET display_order = $display_order WHERE member_id = $member_id";
        // execute query
        if (!$conn->query($sql)) {
            throw new Exception("Error during final update: " . $conn->error);
        }
    }

    // Commit the transaction
    $conn->commit();
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    // roll back the transaction if an error occurs to maintain database consistency
    $conn->rollback();
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}

// close the connection to free resources
$conn->close();
?>
