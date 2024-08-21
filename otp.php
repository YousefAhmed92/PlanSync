<?php
include "mail.php";
$email = $_SESSION['email'];
$error="";

function generateOTP() {
    return rand(1000,9999); // 6-digit OTP
}

if (isset($_POST['resend'])) {
    $new_otp = generateOTP();
    $_SESSION['otp'] = $new_otp;
    $msg="hello, your new password reset code is $new_otp";

    $current_timeotp = new DateTime();
    $expiration_timeotp = clone $current_timeotp;
    $expiration_timeotp->add(new DateInterval('PT60S')); // Set expiration time to 60 seconds

    $_SESSION['current_timeotp'] = $current_timeotp->format('Y-m-d H:i:s');
    $_SESSION['expiration_timeotp'] = $expiration_timeotp->format('Y-m-d H:i:s');


    // Send the OTP via email using PHPMailer

    $mail->setFrom('fatma.said283@gmail.com', 'PlanSync');          //sender mail address , website name

    $mail->addAddress($email);      //reciever mail address

    $mail->isHTML(true);                               

    $mail->Subject = 'Reset Password code';             //mail subject

    $mail->Body=($msg);                  //mail content

    $mail->send();
}
// $error="";
$rand2=$_SESSION['otp'];
if(isset($_POST['submit'])){
     $otp= $_POST['digit1'] . $_POST['digit2'] . $_POST['digit3'] . $_POST['digit_4'];
     $current_timeotp = new DateTime(); // Update current time each time the form is submitted
     $expiration_timeotp = new DateTime($_SESSION['expiration_timeotp']);

     if ($current_timeotp > $expiration_timeotp) {
        $error = "Expired OTP. Please press 'resend'.";
        unset($_SESSION['otp']);
    }elseif($_SESSION['otp'] == $otp){
        echo "Validation completed";
        header("location:resetPass.php");
        unset($_SESSION['otp']);
        exit();
    }else{
          $error="incorrect otp";
     }
}


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
    <link rel="stylesheet" href="css/OTP.Css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- JS LINK -->
    <script src="js/OTP.js " defer></script>
</head>
<body>
    
    <!-- start nav bar -->
   
  <!-- end nav bar -->
<div class="ayhaga">
<div class="containerrr"> 
        <header>
          <i class="material-symbols-outlined"> verified_user</i>
        </header>
        <h4>Enter OTP Code</h4>
        <form method="POST" action="otp.php">
            <div class="input-field">
                <input type="text" name="digit1" />
                <input type="text"  name="digit2" disabled />
                <input type="text"  name="digit3" disabled />
                <input type="text"  name="digit_4" disabled />
            </div>
            <button class=""  type="submit" name="submit">Verify OTP</button>
            <button class="resendBtn"  type="submit" name="resend">Resend OTP</button>
        </form>
        <?php if(!empty($error)){ ?>
            <p> <?php echo $error ;?></p>
        <?php } else{}?>

    </div>
</div>

    <script src="js/OTP.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>
</html>