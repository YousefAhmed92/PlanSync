<?php
include("connection.php");
$id= $_SESSION['user_id'];
// $id=1;
// $id = $_POST['user_id']; 

$user_sql = "SELECT * FROM `user` WHERE `user_id`= '$id'";
$user_result = $connect->query($user_sql);
$user = $user_result->fetch_assoc();
$join_query = " SELECT `sprint`.`sprint_name` , `priority`.`priority_name`, `status`.`status_name`
, `category`.`category_name`,`task`.`task_name`,`task`.`description`, `user`.`username`FROM `task`
JOIN `assignments` ON `assignments`.`task_id` = `task`.`task_id`
//search
JOIN `user` ON `assignments`.`reporter_id` = `task`.`reporter_id`

JOIN `assignments` ON `assignments`.`reporter_id` = `task`.`reporter_id`
JOIN `status` ON `status`.`status_id` = `task`.`status_id`
JOIN  `priority` ON `priority`.`priority_id` = `task`.`priority_id` 
 JOIN `category` ON `category`.`category_id` = `task`.`category_id` 
 JOIN `sprint` ON `sprint`.`sprint_id` = `task`.`sprint_id`
 WHERE $id=`assignments`.`assignee_id`";
$run_query = mysqli_query($connect,$join_query);



if(isset($_POST['logout'])){
    session_unset();
    session_destroy();
    header("location:login.php");
    
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile of <?php echo $user['username']; ?></title>
</head>
<body>
    <h1>Profile of <?php echo $user['username']; ?></h1>
    <p>Email: <?php echo $user['email']; ?></p>

    <h2>Tasks Assigned</h2>
    <table border="1">
        <tr>
            <th>Task Description</th>
            <th>name</th>
            <th>Status</th>
            <th>periority</th>
            <th>category</th>
            <th>sprint</th>
                </form>
            </td>
        </tr>
        <tbody>
                    <tr>
                    <?php foreach ( $run_query as $data) { ?>
            <td><?php echo $data['description'] ?></td>
            <td><?php echo $data['task_name'] ?></td>
            <td><?php echo $data['status_name'] ?></td>
            <td><?php echo $data['priority_name'] ?></td>
            <td><?php echo $data['category_name'] ?></td>
            <td><?php echo $data['sprint_name'] ?></td>
            <td><?php echo $data['username'] ?></td>
                    </tr>
                
                    
                </tbody>
        <form>
        <button type="submit">Update</button>
        <button type="submit" name="logout">Logout</button>
        </form>
        <?php } ?>
    </table>
</body>
</html>
