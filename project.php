<?php
session_start();

$project_id = isset($_GET['project_id']) ? (int)$_GET['project_id'] : null;
$user_id = $_SESSION['user_id']; // Keep the user ID from the session

if (!$project_id) {
    echo "Error: Project ID is missing.";
    exit;
}

$connect = new mysqli('localhost', 'root', '', 'updated-case1');

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

$project_id = $connect->real_escape_string($project_id);

// Fetch project details
$sql = "SELECT * FROM project WHERE project_id = $project_id";
$result = $connect->query($sql);
$project = $result->fetch_assoc();

if (!$project) {
    echo "Error: Project not found.";
    exit;
}

// Fetch sprints and members
$sql_sprints = "SELECT * FROM sprint WHERE project_id = $project_id";
$result_sprints = $connect->query($sql_sprints);

$sql_members = "SELECT * FROM project_members 
                JOIN user u ON project_members.member_id = u.user_id 
                WHERE project_members.project_id = $project_id";
$result_members = $connect->query($sql_members);

$member_count = $result_members->num_rows;

$sql_subscription = "SELECT subscription.capacity, subscription.subscription_id 
                     FROM subscription 
                     JOIN project ON subscription.subscription_id = project.subscription_id 
                     WHERE project.project_id = $project_id";
$result_subscription = $connect->query($sql_subscription);
$subscription = $result_subscription->fetch_assoc();

$capacity = $subscription ? $subscription['capacity'] : 0;
$remaining_capacity = $capacity - $member_count;

$can_add_member = $member_count < $capacity;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_member') {
    if (isset($_POST['project_id']) && isset($_POST['member_id'])) {
        $project_id = (int)$_POST['project_id'];
        $member_id = (int)$_POST['member_id'];

        $sql_delete = "DELETE FROM project_members WHERE project_id = ? AND member_id = ?";
        $stmt = $connect->prepare($sql_delete);
        $stmt->bind_param("ii", $project_id, $member_id);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete member.']);
        }

        $stmt->close();
        $connect->close();
        exit;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="./css/project.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <meta charset="UTF-8">
    <title>Project Details</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #9191916a;
            color: black;
        }

        tr:nth-child(even) {
            background-color: #9191916a;
        }

        .delete-button {
            color: #e74c3c;
            background: none;
            border: none;
            cursor: pointer;
        }

        .delete-button:hover {
            text-decoration: underline;
        }
        .color{
            color: black;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
            color: #333;
        }

        h1, h2 {
            color: #b6622b;
        }

        h1 {
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        h2 {
            margin-top: 10px;
            color: #b6622b;
            text-align: center;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: transparnt;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 0;
            font-size: 16px;
            color: #fff;
            background-color: #ff7418;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
        }

        .button:hover {
            background-color: #2980b9;
        }

        .error {
            color: #e74c3c;
            font-weight: bold;
        }
        .delete-button{
            color: black;
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
    <div class="container">
        <h1>Project: <?= htmlspecialchars($project['project_name']) ?></h1>
        <table>
            <th>Description</th>
            <tr>
                <td><?= htmlspecialchars($project['description']) ?></td>
            </tr>
        </table>
        <h2>Sprints</h2>
        <table>
            <thead>
                <tr>
                    <th>Sprint Name</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_sprints->fetch_assoc()) { ?>
                    <tr>
                        <td>
                            <a class=color href="sprintdetails.php?sprint_id=<?= htmlspecialchars($row['sprint_id']) ?>">
                                <?= htmlspecialchars($row['sprint_name']) ?>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <h2>Project Members</h2>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_members->fetch_assoc()) { ?>
                    <tr>
                        <td>
                            <a class=color href="profile.php?user_id=<?= htmlspecialchars($row['user_id']) ?>">
                                <?= htmlspecialchars($row['username']) ?>
                            </a>
                        </td>
                        <td>
                            <?php if ($user_id == $project['user_id']) { ?>
                                <button onclick="deleteMember(<?= htmlspecialchars($project_id) ?>, <?= htmlspecialchars($row['user_id']) ?>, this)" class="delete-button">Delete</button>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <h2>Project Capacity</h2>
        <table>
            <thead>
                <tr>
                    <th>Metric</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Capacity</td>
                    <td><?= htmlspecialchars($capacity) ?></td>
                </tr>
                <tr>
                    <td>Number of Members</td>
                    <td><?= htmlspecialchars($member_count) ?></td>
                </tr>
                <tr>
                    <td>Remaining Capacity</td>
                    <td><?= htmlspecialchars($remaining_capacity) ?></td>
                </tr>
            </tbody>
        </table>



        <?php if ($user_id == $project['user_id']) { ?>
    <?php if ($can_add_member) { ?>
        <a href="addmember.php?project_id=<?= htmlspecialchars($project_id) ?>" class="button">Manage Members</a>
    <?php } else { ?>
        <p class="error">Error: Cannot add more members. The subscription capacity has been reached.</p>
        <a href="upgrade_package.php?project_id=<?= htmlspecialchars($project_id) ?>" class="button">Upgrade Package</a>
    <?php } ?>
<?php } ?>
<a href="CreateSprint.php?project_id=<?= htmlspecialchars($project_id) ?>" class="button">Create New Sprint</a>





    </div>






    <script>
        function deleteMember(projectId, memberId, element) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onload = function () {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        element.closest('tr').remove();
                    } else {
                        alert(response.message);
                    }
                } else {
                    alert("An error occurred while trying to delete the member.");
                }
            };

            xhr.send("action=delete_member&project_id=" + encodeURIComponent(projectId) + "&member_id=" + encodeURIComponent(memberId));
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>
</html>
