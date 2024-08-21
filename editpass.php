<?php
include 'connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $username = $_SESSION['username']; 

    
    $query = "SELECT * FROM `users` WHERE `username`='$username'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if (password_verify($old_password, $row['password'])) {
        if ($new_password == $confirm_password) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $update_query = "UPDATE `users` SET 'password'='$hashed_password' WHERE username='$username'";
            if (mysqli_query($conn, $update_query)) {
                echo "Password updated successfully!";
            } else {
                echo "Error updating password: " . mysqli_error($conn);
            }
        } else {
            echo "New passwords do not match!";
        }
    } else {
        echo "Current password is incorrect";
    }
}
        ?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Password</title>
</head>
<body>
    <h2>Edit Password</h2>
    <form method="POST" action="edit_password.php">
        <label for="old_password">Old Password:</label>
        <input type="password" id="old_password" name="old_password" required><br><br>
        
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br><br>
        
        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>
        
        <input type="submit" value="Update Password">
    </form>
</body>
</html>
