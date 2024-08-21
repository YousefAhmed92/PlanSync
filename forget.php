<?php
//maybe use the error div after testing
include "mail.php";
$error="";
if(isset($_POST['submit'])){
     $email=$_POST['email'];
    $_SESSION['email']=$email;

     $select="SELECT * FROM user where email='$email'";
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
     <title>Forget password</title>
</head>
<body>
     <div>
     <form method="POST"> 
          <label for="email">enter email</label>
     <input type="email" id="email" name="email" id=""><br><br>
     <button type="submit" name="submit">submit</button>
     </form>
</div>
<?php if(!empty($error)){ ?>
     <p> <?php echo $error ; ?></p>
<?php } else{}?>




<style>
     input,label,button{
          display: block;
          margin-left: 20px;
     }
     div{
     border-radius: 20px;
          height: 200px;
          width: 200px;
          display: flex;
          align-items: center;
          align-content: center;
          border: solid 5px black;
          margin-left: 550px;
          margin-top: 200px;
     }
</style>
</body>
</html>