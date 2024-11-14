<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form fields
    $date = $_POST["date_created"];
    $name = $_POST["name"];
    $program = $_POST["program"];
    $year_level = $_POST["year_level"];
    $sr_code = $_POST["sr_code"];
    $violation_id = $_POST["violation"];
    $reason = $_POST["reason"];

    // Get user name from session
    $user_name = $_SESSION['user_name'];

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

    // Prepare and bind the SQL statement to check if violation entry exists
    $check_query = "SELECT * FROM violation_entry WHERE name = ?";
    $stmt_check = $conn->prepare($check_query);
    $stmt_check->bind_param("s", $name);
    $stmt_check->execute();
    $check_result = $stmt_check->get_result();

    if ($check_result->num_rows > 0) {
        // If the violation entry already exists, update the existing record
        $previous_record = $check_result->fetch_assoc();
        $violation_query = "SELECT name FROM violation WHERE violation_id = ?";
        $stmt_violation = $conn->prepare($violation_query);
        $stmt_violation->bind_param("i", $previous_record['violation_id']);
        $stmt_violation->execute();
        $violation_result = $stmt_violation->get_result();
        $violation_row = $violation_result->fetch_assoc();
        $violation_name = $violation_row['name'];
        
        $update_attempted_query = "UPDATE violation_entry SET attempted = attempted + 1, user_id = ?, violation_id = ?, reason = ? WHERE name = ?";
        $stmt_update = $conn->prepare($update_attempted_query);
        $stmt_update->bind_param("ssss", $user_name, $violation_id, $reason, $name);
        if (!$stmt_update->execute()) {
            echo "Error updating record: " . $stmt_update->error;
            exit;
        }
    } else {
        // If the violation entry doesn't exist, insert a new record
        $insert_query = "INSERT INTO violation_entry (date, name, program, year_level, sr_code, user_id, violation_id, reason, attempted) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)";
        $stmt_insert = $conn->prepare($insert_query);
        $stmt_insert->bind_param("ssssssss", $date, $name, $program, $year_level, $sr_code, $user_name, $violation_id, $reason);
        if (!$stmt_insert->execute()) {
            echo "Error inserting record: " . $stmt_insert->error;
            exit;
        }
    }

    // Prepare and bind the SQL statement to insert data into reports table
    $insert_reports_query = "INSERT INTO reports (sr_code, name, officer, violation, date, attempted) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_reports = $conn->prepare($insert_reports_query);

    // Retrieve the name of the violation from the violation table
    $violation_name_query = "SELECT name FROM violation WHERE violation_id = ?";
    $stmt_violation_name = $conn->prepare($violation_name_query);
    $stmt_violation_name->bind_param("i", $violation_id);
    $stmt_violation_name->execute();
    $violation_result = $stmt_violation_name->get_result();
    $violation_row = $violation_result->fetch_assoc();
    $violation_name = $violation_row["name"];

    // Set the value of attempted column based on the existence of previous record
    $attempted_value = $check_result->num_rows > 0 ? $previous_record['attempted'] + 1 : 1;

    // Bind parameters and execute the insert query for reports table
    $stmt_reports->bind_param("sssssi", $sr_code, $name, $user_name, $violation_name, $date, $attempted_value);
    if (!$stmt_reports->execute()) {
        echo "Error inserting data into reports table: " . $stmt_reports->error;
        exit;
    }

    // Close the database connection
    $conn->close();

    // Display the alert and redirect after a short delay
    echo "<script>
            setTimeout(function() {
                alert('Record updated/inserted successfully!');
                window.location.href = '../guard_index.php';
            }, 1000);
          </script>";
}
?>
