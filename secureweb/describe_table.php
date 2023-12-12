<?php
$conn = new mysqli("localhost", "root", "mysql", "encryption_demo");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "DESCRIBE users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . "<br>";
    }
} else {
    echo "No columns found in the 'users' table.";
}

$conn->close();
?>
