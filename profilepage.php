<?php
include "connection.php";

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$user_id = $_SESSION['user_id'];

// Query for user details
$userQuery = "SELECT * FROM user WHERE user_id = ?";
$userStmt = $connect->prepare($userQuery);
$userStmt->bind_param('i', $user_id);
$userStmt->execute();
$userResult = $userStmt->get_result();
$user = $userResult->fetch_assoc();

if (!$user) {
    echo "You do not have an account.";
    exit;
}

$subscription_id = intval($user['subscription_id']);
$has_subscription = false;

// Check if the user has an active subscription
if ($subscription_id > 0) {
    $subscriptionQuery = "SELECT subscription_name FROM subscription WHERE subscription_id = ?";
    $subscriptionStmt = $connect->prepare($subscriptionQuery);
    $subscriptionStmt->bind_param('i', $subscription_id);
    $subscriptionStmt->execute();
    $subscriptionResult = $subscriptionStmt->get_result();
    $subscription = $subscriptionResult->fetch_assoc();
    
    if ($subscription) {
        $has_subscription = true;
    }
}

// Query for all subscriptions associated with the user
$subscriptionsQuery = "SELECT s.subscription_name 
                       FROM user_subscriptions us
                       JOIN subscription s ON us.subscription_id = s.subscription_id
                       WHERE us.user_id = ?";
$subscriptionsStmt = $connect->prepare($subscriptionsQuery);
$subscriptionsStmt->bind_param('i', $user_id);
$subscriptionsStmt->execute();
$subscriptionsResult = $subscriptionsStmt->get_result();

$subscriptions = [];
while ($row = $subscriptionsResult->fetch_assoc()) {
    $subscriptions[] = $row['subscription_name'];
}

// Query for all projects where the user is a member
$projectsQuery = "SELECT p.project_id, p.project_name 
                  FROM project_members pm
                  JOIN project p ON pm.project_id = p.project_id
                  WHERE pm.member_id = ? AND pm.role_id=2";
                  
$projectsStmt = $connect->prepare($projectsQuery);
$projectsStmt->bind_param('i', $user_id);
$projectsStmt->execute();
$projectsResult = $projectsStmt->get_result();

$projects = [];
while ($row = $projectsResult->fetch_assoc()) {
    $projects[] = $row;
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("location:login.php");
    exit();
}

$sql_tasks = "SELECT t.task_id, t.task_name
              FROM assignments a
              JOIN task t ON a.task_id = t.task_id
              WHERE a.assignee_id = $user_id";
$result_tasks = $connect->query($sql_tasks);

// echo $user_id;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/profilepagee.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .profile-container {
            width: 50%;
            margin: 50px auto;
            background-color:none;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        
        }

        .profile-info {
            margin: 20px 0;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .profile-info label {
            font-weight: bold;
            color: black;
        }

        .profile-info span {
           
            margin-left: 10px;
        }

        /* .actions {
            text-align: center;
            margin-top: 20px;
            
        }

        .actions a {
            text-decoration: none;
            color: #fff;
          
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .actions a:hover {
            background-color: #0056b3;
            text-decoration: none;
        } */

        .actions p {
            margin: 20px 0;
            text-align: center;
        }

        .card {
            margin: 20px 0;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: rgb(195, 193, 193);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .card h2 {
            margin: 0 0 10px;
            color: black;
        }

        .card ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .card ul li {
            margin-bottom: 10px;
            color: black;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background: rgb(195, 193, 193);
        }

        a {
            color: black;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
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
    <div class="profile-container">
        <h1>Welcome, <?= htmlspecialchars($user['username']) ?></h1>
        <div class="profile-info">
            <label>Username:</label>
            <span><?= htmlspecialchars($user['username']) ?></span>
        </div>
        <div class="profile-info">
            <label>Email:</label>
            <span><?= htmlspecialchars($user['email']) ?></span>
        </div>
        
        <!-- Display subscription information -->
        <?php if ($has_subscription): ?>
        <div class="actions">
            <a href="my_subscriptions.php">Paid Subscriptions</a> &MediumSpace;
            <a href="landing.php">Offers</a>
            <!-- <a href="msg.php">messages</a> -->

        </div>
        <?php else: ?>
        <div class="actions">
            <p>You don't have an active subscription. <a href="landing.php">Subscribe now</a>.</p>
        </div>
        
        <?php endif; ?>

        <!-- Card displaying all subscriptions -->
        <div class="card">
            <h2>Your Subscriptions</h2>
            <ul>
                <?php if (!empty($subscriptions)): ?>
                    <?php foreach ($subscriptions as $subscription_name): ?>
                        <li><?= htmlspecialchars($subscription_name) ?></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No subscriptions found.</li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Display all projects the user is a member of -->
         <div class="card">
            <h2>Projects you are member in</h2>
            <ul>
                <?php if (!empty($projects)): ?>
                    <?php foreach ($projects as $project): ?>
                        <li>
                            <a href="project.php?project_id=<?= htmlspecialchars($project['project_id']) ?>">
                                <?= htmlspecialchars($project['project_name']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No projects found.</li>
                <?php endif; ?>
            </ul>
        </div> 

        <h2>Tasks Assigned</h2>
        <table>
            <thead>
                <tr>
                    <th>Task Name</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($task = $result_tasks->fetch_assoc()) { ?>
                    <tr>
                        <td>
                            <a href="taskdetails.php?task_id=<?= htmlspecialchars($task['task_id']) ?>">
                                <?= htmlspecialchars($task['task_name']) ?>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <form method="POST">
            <button type="submit" name="logout">Logout</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
