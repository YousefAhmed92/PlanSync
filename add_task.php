<?php
session_start(); // Start the session

$error_message = ""; // Initialize error message variable

if (isset($_GET['sprint_id'])) {
    $sprint_id = (int)$_GET['sprint_id'];
} else {
    echo "Error: Sprint ID is missing.";
    exit;
}

$connect = new mysqli('localhost', 'root', '', 'updated-case1');

// Check for connection errors
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Fetch status, category, and priority data
$sql_status = "SELECT * FROM status";
$result_status = $connect->query($sql_status);

$sql_category = "SELECT * FROM category";
$result_category = $connect->query($sql_category);

$sql_priority = "SELECT * FROM priority";
$result_priority = $connect->query($sql_priority);

// Fetch sprint start and end dates
$sql_sprint = "SELECT start_date, end_date FROM sprint WHERE sprint_id = ?";
$stmt_sprint = $connect->prepare($sql_sprint);
$stmt_sprint->bind_param("i", $sprint_id);
$stmt_sprint->execute();
$result_sprint = $stmt_sprint->get_result();

if ($result_sprint->num_rows > 0) {
    $sprint = $result_sprint->fetch_assoc();
    $start_date = new DateTime($sprint['start_date']);
    $end_date = new DateTime($sprint['end_date']);
    $sprint_duration = $end_date->diff($start_date)->days; // Duration in days
} else {
    echo "Error: Sprint not found.";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_name = $_POST['task_name'];
    $description = $_POST['description'];
    $status_id = (int)$_POST['status_id'];
    $priority_id = (int)$_POST['priority_id'];
    $category_id = (int)$_POST['category_id'];
    $deadline_days = (int)$_POST['deadline_days'];

    // Validate session user ID
    if (!isset($_SESSION['user_id'])) {
        echo "Error: User not logged in.";
        exit;
    }
    $user_id = (int)$_SESSION['user_id'];

    // Calculate the task deadline
    $task_deadline = (clone $start_date)->add(new DateInterval('P' . $deadline_days . 'D'));

    if ($task_deadline > $end_date) {
        $error_message = "Error: Task deadline exceeds the sprint period.";
    } else {
        $sql = "INSERT INTO task (task_name, description, status_id, priority_id, category_id, sprint_id, deadline, created_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("ssiiissi", $task_name, $description, $status_id, $priority_id, $category_id, $sprint_id, $deadline_days, $user_id);
        $stmt->execute();

        header("Location: sprintdetails.php?sprint_id=" . $sprint_id);
        exit;
    }
}

$connect->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanSync</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lobster+Two:ital,wght@0,400;0,700;1,400;1,700&family=Montserrat:ital,wght@0,100..900;1,100..900&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/addtask.css">
    <style>
        .notification {
            display: none;
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container-content">
        <?php if ($error_message): ?>
            <div class="notification" id="notification">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <div class="content">
            <form method="POST" class="form1">
                <h2>Create Task</h2>
                <input type="text" name="task_name" placeholder="Task Name" class="inputs" required>
                <input type="text" name="description" placeholder="Task Description" class="inputs" required>
                <input type="number" name="deadline_days" placeholder="Days to Deadline" class="inputs" required>

                <div class="form2">
                    <select name="status_id" class="inputs" required>
                        <option value="" disabled selected>Status</option>
                        <?php while ($row_status = $result_status->fetch_assoc()) { ?>
                            <option value="<?php echo $row_status['status_id']; ?>"><?php echo $row_status['status_name']; ?></option>
                        <?php } ?>
                    </select>

                    <select name="priority_id" class="inputs" required>
                        <option value="" disabled selected>Priority</option>
                        <?php while ($row_priority = $result_priority->fetch_assoc()) { ?>
                            <option value="<?php echo $row_priority['priority_id']; ?>"><?php echo $row_priority['priority_name']; ?></option>
                        <?php } ?>
                    </select>

                    <select name="category_id" class="inputs" required>
                        <option value="" disabled selected>Category</option>
                        <?php while ($row_category = $result_category->fetch_assoc()) { ?>
                            <option value="<?php echo $row_category['category_id']; ?>"><?php echo $row_category['category_name']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <button type="submit" class="btn-17">
                    <span class="text-container">
                        <span class="text">Add Task</span>
                    </span>
                </button>
            </form>
        </div>
    </div>
    <script>
        // JavaScript to handle the notification display and auto-hide
        document.addEventListener("DOMContentLoaded", function() {
            var notification = document.getElementById("notification");
            if (notification) {
                notification.style.display = "block";
                setTimeout(function() {
                    notification.style.display = "none";
                }, 3000); // Hide after 3 seconds
            }
        });
    </script>
</body>

</html>
