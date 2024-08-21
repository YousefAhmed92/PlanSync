<?php
include('connection.php');

// Get the reporter ID (assuming you have this stored in a session or similar)
$reporter_id = $_SESSION['user_id'];

// Fetch task completion messages for the reporter
$sql_fetch_messages = "SELECT * FROM task_completion_messages WHERE reporter_id = $reporter_id ORDER BY created_at DESC";
$result_messages = $connect->query($sql_fetch_messages);
?>

<div class="messages">
    <h2>Task Completion Messages</h2>
    <?php while ($message = $result_messages->fetch_assoc()): ?>
        <div class="message">
            <p><?= htmlspecialchars($message['message']) ?></p>
            <small>Received at: <?= htmlspecialchars($message['created_at']) ?></small>
        </div>
    <?php endwhile; ?>
</div>
