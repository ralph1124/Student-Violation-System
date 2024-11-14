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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the form
    $violation_id = $_POST['violation_id'];
    $date = $_POST['date'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    // Prepare SQL statement to update record
    $sql = "UPDATE violation SET date='$date', name='$name', description='$description', status='$status' WHERE violation_id='$violation_id'";

    // Execute SQL statement
    if ($conn->query($sql) === TRUE) {
        // Record updated successfully, redirect to violation_list.php
        header("Location: ../violationlist.php");
        exit(); // Ensure that no other output is sent
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Close database connection
$conn->close();
?>
