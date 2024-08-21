<?php
include("connection.php");
$user_id = $_SESSION['user_id'];
$select_edit = "SELECT * FROM `user` WHERE `user_id` = '$user_id'";
$runedit = mysqli_query($connect, $select_edit);
$fetch = mysqli_fetch_assoc($runedit);
$name=$fetch['username'] ;
$email=$fetch['email'];
if(isset($_POST['update'])){
    $username = $_POST['username'];
    $edit_email = $_POST['email'];
    $update = "UPDATE `user` SET `username`= '$username' ,  `email` = '$edit_email'  WHERE `user_id` = '$user_id'";
    $runupdate = mysqli_query($connect , $update);

    if($fetch['user_id'] > 1){
        header("location:profilpage.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Info.</title>
 
   
</head>

<body>


    <div class="container">
        <div class="login">
            <div class="form">

                <form method="post">
                    <h1>Edit Your Info.</h1>
                    <div class="box">
                  <input type="text" name="username" class="input" placeholder="Enter First Name"
                        value="<?php echo $name ; ?>">
                        <input type="email" name="email" class="input" placeholder="Enter First Name"
                        value="<?php echo $email ; ?>">
                                       <div class="Button">
                        <input type="submit" value="Edit" name="update" id="submit" class="btn2">
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</body>

</html>