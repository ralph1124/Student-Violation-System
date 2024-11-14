<?php
// Database connection settings
$hostname = "localhost";
$db_username = "root";
$db_password = "";
$database = "itpm";

// Create a database connection
$conn = new mysqli($hostname, $db_username, $db_password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'id' parameter is set in the URL and is not empty
if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    // Get the ID parameter from the URL
    $id = trim($_GET['id']);
    
    // Prepare SQL statement to delete record
    $sql = "DELETE FROM violation WHERE violation_id = ?";
    
    // Prepare and bind parameters for the SQL statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    // Execute SQL statement
    if ($stmt->execute()) {
        // Record deleted successfully, redirect to violation_list.php
        header("Location: ../violationlist.php");
        exit(); // Ensure that no other output is sent
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Close database connection
$conn->close();
?>
