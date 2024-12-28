<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title>Project</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
   <link rel="stylesheet" href="css/header.css"> 
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   <!-- Header -->
   <header class="header">
      <nav id="mainNav">
         <div id="leftHeader">
            <a href="index.php"><img src="img/ConcoHeader.png" class="header-logo"></a>
         </div>
         <div id="rightHeader">
            <ul>
               <li><a href="about.php">About</a></li>
               <li><a href="schedule.php">Schedule</a></li>
               <li><a href="contact.php">Contact</a></li>
            </ul>
         </div>
      </nav>

      <!-- Sidebar Toggle Icon - remains in header -->
      <input type="checkbox" id="openSidebarMenu" style="display:none;">
      <label for="openSidebarMenu" class="sidebarIconToggle" onclick="toggleSidebar()">
         <div class="spinner diagonal part-1"></div>
         <div class="spinner horizontal"></div>
         <div class="spinner diagonal part-2"></div>
      </label>
   </header>

   <!-- Sidebar - moved outside header -->
   <div id="sidebarMenu">
      <ul class="sidebarMenuInner">
         <li><a href="about.php">About</a>
            <ul>
               <li><a href="members.php"><i class="bi bi-person"></i> Members</a></li>
               <li><a href="https://www.concordiacollege.edu/academics/programs-of-study/offutt-school-of-business/cobbertunity/" target="_blank"><i class="bi bi-chat-dots"></i> Cobbertunity</a></li>
               <li><a href="https://www.concordiacollege.edu/academics/programs-of-study/offutt-school-of-business/" target="_blank"><i class="bi bi-award"></i> Programs</a></li>
               <li><a href="https://cobberconnect.cord.edu/feeds?type=club&type_id=35499&tab=home/" target="_blank"><i class="bi bi-chat-quote"></i> Cobber Connect</a></li>
               <li><a href="https://www.instagram.com/cobberentrepreneurs/" target="_blank"><i class="bi bi-instagram"></i> Social Media</a></li>
            </ul>
         </li>
         <li><a href="schedule.php">Schedule</a>
            <ul>
               <li><a href="schedule.php"><i class="bi bi-calendar-event"></i> Events</a></li>
            </ul>
         </li>
         <li><a href="contact.php">Contact</a>
            <ul>
               <li><a href="contact.php"><i class="bi bi-person-plus"></i> Join</a></li>
            </ul>
         </li>
      </ul>
      <div class="adminLogin">
         <a href="login.php"><i class="bi bi-shield-lock"></i> Admin Login</a>
      </div>
   </div>

   <!-- JavaScript to toggle sidebar visibility -->
   <script>
      function toggleSidebar() {
         const sidebarMenu = document.getElementById('sidebarMenu');
         sidebarMenu.style.transform = sidebarMenu.style.transform === 'translateX(0px)' ? 'translateX(300px)' : 'translateX(0px)';
      }
   </script>


</body>

</html>