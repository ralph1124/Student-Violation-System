<?php

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Required files
require 'C:\xampp\htdocs\ITPM\phpmailer\src\Exception.php';
require 'C:\xampp\htdocs\ITPM\phpmailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\ITPM\phpmailer\src\SMTP.php';

// Create an instance; passing `true` enables exceptions
if (isset($_POST["send"])) {

    // Get the recipient email from the form input field
    $recipient_email = $_POST["recipient_email"];

    // Instantiate PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();                              // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';          // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                      // Enable SMTP authentication
        $mail->Username   = 'creenciar87@gmail.com';   // SMTP email address
        $mail->Password   = 'upknyoymtojwqzre';        // SMTP password
        $mail->SMTPSecure = 'ssl';                     // Enable implicit SSL encryption
        $mail->Port       = 465;                       // SMTP port

        // Recipients
        $mail->setFrom($_POST["email"], $_POST["name"]);   // Sender email and name
        $mail->addAddress($recipient_email);               // Add recipient email
        $mail->addReplyTo($_POST["email"], $_POST["name"]); // Reply to sender email

        // Content
        $mail->isHTML(true);               // Set email format to HTML
        $mail->Subject = $_POST["subject"]; // Email subject
        $mail->Body    = $_POST["message"]; // Email message

        // Send email
        $mail->send();

        // Success message alert
        echo "
            <script> 
                alert('Message was sent successfully!');
                document.location.href = '../mail.php';
            </script>
        ";

        // Remove data from the reports table with the same sr_code as the recipient email
        if (isset($_POST["recipient_email"])) {
            $recipient_email = $_POST["recipient_email"];

            // Establish database connection
            $hostname = "localhost";
            $db_username = "root";
            $db_password = "";
            $database = "itpm";
            $conn = new mysqli($hostname, $db_username, $db_password, $database);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Prepare and execute SQL statement to delete data from the reports table
            $sql_delete = "DELETE FROM reports WHERE sr_code = ?";
            $stmt_delete = $conn->prepare($sql_delete);
            $stmt_delete->bind_param("s", $recipient_email);
            $stmt_delete->execute();

            // Check if deletion was successful
            if ($stmt_delete->affected_rows > 0) {
                // Redirect to history.php
                header("Location: ../history.php");
                exit(); // Ensure script stops execution after redirect
            } else {
                echo "No data found with sr_code = $recipient_email";
            }

            // Close database connection
            $conn->close();
        }
    } catch (Exception $e) {
        // Error message alert
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

?>
