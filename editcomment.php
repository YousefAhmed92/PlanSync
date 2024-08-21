<?php
include("connection.php");

if(isset($_GET['edit'])){
    $edit_id=$_GET["edit"];
    $select="SELECT * FROM `comment` WHERE `comment_id`='$edit_id'";
    $run_select=mysqli_query($connect,$select);

}
// $task_id=3;
// $user_id=2;

if(isset($_POST['update'])){
    $newcomment=$_POST['newcomment'];
    $update="UPDATE `comment` SET `comment` ='$newcomment' WHERE `comment_id`='$edit_id'";
    $run_update=mysqli_query($connect,$update);
    header("location:taskdetails.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit comment</title>
</head>
<body>
    <form method=post>
        <label for="newcomment" name="edit">EDit Comment</label>
        <input type="text" id="newcomment" name="newcomment">
        <button type="submit" name="update">Add new comment</button>

    </form>
</body>
</html>