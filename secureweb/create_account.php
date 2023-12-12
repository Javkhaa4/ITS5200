<?php
session_start();

// Include settings.php for database connection details
include 'settings.php';

// Database connection
$conn = new mysqli(
    $databases['default']['default']['host'],
    $databases['default']['default']['username'],
    $databases['default']['default']['password'],
    $databases['default']['default']['database'],
    $databases['default']['default']['port']
);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = $_POST["new_username"];
    $new_password = $_POST["new_password"];

    // Perform secure account creation logic
    // Hash the password before storing it in the database
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Insert the new account into the database, providing a default value for 'opn_number'
    $insert_sql = "INSERT INTO users (name, pass, opn_number) VALUES ('$new_username', '$hashed_password', 0)";
    if ($conn->query($insert_sql) === true) {
        // Redirect to login page or dashboard upon successful account creation
        header("Location: index.php");
        exit();
    } else {
        $error = "Error creating the account: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
</head>
<body>
    <h2>Create New Account</h2>
    <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
    
    <!-- HTML form for creating a new account -->
    <form method="post" action="">
        <label for="new_username">Username:</label>
        <input type="text" name="new_username" required autocomplete="off"><br>

        <label for="new_password">Password:</label>
        <input type="password" name="new_password" required autocomplete="off"><br>

        <input type="submit" value="Create Account">
    </form>
</body>
</html>
