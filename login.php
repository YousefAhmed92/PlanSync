<?php
 include "connection.php";
//  include "new nav.php";
 //designer have aplace where error should appear try it later!! done

    // $match = "";
    // $exist = "";
    // $fill = "";
    $error ="";
    if(isset($_POST['btn'])){
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $sel = "SELECT * FROM `user` WHERE `email` ='$email'";
        $run = mysqli_query($connect , $sel);
        $fetch = mysqli_fetch_assoc($run);
        $row = mysqli_num_rows($run);
    
        if($row>0){
            $hash = $fetch['password'];
            if(password_verify($pass , $hash)){
                $id = $fetch['user_id'];
                $_SESSION['user_id'] = $id;
                // header("Location:home.php");
                header("Location:landing.php");
            }
            else{
                //match
                $error = "The password entered is incorrect";
            }
        }else{
            //exist
            $error = "This email doesn't exist";
        }
        if(empty($email) || empty($pass)){
            //fill
            $error = "Fill in the requried input please";
            
        }
    }
?>



<!DOCTYPE html>
<html lang="en">
<!-- designer -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./css/login.Css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
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



        <!-- form login  -->
        <div class="formdiv">
         <form method="POST">
            <div class="buttons">
                <H1 class="head">Login</H1>
                <div class="usernamediv">
                    <i class="fa-solid fa-user"></i>
                    <input class="username" type="email" placeholder="Email"  name="email">
                    <!-- note:changed input type from username to email -->
                </div>
                <div class="passworddiv">
                    <i class="fa-solid fa-lock"></i>
                    <!-- <i id="showPassword" class="fa-solid fa-eye"></i> -->
                    <input id="passwordInput" class="password" type="password" placeholder="Password"  name="password">
                </div>
                <button class="btn-17" type="submit" name="btn">
                <span class="text-container">
                    <span class="text">Login</span>
                </span>
                </button>
            </div>
            <!-- <button class="btn-17" type="submit" name="btn">
                <span class="text-container">
                    <span class="text">Login</span>
                </span>
            </button> -->
         </form>
         <?php if(!empty($error)){ ?>
            <p class="alert"><?php echo $error ;?></p> 
            <?php }else{}?>
            <!-- <p class="alert">eroor</p> -->
            <a class="forget" href="emailforget.php"> Forget Password?</a>

            <!-- <button class="btn-17" type="submit" name="btn">
                <span class="text-container">
                    <span class="text">Login</span>
                </span>
            </button> -->
        </div>
        <!-- end form login -->

        <!-- sign up section -->
        <div class="signup-div">

            <h2> New to this website ? </h2>
            <p class="par">Simply Create your account by <br> clicking the Signup Button</p>
            <!-- <p>join us now</p> -->

            <button class="btn-17">
                <span class="text-container">
                    <!-- note:it may not work :(  -->
                 <a href="signup.php"> </a>
                    <span class="text"><a class="btn1" href="signup.php"> Sign-up</a> </span>

                </span>
            </button>
        </div>
    </div>
    <!-- endd of page -->

    <script src="./T4.js/login.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>