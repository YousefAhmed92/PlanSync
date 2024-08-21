<?php
session_start();
include "connection.php";

$id = $_SESSION['user_id'];

if (isset($_GET['subid'])) {
    $new_subscription_id = $_GET['subid'];

    $select_new_sub = "SELECT * FROM `subscription` WHERE `subscription_id` = '$new_subscription_id'";
    $run_select_new_sub = mysqli_query($connect, $select_new_sub);
    $new_subscription = mysqli_fetch_assoc($run_select_new_sub);

    if (!$new_subscription) {
        echo "Error: Subscription not found.";
        exit;
    }

    $new_sub_name = $new_subscription['subscription_name'];
    $new_sub_price = $new_subscription['price'];
    $new_sub_cap = $new_subscription['capacity'];

    $select_user = "SELECT * FROM `user` JOIN `subscription` ON `user`.`subscription_id` = `subscription`.`subscription_id` WHERE `user_id` = $id";
    $run_select_user = mysqli_query($connect, $select_user);
    $user_data = mysqli_fetch_assoc($run_select_user);

    if (!$user_data) {
        echo "Error: User or subscription not found.";
        exit;
    }

    $current_sub_price = $user_data['price'];
    $price_difference = $new_sub_price - $current_sub_price;

    if (isset($_POST['payment'])) {
        $update_user = "UPDATE `user` SET `subscription_id` = '$new_subscription_id' WHERE `user_id` = $id";
        $updateQry = mysqli_query($connect, $update_user);

        if ($updateQry) {
            header("Location: addmember.php?project_id=".$_SESSION['project_id']);
            exit;
        } else {
            echo "Error: " . mysqli_error($connect);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
</head>
<body>
    <p>Package Name: <?php echo htmlspecialchars($new_sub_name); ?></p>
    <p>Package Price: <?php echo htmlspecialchars($new_sub_price); ?></p>
    <p>Package Capacity: <?php echo htmlspecialchars($new_sub_cap); ?></p>
    <p>Upgrade Price: <?php echo htmlspecialchars($price_difference); ?></p>
    <form method="POST">
        <button type="submit" name="payment">Pay</button>
    </form>
</body>
</html>
