<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $dob = $_POST['dob'];
    $contact = $_POST['contact'];
    $age = $_POST['age'];

    // You may want to perform additional validation and sanitation here

    // Example: Connecting to MySQL database
    $servername = "localhost";
    $username = "root";
    $mysql_password = "1234";
    $dbname = "guvi_projectdb";

    $conn = new mysqli($servername, $username, $mysql_password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO users (username, pass, dob, contact, age) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $name, $password, $dob, $contact, $age);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error registering user: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
