<?php
session_start();

if (isset($_SESSION['userDetails'])) {
    $username = $_SESSION['userDetails']['username'];

    $servername = "localhost";
    $username_db = "root";
    $dbpassword = "";
    $dbname = "guvi_projectdb";

    $conn = new mysqli($servername, $username_db, $dbpassword, $dbname);


    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $stmt_select = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt_select->bind_param("s", $username);
        $stmt_select->execute();
        $result = $stmt_select->get_result();

        if ($result->num_rows > 0) {
            $userDetails = $result->fetch_assoc();
            header('Content-Type: application/json');
            echo json_encode($userDetails);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'User not found']);
        }

        $stmt_select->close();
    } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
        $updateDob = $_POST["updateDob"];
        $updateContact = $_POST["updateContact"];
        $updateAge = $_POST["updateAge"];

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

        if (!empty($updateFields)) {
            $updateQuery = "UPDATE users SET " . implode(", ", $updateFields) . " WHERE username = ?";
            $stmt_update = $conn->prepare($updateQuery);
            $paramTypes .= "s";
            $paramValues[] = $username;
            $stmt_update->bind_param($paramTypes, ...$paramValues);

            if ($stmt_update->execute()) {
                $stmt_select = $conn->prepare("SELECT * FROM users WHERE username = ?");
                $stmt_select->bind_param("s", $username);
                $stmt_select->execute();
                $result = $stmt_select->get_result();

                if ($result->num_rows > 0) {
                    $userDetails = $result->fetch_assoc();
                    header('Content-Type: application/json');
                    echo json_encode($userDetails);
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(['error' => 'User not found']);
                }

                $stmt_select->close();
            } else {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Error updating profile']);
            }

            $stmt_update->close();
        } else {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'No fields provided for update']);
        }
    }

    $conn->close();
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'User not authenticated']);
}
?>
