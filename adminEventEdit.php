<?php

require_once("header.php");

session_start();

// Session timeout (20 minutes)
$timeout_duration = 1200; 

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: login.php?timeout=true");
    exit();
}

$_SESSION['last_activity'] = time();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

require_once('db_config.php');

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['delete_event'])) {
        $event_id = $conn->real_escape_string($_POST['event_id']);

        // 1. Retrieve the image path BEFORE deleting the database entry
        $sql_image = "SELECT event_img FROM events WHERE ID = $event_id";
        $result_image = $conn->query($sql_image);
        $imagePath = null; // Initialize imagePath

        if ($result_image && $result_image->num_rows > 0) {
            $row_image = $result_image->fetch_assoc();
            $imagePath = $row_image['event_img'];
        }

        // 2. Delete the event from the database
        $sql = "DELETE FROM events WHERE ID = $event_id";
        if ($conn->query($sql) === TRUE) {
            // 3. Delete the image file ONLY if it exists
            if ($imagePath && file_exists($imagePath)) {
                if (unlink($imagePath)) {
                    // Image deleted successfully
                } else {
                    // Handle the error: Log it, display a message, etc.
                    error_log("Error deleting image file: $imagePath");
                    echo "Error deleting image file. Please check server logs.";
                }
            }
            header("Location: adminEventEdit.php?success=deleted");
            exit();
        } else {
            echo "Error deleting event: " . $conn->error;
        }
    } else {

        $event_id = $_POST['event_id'];
        $event_title = $conn->real_escape_string($_POST['event_title']);
        $event_desc = $conn->real_escape_string($_POST['event_desc']);
        $event_detailed_desc = $conn->real_escape_string($_POST['event_detailed_desc']); // Added detailed description
        $event_time = $conn->real_escape_string($_POST['event_time']); // Added event time
        $event_type = $conn->real_escape_string($_POST['event_type']); // Added event type
        $event_date = $conn->real_escape_string($_POST['event_date']);

        $target_dir = "scheduleMEDIA/"; // Directory to store uploaded images
        $target_file = $target_dir . basename($_FILES["event_img"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Check if image file is an actual image or fake image
        $check = getimagesize($_FILES["event_img"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["event_img"]["size"] > 500000) { // 500KB limit
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["event_img"]["tmp_name"], $target_file)) {
                $event_img = $target_file; // Store the relative path
            } else {
                echo "Sorry, there was an error uploading your file.";
                $event_img = null; //Keep the old image if upload fails.
            }
        }
    }

    $sql = "UPDATE events SET event_title = '$event_title', event_desc = '$event_desc', event_detailed_desc = '$event_detailed_desc', event_time = '$event_time', event_type = '$event_type', event_date = '$event_date'";
    if ($event_img !== null) {
        $sql .= ", event_img = '$event_img'";
    }
    $sql .= " WHERE ID = $event_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: adminEventEdit.php?success=1");
        exit();
    } else {
        echo "Error updating event: " . $conn->error;
    }
}

$sql = "SELECT ID, event_title, event_desc, event_detailed_desc, event_time, event_type, event_img, event_date FROM events";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/editMembers.css">
    </head>
    <body>
        <?php require_once("header.php") ?>

        <div class="admin-page">
            <div class="sidebar">
                <button onclick="window.location.href='orderMembers.php'">
                    <i class="bi bi-list-ul"></i> Order Members
                </button>
                <button onclick="window.location.href='editMembers.php'">
                    <i class="bi bi-pencil-square"></i> Edit Members
                </button>
                <button onclick="window.location.href='addMember.php'">
                    <i class="bi bi-person-plus"></i> Add Member
                </button>
                <button onclick="window.location.href='adminEventAdd.php'">
                    <i class="bi bi-person-plus"></i> Add Event
                </button>
                <button onclick="window.location.href='adminEventEdit.php'">
                    <i class="bi bi-pencil-square"></i> Edit Event
                </button>
                <button onclick="window.location.href='adminSignUp.php'">
                    <i class="bi bi-person-plus"></i> Add User
                </button>
            </div>

                <div class="content">
                    <div class="middle-column">
                        <div class="member-grid">
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <div class="member-grid-item"
                                        onclick="editEvent(
                                            '<?php echo addslashes($row['event_title']); ?>',
                                            '<?php echo addslashes($row['event_desc']); ?>',
                                            '<?php echo addslashes($row['event_detailed_desc']); ?>',
                                            '<?php echo addslashes($row['event_time']); ?>',
                                            '<?php echo addslashes($row['event_type']); ?>',
                                            '<?php echo addslashes($row['event_img']); ?>',
                                            '<?php echo addslashes($row['event_date']); ?>',
                                            <?php echo $row['ID']; ?>)">
                                        <img src="<?php echo $row['event_img']; ?>" alt="Event Photo">
                                        <div class="middle">
                                            <div class="eventName"><?php echo htmlspecialchars($row['event_title']); ?></div>
                                            <div class="eventName"><?php echo htmlspecialchars($row['event_date']); ?></div>
                                        </div>
                                        <form method="POST" action="adminEventEdit.php">
                                            <input type="hidden" name="event_id" value="<?php echo $row['ID']; ?>">
                                            <button type="submit" name="delete_event" onclick="return confirm('Are you sure you want to delete this event?');">Delete</button>
                                        </form>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p>No events found.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="right-column">
                        <h3>Edit Event</h3>
                        <form id=eventEditForm method="POST" action="editEvent.php" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="eventTitle">Title</label>
                                <input type="text" id="eventTitle" name="event_title" value="">
                            </div>
                            <div class="form-group">
                                <label for="eventPhoto">Photo</label>
                                <div class="photo-preview" style="display: none;">  <!-- Initially hidden -->
                                    <img id="photoPreview" src="" alt="">
                                    <span id="deletePhotoButton" class="delete-button">X</span>
                                    <span class="hover-text">Remove</span> <!-- Text to show on hover -->
                                </div>
                                <input type="file" id="eventPhotoUpload" name="event_img" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label for="eventDesc">Short Description</label>
                                <textarea id="eventDesc" name="event_desc"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="eventDetailedDesc">Detailed Description</label>
                                <textarea id="eventDetailedDesc" name="event_detailed_desc"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="eventTime">Time</label>
                                <input type="time" id="eventTime" name="event_time" value="">
                            </div>
                            <div class="form-group">
                                <label for="eventType">Event Type</label>
                                <select id="eventType" name="event_type">
                                    <option value="workshop">Workshop</option>
                                    <option value="networking">Networking</option>
                                    <option value="competition">Competition</option>
                                    <option value="speaker">Speaker Event</option>
                                    <option value="financial">Financial Event</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="eventDate">Event Date</label>
                                <input type="date" id="eventDate" name="event_date" value="">
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="event_id" id="event_id" value="">
                                <input type="hidden" id="initial_img_path" name="initial_img_path" value="">
                                <button type="submit" name="save">Commit Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
        </div>

        <script src="js/adminEvents.js"></script>
    </body>
    <?php $conn->close(); ?>
    <?php require_once("footer.php"); ?>
</html>
