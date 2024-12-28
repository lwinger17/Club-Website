<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/addMember.css">
</head>
<body>

<?php
session_start();

// set session timeout duration (20 minutes)
$timeout_duration = 1200; // 20 minutes in seconds

// check if the session has expired
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    // destroy the session if it has expired
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
?>
<?php 
  require_once("header.php")
  ?>


<div class="admin-page">
   <!-- siderbar for actions -->
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

   <!-- Content area -->
   <div class="content">
      <form id="event-form" enctype="multipart/form-data">
         <h1>Add Member</h1>
         <div class="form-group">
         <input type="text" id="memberName" name="memberName" required>
         <label for="memberName">Name</label>

         </div>

         <div class="form-group">
            <textarea id="memberBio" rows="4" name = "memberBio" required></textarea>
            <label for="memberBio">Bio</label>
         </div>

         <div class="form-group">
               <div class="photo-preview">
                  <img id="newPhotoPreview" src="" alt="" style="width: 150px; height: auto; display: none;">
               </div>
            <input type="file" id="newEventPhotoUpload" name = "event_img" accept="image/*" onchange="previewNewPhoto()">
            <label for="newEventPhoto"></label>
         </div>

         <button type="button" onclick="addNewMember()">Submit</button>
      </form>
   </div>
</div>

<script src="js/addMember.js"></script>

<?php $conn->close(); ?>


<?php
   require_once("footer.php")
   ?>
</body>
</html>