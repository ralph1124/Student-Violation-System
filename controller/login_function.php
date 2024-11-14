<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Database connection settings
    $hostname = "localhost";
    $db_username = "root";
    $db_password = "";
    $database = "itpm";

    // Create a database connection
    $conn = new mysqli($hostname, $db_username, $db_password, $database);

    // Check connection
    if ($conn->connect_error) {
        header("Location: login.php?error=db_connection_failed&message=" . urlencode("Database connection failed: " . $conn->connect_error));
        exit;
    }

    $sql = "SELECT user_id, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password === $row['password']) {
            // Passwords match, user is authenticated
            $_SESSION['user_id'] = $row['user_id'];
            // Check if email contains "admin" or "sg"
            if (strpos($email, 'admin') !== false) {
                header("Location: ../admin.php");
                exit;
            } elseif (strpos($email, 'sg') !== false) {
                header("Location: ../guard_index.php");
                exit;
            } else {
                // Redirect to default page if email does not match any condition
                header("Location: ../index.php");
                exit;
            }
        } else {
            // Incorrect password, redirect with error message
            header("Location: ../login.php?error=incorrect_password&message=" . urlencode("Incorrect password."));
            exit;
        }
    } else {
        // User not found, echo alert message then redirect
        echo "<script>alert('Invalid email or password. Please try again.'); window.location.href='../login.php';</script>";
        exit;
    }

    $conn->close();
}
?>
