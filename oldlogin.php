    <?php
    include("connection.php");
    $match = "";
    $exist = "";
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
                header("Location:allprojects.php");
            }
            else{
                $match = "The password entered is incorrect";
            }
        }else{
            $exist = "This email doesn't exist";
        }
    }

    ?>


    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        
        <title>Document</title>
    </head>
    <body> 
    <div class="container">
        <div class="login">
            <div class="form">
                <h1>Login</h1>
                <form method="post">
                    <div class="box">
                        <input type="email" name="email" class="input" placeholder="Enter Email" >
                        
                    </div>
                    <div class="box">
                        <input type="password" name="password" class="input" placeholder="Password">
                        
                    </div>
                    <div class="Anchor">
                        <a href="">Forget Password?</a>
                    </div>
                    <input type="submit" value="Login" name="btn" id="submit" class="btn2">
                    <div class="last">
                        <p class="text2">Don't Have An Account? <a href="signup.php" class="sign">Sign Up</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </body>
    </html>
