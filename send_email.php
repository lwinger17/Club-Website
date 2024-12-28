<?php  
// form data
$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

// email variables
$to = "club@example.com"; // replace with the club email address
$subject = "Form Submission from $name";
$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=utf-8\r\n";

// email body
$body = "Message from Entrepreneurship Club contact form:\n\n";
$body .= "Name: $name\n";
$body .= "Email: $email\n";
$body .= "Message: $message\n";


// validate email format and required fields
if (!empty($name) && !empty($email) && !empty($message)) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // use the mail() function (disabled here for localhost simulation)
        // mail($to, $subject, $body, $headers);

        // rediret to the confirmation page
        header("Location: contact.php?status=success");
        exit();
    } else {
        echo "<p>Invalid email format. Please enter a valid email address.</p>";
    }
} else {
    echo "<p>Failed to submit the message. Please fill out all fields.</p>";
}
?>
