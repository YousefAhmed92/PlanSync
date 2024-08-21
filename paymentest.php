<?php
include "connection.php";
$id=$_SESSION['user_id'];

// $select="SELECT * FROM `subscription` JOIN `user` ON `subscription`.`subscription_id`=`user`.`subscription_id`
//  WHERE `user_id`= $id ";
if (isset($_GET['subid'])){
    $subscription_id=$_GET['subid'];

$select="SELECT * FROM `subscription` WHERE `subscription_id`= '$subscription_id' ";
$runselect=mysqli_query($connect,$select);
$fetchdata=mysqli_fetch_assoc($runselect);

$subname=$fetchdata['subscription_name'];
$subprice=$fetchdata['price'];
$subcap=$fetchdata['capacity'];

// if (isset($_GET['subid'])){
//     $subscription_id=$_GET['subid'];
// }


if(isset($_POST['payment'])){
    $select_role="SELECT * FROM `user` WHERE `subscription_id`='$subscription_id'";
    //roleid
    $run_select_role=mysqli_query($connect,$select_role);      
    //if i added id in anchor i suppose to use if get? and then the update as awhole??
    //added subid change?
    $role = 1;
    $update = "UPDATE `user` SET `role_id` = '$role' , `subscription_id`= '$subscription_id'";
    $updateQry = mysqli_query($connect, $update);
    
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>payment</title>
</head>
<body>
   
        <p> package name: <?php echo $subname;?></p>
        <p>package price: <?php echo $subprice;?></p>
        <p> package capacity: <?php echo $subcap;?></p>
        <form method="POST">
        <button type="submit" name="payment"> payment</button>
        </form>
       
    


</body>
</html>