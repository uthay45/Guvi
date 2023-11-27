<?php
session_start();

// Assuming you have a valid username in your session
if (isset($_SESSION['userDetails'])) {
    $username = $_SESSION['userDetails']['username'];

    // Example: Connecting to MySQL database
    $servername = "localhost";
    $username_db = "root";
    $dbpassword = "";
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
            // Output JSON response
            header('Content-Type: application/json');
            echo json_encode($userDetails);
        } else {
            // Output JSON response for user not found
            header('Content-Type: application/json');
            echo json_encode(['error' => 'User not found']);
        }

        $stmt_select->close();
    } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve updated values from the profile update form
        $updateDob = $_POST["updateDob"];
        $updateContact = $_POST["updateContact"];
        $updateAge = $_POST["updateAge"];

        // Build the update query based on provided fields
        $updateFields = [];
        $paramTypes = "";
        $paramValues = [];

        if (!empty($updateDob)) {
            $updateFields[] = "dob = ?";
            $paramTypes .= "s";
            $paramValues[] = $updateDob;
        }

        if (!empty($updateContact)) {
            $updateFields[] = "contact = ?";
            $paramTypes .= "s";
            $paramValues[] = $updateContact;
        }

        if (!empty($updateAge)) {
            $updateFields[] = "age = ?";
            $paramTypes .= "s";
            $paramValues[] = $updateAge;
        }

        // Check if any fields are provided for update
        if (!empty($updateFields)) {
            // Update user profile
            $updateQuery = "UPDATE users SET " . implode(", ", $updateFields) . " WHERE username = ?";
            $stmt_update = $conn->prepare($updateQuery);
            $paramTypes .= "s";
            $paramValues[] = $username;
            $stmt_update->bind_param($paramTypes, ...$paramValues);

            // Execute the statement
            if ($stmt_update->execute()) {
                // If update is successful, fetch and return updated user details
                $stmt_select = $conn->prepare("SELECT * FROM users WHERE username = ?");
                $stmt_select->bind_param("s", $username);
                $stmt_select->execute();
                $result = $stmt_select->get_result();

                if ($result->num_rows > 0) {
                    $userDetails = $result->fetch_assoc();
                    // Output JSON response
                    header('Content-Type: application/json');
                    echo json_encode($userDetails);
                } else {
                    // Output JSON response for user not found
                    header('Content-Type: application/json');
                    echo json_encode(['error' => 'User not found']);
                }

                $stmt_select->close();
            } else {
                // Output JSON response for update error
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Error updating profile']);
            }

            $stmt_update->close();
        } else {
            // Output JSON response if no fields are provided for update
            header('Content-Type: application/json');
            echo json_encode(['error' => 'No fields provided for update']);
        }
    }

    $conn->close();
} else {
    // Output JSON response for user not authenticated
    header('Content-Type: application/json');
    echo json_encode(['error' => 'User not authenticated']);
}
?>
