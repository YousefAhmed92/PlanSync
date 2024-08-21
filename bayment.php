<?php

// include "connection.php";
include "mail.php";

if (!isset($_SESSION['user_id'])) {
    die("User not logged in. Please <a href='login.php'>log in</a>.");
}

$user_id = $_SESSION['user_id'];
$subscription_id = isset($_GET['subid']) ? intval($_GET['subid']) : 0;

if ($subscription_id <= 0) {
    die("Invalid subscription ID.");
}

$conn = new mysqli('localhost', 'root', '', 'updated-case1');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

$subscriptionQuery = "SELECT * FROM subscription WHERE subscription_id = ?";
$stmt = $conn->prepare($subscriptionQuery);
$stmt->bind_param('i', $subscription_id);
$stmt->execute();
$subscriptionResult = $stmt->get_result();
$subscription = $subscriptionResult->fetch_assoc();

if (!$subscription) {
    die("Subscription not found.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['purchase_subscription'])) {
    // Insert the subscription into user_subscriptions
    $sql = "INSERT INTO user_subscriptions (user_id, subscription_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $user_id, $subscription_id);
    $stmt->execute();
    $stmt->close();

    // Fetch user email and username
    $select_email = "SELECT * FROM user WHERE user_id = ?";
    $stmt = $conn->prepare($select_email);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $run_select_email = $stmt->get_result();
    $fetch_email = $run_select_email->fetch_assoc();
    $email = $fetch_email['email'];
    $username = $fetch_email['username'];

    // Fetch subscription details
    $select_data = "SELECT * FROM subscription WHERE subscription_id = ?";
    $stmt = $conn->prepare($select_data);
    $stmt->bind_param('i', $subscription_id);
    $stmt->execute();
    $run_select_data = $stmt->get_result();
    $fetch_data = $run_select_data->fetch_assoc();
    $subscription_name = $fetch_data['subscription_name'];
    $subscription_price = $fetch_data['price'];
    $subscription_capacity = $fetch_data['capacity'];

    // Send confirmation email
    $payment_msg = "Hello $username, your subscription package is now activated! 
    Subscription details: 
    Package name: $subscription_name 
    Package price: $subscription_price 
    Package capacity: $subscription_capacity 
    Thank you for your trust and enjoy your productive journey on PlanSync!";

    $mail->setFrom('fatma.said283@gmail.com', 'PlanSync');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Subscription Secured';
    $mail->Body = $payment_msg;
    $mail->send(); 

    // Update the user's role
    $updateUserQuery = "UPDATE user SET subscription_id = ?, role_id = 1 WHERE user_id = ?";
    $stmt = $conn->prepare($updateUserQuery);
    $stmt->bind_param('ii', $subscription_id, $user_id);
    if ($stmt->execute()) {
        // Update the user's role in the project_members table
        $updateProjectMembersQuery = "UPDATE project_members SET role_id = 1 WHERE member_id = ?";
        $stmt = $conn->prepare($updateProjectMembersQuery);
        $stmt->bind_param('i', $user_id);

        if ($stmt->execute()) {
            $message = "Subscription purchased successfully, and role updated.";
            header("Location: profilepage.php");
            exit();
        } else {
            $message = "Error updating role in project_members: " . $stmt->error;
        }
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close(); // Close the statement
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase Subscription</title>
    <link rel="stylesheet" href="./css/bayment.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
       
    </style>
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
        <li class="nav-item" style="margin-right: 40px;">
          <a class="nav-link" aria-current="page" href="#">Profile</a>
        </li>
        <li class="nav-item" style="margin-right: 40px;">
          <a class="nav-link" aria-current="page" href="#">Projects</a>
        </li>
        <li class="nav-item" style="margin-right: 40px;">
          <a class="nav-link" aria-current="page" href="#">Tasks</a>
        </li>
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


    <?php if (!empty($message)): ?>
        <div class="message <?php echo isset($error) ? 'error' : ''; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST">
    <h1>Purchase Subscription</h1>
        <div>
            <label for="subscription_name">Package Name:</label>
            <input type="text" id="subscription_name" value="<?php echo htmlspecialchars($subscription['subscription_name']); ?>" readonly>
        </div>
        <br>
        <div>
            <label for="subscription_price">Package Price:</label>
            <input type="text" id="subscription_price" value="<?php echo htmlspecialchars($subscription['price']); ?>" readonly>
        </div>
        <br>
        <div>
            <label for="subscription_capacity">Package Capacity:</label>
            <input type="text" id="subscription_capacity" value="<?php echo htmlspecialchars($subscription['capacity']); ?>" readonly>
        </div>
        <div>
            <label for="subscription_capacity">credit card:</label>
            <input type="number" >
        </div>
        <br>
        <button type="submit" name="purchase_subscription">buy plan</button>
        <a class=a href="profilepage.php">Back to Profile</a>


    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
