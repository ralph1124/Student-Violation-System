<?php
// Mga credentials sa database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "itpm";

// Surin kung may tamang POST request
if(!isset($_POST['rfid_number'])) {
    $response = array(
        'success' => false,
        'message' => 'RFID number not provided'
    );
    echo json_encode($response);
    exit; // Itigil ang pagproseso ng script
}

// Kunin ang RFID number mula sa POST request
$rfid_number = $_POST['rfid_number'];

// Lumikha ng connection sa database
$conn = new mysqli($servername, $username, $password, $dbname);

// Suriin kung may error sa connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Gumawa ng SQL query para hanapin ang detalye ng mag-aaral batay sa RFID number
$sql = "SELECT * FROM students WHERE rfid_number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $rfid_number);
$stmt->execute();
$result = $stmt->get_result();

// Surin kung mayroong mga resulta
if ($result->num_rows > 0) {
    // Kung may resulta, kunin ang mga detalye
    $row = $result->fetch_assoc();
    $response = array(
        'success' => true,
        'data' => array(
            'name' => $row['name'],
            'program' => $row['program'],
            'year_level' => $row['year_level'],
            'sr_code' => $row['sr_code']
        )
    );
} else {
    // Kung walang resulta, ibalik ang mensahe ng "No record found"
    $response = array(
        'success' => false,
        'message' => 'No record found'
    );
}

// I-echo ang response bilang isang JSON
echo json_encode($response);

// Isara ang database connection
$stmt->close();
$conn->close();
?>
