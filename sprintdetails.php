<?php
session_start();

$connect = new mysqli('localhost', 'root', '', 'updated-case1');

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

$sprint_id = isset($_GET['sprint_id']) ? (int)$_GET['sprint_id'] : null;

if (!$sprint_id) {
    echo "Error: Sprint ID is missing.";
    exit;
}

// Fetch sprint details
$sprint_id = $connect->real_escape_string($sprint_id);
$sql_sprint = "SELECT * FROM sprint WHERE sprint_id = $sprint_id";
$result_sprint = $connect->query($sql_sprint);

if ($result_sprint->num_rows > 0) {
    $sprint = $result_sprint->fetch_assoc();

    // Calculate the sprint period
    $start_date = new DateTime($sprint['start_date']);
    $end_date = new DateTime($sprint['end_date']);
    $interval = $end_date->diff($start_date);
    $sprint_period = $interval->days; // Number of days in the sprint period
} else {
    echo "Error: Sprint not found.";
    exit;
}

// Count the number of tasks
$sql_tasks_count = "SELECT COUNT(*) AS task_count FROM task WHERE sprint_id = $sprint_id";
$result_tasks_count = $connect->query($sql_tasks_count);
$task_count = $result_tasks_count->fetch_assoc()['task_count'];

// Fetch categories for filter
$sql_categories = "SELECT * FROM category";
$result_categories = $connect->query($sql_categories);
$categories = $result_categories->fetch_all(MYSQLI_ASSOC);

// Initialize task query
$task_query = "SELECT * FROM task WHERE sprint_id = $sprint_id";

// Apply search if set
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = $connect->real_escape_string($_GET['search']);
    $task_query .= " AND task_name LIKE '%$search_term%'";
}

// Apply filter if set
if (isset($_GET['filter']) && !empty($_GET['filter'])) {
    $cat_id = $connect->real_escape_string($_GET['filter']);
    $task_query .= " AND category_id = '$cat_id'";
}

// Fetch tasks
$result_tasks = $connect->query($task_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sprint Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        /* Your existing styles */
        * {
            padding: 0;
            box-sizing: border-box;
            text-decoration: none !important;
            list-style: none !important;
            font-family: "Roboto", sans-serif;
        }
        body {
            background-image: url(Img/Capture.PNG);
        }

        .filter, .search-input {
            width: 15%;
            text-decoration: none;
            border-radius: 6px;
            height: 35px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #9191916a;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
            background-color: #9191916a;
        }
        th {
            background-color: #9191916a;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #9191916a;
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
            background-color: #f4f4f4;
        }
        h1, h2 {
            color: #333;
        }
        p {
            font-size: 16px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin: 10px 0;
        }
        a {
            text-decoration: none;
            color: #007BFF;
        }
        a:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .buttons {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 10px;
            background-color: #ff7418;
            padding: 10px;
            border-radius: 5px;
        }
        .button {
            color: white;
            background-color: #ff7418;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
        }
        .button:hover {
            background-color: #e65c00;
        }
        .items {
            display: flex;
            flex-direction: row;
            position: relative;
            left: 70%;
            color: #ddd !important;
            font-size: 22px;
        }
        .nav-link:hover {
            color: white !important;
            opacity: 50%;
        }
        .nav-link {
            color: white !important;
        }
        .navbar-brand {
            color: white !important;
            font-size: 26px !important;
        }
        .btn {
            color: white !important;
            border: solid 1px white !important;
        }
        .btn:hover {
            color: white !important;
            border: solid 1px white !important;
            opacity: 50%;
        }
        nav {
            margin: -20px;
            margin-bottom: 20px !important;
            padding: 0px !important;
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
          <a class="nav-link" aria-current="page" href="profilepage.php">Projects</a>
        </li>
        <li class="nav-item" style="margin-right: 40px;">
          <a class="nav-link" aria-current="page" href="profilepage.php">Tasks</a>
        </li>
        </div>
      </ul>
      <form class="d-flex" role="search" method="get">
        <input style= "width : 300px "class="form-control me-2 search-input" type="search" name="search" placeholder="Search Tasks" aria-label="Search" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
        <input type="hidden" name="sprint_id" value="<?= htmlspecialchars($sprint_id) ?>">
        <button class="btn" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>
<div class="container">
    <h1>Sprint Details</h1>
    <table>
        <tbody>
            <tr>
                <th>Sprint Name</th>
                <td><?= htmlspecialchars($sprint['sprint_name']) ?></td>
            </tr>
            <tr>
                <th>Start Date</th>
                <td><?= htmlspecialchars($sprint['start_date']) ?></td>
            </tr>
            <tr>
                <th>End Date</th>
                <td><?= htmlspecialchars($sprint['end_date']) ?></td>
            </tr>
            <tr>
                <th>Sprint Period</th>
                <td><?= htmlspecialchars($sprint_period) ?> days</td>
            </tr>
            <tr>
                <th>Number of Tasks</th>
                <td><?= htmlspecialchars($task_count) ?></td>
            </tr>
        </tbody>
    </table>

    <h2>Tasks</h2>
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
                        <a href="taskdetails.php?task_id=<?= htmlspecialchars($task['task_id']) ?>" class="task-link">
                            <?= htmlspecialchars($task['task_name']) ?>
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Filter -->
    <form action="" method="get">
        <input type="hidden" name="sprint_id" value="<?= htmlspecialchars($sprint_id) ?>">
        <label for="filter">Filter by Category:</label><br>
        <select id="filter" name="filter" class="filter">
            <option value="">--All Categories--</option>
            <?php foreach($categories as $catdata) { ?>
                <option value="<?= htmlspecialchars($catdata['category_id']) ?>" <?= isset($_GET['filter']) && $_GET['filter'] == $catdata['category_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($catdata['category_name']) ?>
                </option>
            <?php } ?>
        </select>
        <button class="filter" type="submit">Apply Filter</button>
    </form>

    <div class="buttons">
        <button onclick="window.location.href='add_task.php?sprint_id=<?= htmlspecialchars($sprint_id) ?>'" class="button">Create New Task</button>
        <button onclick="window.location.href='project.php?project_id=<?= htmlspecialchars($sprint['project_id']) ?>'" class="button">Back to Project Details</button>
        <button onclick="window.location.href='profilepage.php'" class="button">Your Profile</button>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
