<?php

function updateStatus($sr_code) {
    // Database connection settings
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "itpm";

    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute SQL statement to update status in records table
    $sql = "UPDATE records SET status = 'Done' WHERE sr_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $sr_code);
    $stmt->execute();

    // Check if update was successful
    if ($stmt->affected_rows > 0) {
        // Close the database connection
        $conn->close();
        return true; // Update successful
    } else {
        // Close the database connection
        $conn->close();
        return false; // Update failed
    }
}

// Check if sr_code is set in the URL
if (isset($_GET['sr_code'])) {
    $sr_code = $_GET['sr_code'];

    // Call the function to update status
    if (updateStatus($sr_code)) {
        // Redirect to history.php
        header("Location: ../history.php");
        exit();
    } else {
        echo "Failed to update status!";
    }
} else {
    echo "SR Code not provided!";
}

?>
