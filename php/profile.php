<?php
session_start();

// Assuming you have a valid username in your session
if (isset($_SESSION['userDetails'])) {
    $username = $_SESSION['userDetails']['username'];

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

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        // Fetch user details for GET request
        $stmt_select = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt_select->bind_param("s", $username);
        $stmt_select->execute();
        $result = $stmt_select->get_result();

        if ($result->num_rows > 0) {
            $userDetails = $result->fetch_assoc();
            // Return user details without checking password for GET request
            echo json_encode($userDetails);
        } else {
            echo json_encode(['error' => 'User not found']);
        }

        $stmt_select->close();
    } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve updated values from the profile update form
        $updateDob = $_POST["updateDob"];
        $updateContact = $_POST["updateContact"];
        $updateAge = $_POST["updateAge"];

        // Update user profile
        $stmt_update = $conn->prepare("UPDATE users SET dob = ?, contact = ?, age = ? WHERE username = ?");
        $stmt_update->bind_param("ssss", $updateDob, $updateContact, $updateAge, $username);

        // Execute the statement
        if ($stmt_update->execute()) {
            // If update is successful, fetch and return updated user details
            $stmt_select = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt_select->bind_param("s", $username);
            $stmt_select->execute();
            $result = $stmt_select->get_result();

            if ($result->num_rows > 0) {
                $userDetails = $result->fetch_assoc();
                echo json_encode($userDetails);
            } else {
                echo json_encode(['error' => 'User not found']);
            }

            $stmt_select->close();
        } else {
            echo json_encode(['error' => 'Error updating profile']);
        }

        $stmt_update->close();
    }

    $conn->close();
} else {
    echo json_encode(['error' => 'User not authenticated']);
}
?>
