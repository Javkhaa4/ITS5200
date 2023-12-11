<?php
session_start();

// Define the endpoint for the external service
$GLOBALS['endpoint'] = "https://localhost"; // Replace with your actual endpoint

// Database connection
$conn = new mysqli("localhost", "root", "mysql", "encryption_demo");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function get_session($username, $password) {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $GLOBALS['endpoint'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_SSL_VERIFYHOST => 1, // Set to 0 to disable verification (for development purposes)
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode(array("username" => $username, "password" => $password)),
        CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "cache-control: no-cache",
            "content-type: application/json"
        ),
    ));

    // Disable SSL verification (for development purposes; not recommended in production)
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    // Check for errors
    if ($err) {
        echo "cURL Error: " . $err;
    } else {
        // Process the $response as needed
        return json_decode($response, true);
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Perform authentication (replace with secure authentication logic)
    $sql = "SELECT * FROM users WHERE name = '$username' AND pass = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION["username"] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body>
    <h2>Client Sign In</h2>
    <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
    <form method="post" action="" autocomplete="off">
        <label for="username">Username:</label>
        <input type="text" name="username" required autocomplete="off"><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required autocomplete="off"><br>

        <input type="submit" value="Login">
    </form>
</body>
</html>
