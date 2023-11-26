<?php
// Retrieve user details from MySQL based on session or local storage

// Example code to fetch user details by user ID

$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "guvi_projectdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $userDetails = $result->fetch_assoc();
    // Use $userDetails to display user information in the profile page
} else {
    echo "User not found";
}

$stmt->close();
$conn->close();
?>
