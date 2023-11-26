<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve values from the login form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Perform necessary validations

    // Check the user credentials from MySQL (use prepared statements)
    $servername = "localhost";
    $username_db = "root";
    $dbpassword = "1234";
    $dbname = "guvi_projectdb";

    $conn = new mysqli($servername, $username_db, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, verify the password
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['pass'])) {
            // Password is correct, redirect to profile page
            header("Location: /Guvi/profile.html");
            exit();
        } else {
            echo "Invalid password";
        }
    } else {
        echo "Invalid username";
    }

    $stmt->close();
    $conn->close();
}
?>
