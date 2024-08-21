<?php
include 'connection.php';
$select="SELECT * FROM `user` ";
$runselect= mysqli_query($connect,$select);
if(isset($_POST['search'])){
    $text=$_POST['text'];
    $select_search="SELECT * FROM `user` WHERE (`username` LIKE '%$text%') 
    or (`email` LIKE '%$text%')  or (`age` LIKE '%$text%')";
    $run_select_search= mysqli_query($connect,$select_search);
}
?>
<!DOCTYPE html>
<html>
<body>
    <form method="post">
        <input type="search" name="text">
        <button type="submit" name="search">search</button>
    </form>
</body>
</html>