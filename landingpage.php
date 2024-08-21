<?php
include "connection.php";

$id = $_SESSION['user_id'] ?? null;

$subscriptionQuery = "SELECT * FROM subscription";
$subscriptionResult = mysqli_query($connect, $subscriptionQuery);

if (!$subscriptionResult) {
    die("Database query failed: " . mysqli_error($connect));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Plans</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            background-color: #f4f4f4;
        }
        h1 {
            color: #333;
        }
        .card {
            background: #fff;
            margin: 10px 0;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            margin: 0;
            font-size: 1.5em;
            color: #333;
        }
        .card-text {
            margin: 5px 0;
            color: #555;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .message {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Subscription Plans</h1>
    <?php if ($id): ?>
        <?php while ($plan = mysqli_fetch_assoc($subscriptionResult)) { ?>
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Package Name: <?php echo htmlspecialchars($plan['subscription_name']); ?></h3>
                    <p class="card-text">Package Price: <?php echo htmlspecialchars($plan['price']); ?></p>
                    <p class="card-text">Package Capacity: <?php echo htmlspecialchars($plan['capacity']); ?></p>
                    <a href="bayment.php?subid=<?php echo htmlspecialchars($plan['subscription_id']); ?>">Buy Package</a>
                </div>
            </div>
        <?php } ?>
    <?php else: ?>
        <p class="message">You must sign up first <a href="login.php">login</a></p>
    <?php endif; ?>

    <br><br>
    <a href="profilepage.php">Your Profile</a>
</body>
</html>
