<?php
include 'connection.php';

if(isset($_POST['submit'])){
     $email=$_SESSION['email'];
     $password=$_POST['pass'];
     $confirm=$_POST['cpass'];

     $uppercase = preg_match('@[A-Z]@', $password);
     $lowercase = preg_match('@[a-z]@', $password);
     $numbers = preg_match('@[0-9]@', $password);
     $character = preg_match('@[^a-zA-Z0-9]@', $password);

     if(empty($password)||empty($confirm)){
          echo "please fill required data";
     }elseif($password!=$confirm){
          echo "password doesn't match comfirm password";

     }elseif($uppercase<1||$lowercase<1||$numbers<1||$character<1){
          echo "password should contain atleast 1 uppercase,lowercase,numbers or special character";
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
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Change password</title>
</head>
<body>
     <form method="POST">
          <label for="password">password</label>
          <input type="password" name="pass" id="password"><br>
          <br><br>
          <label for="">confirm password</label>
          <input type="password" name="cpass" id=""><br><br>
          <button type="submit" name="submit">submit</button>
     </form>
</body>
</html>