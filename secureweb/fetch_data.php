<?php
session_start();

include 'crypt.php';

// Set your encryption key and block size
$GLOBALS['hashkey'] = 'your_secret_key'; // Replace with your actual key
$GLOBALS['blocksize'] = 128;

// Database connection
$conn = new mysqli("localhost", "root", "mysql", "encryption_demo");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$loggedInUsername = $_SESSION["username"];
$sql = "SELECT job_id, opn_number FROM users WHERE name = '$loggedInUsername'";
$result = $conn->query($sql);

// Check if data is available
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $jobID = $row["job_id"];
    $encryptedOPN = $row["opn_number"];
    echo "Job ID: $jobID, Encrypted OPN Number: $encryptedOPN";
} else {
    echo "No data available";
}
?>
