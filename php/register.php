<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $name = $_POST['name'];
    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $dob = $_POST['dob'];
    $contact = $_POST['contact'];
    $age = $_POST['age'];

    // Example: Connecting to MySQL database
    $servername = "localhost";
    $username = "root";
    $mysql_password = "12345";
    $dbname = "guvi_projectdb";

    $conn = new mysqli($servername, $username, $mysql_password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO users (username, pass, dob, contact, age) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $name, $hashedPassword, $dob, $contact, $age);

    // Execute the statement
    if ($stmt->execute()) {
        // Registration successful, set session data
        $_SESSION['userDetails'] = [
            'username' => $name,
            'dob' => $dob,
            'contact' => $contact,
            'age' => $age,
        ];

        echo json_encode(["success" => true]);
    } else {
        // Error registering user
        echo json_encode(["success" => false, "error" => "Error registering user: " . $stmt->error]);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
