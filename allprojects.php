<?php
include("connection.php");
// session_start();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

$conn = new mysqli('localhost', 'root', '', 'updated-case1');

$user_select = "SELECT * FROM `user` WHERE `user_id`='$user_id'";
$run = mysqli_query($conn, $user_select);
$fetch = mysqli_fetch_assoc($run);
$role = $fetch['role_id'];

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($user_id) {
    $sql = "SELECT * FROM project WHERE user_id = " . intval($user_id);
    $result = $conn->query($sql);

    if (!$result) {
        die("Query failed: " . $conn->error);
    }
} else {
    die("User ID is not set.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Projects</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            color: #333;
            text-align: center;
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
            font-size: 18px;
        }
        a:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 0;
            font-size: 16px;
            color: #fff;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>My Projects</h1>
        <ul>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <li>
                    <a href="project.php?project_id=<?= htmlspecialchars($row['project_id']) ?>">
                        <?= htmlspecialchars($row['project_name']) ?>
                    </a>
                </li>
            <?php } ?>
        </ul>

        <?php if ($role == 1) { ?>
            <a href="createproject.php" class="button">Add New Project</a>
        <?php } ?>

        <br>

        <a href="profilepage.php" class="button">Your Profile</a>
    </div>
</body>
</html>
