<?php
 include("connection.php");
 
 $task_id=3;
 $user_id = $_SESSION['user_id'];

 $select_comment = "SELECT * FROM `comment` JOIN `task` ON `comment`.`task_id` = `task`.`task_id` WHERE `task`.`task_id` = '$task_id'";
 $run_comment = mysqli_query($connect ,$select_comment);
 if(isset($_POST['comment2'])){
     $comment = $_POST['comment'];
     $insert = "INSERT INTO `comment` VALUES (NULL,'$comment','$task_id','$user_id')";
     $run_insert = mysqli_query($connect ,$insert); 
     header("location:comment.php");
 }

if(isset($_GET['delete'])){
    $comment_id = $_GET['delete'];
    $delete = "DELETE FROM `comment` WHERE `comment_id` = '$comment_id'";
    $runSelect= mysqli_query($connect ,$delete);
    header("location:comment.php");} 

?>
 <!DOCTYPE html>
<form method="post">
    <textarea name="comment" required></textarea>
    <button type="submit" name="comment2">Comment</button>
</form>
<form method="post">
    <input type="hidden" name="comment" value="1"> 
</form>
<form method="get">
    <tr>
    <?php foreach($run_comment as $data){?>

        <td><?php echo $data['comment']?><br></td>
   <td> <a href="comment.php?delete=<?php echo $data['comment_id'] ?>"> Delete</a> </td>
   <td> <a href="editcomment.php?edit=<?php echo $data['comment_id'] ?>"> Edit</a> </td>


    </tr>
    <?php } ?>
</form>
</html>




