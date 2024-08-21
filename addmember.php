<?php
include ('connection.php');

if (isset($_GET['project_id'])) {
    $project_id = $_GET['project_id'];
} else {
    echo "Error: Project ID is missing.";
    exit;
}

$connect = new mysqli('localhost', 'root', '', 'updated-case1');

if (isset($_POST['submit'])) {
    $member_name = $_POST['member_name'];
    $member_email = $_POST['member_email'];

    $check_user_query = "SELECT COUNT(*) AS user_count FROM user WHERE email = ?";
    $stmt_check = $connect->prepare($check_user_query);
    $stmt_check->bind_param("s", $member_email);
    $stmt_check->execute();
    $result = $stmt_check->get_result()->fetch_assoc();

    if ($result['user_count'] == 0) {
        echo '<div class="error">Error: This email does not exist in the user table.</div>';
    } else {
        $get_user_id_query = "SELECT user_id FROM user WHERE email = ?";
        $stmt_get_user_id = $connect->prepare($get_user_id_query);
        $stmt_get_user_id->bind_param("s", $member_email);
        $stmt_get_user_id->execute();
        $user = $stmt_get_user_id->get_result()->fetch_assoc();
        $member_id = $user['user_id'];

        $insert = "INSERT INTO project_members (member_id, member_email, project_id, role_id) VALUES (?, ?, ?, 2)";
        $stmt_insert = $connect->prepare($insert);
        $stmt_insert->bind_param("isi", $member_id, $member_email, $project_id);

        if ($stmt_insert->execute()) {
            echo '<div class="success">Record inserted successfully.</div>';
            header("Location: project.php?project_id=$project_id");
            exit;
        } else {
            echo '<div class="error">Error: ' . $stmt_insert->error . '</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Members</title>
    <link rel="stylesheet" href="./css/addmembernew.css">

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

        .container-content {
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
            height: 55vh;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0px 0px 42px -12px black;
            background-color: rgba(0, 0, 0, 0.1);
            padding: 50px;
            text-align: center;
            justify-content: center;
            align-items: center;
            bottom: 30%;
        }

        input {
            border-radius: 5px;
            background: rgb(195, 193, 193);
            width: 50%;
            height: 45px;
            margin-top: 10px;
            border: none;
            outline: none;
        }

        .checkbox {
            width: 3%;
            margin-right: 2%;
            margin-bottom: 10px;
        }

        .check {
            display: flex;
            align-items: center;
        }

        h2 {
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
            width: 45%;
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

        .error {
            width: 45%;
            height: 20px;
            background-color: red;
            color: var(--color-4);
            border-radius: 5px;
            padding-left: 5px;
            margin-top: 15px;
            text-align: center;
        }


        .success {
            width: 45%;
            height: 20px;
            background-color: green;
            color: var(--color-4);
            border-radius: 5px;
            padding-left: 5px;
            margin-top: 15px;
            text-align: center;
        }

        @media (max-width: 800px) {
            .content {
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
                width: 90%;
            }

            .form1 {
                width: 90%;
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
                width: 100%;
            }
        }

        @media (max-width: 500px) {
            .btn-17 {
                width: 100%;
                height: 50px;
                font-size: 14px;
            }

            .form1 {
                width: 100%;
                height: 65%;
            }
        }
    </style>
</head>
<body>
    <div class="container-content">
        <div class="content">
            <div class="form1">
                <h2>Add New Member</h2>
                <form method="POST" class="form">
                    <div>
                        <label for="member_name">Member Name:</label>
                        <input class="inputs" type="text" id="member_name" name="member_name" required>
                    </div>
                    <div>
                        <label for="member_email">Member Email:</label>
                        <input class="inputs" type="email" id="member_email" name="member_email" required>
                    </div>
                    <button class="btn-17" type="submit" name="submit">
                        <span class="text-container">
                            <span class="text">Add Member</span>
                        </span>
                    </button>
                </form>
                <a href="project.php?project_id=<?= htmlspecialchars($project_id) ?>">Back to Project Details</a>
            </div>
        </div>
    </div>
</body>
</html>
