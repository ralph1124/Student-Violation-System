<?php
// Start session
session_start();

// Include database connection settings
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

// Check if user is logged in
if(isset($_SESSION['user_id'])) {
    // Get user's ID from session
    $user_id = $_SESSION['user_id'];

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if file is uploaded
        if(isset($_FILES["avatar"]) && $_FILES["avatar"]["error"] == 0) {
            $target_dir = "../img/profile/";
            $target_file = $target_dir . basename($_FILES["avatar"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
                    // Set avatar name
                    $avatar_name = basename($_FILES["avatar"]["name"]);

                    // Update avatar in database
                    $update_avatar_sql = "UPDATE users SET avatar = ? WHERE user_id = ?";
                    $stmt = $conn->prepare($update_avatar_sql);
                    $stmt->bind_param("si", $avatar_name, $user_id);
                    if ($stmt->execute()) {
                        echo "The file ". htmlspecialchars( basename( $_FILES["avatar"]["name"])). " has been uploaded.";
                    } else {
                        echo "Error updating avatar: " . $stmt->error;
                    }
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }

        // Update email, first name, and last name in database
        $email = $_POST["email"];
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $avatar = isset($avatar_name) ? $avatar_name : ""; // Set default value for $avatar

        // Update profile in database including avatar
        $update_profile_sql = "UPDATE users SET email = ?, firstname = ?, lastname = ?, avatar = ? WHERE user_id = ?";
        $stmt = $conn->prepare($update_profile_sql);
        $stmt->bind_param("ssssi", $email, $firstName, $lastName, $avatar, $user_id); // Added an extra 's' for the avatar parameter
        if ($stmt->execute()) {
            echo "Profile updated successfully.";
        } else {
            echo "Error updating profile: " . $stmt->error;
        }

        // Redirect to profile.php after updating profile
        header("Location: ../guard_profile.php");
        exit();
    }
}

// Close database connection
$conn->close();
?>
