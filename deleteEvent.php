<?php
$host = "localhost";
$username = "root";
$password = ""; 
$dbname = "ClubDatabase";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete']) && isset($_POST['event_id'])) {
    $event_id = $_POST['event_id'];
    
    // Ensure the ID is an integer to prevent SQL injection
    $event_id = (int)$event_id;

    // SQL to delete the event
    $sql = "DELETE FROM events WHERE ID = $event_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: adminEventEdit.php?success=1");
        exit();
    } else {
        echo "Error deleting event: " . $conn->error;
    }
}

$conn->close();
?>

