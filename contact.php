<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/contact.css"> 
    <link rel="stylesheet" href="css/style.css">
    
</head>
<body>

<?php 
require_once("header.php")
?>

<!---------------------- CONTENT ---------------------->
<div class = "page">
    <div class="contact-container">
    <?php 
        // check status of email
        if (isset($_GET['status']) && $_GET['status'] === 'success') {
            require_once("contactB.php"); // load the contactB.php
        } else {
            require_once("contactA.php"); // load the contactA.php
        }
        ?>
 </div>
</body>
</html>
