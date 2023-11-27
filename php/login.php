<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $username = $_POST['username'];
    $plainPassword = $_POST['password'];

    // Example: Connecting to MySQL database
    $servername = "localhost";
    $username_db = "root";
    $dbpassword = "12345";
    $dbname = "guvi_projectdb";

    $conn = new mysqli($servername, $username_db, $dbpassword, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve the hashed password from the database
    $stmt_auth = $conn->prepare("SELECT pass FROM users WHERE username = ?");
    $stmt_auth->bind_param("s", $username);

    $stmt_auth->execute();
    $result_auth = $stmt_auth->get_result();

    if ($result_auth->num_rows > 0) {
        $hashedPassword = $result_auth->fetch_assoc()['pass'];

        // Verify the password
        if (password_verify($plainPassword, $hashedPassword)) {
            // Authentication successful, set session data
            $_SESSION['userDetails'] = [
                'username' => $username,
                'password' => $hashedPassword, // Store the hashed password
            ];

            echo json_encode(["success" => true]);
        } else {
            // Authentication failed
            echo json_encode(["success" => false, "error" => "Incorrect password"]);
        }
    } else {
        // User not found
        echo json_encode(["success" => false, "error" => "User not found"]);
    }

    // Close the statement and connection
    $stmt_auth->close();
    $conn->close();
}
?>
