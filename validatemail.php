<?php
include "mail.php";  // Include your PHPMailer script

// Function to generate a random OTP
function generateOTP() {
    return rand(100000, 999999); // 6-digit OTP
}

// Check if the resend button was clicked
if (isset($_POST['resend'])) {
    $new_otp = generateOTP();
    $_SESSION['otpemail'] = $new_otp;

    // Send the OTP via email using PHPMailer
    $to = $_SESSION['email'];  // Use the stored email from the session
    $subject = "Your OTP Code";
    $message = "Your OTP code is: " . $new_otp;

    $mail->setFrom('fatma.said283@gmail.com', 'Website Name');
    $mail->addAddress($to);
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $message;

    if ($mail->send()) {
        echo "OTP has been resent to your email.";
    } else {
        echo "Failed to resend OTP. Please try again.";
    }
}

// Check if the submit button was clicked to validate the OTP
$rand2 = $_SESSION['otpemail'];
if (isset($_POST['submit'])) {
    $otp = $_POST['email_otp'];
    if ($rand2 == $otp) {
        echo "Email validated successfully";
        unset($_SESSION['otpemail']);
        header("Refresh: 1; url=login.php");
        exit();  // Stop further execution
    } else {
        echo "Incorrect OTP";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validate Email</title>
    <link rel="stylesheet" href="css/validation.css">
    
    </head>
<body>
    <form method="POST">
        <p>To validate your email, please enter the OTP sent to your email:</p>
        <input type="number" name="email_otp" >
        <button type="submit" name="submit">Submit</button>
        <br><br>
        <p>If you did not receive the OTP, you can request a new one:</p>
        <button type="submit" name="resend">Resend OTP</button>
    </form>
</body>
</html>
