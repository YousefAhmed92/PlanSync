<?php
include "mail.php";
// include "new nav.php";

// $fill = "";
// $exist = "";
// $match = "";
// $pass_require = "";
$error="";
$edit = false;
if(isset($_POST['submit'])){
  $name = $_POST['Name'];
  $_SESSION['name']=$name;

  $role_id=$_POST['role_id'];
  $_SESSION['role_id']=$role_id;
  $email = $_POST['Email'];
  $_SESSION['email']=$email;
  $sub_id=$_POST['sub_id'];
  $_SESSION['sub_id']=$sub_id;
  $pass = $_POST['password'];
  $_SESSION['pass']=$pass;
  $conpass = $_POST['Confirm_Password'];
  $_SESSION['conpass']=$conpass;

    // $passhash = password_hash($pass, PASSWORD_DEFAULT);
    $uppercase=preg_match('@[A-Z]@' ,$pass);
    $lowercase=preg_match('@[a-z]@' ,$pass);
    $number=preg_match('@[0-9]@' ,$pass);
    $character=preg_match('@[^/w]@' ,$pass);
    $select = "SELECT * FROM `user` WHERE `email` = '$email'";
    $runsel = mysqli_query($connect , $select);
    $row = mysqli_num_rows($runsel);
    // $sel = "SELECT * FROM `user`";
    // $run = mysqli_query($connect, $sel);
    if(empty($name) || empty($pass) || empty($email)){
        $error = "Fill in the requried input please";
        //  fill echo "2";
    }
    elseif($uppercase<1 || $lowercase<1 ||$number<1 ||$character<1 ){
        $error = "password must contain atleast 1 uppercase, lowercase, number, special characters";
        // pass req echo 3; 
    }
    
    elseif($row > 0){
        $error = "This email already exists";
        //  exist echo 4;
    }
    elseif($pass != $conpass){
        $error = "Password doesn't match confirm password";
        //  match echo 5;
    }
   
        // $insert = "INSERT INTO `user` VALUES('$name',NULL,'$role_id','$email','$passhash','$sub_id')";
        // $insert ="INSERT INTO `user` (`username`, `user_id`, `role_id`, `email`, `password`, `subscription_id`) 
        // VALUES ( '$name', NULL, '$role_id', '$email', '$passhash', NULL)";
        // $runinsert = mysqli_query($connect, $insert);
        // header("location:code.php");
        //try

        if(empty($error)){
          header("location:signupcode.php");
          $rand=rand(1000,9999);

    $msg="hello, your otp is $rand";
    $current_time = new DateTime();

    // Clone the current time and add 60 seconds
        $expiration_time = clone $current_time;
        $expiration_time->add(new DateInterval('PT60S')); // PT60S means 60 seconds
    
    // Store both in session
        $_SESSION['current_time'] = $current_time->format('Y-m-d H:i:s');
        $_SESSION['expiration_time'] = $expiration_time->format('Y-m-d H:i:s');
    

          // php mail start->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>


          $mail->setFrom('fatma.said283@gmail.com', 'PlanSync');          //sender mail address , website name

          $mail->addAddress($email);      //reciever mail address

          $mail->isHTML(true);                               

          $mail->Subject = 'Activation code';             //mail subject

          $mail->Body=($msg);                  //mail content

          $mail->send(); 
// php mail end ->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    $_SESSION['otpemail']=$rand;
        
//         $rand=rand(1000,9999);

//     $msg="hello, your otp is $rand";

//           // php mail start->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>


//           $mail->setFrom('fatma.said283@gmail.com', 'website_name');          //sender mail address , website name

//           $mail->addAddress($email);      //reciever mail address

//           $mail->isHTML(true);                               

//           $mail->Subject = 'Activation code';             //mail subject

//           $mail->Body=($msg);                  //mail content

//           $mail->send(); 
// // php mail end ->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//     $_SESSION['otpemail']=$rand;
//     header("location:signupcode.php");
    //change to new one called code.php done!!
    }
//try
//     $rand=rand(1000,9999);

//     $msg="hello, your otp is $rand";

//           // php mail start->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>


//           $mail->setFrom('fatma.said283@gmail.com', 'website_name');          //sender mail address , website name

//           $mail->addAddress($email);      //reciever mail address

//           $mail->isHTML(true);                               

//           $mail->Subject = 'Activation code';             //mail subject

//           $mail->Body=($msg);                  //mail content

//           $mail->send(); 
// // php mail end ->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//     $_SESSION['otpemail']=$rand;
//     header("location:code.php");
//     //change to new one called code.php done!!

}
?>


<!DOCTYPE html>
<html lang="en">
<!-- designer -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanSync</title>
    <link rel="stylesheet" href="./css/signup.Css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
        <!-- <li class="nav-item" style="margin-right: 40px;">
          <a class="nav-link" aria-current="page" href="#">Profile</a>
        </li>
        <li class="nav-item" style="margin-right: 40px;">
          <a class="nav-link" aria-current="page" href="#">Projects</a>
        </li>
        <li class="nav-item" style="margin-right: 40px;">
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

<!-- all page container -->
<div class="mycontainer">


  
  <!-- form sign up  -->
  <div class="formdiv">
    <form method="POST">
      <div class="buttons">
          <H1 class="head">Signup</H1> 
          <!-- <h3 class="create">Dont't Have an Account? Create Your Account </h3> -->
          <div class="usernamediv">
            <i class="fa-solid fa-user"></i>
            <input class="username" type="Username" placeholder="Username"  name="Name">
        </div>
          <!-- <input class="username" type="Username" placeholder="Username" required> -->
          <!-- <input class="email" type="email" placeholder="E-mail" !required > -->

          <div class="emaildiv">
            <i class="fa-solid fa-envelope"></i>
            <input class="email" type="email" placeholder="E-mail"  name="Email">
        </div>
        <div class="passworddiv">
            <i class="fa-solid fa-lock"></i>
            <!-- <i id="showPassword" class="fa-solid fa-eye"></i> -->
            <input id="passwordInput" class="password" type="password" placeholder="Password"  name="password">
        </div>
        
          <!-- <input class="password" type="password" placeholder="Password" !required> -->
          <input class="confirm-password" type="password" placeholder="Confirm password"  name="Confirm_Password">

          <input type="hidden" name="role_id" value="2">
          <!-- <input type="hidden" name="sub_id" value="0"> -->

          <!-- <button class="btn-17">
            <span class="text-container">
                <span class="text">Submit</span> -->
            </span>
        <!-- </button> -->
        <!-- i closed this one  -->
      </div>
      <?php if(!empty($error)){ ?>
            <p class="alert"><?php echo $error ;?></p> 
            <?php }else{}?>
      <!-- <p class="alert">The Username or Password is incorrect!</p> -->
       <a class="forget" href="login.php"> Already have an account?</a> 

      <button class="btn-17" name="submit" type="submit">
        <!-- i added type submit -->
          <span class="text-container">
              <span class="text">Sign up</span>
          </span>
      </button>
    </form>
  </div>
  <!-- end form sign up -->

  
  <!-- sign up section -->
  <!-- <div class="signup-div">

      <h2> New to this website ? </h2>
      <p class="par">Simply Create your account by <br> clicking the Signup Button</p> -->
      <!-- <p>join us now</p> -->

      <!-- <button class="btn-17">
          <span class="text-container">
              <span class="text">Sign-up</span>

          </span>
      </button>
  </div> -->
</div>
<!-- endd of page -->

<script src="./T4.JS/SIGNUP.JS"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>