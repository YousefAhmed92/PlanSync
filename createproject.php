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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['project_name']) && isset($_POST['description'])) {
        $project_name = $conn->real_escape_string($_POST['project_name']);
        $description = $conn->real_escape_string($_POST['description']);

        $sql = "INSERT INTO project (project_name, description, user_id, subscription_id) VALUES ('$project_name', '$description', $user_id, $subscription_id)";
        
        if ($conn->query($sql) === TRUE) {
            // Get the last inserted project ID
            $project_id = $conn->insert_id;

            // Add the user to the project_members table
            $sql_add_member = "INSERT INTO project_members (member_id,member_email,project_id,role_id) VALUES ($user_id,'email',$project_id, 1)";
            
            if ($conn->query($sql_add_member) === TRUE) {
                // Redirect to subscription details page with success message
                header("Location: subscriptiondetails.php?subscription_id=$subscription_id&success=1");
                exit();
            } else {
                $message = "Error adding user to project members: " . $conn->error;
            }
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
    <link rel="stylesheet" href="css/createproject.css">
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanSync</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Lobster+Two:ital,wght@0,400;0,700;1,400;1,700&family=Montserrat:ital,wght@0,100..900;1,100..900&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- <link rel="stylesheet" href="./css/createproject2.Css"> -->
    
    <style>
        * {
    padding: 0;
    margin: 0;
    box-sizing: border-box;
    }

:root {
    --color-1: #ff7418;
    --color-2: #6C5F5B;
    --color-3: #68635e;
    --color-4: #F6F1EE;
    --color-5: #686D76;
}

body {
    background-image: url(Img/Capture.PNG);

}

.conrainer-content {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100vh;
}

.content {
    width: 100%;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;

}

.form1 {
    width: 45%;
    height: 60vh;
    border-radius: 10px;

    box-shadow: 0px 0px 42px -12px black;
    /* background-color: var(--color-4); */
    background-color: rgba(0, 0, 0, 0.1);
    padding: 50px;
    text-align: center;
    justify-content: center;
    align-items: center;
    bottom: 30%;
    font-size:19px;
}

.form2 {
    width: 64%;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;

}

input {
    border-radius: 5px;
    background: rgb(195, 193, 193);
    width: 50%;
    height: 45px;
    margin-top: 10px;
    border: none;
    outline: none;
    padding: 10px;
}


.hh {
    margin-top: 10px;
    margin-bottom: 10px;
    font-family: "PT Serif", serif;
    font-weight: 600;
    font-size: 40px;
    color: #c15005;
}

.inputs {
    width: 64%;
    border: none;
    outline: none;
    font-size: 20px;
    padding-left: 20px;
    gap: 20px;
}

.btn-17,
.btn-17 *,
.btn-17 :after,
.btn-17 :before,
.btn-17:after,
.btn-17:before {
    border: 0 solid;
    box-sizing: border-box;
}

.btn-17 {
    -webkit-tap-highlight-color: transparent;
    -webkit-appearance: button;
    background-color: #ee721f;
    background-image: none;
    color: white;
    cursor: pointer;
    font-family: "PT Serif", serif;
    font-size: 100%;
    font-weight: 900;
    line-height: 1.5;
    margin: 0;
    -webkit-mask-image: -webkit-radial-gradient(#ee721f, white);
    padding: 0;
    text-transform: uppercase;
    width: 30%;
    margin-top: 15px;

}

.btn-17:disabled {
    cursor: default;
}

.btn-17:-moz-focusring {
    outline: auto;
}

.btn-17 svg {
    display: block;
    vertical-align: middle;
}

.btn-17 [hidden] {
    display: none;
}

.btn-17 {
    border-radius: 99rem;
    border-width: 2px;
    padding: 0.8rem 3rem;
    z-index: 0;
}

.btn-17,
.btn-17 .text-container {
    overflow: hidden;
    position: relative;
}

.btn-17 .text-container {
    display: block;
    /* mix-blend-mode: difference; */
    color: white;
}

.btn-17 .text {
    display: block;
    position: relative;
    color: white;
}

.btn-17:hover .text {
    -webkit-animation: move-up-alternate 0.3s forwards;
    animation: move-up-alternate 0.3s forwards;
    color: var(--color-1);

}

@-webkit-keyframes move-up-alternate {
    0% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(80%);
    }

    51% {
        transform: translateY(-80%);
    }

    to {
        transform: translateY(0);
    }
}

@keyframes move-up-alternate {
    0% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(80%);
    }

    51% {
        transform: translateY(-80%);
    }

    to {
        transform: translateY(0);
    }
}

.btn-17:after,
.btn-17:before {
    --skew: 0.2;
    background: #fff;
    content: "";
    display: block;
    height: 102%;
    left: calc(-50% - 50% * var(--skew));
    pointer-events: none;
    position: absolute;
    top: -104%;
    transform: skew(calc(150deg * var(--skew))) translateY(var(--progress, 0));
    transition: transform 0.2s ease;
    width: 100%;
    transition: 0.8s;
}

.btn-17:after {
    --progress: 0%;
    left: calc(50% + 50% * var(--skew));
    top: 102%;
    z-index: -1;
}

.btn-17:hover:before {
    --progress: 100%;
}

.btn-17:hover:after {
    --progress: -102%;
}

.parentlog {
    align-items: center;
}

a {
    margin-top: 7px;
    text-decoration: none;
    color: white;
}

button:hover {
    background-color: var(--color-1);

}

@media (max-width:800px) {


    .content {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        width: 100%;
    }

    .form1 {
        width: 100%;
        right: 20%;
        justify-content: center;
        text-align: center;
        align-items: center;
    }

    .form2 {
        width: 100%;
    }

    .btn-17 {
        width: 74%;
    }

    .inputs {
        width: 84%;
    }


}

.erorr {
    width: 45%;
    height: 20px;
    background-color: red;
    color: var(--color-4);
    border-radius: 5px;
    padding-left: 5px;
    margin-top: 15px;

}

.container-inputs {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 100%;

}

.container-inputs .form {
    width: 100%;
}
.anc{
    text-decoration: none;
    color: #F6F1EE;
}
    </style>
</head>

<body>
    <!-- <h1>Add New Project</h1> -->
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
<div class="content">
 <div class="form1">


    <?php if (!empty($message)) { echo "<p>$message</p>"; } ?>

    <form method="POST">
    <h2 class="hh">Add New Project</h2>
    <div class="form">


        
            <label for="project_name">Project Name:</label>
            <input type="text" id="project_name" name="project_name" required>
    </div>
        <br>
        <div class="form">
            <label for="description">Description:</label>
            <!-- <textarea id="description" name="description" rows="4" required></textarea> -->
             <input type="text" id="description" name="description">
        </div>
        <br>
        <button class="btn-17" type="submit">
                    <span class="text-container">
                        <span class="text">Submit</span>
                    </span>
         </button>
    </form>

    
     <a href="subscriptiondetails.php?subscription_id=<?= htmlspecialchars($subscription_id) ?>">Back to Subscription Details</a>
 </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

</body>
</html>
