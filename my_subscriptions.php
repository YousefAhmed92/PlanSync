<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$user_id = $_SESSION['user_id'];

$conn = new mysqli('localhost', 'root', '', 'updated-case1');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT s.subscription_id, s.subscription_name 
        FROM subscription s
        JOIN user_subscriptions us ON s.subscription_id = us.subscription_id
        WHERE us.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="./css/my_subscriptions.Css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <meta charset="UTF-8">
    <title>My Subscriptions</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        h1{
            color:black;
            text-align: center;
            margin-top:10%;
        }

        .container1 {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center; /* Center the cards */
/* border-color: rgb(74, 74, 74); */
            margin-top:5%;
        }

        .subscription {
            /* background-color: #fff;
            border: 2px solid #ff7418;  */
            border-radius: 8px;
            padding: 15px;
            width: calc(50% - 20px); /* Half width minus the gap */
            box-shadow: 0 40px 40px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        border:  2px solid ;
        }

        .subscription:hover {
            transform: scale(1.02);
        }

        .subscription h3 {
            margin: 0;
            color: black;
        }

        .subscription a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #fff;
            background-color: #ff7418;
            padding: 8px 12px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .subscription a:hover {
            background-color: #e06414;
        }
.back{
    display: flex;
    justify-content:center;
    align-items:center;
    margin-top:10px;


} 


        .button {
           color:whitesmoke;
            text-decoration: none;
            margin-top: 20px;
            display: flex;
            text-align: center;
            justify-content:center;
            margin-top: 10px;
            text-decoration: none;
            color: #fff;
            background-color: #ff7418;
            padding: 8px 12px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .back a:hover {
            text-decoration: none;
 transform: scale(1.02);
        
        }
    </style>

</head>
<body>
    
    <!-- start nav bar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary" style="background-color: rgba(50, 50, 50, 0.848) !important;">
  <div class="container-fluid">
    <a class="navbar-brand" href="landing.php">PlanSync</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <div class="items">
        <li class="nav-item" style="margin-right: 40px;">
          <a class="nav-link" aria-current="page" href="landing.php">Home</a>
        </li>
        <li class="nav-item" style="margin-right: 40px;">
          <a class="nav-link" aria-current="page" href="profilepage.php">Profile</a>
        </li>
        <li class="nav-item" style="margin-right: 40px;">
          <a class="nav-link" aria-current="page" href="my_subscriptions.php">my subscriptions</a>
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
    <h1>My Subscriptions</h1>

    <div class="container1">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="subscription">
                <h3><?php echo htmlspecialchars($row['subscription_name']); ?></h3>
                <a href="subscriptiondetails.php?subscription_id=<?php echo htmlspecialchars($row['subscription_id']); ?>">View Details</a>
            </div>
        <?php } ?>
    </div>

    <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="subscription">
            <h3><?php echo htmlspecialchars($row['subscription_name']); ?></h3>
            <a href="subscriptiondetails.php?subscription_id=<?php echo htmlspecialchars($row['subscription_id']); ?>">View Details</a>
        </div>
    <?php } ?>
<div class="back">
    <div class="anchor2">
    <a class=button href="profilepage.php">Back to Profile</a>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
</body>
</html>
