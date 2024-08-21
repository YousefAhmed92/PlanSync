<?php
include("connection.php");

$tasks_query = "SELECT task_id, task_name FROM `task`";
$tasks_result = mysqli_query($connect, $tasks_query);

$message = ""; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['task_id']) && isset($_POST['assignee_id'])) {
        $task_id = $_POST['task_id'];
        $reporter_id = 1; 
        $assignee_id = $_POST['assignee_id'];

        $task_id = mysqli_real_escape_string($connect, $task_id);
        $reporter_id = mysqli_real_escape_string($connect, $reporter_id);
        $assignee_id = mysqli_real_escape_string($connect, $assignee_id);

        $sql = "INSERT INTO assignments (task_id, reporter_id, assignee_id) VALUES ($task_id, $reporter_id, $assignee_id)";

        if (mysqli_query($connect, $sql)) {
            $message = "Task successfully assigned.";
        } else {
            $message = "Error: " . mysqli_error($connect);
        }
    } else {
        $message = "Error: All required fields are not set.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Task</title>
</head>
<body>
    <h1>Assign Task</h1>
    <?php if (!empty($message)) { echo "<p>$message</p>"; } ?> 
    <div class="main-form">
        <form method="POST">
            <div>
                <label for="task_id">Select Task:</label>
                <select id="task_id" name="task_id" required>
                    <option value="">Select Task</option>
                    <?php
                    while ($task = mysqli_fetch_assoc($tasks_result)) {
                        echo "<option value=\"" . htmlspecialchars($task['task_id']) . "\">" . htmlspecialchars($task['task_name']) . "</option>";
                    }
                    ?>
                </select>
                <br><br>
            </div>

            <div>
                <label for="assignee_id">Select Member:</label>
                <select id="assignee_id" name="assignee_id" required>
                    <option value="">Select Member</option>
                    <?php
                    $members_query = "SELECT * FROM `project_members`";
                    $members_result = mysqli_query($connect, $members_query);
                    while ($member = mysqli_fetch_assoc($members_result)) {
                        echo "<option value=\"" . htmlspecialchars($member['member_id']) . "\">" . htmlspecialchars($member['member-email']) . "</option>";
                    }
                    ?>
                </select>
                <br><br>
            </div>

            <button type="submit" name="submit">Assign Task</button>
        </form>
    </div>
</body>
</html>
