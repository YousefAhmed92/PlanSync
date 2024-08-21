<?php
include('connection.php');

if (isset($_GET['user_id'])) {
    $member_id = $_GET['user_id'];
} else {
    echo "Error: Member ID is missing.";
    exit;
}

$sql_member = "SELECT * FROM user WHERE user_id = $member_id";
$result_member = $connect->query($sql_member);
$member = $result_member->fetch_assoc();

if (!$member) {
    echo "Error: Member not found.";
    exit;
}

$sql_projects = "SELECT p.project_id, p.project_name 
                 FROM project_members pm
                 JOIN project p ON pm.project_id = p.project_id
                 WHERE pm.member_id = $member_id";
$result_projects = $connect->query($sql_projects);

$sql_tasks = "SELECT t.task_id, t.task_name
              FROM assignments a
              JOIN task t ON a.task_id = t.task_id
              WHERE a.assignee_id = $member_id";
$result_tasks = $connect->query($sql_tasks);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Member Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-image: url(Img/Capture.PNG);
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            /* background-color: #f4f4f4; */
        }
        h1 {
            color: black;
            border-bottom: 2px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        h2 {
            color: #ff7418;
            border-bottom: 1px solid #ddd;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            color:black;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            background-color: #9191916a;
            
        }
        th {
            background-color: #9191916a;
            
        }
        tr:nth-child(even) {
            background-color: #9191916a;
            
        }
        a {
            color:black;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            color: black;
            text-decoration: none !important;
            font-weight: bold;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        /* naaaavvvv */
.items {
    display: flex;
    flex-direction: row;
    position: absolute;
    top: 10%;
    right: 0%;
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
nav{
    margin: -20px;
    margin-bottom:10px !important;
}

/* -------------------------------------------------naaaavvvvv---------------------------------------------------------- */


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
    <h1>Member Profile: <?= htmlspecialchars($member['username']) ?></h1>

    <h2>Projects Assigned</h2>
    <table>
        <thead>
            <tr>
                <th>Project Name</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($project = $result_projects->fetch_assoc()) { ?>
                <tr>
                    <td>
                        <a href="project.php?project_id=<?= htmlspecialchars($project['project_id']) ?>">
                            <?= htmlspecialchars($project['project_name']) ?>
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

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

    <!-- <a class="back-link" href="sprintdetails.php?sprint_id=<?= htmlspecialchars($task['sprint_id']) ?>">Back to Sprint Details</a> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>
</html>
