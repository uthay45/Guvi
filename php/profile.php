<?php
session_start();

// Assuming you have a valid username and password hash in your session
if (isset($_SESSION['userDetails'])) {
    $username = $_SESSION['userDetails']['username'];
    $hashedPassword = $_SESSION['userDetails']['password']; // hashed password stored in the session

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

            // Compare the hashed password from the database with the stored hashed password
            if (password_verify($userDetails['pass'], $hashedPassword)) {
                // Passwords match, proceed with sending user details
                echo json_encode($userDetails);
            } else {
                echo json_encode(['error' => 'Authentication failed']);
            }
        } else {
            echo json_encode(['error' => 'User not found']);
        }

        $stmt_select->close();
    } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve updated values from the profile update form
        $updateDob = $_POST["updateDob"];
        $updateContact = $_POST["updateContact"];
        $updateAge = $_POST["updateAge"];

        // Authenticate user based on username and hashed password
        $stmt_auth = $conn->prepare("SELECT pass FROM users WHERE username = ?");
        $stmt_auth->bind_param("s", $username);

        $stmt_auth->execute();
        $result_auth = $stmt_auth->get_result();

        if ($result_auth->num_rows > 0) {
            $storedHashedPassword = $result_auth->fetch_assoc()['pass'];

            // Compare the stored hashed password with the hashed password from the session
            if (password_verify($hashedPassword, $storedHashedPassword)) {
                // User authenticated, update user profile
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
            } else {
                echo json_encode(['error' => 'Authentication failed']);
            }
        } else {
            echo json_encode(['error' => 'Authentication failed']);
        }

        // Close the statements and connection
        $stmt_auth->close();
    }

    $conn->close();
} else {
    echo json_encode(['error' => 'User not authenticated']);
}
?>
