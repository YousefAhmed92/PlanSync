<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$user_id = $_SESSION['user_id'];
$subscription_id = isset($_GET['subscription_id']) ? intval($_GET['subscription_id']) : 0;

if ($subscription_id <= 0) {
    die("Invalid subscription ID.");
}

$conn = new mysqli('localhost', 'root', '', 'updated-case1');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM subscription WHERE subscription_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $subscription_id);
$stmt->execute();
$result = $stmt->get_result();
$subscription = $result->fetch_assoc();

$search_query = isset($_GET['query']) ? '%' . $_GET['query'] . '%' : '%';
$projectQuery = "SELECT project_id, project_name FROM project WHERE subscription_id = ? AND user_id = ? AND project_name LIKE ?";
$projectStmt = $conn->prepare($projectQuery);
$projectStmt->bind_param('iis', $subscription_id, $user_id, $search_query);
$projectStmt->execute();
$projectsResult = $projectStmt->get_result();

$conn->close();

$show_notification = isset($_GET['success']) && $_GET['success'] == 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <link rel="stylesheet" href="./css/subscription.css">
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <meta charset="UTF-8">
    <title>Subscription Details</title>
<style>
  body {
    font-family: Arial, sans-serif;
    background-color: #f7f7f7; /* Background color for the body */
    margin: 0;
    padding: 20px;
    color: #333;

}

table {
    width: 70%;
    border-collapse: collapse;
    margin: 40px auto 60px auto; /* Centering the table */
    background-color: #fff; /* White background for the table */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Light shadow for subtle depth */
}

table, th, td {
    border: 1px solid #ff7418; /* Primary color for borders */
}

th, td {
    padding: 10px;
    text-align: left;
}

th {
    background-color: #f8f8f8; /* Light gray for table headers */
    color: #ff7418; /* Primary color for header text */
}


.projects-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center; /* Center align the cards */
}

.project-card {
    background-color: #ffffff; /* White background for cards */
    border: 2px solid #ff7418; /* Primary color for card borders */
    border-radius: 8px;
    padding: 15px;
    width: calc(25% - 20px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Light shadow for depth */
    transition: transform 0.3s ease-in-out;
    text-align: center;
}

.project-card:hover {
    transform: scale(1.02); /* Slight zoom effect on hover */
}

.project-card p {
    margin: 0 0 10px;
}

.view-details {
    display: inline-block;
    padding: 8px 12px;
    text-decoration: none;
    color: #ffffff; /* White text for buttons */
    background-color: #ff7418; /* Primary color for button background */
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.view-details:hover {
    background-color: #e06414; /* Slightly darker shade for hover effect */
}

.button {
    display: inline-block;
    padding: 10px 20px;
    margin-top: 20px;
    background-color: #ff7418; /* Primary color for buttons */
    color: #fff; /* White text */
    border: none;
    border-radius: 5px;
    text-align: center;
    cursor: pointer;
    text-decoration: none;
}

.button:hover {
    background-color: #e06414; /* Slightly darker shade for hover effect */
}

.notification {
    display: none;
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #4CAF50; /* Green background for notifications */
    color: white;
    padding: 15px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    z-index: 1000;
}

.notification .close {
    float: right;
    font-size: 16px;
    cursor: pointer;
    margin-left: 15px;
}

.buttons {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    margin-top: 20px;
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
      
      <form class="d-flex" role="search" method="get">
    <input type="hidden" name="subscription_id" value="<?= htmlspecialchars($subscription_id) ?>">
    <input class="form-control me-2" type="search" name="query" placeholder="Search" aria-label="Search" value="<?= isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '' ?>">
    <button class="btn" type="submit">Search</button>
</form>



      <!-- <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn" type="submit">Search</button>
      </form> -->


    </div>
  </div>
</nav>
<div class="container">
    <?php if ($show_notification): ?>
        <div id="notification" class="notification">
            Project is added successfully!
            <span class="close">&times;</span>
        </div>
    <?php endif; ?>

    <h1>Subscription Details</h1>

    <?php if ($subscription): ?>
        <div class="details">
            <div class="right">

            </div>
        </div>
        <table>
            <tr>
                <th>Package Name</th>
                <td><?= htmlspecialchars($subscription['subscription_name']) ?></td>
            </tr>
            <tr>
                <th>Package Price</th>
                <td><?= htmlspecialchars($subscription['price']) ?></td>
            </tr>
            <tr>
                <th>Package Capacity</th>
                <td><?= htmlspecialchars($subscription['capacity']) ?></td>
            </tr>
        </table>
    <?php else: ?>
        <p>Subscription details not found.</p>
    <?php endif; ?>

    <h1>Associated Projects</h1>
<?php if ($projectsResult->num_rows > 0): ?>
    <div class="projects-container">
        <?php while ($project = $projectsResult->fetch_assoc()): ?>
            <div class="project-card">
                <p><?= htmlspecialchars($project['project_name']) ?></p>
                <a href="project.php?project_id=<?= htmlspecialchars($project['project_id']) ?>" class="view-details">View Details</a>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <p class="no-projects">No projects found.</p>
<?php endif; ?>


    <div class="buttons">
    <a href="createproject.php?subscription_id=<?= htmlspecialchars($subscription_id) ?>" class="button">Create New Project</a>
    <a href="profilepage.php" class="button">Profile</a>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            var notification = $('#notification');
            if (notification.length) {
                notification.fadeIn().delay(5000).fadeOut();

                $('.close').click(function() {
                    notification.fadeOut();
                });
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>