<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date_created = $_POST["date_created"];
    $name = $_POST["name"];
    $description = $_POST["description"];

    // Database connection settings
    $hostname = "localhost";
    $db_username = "root";
    $db_password = "";
    $database = "itpm";

    // Create a database connection
    $conn = new mysqli($hostname, $db_username, $db_password, $database);

    // Check connection
    if ($conn->connect_error) {
        header("Location: ../violationlist.php?error=db_connection_failed&message=" . urlencode("Database connection failed: " . $conn->connect_error));
        exit;
    }

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO violation (date, name, description, status) VALUES (?, ?, ?, 'Active')"); // Inserting with 'Active' status
    $stmt->bind_param("sss", $date_created, $name, $description);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the violation list page after successful insertion
        header("Location: ../violationlist.php");
        exit;
    } else {
        // Redirect with an error message if insertion fails
        header("Location: ../violationlist.php?error=insertion_failed&message=" . urlencode("Failed to insert violation into the database"));
        exit;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

// Check if the status needs to be updated
if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];

    // Update status query
    $update_query = "UPDATE violation SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $status, $id);

    // Execute the update statement
    if ($stmt->execute()) {
        // Redirect back to the violation list page after successful update
        header("Location: ../violationlist.php");
        exit;
    } else {
        // Redirect with an error message if update fails
        header("Location: ../violationlist.php?error=update_failed&message=" . urlencode("Failed to update status"));
        exit;
    }

    // Close the statement
    $stmt->close();
    $conn->close();
}
?>
