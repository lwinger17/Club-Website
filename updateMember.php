<?php
require_once('db_config.php');

// connection to database
$conn = new mysqli($host, $username, $password, $dbname);


//check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// retrive form data submitted via POST method
$id = $_POST['id'];
$name = $_POST['name'];
$bio = $_POST['bio'];

//check if a photo file has been uplaoded
if (!empty($_FILES['photo']['tmp_name'])) {
    // if a photo has been updated, process the file by reading contents
    $photo = addslashes(file_get_contents($_FILES['photo']['tmp_name']));

    //update everything including the photo
    $sql = "UPDATE members SET member_name='$name', member_bio='$bio', member_img='$photo' WHERE ID=$id";
} else {

    //if no photo, only update other stuff
    $sql = "UPDATE members SET member_name='$name', member_bio='$bio' WHERE ID=$id";
}

//execute query and check if successful
if ($conn->query($sql) === TRUE) {
    echo "Member updated successfully.";
} else {
    echo "Error updating member: " . $conn->error;
}

//close up data connection to free resources
$conn->close();
?>
