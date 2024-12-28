<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Project</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="css/about.css">
  <link rel="stylesheet" href="css/style.css">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>


<?php
require_once("header.php")
?>


<!---------------------- CONTENT ---------------------->


<body>
<div class = "pageTotal">
<main>
   <div class="top-section">
   <div class="left-section">
       <h1>The <br /><span class="highlight">Entrepreneurship</span> <br />Club</h1>
   </div> 
   <div class="right-section">
       <img src="img/contactImg/about.png">
       <img src = "img/contactImg/flowerPot.png" class = "flowerPot">
       <img src = "img/contactImg/aboutLeftLeaf.png" class = "leaf1">
       <img src = "img/contactImg/aboutMiddleLeaf.png" class = "leaf2">
       <img src = "img/contactImg/aboutRightLeaf.png" class = "leaf3">
       <img src = "img/contactImg/aboutArm.png" class = "arm">
       <img src = "img/contactImg/aboutHead.png" class = "head">
   </div>
   </div>
<!---------------------- CONTAINER 1 MEMBERS---------------------->
  <div class = "container">
      <div class = "contentMembers">
          <div>
              <h1>Our Members</h1>
              <p>
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                  sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit.
              </p>
              <div class = "buttonMembersAlign">
                  <a href = "members.php" class = "buttonMembers">Meet Our Members</a>
               </div>
          </div>
      </div>
      <div class = "imageContainerMembers">
          <img id = "aboutMem" src="img/ActualPhotos/members.jpeg">
      </div>
  </div>


<!---------------------- CONTAINER 2 COBBERTUNITY---------------------->
<div class = "container" data-aos="fade-left" data-aos-duration="150">
   <div class = "imageCobbertunity">
       <img id = "ferret2" src="img/ActualPhotos/cobber.jpeg">
   </div>
   <div class = "contentCobbertunity">
       <div>
           <h1>Cobbertunity</h1>
           <p>
               Lorem ipsum dolor sit amet, consectetur adipiscing elit,
               sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
               Lorem ipsum dolor sit amet, consectetur adipiscing elit.
               Lorem ipsum dolor sit amet, consectetur adipiscing elit.
           </p>
           <div>
               <div class = "buttonCobbertunityAlign">
                   <a href = "https://www.concordiacollege.edu/academics/programs-of-study/offutt-school-of-business/cobbertunity/"
                   target="_blank" class = "buttonCobbertunity">Learn More</a>
               </div>
            </div>
       </div>
   </div>
</div>




<!---------------------- CONTAINER 3 PROGRAMS ---------------------->
<div class = "container" data-aos="fade-right" data-aos-duration="150">
   <div class = "contentPrograms">
       <div>
           <h1>Programs</h1>
           <p>
               Lorem ipsum dolor sit amet, consectetur adipiscing elit,
               sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
               Lorem ipsum dolor sit amet, consectetur adipiscing elit.
               Lorem ipsum dolor sit amet, consectetur adipiscing elit.
           </p>
           <div class = "buttonProgramsAlign">
               <a href = "https://www.concordiacollege.edu/academics/programs-of-study/offutt-school-of-business/"
               target="_blank" class = "buttonPrograms">Learn About Programs</a>
            </div>
       </div>
   </div>
   <div class = "imagePrograms">
       <img id = "ferret3" src="img/programsImage.png">
   </div>
</div>




<!---------------------- CONTAINER 4 CONNECT ---------------------->
   <div class = "container" data-aos="fade-left" data-aos-duration="150">
       <div class = "imageConnect">
           <img id = "ferret4" src="img/ActualPhotos/connect.jpg">
       </div>
       <div class = "contentConnect">
           <div>
               <h1>Cobber Connect</h1>
               <p>
                   Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                   sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                   Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                   Lorem ipsum dolor sit amet, consectetur adipiscing elit.
               </p>
               <div class = "buttonConnectAlign">
                   <a href = "https://cobberconnect.cord.edu/feeds?type=club&type_id=35499&tab=home/"
                   target="_blank" class = "buttonConnect">Learn More</a>
               </div>
           </div>
       </div>
   </div>






<!---------------------- CONTAINER 5 SOCIAL MEDIA ---------------------->
<div class = "container" data-aos="fade-right" data-aos-duration="150">
   <div class = "contentSocial">
       <div>
           <h1>Social Media</h1>
           <p>
               Lorem ipsum dolor sit amet, consectetur adipiscing elit,
               sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
               Lorem ipsum dolor sit amet, consectetur adipiscing elit.
               Lorem ipsum dolor sit amet, consectetur adipiscing elit.
           </p>
           <div class = "buttonSocialAlign">
               <a href = "https://www.instagram.com/cobberentrepreneurs/"
               target="_blank" class = " bi bi-instagram buttonSocial">Entrepreneurship Club</a>
            </div>
       </div>


   </div>
   <div class = "imageSocial">
       <img id = "ferret5" src="img/instagramImg.png">
   </div>
</div>
</div>






  <!-- JavaScript -->




<script src="https://cdn.jsdelivr.net/gh/greentfrapp/pocoloco@minigl/minigl.js"></script>
<script>
var gradient = new Gradient();
gradient.initGradient("#canvas");
</script>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
  AOS.init();
</script>


<?php
   require_once("footer.php")
   ?>
</body>
</html>