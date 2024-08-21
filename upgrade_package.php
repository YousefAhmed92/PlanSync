<?php
session_start();

// Retrieve project ID from the URL
$project_id = isset($_GET['project_id']) ? (int)$_GET['project_id'] : null;

if (!$project_id) {
    echo "Error: Project ID is missing.";
    exit;
}

// Connect to the database
$connect = new mysqli('localhost', 'root', '', 'updated-case1');

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Fetch current subscription to compare capacity
$sql_current = "SELECT subscription.capacity, subscription.subscription_id 
                FROM subscription 
                JOIN project ON subscription.subscription_id = project.subscription_id 
                WHERE project.project_id = ?";
$stmt_current = $connect->prepare($sql_current);
$stmt_current->bind_param("i", $project_id);
$stmt_current->execute();
$result_current = $stmt_current->get_result();
$current_subscription = $result_current->fetch_assoc();

if (!$current_subscription) {
    echo "Error: Current subscription not found.";
    exit;
}

$current_capacity = $current_subscription['capacity'];
$current_subscription_id = $current_subscription['subscription_id'];

// Fetch available packages with higher capacities
$sql_packages = "SELECT * FROM subscription WHERE capacity > ?";
$stmt_packages = $connect->prepare($sql_packages);
$stmt_packages->bind_param("i", $current_capacity);
$stmt_packages->execute();
$result_packages = $stmt_packages->get_result();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'upgrade') {
    if (isset($_POST['new_subscription_id'])) {
        $new_subscription_id = (int)$_POST['new_subscription_id'];
        
        // Assuming you have the user ID in the session
        $user_id = $_SESSION['user_id'];

        // Begin transaction
        $connect->begin_transaction();

        try {
            // Update the project's subscription
            $sql_update_project = "UPDATE project SET subscription_id = ? WHERE project_id = ?";
            $stmt_project = $connect->prepare($sql_update_project);
            $stmt_project->bind_param("ii", $new_subscription_id, $project_id);
            
            if ($stmt_project->execute()) {
                // Update the user's subscription
                $sql_update_user = "UPDATE user_subscriptions SET subscription_id = ? WHERE user_id = ?";
                $stmt_user = $connect->prepare($sql_update_user);
                $stmt_user->bind_param("ii", $new_subscription_id, $user_id);
                
                if ($stmt_user->execute()) {
                    // Commit the transaction
                    $connect->commit();
                    header("Location: project.php?project_id=" . $project_id);
                    exit;
                } else {
                    throw new Exception("Error updating user subscription: " . $stmt_user->error);
                }

            } else {
                throw new Exception("Error updating project subscription: " . $stmt_project->error);
            }

        } catch (Exception $e) {
            // Rollback the transaction on error
            $connect->rollback();
            echo $e->getMessage();
        } finally {
            $connect->close();
        }
        exit;
    } else {
        echo "Error: No new subscription selected.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upgrade Package</title>
    <link rel="stylesheet" href="./css/upgrade_package.Css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
<body>
    
    <!-- start nav bar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary" style="background-color: rgba(50, 50, 50, 0.848) !important;">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">PlanSync</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <div class="items">
        <li class="nav-item" style="margin-right: 40px;">
          <a class="nav-link" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item" style="margin-right: 40px;">
          <a class="nav-link" aria-current="page" href="#">Profile</a>
        </li>
        <li class="nav-item" style="margin-right: 40px;">
          <a class="nav-link" aria-current="page" href="#">Projects</a>
        </li>
        <li class="nav-item" style="margin-right: 40px;">
          <a class="nav-link" aria-current="page" href="#">Tasks</a>
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

    <div class="containerrr">
        <h1>Upgrade Package</h1>
        <form method="post">
            <input type="hidden" name="action" value="upgrade">
            <label for="subscription">Select New Package:</label>
            <select name="new_subscription_id" id="subscription" required>
                <?php while ($row = $result_packages->fetch_assoc()) { ?>
                    <option value="<?= htmlspecialchars($row['subscription_id']) ?>">
                        <?= htmlspecialchars($row['subscription_name']) ?> - Capacity: <?= htmlspecialchars($row['capacity']) ?>
                    </option>
                <?php } ?>
            </select>
            <button type="submit">Upgrade</button>
        </form>
        <a href="project.php?project_id=<?= htmlspecialchars($project_id) ?>" class="button">Back to Project Details</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>
