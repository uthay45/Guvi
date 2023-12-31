<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $dob = $_POST['dob'];
    $contact = $_POST['contact'];
    $age = $_POST['age'];

    $servername = "localhost";
    $username = "root";
    $mysql_password = "12345";
    $dbname = "guvi_projectdb";

    $conn = new mysqli($servername, $username, $mysql_password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO users (username, pass, dob, contact, age) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $name, $hashedPassword, $dob, $contact, $age);

    if ($stmt->execute()) {
        $_SESSION['userDetails'] = [
            'username' => $name,
            'dob' => $dob,
            'contact' => $contact,
            'age' => $age,
        ];

        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Error registering user: " . $stmt->error]);
    }
    $stmt->close();
    $conn->close();
}
?>
