<?php
//maybe use the error div after testing
include "mail.php";
$error="";
if(isset($_POST['submit'])){
     $email=$_POST['email'];
    $_SESSION['email']=$email;

     $select="SELECT * FROM `user` where `email`='$email'";
     $runselect=mysqli_query($connect,$select);
     if(mysqli_num_rows($runselect)>0){
$rand=rand(1000,9999);
            $msg="hello, your password reset code is $rand";
            $current_timeotp = new DateTime();

                // Clone the current time and add 60 seconds
                    $expiration_timeotp = clone $current_timeotp;
                    $expiration_timeotp->add(new DateInterval('PT60S')); // PT60S means 60 seconds
                
                // Store both in session
                    $_SESSION['current_timeotp'] = $current_timeotp->format('Y-m-d H:i:s');
                    $_SESSION['expiration_timeotp'] = $expiration_timeotp->format('Y-m-d H:i:s');
           
   
             // php mail start->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
   
                $mail->setFrom('fatma.said283@gmail.com', 'PlanSync');          //sender mail address , website name
                $mail->addAddress($email);      //reciever mail address
                $mail->isHTML(true);                               
                $mail->Subject = 'Reset password code';             //mail subject
                $mail->Body=($msg);                  //mail content
                $mail->send(); 
   // php mail end ->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            $_SESSION['otp']=$rand;
            header("location:otp.php");
                // exit();
            }else{
             $error= "email not found";
        }
// $rand=rand(1000,9999);
// $msg="hello, your otp is $rand";

//           // php mail start->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

// $mail->setFrom('fatma.said283@gmail.com', 'website_name');          //sender mail address , website name
// $mail->addAddress($email);      //reciever mail address
// $mail->isHTML(true);                               
// $mail->Subject = 'Activation code';             //mail subject
// $mail->Body=($msg);                  //mail content
// $mail->send(); 
// // php mail end ->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
// $_SESSION['otp']=$rand;
// header("location:code2.php");
//      }else{
//           $error= "email not found";
//      }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanSync</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lobster+Two:ital,wght@0,400;0,700;1,400;1,700&family=Montserrat:ital,wght@0,100..900;1,100..900&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/emailforget.Css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    
    <!-- start nav bar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary" style="background-color: rgba(50, 50, 50, 0.848) !important;">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">PlanSync</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <div class="items">
        <li class="nav-item" style="margin-right: 40px;">
          <a class="nav-link" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item" style="margin-right: 40px;">
          <a class="nav-link" aria-current="page" href="#">Login</a>
        </li>
        <li class="nav-item" style="margin-right: 40px;">
          <a class="nav-link" aria-current="page" href="#">Sign-up</a>
        </li>
        <!-- <li class="nav-item" style="margin-right: 40px;">
          <a class="nav-link" aria-current="page" href="#">Tasks</a>
        </li> -->
        </div>

      </ul>
      <!-- <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn" type="submit">Search</button>
      </form> -->
    </div>
  </div>
</nav>
  <!-- end nav bar -->
    <div class="conrainer-content">
        <div class="content">
            <form method="POST">
            <div class="form1">
                <h2 class=hh>verification</h2>
                <div class="form">
                    <input class="inputs" type="email" placeholder="E-mail" name="email">
                    <!-- note:changed the input type to number -->
                    <i class="fa-solid fa-user"></i>
                </div>
                
                   <?php if(!empty($error)){ ?>
                    <div class="erorr">
                     <p> <?php echo $error ; ?></p>
                     </div>
                   <?php } else{ }?>
              
                
                <!-- button -->
                <button class="btn-17" type="submit" name="submit">
                    <span class="text-container">
                        <span class="text">Submit</span>
                    </span>
                </button>
            </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>