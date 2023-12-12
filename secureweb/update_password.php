<?php
session_start();

// Sample database connection
$conn = new mysqli("localhost", "root", "mysql", "encryption_demo");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $new_password = $_POST["new_password"];

    // Perform secure password update logic
    // Hash the new password before updating it in the database
    $hashed_new_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Update the password in the database
    $update_sql = "UPDATE users SET pass = '$hashed_new_password' WHERE name = '$username'";
    if ($conn->query($update_sql) === true) {
        // Redirect to login page or dashboard upon successful password update
        header("Location: index.php");
        exit();
    } else {
        $error = "Error updating the password: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Password</title>
</head>
<body>
    <h2>Update Password</h2>
    <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
    
    <!-- HTML form for updating the password -->
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" required autocomplete="off"><br>

        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" required autocomplete="off"><br>

        <input type="submit" value="Update Password">
    </form>
</body>
</html>
