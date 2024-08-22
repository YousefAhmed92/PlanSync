<?php
session_start();

// Ensure project_id is set in session from URL
if (isset($_GET['project_id'])) {
    $_SESSION['project_id'] = (int)$_GET['project_id'];
}

// Check if user is logged in and user_id is set in session
if (!isset($_SESSION['user_id']) || !isset($_SESSION['project_id'])) {
    echo "Error: Project ID or User ID is missing.";
    exit;
}

$project_id = $_SESSION['project_id'];
$id = $_SESSION['user_id'];

$connect = new mysqli('localhost', 'root', '', 'updated-case1');

$message = '';
$notification_class = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sprint_name = $_POST['sprint_name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Convert dates to DateTime objects for validation
    $today = new DateTime();
    $start_date_obj = new DateTime($start_date);
    $end_date_obj = new DateTime($end_date);

    // Check if start date is before today
    if ($start_date_obj < $today) {
        $message = "Error: Start date cannot be before today.";
        $notification_class = 'error-notification';
    }
    // Check if end date is before start date
    elseif ($end_date_obj < $start_date_obj) {
        $message = "Error: End date cannot be before the start date.";
        $notification_class = 'error-notification';
    }
    // Check if end date is more than one month after start date
    elseif ($end_date_obj > $start_date_obj->modify('+1 month')) {
        $message = "Error: End date cannot be more than one month after the start date.";
        $notification_class = 'error-notification';
    } else {
        // Prepare and execute SQL statement if validation passes
        $sql = "INSERT INTO sprint (sprint_name, start_date, end_date, user_id, project_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("ssssi", $sprint_name, $start_date, $end_date, $id, $project_id);
        if ($stmt->execute()) {
            $message = "Sprint created successfully.";
            $notification_class = 'success-notification';
        } else {
            $message = "Error: " . $connect->error;
            $notification_class = 'error-notification';
        }
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
    <link rel="stylesheet" href="css/addsprint.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .notification {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none; /* Initially hidden */
        }
        .notification.success {
            background-color: #d4edda;
            color: #155724;
        }
        .notification.error {
            background-color: #f8d7da;
            color: #721c24;
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
    <div class="notification <?= htmlspecialchars($notification_class) ?>">
        <?= htmlspecialchars($message) ?>
    </div>
    <div class="content">
        <div class="form1">
            <form method="POST">
                <h2 class=hh >Add Sprint</h2>
                <div class="form">
                    <input class="inputs" type="text" placeholder="Sprint Name" name="sprint_name" required>
                </div>
                <div class="form2">
                    <input class="inputs" type="date" placeholder="Start Date" name="start_date" required>
                    <input class="inputs" type="date" placeholder="End Date" name="end_date" required>
                </div>
                <button class="btn-17" type="submit">
                    <span class="text-container">
                        <span class="text">Submit</span>
                    </span>
                </button>
            </form> <br>
            <a href="project.php?project_id=<?= htmlspecialchars($project_id) ?>" 
            style="color: darkorange; text-decoration:underline;">Back to Project Details</a>
        </div>
    </div>
    <script>
        // Show the notification if it exists
        const notification = document.querySelector('.notification');
        if (notification) {
            notification.style.display = 'block';
            // Hide the notification after 3 seconds
            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>
</html>
