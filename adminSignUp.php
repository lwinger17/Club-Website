<?php 
session_start(); 

require_once('db_config.php');

// Establish connection
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$error = "";
$success = "";

$timeout_duration = 1200; // 20 minutes in seconds

// Check if the session has expired
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    // Destroy the session if it has expired
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

// Process sign up
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $signup_email = $_POST['first'];
    $signup_pass = $_POST['password'];
    $signup_confirm_pass = $_POST['confirm_password'];

    // Check if password and confirm password match
    if ($signup_pass !== $signup_confirm_pass) {
        $error = "Passwords do not match!";
    } else {
        // Hash the password
        $hashed_password = password_hash($signup_pass, PASSWORD_DEFAULT);

        // Check if the username or email already exists
        $stmt = $conn->prepare("SELECT ID FROM login WHERE login_email = ? ");
        $stmt->bind_param("s", $signup_email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "User already exists!";
        } else {
            // Insert the new user into the database
            $stmt = $conn->prepare("INSERT INTO login (login_email, login_pass) VALUES (?, ?)");
            $stmt->bind_param("ss", $signup_email, $hashed_password);
            $stmt->execute();

            $success = "Account created successfully. You can now log in!";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Signup</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/orderMembers.css">
</head>
<body>
<?php require_once("header.php"); ?>

<div class="admin-page">
    <!-- Sidebar for actions -->
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

    <!-- Content -->
    <div class="content">
        <main>
            <div class="main">
                <link rel="stylesheet" href="css/login.css"> <!-- Login form styles -->
                <h1>Create an Admin Account</h1>
                <form method="POST" action="">
                    <label for="first">Username:</label>
                    <input type="text" id="first" name="first" placeholder="Enter your Username" required>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Enter your Password" required>

                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your Password" required>

                    <?php if (!empty($error)): ?>
                        <div class="error" style="color: red;"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="success" style="color: green;"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <div class="wrap">
                        <button type="submit">Sign Up</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>
</body>
</html>
