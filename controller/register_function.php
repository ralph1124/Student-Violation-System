<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $hostname = "localhost";
    $db_username = "root";
    $db_password = "";
    $database = "itpm";

    $conn = new mysqli($hostname, $db_username, $db_password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if all required fields are present
    if (isset($_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['password'])) {
        $firstname = validate($_POST['firstname']);
        $lastname = validate($_POST['lastname']);
        $email = validate($_POST['email']);
        $password = validate($_POST['password']);

        // Check if any field is empty
        if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
            echo "<script>alert('All fields are required');</script>";
        } else {
            // Check if the email already exists in the database
            $check_query = "SELECT * FROM users WHERE email = ?";
            $check_stmt = $conn->prepare($check_query);
            $check_stmt->bind_param("s", $email);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows > 0) {
                echo "<script>alert('Email already exists'); window.location.href = '../register.php';</script>"; // Redirect to signup.php
            } else {
                // Insert user data into the database
                $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);

                if ($stmt === false) {
                    echo "Error: " . $conn->error;
                } else {
                    $stmt->bind_param("ssss", $firstname, $lastname, $email, $password);

                    if ($stmt->execute()) {
                        echo "<script>alert('You are successfully registered.'); window.location.href = '../login.php';</script>"; // Alert message after successful registration
                    } else {
                        echo "Error: " . $stmt->error;
                    }
                }
            }
        }
    } else {
        echo "<script>alert('All fields are required');</script>";
    }

    $conn->close();
}
?>
