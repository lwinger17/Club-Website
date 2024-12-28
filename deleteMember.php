<?php
header('Content-Type: application/json');

//database connection
require_once('db_config.php');

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed."]);
    exit();
}

// Get memberID from the POST request
$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['memberID'])) {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
    exit();
}

$memberID = $conn->real_escape_string($data['memberID']);

// being transaction 
$conn->begin_transaction();

try {
    // delete the member from `memberOrder` table where member_id matches
    $sqlDeleteOrder = "DELETE FROM memberOrder WHERE member_id = $memberID";
    if (!$conn->query($sqlDeleteOrder)) {
        throw new Exception("Error deleting from memberOrder: " . $conn->error);
    }

    // delete the member from `members` table where ID matches
    $sqlDeleteMember = "DELETE FROM members WHERE ID = $memberID";
    if (!$conn->query($sqlDeleteMember)) {
        throw new Exception("Error deleting from members: " . $conn->error);
    }

    // commit the transaction
    $conn->commit();

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    // rollback the transaction on error
    $conn->rollback();
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}

$conn->close();
?>
