<?php
include('connection.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Error: You must be logged in to view or assign tasks.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Get task ID from the URL
if (!isset($_GET['task_id']) || !is_numeric($_GET['task_id'])) {
    echo "Error: Task ID is missing or invalid.";
    exit;
}

$task_id = intval($_GET['task_id']);

// Fetch the task details and sprint ID from the task table
$sql_task = "SELECT t.task_id, t.task_name, t.description, t.deadline, t.sprint_id, s.status_name, p.priority_name, c.category_name
             FROM task t
             JOIN status s ON t.status_id = s.status_id
             JOIN priority p ON t.priority_id = p.priority_id
             JOIN category c ON t.category_id = c.category_id
             WHERE t.task_id = ?";
$stmt_task = $connect->prepare($sql_task);
$stmt_task->bind_param("i", $task_id);
$stmt_task->execute();
$result_task = $stmt_task->get_result();

if ($result_task->num_rows === 0) {
    echo "Error: Task not found.";
    exit;
}

$task = $result_task->fetch_assoc();
$sprint_id = $task['sprint_id']; // Fetch sprint_id from the task details

// Fetch the project_id from the sprint table using the sprint_id
$sql_project = "SELECT project_id FROM sprint WHERE sprint_id = ?";
$stmt_project = $connect->prepare($sql_project);
$stmt_project->bind_param("i", $sprint_id);
$stmt_project->execute();
$result_project = $stmt_project->get_result();

if ($result_project->num_rows === 0) {
    echo "Error: Project not found for this sprint.";
    exit;
}

$project_row = $result_project->fetch_assoc();
$project_id = $project_row['project_id'];





$sql_assignment = "SELECT * FROM assignments WHERE task_id = ? AND assignee_id = ?";
$stmt_assignment = $connect->prepare($sql_assignment);
$stmt_assignment->bind_param("ii", $task_id, $user_id);
$stmt_assignment->execute();
$result_assignment = $stmt_assignment->get_result();

$is_assignee09 = $result_assignment->num_rows > 0;


// Fetch project members
$sql_members = "SELECT u.*
                FROM project_members pm
                JOIN user u ON pm.member_id = u.user_id
                WHERE pm.project_id = $project_id 
                AND u.user_id NOT IN (
                    SELECT assignee_id 
                    FROM assignments 
                    WHERE task_id = $task_id
                )";
$result_members = $connect->query($sql_members);


// Fetch the assignee for this task
$sql_assignee = "SELECT assignee_id FROM assignments WHERE task_id = ?";
$stmt_assignee = $connect->prepare($sql_assignee);
$stmt_assignee->bind_param("i", $task_id);
$stmt_assignee->execute();
$result_assignee = $stmt_assignee->get_result();

if ($result_assignee->num_rows > 0) {
    $assignee_row = $result_assignee->fetch_assoc();
    $assignee_id = $assignee_row['assignee_id'];
} else {
    $assignee_id = null;
}
$is_assignee = ($assignee_id == $user_id);





$sql_reporter = "SELECT reporter_id FROM assignments WHERE task_id = ?";
$stmt_reporter = $connect->prepare($sql_reporter);
$stmt_reporter->bind_param("i", $task_id);
$stmt_reporter->execute();
$result_reporter = $stmt_reporter->get_result();

if ($result_reporter->num_rows > 0) {
    $reporter_row = $result_reporter->fetch_assoc();
    $reporter_id = $reporter_row['reporter_id'];
} else {
    $reporter_id = null;
}
$is_reporter = ($reporter_id == $user_id);








// Handle form submission for updating the task
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_task'])) {
    $task_name = $connect->real_escape_string($_POST['task_name']);
    $description = $connect->real_escape_string($_POST['description']);
    $deadline = $connect->real_escape_string($_POST['deadline']);
    
    // Retrieve sprint details
    $sql_sprint = "SELECT start_date, end_date FROM sprint WHERE sprint_id = ?";
    $stmt_sprint = $connect->prepare($sql_sprint);
    $stmt_sprint->bind_param("i", $sprint_id); // Use sprint_id from task details
    $stmt_sprint->execute();
    $result_sprint = $stmt_sprint->get_result();

    if ($result_sprint->num_rows === 0) {
        echo "Error: Sprint not found.";
        exit;
    }

    $sprint = $result_sprint->fetch_assoc();
    $start_date = new DateTime($sprint['start_date']);
    $end_date = new DateTime($sprint['end_date']);
    $sprint_period = $end_date->diff($start_date)->days;
   

    // Check if deadline is within the sprint period
    if ($deadline <= $sprint_period) {
        $sql_update = "UPDATE task SET task_name = ?, description = ?, deadline = ? WHERE task_id = ?";
        $update_stmt = $connect->prepare($sql_update);
        $update_stmt->bind_param("ssii", $task_name, $description, $deadline, $task_id);
        
        if ($update_stmt->execute()) {
            echo "Task updated successfully.";
            header("Location: taskdetails.php?task_id=$task_id"); 
            exit();
        } else {
            echo "Error: " . $update_stmt->error;
        }
    } else {
        echo"Error: Deadline exceeds sprint period.";
    }

}

// Handle form submission for assigning a task
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign_task'])) {
    if (isset($_POST['task_id']) && isset($_POST['reporter_id']) && isset($_POST['assignee_id'])) {
        $task_id = $connect->real_escape_string($_POST['task_id']);
        $reporter_id = $connect->real_escape_string($_POST['reporter_id']);
        $assignee_id = $connect->real_escape_string($_POST['assignee_id']);

        $sql_insert = "INSERT INTO assignments (task_id, reporter_id, assignee_id) VALUES (?, ?, ?)";
        $insert_stmt = $connect->prepare($sql_insert);
        $insert_stmt->bind_param("iii", $task_id, $reporter_id, $assignee_id);
        
        if ($insert_stmt->execute()) {
            $message = "Task successfully assigned.";
        } else {
            $message = "Error: " . $insert_stmt->error;
        }
    } else {
        $message = "Error: All required fields are not set.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_task'])) {
    $sql_update_status = "UPDATE task SET status_id = (SELECT status_id FROM status WHERE status_name = 'done') WHERE task_id = ?";
    $update_status_stmt = $connect->prepare($sql_update_status);
    $update_status_stmt->bind_param("i", $task_id);
    
    if ($update_status_stmt->execute()) {
        $message = "Task status updated to done.";
    } else {
        $message = "Error: " . $update_status_stmt->error;
    }
}


// Fetch assigned members
$sql_assignees = "SELECT a.assignee_id, u.username, u.user_id
                  FROM assignments a
                  JOIN user u ON a.assignee_id = u.user_id
                  WHERE a.task_id = ?";
$stmt_assignees = $connect->prepare($sql_assignees);
$stmt_assignees->bind_param("i", $task_id);
$stmt_assignees->execute();
$result_assignees = $stmt_assignees->get_result();




// Fetch comments
$select_comment = "SELECT c.comment_id, c.comment, u.username , u.user_id
FROM comment c
JOIN user u ON c.user_id = u.user_id
WHERE c.task_id = ?
ORDER BY c.comment_id DESC ";
$stmt_comment = $connect->prepare($select_comment);
$stmt_comment->bind_param("i", $task_id);
$stmt_comment->execute();
$run_comment = $stmt_comment->get_result();






// Handle comment form submission
if (isset($_POST['comment2'])) {
    $comment = $connect->real_escape_string($_POST['comment']);
    $insert = "INSERT INTO comment (comment, task_id, user_id) VALUES (?, ?, ?)";
    $insert_stmt = $connect->prepare($insert);
    $insert_stmt->bind_param("sii", $comment, $task_id, $user_id);
    if ($insert_stmt->execute()) {
        header("Location: taskdetails.php?task_id=$task_id");
        exit();
    }
}

// Handle comment deletion
if (isset($_GET['delete'])) {
    $comment_id = intval($_GET['delete']);
    $delete = "DELETE FROM comment WHERE comment_id = ?";
    $delete_stmt = $connect->prepare($delete);
    $delete_stmt->bind_param("i", $comment_id);
    if ($delete_stmt->execute()) {
        header("Location: taskdetails.php?task_id=$task_id");
        exit();
    } else {
        echo "Error: " . $connect->error;
    }
}

// Handle comment update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_comment'])) {
    $comment_id = $connect->real_escape_string($_POST['comment_id']);
    $updated_comment = $connect->real_escape_string($_POST['comment']);

    $update_comment_query = "UPDATE comment SET comment = ? WHERE comment_id = ?";
    $update_stmt = $connect->prepare($update_comment_query);
    $update_stmt->bind_param("si", $updated_comment, $comment_id);
    if ($update_stmt->execute()) {
        header("Location: taskdetails.php?task_id=$task_id");
        exit();
    } else {
        echo "Error updating comment: " . $connect->error;
    }
}


$sql_tasks = "SELECT t.task_id, t.task_name
              FROM assignments a
              JOIN task t ON a.task_id = t.task_id
              WHERE a.assignee_id = $user_id";
$result_tasks = $connect->query($sql_tasks);


 // Fetch task details
    $sql_task = "SELECT task_name, description, deadline, created_by FROM task WHERE task_id = ?";
    $stmt_task = $connect->prepare($sql_task);
    $stmt_task->bind_param("i", $task_id);
    $stmt_task->execute();
    $result_task = $stmt_task->get_result();
    $task02 = $result_task->fetch_assoc();

    if (!$task02) {
        echo "Error: Task not found.";
        exit;
    }

$task_creator_id = $task02['created_by'];
$is_reporter02 = $_SESSION['user_id'] == $task_creator_id; // Check if current user is the creator




if (!$is_reporter02 && !$is_assignee09) {
    echo "You are not a member in this task.";
} 





if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign_task'])) {

    $reporter_id = $_SESSION['user_id'];

    $assignee_id = $connect->real_escape_string($_POST['assignee_id']);

    $sql_insert = "INSERT INTO assignments (task_id, reporter_id, assignee_id) VALUES (?, ?, ?)";
    $insert_stmt = $connect->prepare($sql_insert);
    $insert_stmt->bind_param("iii", $task_id, $reporter_id, $assignee_id);

    if ($insert_stmt->execute()) {
        echo "Task successfully assigned.";
    } else {
        echo "Error: " . $insert_stmt->error;
    }

    $insert_stmt->close();
}


$sql_assignees = "
    SELECT u.username, a.assignee_id 
    FROM assignments a
    JOIN user u ON a.assignee_id = u.user_id
    WHERE a.task_id = ?
";
$stmt_assignees = $connect->prepare($sql_assignees);
$stmt_assignees->bind_param("i", $task_id);
$stmt_assignees->execute();
$result_assignees = $stmt_assignees->get_result();

// Check if any assignees are found
if ($result_assignees->num_rows > 0) {
    $assignees = $result_assignees->fetch_all(MYSQLI_ASSOC);
} else {
    $assignees = [];
}

// $done = "SELECT a.task_id FROM assignment a JOIN task t ON t.task_id = a.task_id AND t.task_id = $task_id AND t.status == 'done'" ;

// if ($done)
// echo "done" ;

?>




<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Details</title>
    <style>
        /* Your existing CSS styles */
    </style>
    <script>
        function toggleEditForm() {
            var form = document.getElementById("edit-task-form");
            form.style.display = form.style.display === "none" ? "block" : "none";
        }
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var likeIcons = document.querySelectorAll('.like-icon');
        var dislikeIcons = document.querySelectorAll('.dislike-icon');

        likeIcons.forEach(function(icon) {
            icon.addEventListener('click', function() {
                var commentId = this.getAttribute('data-comment-id');
                this.style.display = 'none'; // Hide the like icon
                this.nextElementSibling.style.display = 'inline'; // Show the dislike icon

                // Optionally, send an AJAX request to update the like status
                // Example:
                // fetch(like.php?comment_id=${commentId}, { method: 'POST' });
            });
        });




        dislikeIcons.forEach(function(icon) {
            icon.addEventListener('click', function() {
                var commentId = this.getAttribute('data-comment-id');
                this.style.display = 'none'; // Hide the dislike icon
                this.previousElementSibling.style.display = 'inline'; // Show the like icon

                // Optionally, send an AJAX request to update the dislike status
                // Example:
                // fetch(dislike.php?comment_id=${commentId}, { method: 'POST' });
            });
        });
    });
</script>
<link rel="stylesheet" href="./css/taskdetails.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
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
<h1>Task: <?= htmlspecialchars($task['task_name']) ?></h1>
    <div class="form-container">
        <h2>Task Details</h2>
        <table>
            <tr>
                <th>Description</th>
                <td><?= htmlspecialchars($task['description']) ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?= htmlspecialchars($task['status_name']) ?></td>
            </tr>
            <tr>
                <th>Priority</th>
                <td><?= htmlspecialchars($task['priority_name']) ?></td>
            </tr>
            <tr>
                <th>Category</th>
                <td><?= htmlspecialchars($task['category_name']) ?></td>
            </tr>
            <tr>
                <th>Deadline (in days)</th>
                <td><?= htmlspecialchars($task['deadline']) ?></td>
            </tr>
        </table>

         <!-- Show the "Upload Task" button only if the logged-in user is the assignee -->
         <?php if ($is_assignee09): ?>
    <div>
        <form method="POST">
           <h3>when you finish this task, kindly Upload it</h3>  
           <button type="submit" name="upload_task">Upload</button> 
        </form>
    </div>
<?php endif; ?>


<div?php 


?>

    <?php if ($is_reporter02): ?>
    <div>
        <button id="edit-task-btn">Edit Task</button>
        <div id="edit-task-form" class="edit-form" style="display: none;">
            <h2>Edit Task</h2>
            <form method="POST" action="">
                <input type="hidden" name="task_id" value="<?= htmlspecialchars($task_id) ?>">
                <label for="task_name">Task Name:</label>
                <input type="text" id="task_name" name="task_name" value="<?= htmlspecialchars($task['task_name']) ?>">

                <label for="description">Description:</label>
                <textarea id="description" name="description"><?= htmlspecialchars($task['description']) ?></textarea>

                <label for="deadline">Deadline (in days):</label>
                <input type="number" id="deadline" name="deadline" value="<?= htmlspecialchars($task['deadline']) ?>">

                <button type="submit" name="update_task">Update Task</button>
            </form>
        </div>
    </div>

<?php endif; ?>

<?php if ($is_reporter02): ?>
    <?php if ($result_members->num_rows > 0): ?>
        <form method="POST">
            <label for="assignee_id">Select Member:</label>
            <select id="assignee_id" name="assignee_id" required>
                <option value="">Select Member</option>
                <?php while ($member = $result_members->fetch_assoc()) { ?>
                    <option value="<?= htmlspecialchars($member['user_id']) ?>">
                        <?= htmlspecialchars($member['username']) ?>
                    </option>
                <?php } ?>
            </select>
            <button type="submit" name="assign_task">Assign Task</button>
        </form>
    <?php else: ?>
        <p>No members available to assign.</p>
    <?php endif; ?>
<?php else: ?>
    <!-- <p>You are not authorized to assign tasks.</p> -->
<?php endif; ?>







<div class="form-container">
    <h2>Assigned Members</h2>
    <table>
        <tr>
            <th>Member</th>
        </tr>
        <?php if (!empty($assignees)): ?>
            <?php foreach ($assignees as $assignee): ?>
                <tr>
                    <td>
                        <a href="profile.php?user_id=<?= htmlspecialchars($assignee['assignee_id']) ?>">
                            <?= htmlspecialchars($assignee['username']) ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td>No members assigned yet.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>


    <div class="form-container">
        <h2>Comments</h2>
        <?php if (isset($_GET['edit'])) { 
            $edit_comment_id = $_GET['edit'];
            $comment_query = $connect->query("SELECT * FROM comment WHERE comment_id = $edit_comment_id");
            $comment_data = $comment_query->fetch_assoc();
        ?>
            <form method="POST">
                <input type="hidden" name="comment_id" value="<?= htmlspecialchars($comment_data['comment_id']) ?>">
                <textarea name="comment" required><?= htmlspecialchars($comment_data['comment']) ?></textarea>
                <button type="submit" name="update_comment">Update Comment</button>
            </form>
        <?php } else { ?>
            <form method="POST">
                <textarea name="comment" required></textarea>
                <button type="submit" name="comment2">Comment</button>
            </form>
        <?php } ?>


        <div class="comment-table">
    <table>
        <tr>
            <th>By</th>
            <th>Comment</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($run_comment as $data) { ?>
            <tr>
                <td><?= htmlspecialchars($data['username']) ?></td>
                <td><?= htmlspecialchars($data['comment']) ?></td>
                <td class="actions">
                    <?php if ($data['user_id'] == $user_id) { ?>
                        <!-- User can edit or delete their own comment -->
                        <a href="taskdetails.php?task_id=<?= htmlspecialchars($task_id) ?>&delete=<?= htmlspecialchars($data['comment_id']) ?>">Delete</a>
                        <a href="taskdetails.php?task_id=<?= htmlspecialchars($task_id) ?>&edit=<?= htmlspecialchars($data['comment_id']) ?>">Edit</a>
                    <?php } else { ?>
                        <!-- User can like or dislike if they are not the author -->
                        <i class="fas fa-thumbs-up like-icon" data-comment-id="<?= htmlspecialchars($data['comment_id']) ?>"></i>
                        <i class="fas fa-thumbs-down dislike-icon" data-comment-id="<?= htmlspecialchars($data['comment_id']) ?>"></i>
                        <a href="reply.php?task_id=<?= htmlspecialchars($task_id) ?>&comment_id=<?= htmlspecialchars($data['comment_id']) ?>">Reply</a>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editButton = document.getElementById('edit-task-btn');
            var editForm = document.getElementById('edit-task-form');

            editButton.addEventListener('click', function() {
                if (editForm.style.display === 'none' || editForm.style.display === '') {
                    editForm.style.display = 'block';
                    editButton.textContent = 'Cancel Edit';
                } else {
                    editForm.style.display = 'none';
                    editButton.textContent = 'Edit Task';
                }
            });
        });
    </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>