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
<form action="send_email.php" method="post">
            <h2>Contact Us</h2>
  
            <label for="name"></label><br>
            <input type="text" id="name" name="name" placeholder="Name"> <br>

            <label for="email"></label><br>
            <input type="email" id="email" name="email" placeholder="Email"> <br>

            <label for="message"></label>
            <textarea id="message" name="message" placeholder="Message"></textarea>

            <input type="submit" value="Submit">

        </form>
        <div class="contact-right">
                <img src="img/contactImg/fullImage2.png" alt="">
                <img src="img/contactImg/StarRotate1.png" class="star-icon1">
                <img src="img/contactImg/StarRotate1.png" class="star-icon2">
                <img src="img/contactImg/StarRotate3.png" class="star-icon3">
                <img src="img/contactImg/StarRotate1.png" class="star-icon4">
                <img src="img/contactImg/StarRotate3.png" class="star-icon5">
                <img src="img/contactImg/StarRotate1.png" class="star-icon6">
                <img src="img/contactImg/Mail_Icon.png" class="mail_icon">
                <img src="img/contactImg/Phone_Icon.png" class="phone_icon">
                <img src="img/contactImg/Question_Icon.png" class="question_icon">
        </div>
</body>