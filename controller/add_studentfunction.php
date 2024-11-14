<?php
// Mga credentials sa database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "itpm";

// Lumikha ng connection sa database
$conn = new mysqli($servername, $username, $password, $dbname);

// Suriin kung may error sa connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Kunin ang mga input mula sa form
$name = $_POST['name'];
$program = $_POST['description'];
$yearLevel = $_POST['yearLevel'];
$email = $_POST['email'];
$rfidNumber = $_POST['rfidNumber'];

// Query para mag-insert ng data sa database
$sql = "INSERT INTO students (name, program, year_level, sr_code, rfid_number)
VALUES ('$name', '$program', '$yearLevel', '$email', '$rfidNumber')";

// Subukang i-execute ang query
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Isara ang connection sa database
$conn->close();

// Redirect papunta sa add_student.php
header("Location: ../add_student.php");
exit();
?>
