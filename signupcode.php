<?php

include "mail.php"; 
$current_time = new DateTime($_SESSION['current_time']);
$expiration_time = new DateTime($_SESSION['expiration_time']);
$new_time = new DateTime(); // Include your PHPMailer script
$msg="";

$name=$_SESSION['name'];
$role_id=$_SESSION['role_id'];
$email=$_SESSION['email'];
$sub_id=$_SESSION['sub_id'];
$pass=$_SESSION['pass'];
$conpass= $_SESSION['conpass'];
// $old_time = $_SESSION['old_time'];



// Function to generate a random OTP
function generateOTP() {
    return rand(1000,9999); // 6-digit OTP
}

// Check if the resend button was clicked
// if (isset($_POST['resend'])) {
//     $new_otp = generateOTP();
//     $_SESSION['otpemail'] = $new_otp;

//     // Send the OTP via email using PHPMailer
//     $to = $_SESSION['email'];  // Use the stored email from the session
//     $subject = "Your OTP Code";
//     $message = "Your OTP code is: " . $new_otp;

//     $mail->setFrom('fatma.said283@gmail.com', 'Website Name');
//     $mail->addAddress($to);
//     $mail->isHTML(true);
//     $mail->Subject = $subject;
//     $mail->Body = $message;

//     if ($mail->send()) {echo "OTP has been resent to your email.";
//     } else {
//         $msg= "Failed to resend OTP. Please try again.";
//     }
// }

if (isset($_POST['resend'])) {
    $new_otp = generateOTP();
    $_SESSION['otpemail'] = $new_otp;
    $msgi="hello your new OTP is, $new_otp ";

    $current_time = new DateTime();
    $expiration_time = clone $current_time;
    $expiration_time->add(new DateInterval('PT60S'));

    $_SESSION['current_time'] = $current_time->format('Y-m-d H:i:s');
    $_SESSION['expiration_time'] = $expiration_time->format('Y-m-d H:i:s');

    // Send the OTP via email using PHPMailer
    $mail->setFrom('fatma.said283@gmail.com', 'PlanSync'); // Sender mail address
    $mail->addAddress($email); // Receiver mail address
    $mail->isHTML(true);
    $mail->Subject = 'Activation code'; // Mail subject
    $mail->Body = $msgi; // Mail content
    $mail->send();
}

$rand2 = $_SESSION['otpemail'];

if (isset($_POST['submit'])) {
    // $otp = $_POST['email_otp'];
    $otp= $_POST['digit1'] . $_POST['digit2'] . $_POST['digit3'] . $_POST['digit_4'];
    if ($new_time > $expiration_time) {
        $msg = "Expired OTP. Please press 'resend'.";
        unset($_SESSION['otpemail']); // Unset OTP if expired
    } elseif ($_SESSION['otpemail'] == $otp) {
        echo "Email validated successfully";
        // Insert freelancer data into the database
        $passhash = password_hash($pass, PASSWORD_DEFAULT);
        // $insert = "INSERT INTO user VALUES ('$name',NULL, '$role_id','$email', '$passhash', '$sub_id')";
        $insert ="INSERT INTO user (username, user_id, role_id, email, password, subscription_id) 
         VALUES ( '$name', NULL, '$role_id', '$email', '$passhash', NULL)";
        $run_insert = mysqli_query($connect, $insert);
        unset($_SESSION['otpemail']); // Unset OTP after successful validation
        echo "Registration complete";
        header("location:login.php");
        exit();
    } else {
        $msg = "Incorrect OTP";
    }
}

// $rand2 = $_SESSION['otpemail'];
// if (isset($_POST['submit'])) {
//     $otp = $_POST['email_otp'];
//     if ($rand2 == $otp) {
//         echo "Email validated successfully";
//         unset($_SESSION['otpemail']);
//         header("Refresh: 1; url=login.php");
//         exit();  // Stop further execution
//     } else {
//         $msg= "Incorrect OTP";
//     }
// }
// include "connection.php";
// $msg="";
// $rand2=$_SESSION['otpemail'];
// if(isset($_POST['submit'])){
//      $otp=$_POST['email_otp'];
//      if($rand2==$otp){
//         //   header("location:login.php");
//           echo "email validated sucessfully";
//           unset($_SESSION['otpemail']);
//         //   unset($_SESSION['email']);
//           header("Refresh: 1; url=landing.php");

//      }else{
//           $msg="incorrect otp";
//      }
//     //  echo "password changed sucessfully";
//     //       unset($_SESSION['otp']);
//     //       unset($_SESSION['email']);
//     //       header("Refresh: 1; url=login.php");
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- WEBSITE TITLE -->
    <title>OTP verification Form</title>
    <!-- FONT LINK -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Philosopher:ital,wght@0,400;0,700;1,400;1,700&family=Reem+Kufi:wght@400..700&display=swap" rel="stylesheet">
    <!-- GOOGLE ICONS LINK -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <!-- CSS LINK -->
    <link rel="stylesheet" href="css/OTP.css">
    <!-- JS LINK -->
    <script src="js/OTP.js " defer></script>
</head>
<body>
    <div class="container"> 
        <header>
          <i class="material-symbols-outlined"> verified_user</i>
        </header>
        <h4>Enter OTP Code</h4>
        <form method="POST" action="signupcode.php">
            <div class="input-field">
                <input type="text" name="digit1" />
                <input type="text"  name="digit2" disabled />
                <input type="text"  name="digit3" disabled />
                <input type="text"  name="digit_4" disabled />
            </div>
            <button class=""  type="submit" name="submit">Verify OTP</button>
            <button class="resendBtn"  type="submit" name="resend">Resend OTP</button>
        </form>
        <?php if(!empty($msg)){ ?>
            <p> <?php echo $msg ;?></p>
        <?php } else{}?>

    </div>
    <script src="js/OTP.js"></script>
</body>
</html>
