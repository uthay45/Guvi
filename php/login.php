<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $plainPassword = $_POST['password'];

    $servername = "localhost";
    $username_db = "root";
    $dbpassword = "12345";
    $dbname = "guvi_projectdb";

    $conn = new mysqli($servername, $username_db, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt_auth = $conn->prepare("SELECT pass FROM users WHERE username = ?");
    $stmt_auth->bind_param("s", $username);

    $stmt_auth->execute();
    $result_auth = $stmt_auth->get_result();

    if ($result_auth->num_rows > 0) {
        $hashedPassword = $result_auth->fetch_assoc()['pass'];

        if (password_verify($plainPassword, $hashedPassword)) {
            $_SESSION['userDetails'] = [
                'username' => $username,
                'password' => $hashedPassword,
            ];

            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "Incorrect password"]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "User not found"]);
    }
    $stmt_auth->close();
    $conn->close();
}
?>
