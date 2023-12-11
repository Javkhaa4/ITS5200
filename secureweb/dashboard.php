<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

include 'crypt.php';

// Set your encryption key and block size
$GLOBALS['hashkey'] = 'your_secret_key'; // Replace with your actual key
$GLOBALS['blocksize'] = 128;

// Database connection
$conn = new mysqli("localhost", "root", "mysql", "encryption_demo");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to decrypt OPN Number
function getDecryptedOPN($encryptedOPN) {
    global $GLOBALS;
    return _decrypt($encryptedOPN, $GLOBALS['hashkey'], $GLOBALS['blocksize']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["postJob"])) {
    // Handle the form submission as before
    $jobID = $_POST["jobID"];
    $opnNumber = _encrypt($_POST["opnNumber"], $GLOBALS['hashkey'], $GLOBALS['blocksize']);

    $loggedInUsername = $_SESSION["username"];
    $sql = "UPDATE users SET job_id = '$jobID', opn_number = '$opnNumber' WHERE name = '$loggedInUsername'";

    if ($conn->query($sql) === TRUE) {
        echo "Job data posted successfully!";
    } else {
        echo "Error posting job data: " . $conn->error;
    }
}

// Fetch Job ID and OPN Number from the database
$loggedInUsername = $_SESSION["username"];
$sql = "SELECT job_id, opn_number FROM users WHERE name = '$loggedInUsername'";
$result = $conn->query($sql);

// Check if data is available
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $jobID = $row["job_id"];
    $encryptedOPN = $row["opn_number"];
    $decryptedOPN = getDecryptedOPN($encryptedOPN);
} else {
    $jobID = "";
    $decryptedOPN = "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <h2>Client</h2>
	<label>Welcome, <?php echo $_SESSION["username"]; ?></label>
	<h3>Create New Job</h3>

    <form method="post" action="">
        <label for="jobID">JobID:</label>
        <input type="text" name="jobID" value="<?php echo $jobID; ?>" required autocomplete="off"><br>

        <label for="opnNumber">OPN Number:</label>
        <input type="text" name="opnNumber" value="<?php echo $decryptedOPN; ?>" required autocomplete="off"><br>

        <input type="submit" name="postJob" value="Post Job to Server">
    </form>
	<br/>
	<h3>Fetch Data</h3>
    <!-- Button to fetch and display Job ID and OPN Number -->
    <button id="fetchDataBtn">Fetch Data</button>
    <div id="result"></div>

    <script>
        $(document).ready(function() {
            // AJAX request to fetch Job ID and OPN Number
            $("#fetchDataBtn").click(function() {
                $.ajax({
                    type: "GET",
                    url: "fetch_data.php", // Create a new PHP file (e.g., fetch_data.php) to handle the AJAX request
                    success: function(response) {
                        $("#result").html(response);
                    }
                });
            });
        });
    </script>
	
</body>
</html>
