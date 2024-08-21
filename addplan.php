<?php
include ('connection.php');
$conn = new mysqli('localhost', 'root', '', 'c_case1');

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

$message = "";

if (isset($_POST['submit_subscription'])) {
    if (isset($_POST['subscription_name']) && isset($_POST['price']) && isset($_POST['capacity'])) {
        $subscription_name = $connect->real_escape_string($_POST['subscription_name']);
        $price = intval($_POST['price']);
        $capacity = intval($_POST['capacity']);

        $sql = "INSERT INTO subscription (subscription_name, price, capacity) VALUES ('$subscription_name', $price, $capacity)";
        
        if ($connect->query($sql) === TRUE) {
            $message = "New subscription added successfully.";
        } else {
            $message = "Error: " . $connect->error;
        }
    } else {
        $message = "Error: All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Subscription</title>
</head>
<body>
    <h1>Add New Subscription</h1>

    <?php if (!empty($message)) { echo "<p>$message</p>"; } ?>

    <form method="POST">
        <div>
            <label for="subscription_name">Subscription Name:</label>
            <input type="text" id="subscription_name" name="subscription_name" required>
        </div>
        <br>
        <div>
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" required>
        </div>
        <br>
        <div>
            <label for="capacity">Capacity:</label>
            <input type="number" id="capacity" name="capacity" required>
        </div>
        <br>
        <button type="submit" name="submit_subscription">Add Subscription</button>
    </form>

    <a href="subscribe.php">Back to Subscriptions</a>
</body>
</html>
