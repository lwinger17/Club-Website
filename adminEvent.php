<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Project</title>
    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/adminMemberEvent.css"> 
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php 
require_once("header.php")
?>
    

<!-- -------------------- MAIN CONTENT ---------------------->
<main>
    <h1>Events List</h1>
    <ol id="List">
        <li>
            <img src="img/ferrets/ferret6.png" alt="Event 1">
            <span>Event 1</span>
            <div class="actions">
                <button> <a href="adminEventEdit.html">Edit</a></button>
                <button>Delete</button>
            </div>
        </li>
        <!-- add more list items as needed -->
    </ol>
    <div class="add">
        <a href="adminEventAdd.html">
            <button>Add Event</button>
          </a>
        
      </div>
</main>

<?php 
    require_once("footer.php")
    ?>
</body>
</html>
