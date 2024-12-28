<?php

// Database credentials (REPLACE WITH YOUR ACTUAL CREDENTIALS -  DO NOT HARDCODE IN PRODUCTION!)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ClubDatabase";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the DELETE statement.  Use with extreme caution!
$sql = "DELETE FROM events";
if ($conn->query($sql) === TRUE) {
    echo "All events deleted successfully";
} else {
    echo "Error deleting events: " . $conn->error;
}

$conn->close();

?>
