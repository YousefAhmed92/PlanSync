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

$message = "";

$sql_subscription = "SELECT * FROM subscription WHERE subscription_id = ?";
$stmt = $conn->prepare($sql_subscription);
$stmt->bind_param('i', $subscription_id);
$stmt->execute();
$result = $stmt->get_result();
$subscription = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_project'])) {
    $project_name = isset($_POST['project_name']) ? $conn->real_escape_string($_POST['project_name']) : '';
    $description = isset($_POST['description']) ? $conn->real_escape_string($_POST['description']) : '';

    if ($project_name && $description) {
        $sql = "INSERT INTO project (project_name, description, user_id, subscription_id) VALUES ('$project_name', '$description', $user_id, $subscription_id)";

        if ($conn->query($sql) === TRUE) {
            $message = "New project added successfully.";
        } else {
            $message = "Error: " . $conn->error;
        }
    } else {
        $message = "Error: All fields are required.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subscription Details and Create Project</title>
</head>
<body>
    <h1>Subscription Details</h1>

    <?php if (!empty($message)) { echo "<p>$message</p>"; } ?>

    <?php if ($subscription): ?>
        <p><strong>Subscription Name:</strong> <?= htmlspecialchars($subscription['subscription_name']) ?></p>
        <p><strong>Price:</strong> <?= htmlspecialchars($subscription['price']) ?></p>
        <p><strong>Capacity:</strong> <?= htmlspecialchars($subscription['capacity']) ?></p>
    <?php else: ?>
        <p>No subscription details found.</p>
    <?php endif; ?>

    <h2>Add New Project</h2>
    <form method="POST">
        <div>
            <label for="project_name">Project Name:</label>
            <input type="text" id="project_name" name="project_name" required>
        </div>
        <br>
        <div>
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required></textarea>
        </div>
        <br>
        <input type="hidden" name="subscription_id" value="<?= htmlspecialchars($subscription_id) ?>">
        <button type="submit" name="create_project">Add Project</button>
    </form>

    <a href="profilepage.php">Back to Profile</a>
    <a href="allprojects.php">View All Projects</a>
</body>
</html>
