<?php
session_start();

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

// Default status
$status = "Pending";

// Check if the form is submitted and all necessary parameters are set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sr_code'], $_POST['name'], $_POST['officer'], $_POST['attempted'], $_POST['violation'], $_POST['date'])) {
    // Get data from the form
    $sr_code = $_POST['sr_code'];
    $name = $_POST['name'];
    $officer = $_POST['officer'];
    $attempted = $_POST['attempted'];
    $violation = $_POST['violation'];
    $date = $_POST['date'];

    // Prepare and execute the SQL statement to insert data into the records table
    $sql = "INSERT INTO records (sr_code, name, officer, attempted, violation, date, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $sr_code, $name, $officer, $attempted, $violation, $date, $status);
    
    if ($stmt->execute()) {
        // Data successfully inserted
        // Redirect to mail.php and pass the sr_code parameter
        header("Location: ../mail.php?sr_code=$sr_code");
        exit(); // Stop further execution
    } else {
        // Error occurred while inserting data
        echo "Error inserting records: " . $stmt->error;
    }
} else {
    // If form not submitted or missing parameters, display error message
    echo "One or more parameters are missing!";
}

// Close the database connection
$conn->close();
?>
