<?php 
include "connection.php";
$id=$_SESSION['user_id'];
// $_SESSION['user_id']=$id;
// $user_id = 1;
$select="SELECT * FROM `subscription`";
$runselect=mysqli_query($connect,$select);
$fetchdata=mysqli_fetch_assoc($runselect);
$subscription_id=$fetchdata['subscription_id'];
//is user logged?
//if get 
// if(!empty($user_id)){
//     header("payment.php");
//     //or thispage
// }else{ echo "you are not logged";}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>subcribtion</title>
</head>
<body>
        <?php foreach($runselect as $data) { ?>
            <div class="card p-0" data-name="nature">
                <div class="card-body">
                
                        <h3 class="card-title"> package name:<?php echo $data['subscription_name'];?></h3>
                        <p class="card-text">package price:<?php echo $data['price'];?></p>
                        <p class="card-text">package capacity:<?php echo $data['capacity'];?></p>
                            <!-- condition if free package dont show not working -->
                    
                        <!-- sub_id in anchor? -->

                            <a href="paymentest.php?subid=<?php echo $data['subscription_id']?>">buy package</a>
                         
                        
                   

                    <div class="b">
        <?php } ?>
</body>
</html>