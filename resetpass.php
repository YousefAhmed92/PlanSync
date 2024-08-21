<?php
include 'connection.php';
$er_msg="";
$er_fill="";
$er_strong="";

if(isset($_POST['submit'])){
     $email=$_SESSION['email'];
     $password=$_POST['pass'];
     $confirm=$_POST['cpass'];

     $uppercase = preg_match('@[A-Z]@', $password);
     $lowercase = preg_match('@[a-z]@', $password);
     $numbers = preg_match('@[0-9]@', $password);
     $character = preg_match('@[^a-zA-Z0-9]@', $password);

     if(empty($password)||empty($confirm)){
          $er_fill="please fill required data";
     }elseif($password!=$confirm){
          $er_msg="password doesn't match comfirm password";

     }elseif($uppercase<1||$lowercase<1||$numbers<1||$character<1){
          $er_strong="password should contain atleast 1 uppercase,lowercase,numbers or special character";
     }else{
          $hashed=password_hash($password,PASSWORD_DEFAULT);
          $update="UPDATE `user` SET `password`='$hashed' WHERE `email`='$email'";
          $runupdate=mysqli_query($connect,$update);
          echo "password changed sucessfully";
          unset($_SESSION['otp']);
          unset($_SESSION['email']);
          header("Refresh: 1; url=login.php");

     }
     

}
?>


<!DOCTYPE html>
<html lang="en">
<!-- designer -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanSync</title>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IM+Fell+English+SC&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/pass.Css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lobster+Two:ital,wght@0,400;0,700;1,400;1,700&family=Montserrat:ital,wght@0,100..900;1,100..900&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
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
    <!-- <div class="ayhaga"> -->
    <div class="conrainer-content">
        <div class="contenttt">
            <div class="form1">
                <h2 class=hh>Reset Password</h2>
             <form method="POST">
                <div class="main-form">
                    <div class="formicons">
                        <input id="passwordInput" class="inputs" type="password" placeholder="New password" name="pass">
                        <i id="lockIcon" class="fa-solid fa-lock"></i>
                        <!-- <i id="showPassword" class="fa-solid fa-eye"></i> -->
                    </div>

                    <div class="confirmpassword">
                        <input id="confrimInput" class="inputs" type="password" placeholder="Confirm Password" name="cpass">
                        <i class="fa-solid fa-lock"></i>
                        <!-- <i id="confrimShow" class="fa-solid fa-eye"></i> -->

                    </div>
                    <!-- <div class="check">
                        <input type="checkbox" class="checkbox">
                        <label for="checkbox">Remember Me</label>
                    </div> -->
                <!-- </div> -->

                <?php if(!empty($er_msg)){ ?>
                <div class="erorr">

                  <p> <?php echo $er_msg ;?></p>
                </div>

                

                <?php } elseif(!empty($er_fill)){ ?>
                <div class="erorr1">

                <p> <?php echo $er_fill ;?></p> 
                </div>

                

                    <?php } elseif(!empty($er_strong)){ ?>
                        <div class="erorr2">
                        <p> <?php echo $er_strong ;?></p>
                        <?php } else {} ?>
                </div>
                <!-- button -->
                <button class="btn-17" type="sunmit" name="submit">
                    <span class="text-container">
                        <span class="text">Submit</span>
                    </span>
                </button>
                </div>

             </form>
            </div>
        </div>
    </div>
    <!-- </div> -->

    <script src="./T4.js/update.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>