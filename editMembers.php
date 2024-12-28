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
      <?php 
         require_once("header.php")
         ?>
      <?php
         session_start();
         
         // Set session timeout duration (20 minutes)
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
         //database connection
         require_once('db_config.php');
         
         $conn = new mysqli($host, $username, $password, $dbname);
         
         if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
         }
         
         //sql query from database
         $sql = "SELECT ID, member_name, member_bio, member_img FROM members";
         $result = $conn->query($sql);
         ?>
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
            <!-- Middle Column -->
            <div class="middle-column">
               <div class="member-grid">
                  <?php if ($result->num_rows > 0): ?> <!---check if there are members in the database---->
                  <?php while ($row = $result->fetch_assoc()): ?>        <!-- loop through each member retrieved from the database -->
                  <div class="member-grid-item" 
                     onclick="editMember('<?php echo addslashes($row['member_name']); ?>',  //retrive member name
                     //retrive member image
                     'data:image/jpeg;base64,<?php echo base64_encode($row['member_img']); ?>', 
                     //retrive member bio
                     '<?php echo addslashes($row['member_bio']); ?>',
                     //retrieve member id
                     <?php echo $row['ID']; ?>)">
                     <!-- display photo photo -->
                     <img src="data:image/jpeg;base64,<?php echo base64_encode($row['member_img']); ?>" alt="Member Photo">
                     <div class="middle">
                        <!-- show the member's name  -->
                        <div class="memberName"><?php echo htmlspecialchars($row['member_name']); ?></div>
                     </div>
                  </div>
                  <?php endwhile; ?>
                  <?php else: ?>
                  <!-- error if no members are found in the table -->
                  <p>No members found.</p>
                  <?php endif; ?>
               </div>
            </div>
            <!-- Right Column -->
            <div class="right-column">
               <h3>Edit Member</h3>
               <div class="form-group">
                  <label for="memberName">Name</label>
                  <input type="text" id="memberName" value="">
               </div>
               <div class="form-group">
                  <label for="memberPhoto">Photo</label>
                  <div class="photo-preview">
                     <img id="photoPreview" src="" alt="" style="width: 150px; height: auto;">
                  </div>
                  <input type="file" id="memberPhotoUpload" accept="image/*" onchange="previewPhoto()"> <!----preview using js in editMembers.js----->
               </div>
               <div class="form-group">
                  <label for="memberBio">Bio</label>
                  <textarea id="memberBio"></textarea>
               </div>
               <div class="form-group">
                  <button onclick="saveMember()">Save Changes</button>
               </div>
               <div class="form-group">
                  <button id="delete" data-id="">Delete Member</button>
               </div>
            </div>
         </div>
      </div>
      <script src="js/editMembers.js"></script>
      <?php $conn->close(); ?>

   </body>

</html>
