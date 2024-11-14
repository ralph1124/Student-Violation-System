<?php
date_default_timezone_set('Asia/Manila');
session_start();

$hostname = "localhost";
$db_username = "root";
$db_password = "";
$database = "itpm";
$conn = new mysqli($hostname, $db_username, $db_password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Prepare SQL statement to fetch user data
    $sql = "SELECT firstname, lastname, avatar FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['user_name'] = $row['firstname'] . ' ' . $row['lastname'];
        $_SESSION['user_avatar'] = $row['avatar'];
    } else {
        echo "User data not found.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Team Isidro</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    
    

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="guard_index.php">
                <div class="sidebar-brand-icon">
                    <img class="img-profile rounded-circle" src="img/bsulogo.jpg" class="img-fluid" alt="BSU Logo" style="max-width: 60px;">
                </div>
                <div class="sidebar-brand-text mx-1">DCVS</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->


                                                <li class="nav-item active">
                <a class="nav-link" href="guard_violationentry.php">
                    <i class="fa fa-exclamation-triangle"></i>
                    <span>Violation Entry</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="guard_index.php">
                    <i class="fas fa-id-card"></i>
                    <span>Students List</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Maintenance
            </div>
            <li class="nav-item">
                <a class="nav-link" href="guard_violation_list.php">
                    <i class="fa fa-exclamation-triangle"></i>
                    <span>Violation List</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="guard_user_list.php">
                    <i class="fas fa-users"></i>
                    <span>User List</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>


        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>
                    <ul class="navbar-nav ml-auto">

                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                            <?php 
                                // Check if user is logged in and if session variables are set
                                if(isset($_SESSION['user_id']) && isset($_SESSION['user_name'])) {
                                    // Display user's name
                                    echo $_SESSION['user_name'];
                                } else {
                                    echo "Guest"; // Display "Guest" if no user is logged in
                                }
                            ?>
                        </span>
                        <img class="img-profile rounded-circle" src="<?php echo isset($_SESSION['user_avatar']) ? 'img/profile/' . $_SESSION['user_avatar'] : 'img/undraw_profile.svg'; ?>">
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                        aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="guard_profile.php">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                            Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </a>
                    </div>
                </li>


                    </ul>

                </nav>


                <!-- End of Topbar -->
<?php


// Ensure user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_name'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: login.php");
    exit;
}

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

// Query to fetch violations with status "Active"
$sql_violation = "SELECT violation_id, name FROM violation WHERE status = 'Active'";
$result_violation = $conn->query($sql_violation);
?>

<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Add New Violator</h1>

    <form action="controller/violationentry_function.php" method="POST">
        <!-- RFID Number Field -->
        <div class="form-group">
            <input type="text" class="form-control" id="rfid_number" name="rfid_number" placeholder="SCAN">
            <div id="rfid_error" style="color: red;"></div> <!-- Error message container -->
        </div>

        <!-- Date Field -->
<!-- Date and Time Field -->
<div class="form-group">
    <label for="date_created">Date and Time:</label>
    <?php
    // Get the current date and time in the correct format
    $current_datetime = date('Y-m-d\TH:i:s');
    ?>
    <input type="datetime-local" class="form-control" id="date_created" name="date_created" value="<?php echo $current_datetime; ?>" required>
</div>


        <!-- Student Name Field -->
        <div class="form-group">
            <label for="name">Student Name:</label>
            <input type="text" class="form-control" id="name" name="name" required readonly>
        </div>

        <!-- Program Field -->
        <div class="form-group">
            <label for="program">Department:</label>
            <input type="text" class="form-control" id="program" name="program" required readonly>
        </div>

        <!-- Year Level Field -->
        <div class="form-group">
            <label for="year_level">Year Level:</label>
            <input type="text" class="form-control" id="year_level" name="year_level" required readonly>
        </div>

        <!-- Sr-Code Field -->
        <div class="form-group">
            <label for="sr_code">Sr-Code:</label>
            <input type="text" class="form-control" id="sr_code" name="sr_code" required readonly>
        </div>

        <!-- Violation Dropdown -->
        <div class="form-group">
            <label for="violation">Violation:</label>
            <select class="form-control" id="violation" name="violation" required>
                <?php
                // Display violations in dropdown
                if ($result_violation->num_rows > 0) {
                    while ($row = $result_violation->fetch_assoc()) {
                        echo '<option value="' . $row["violation_id"] . '">' . $row["name"] . '</option>';
                    }
                } else {
                    echo '<option value="">No active violations found</option>';
                }
                ?>
            </select>
        </div>

        <!-- Officer on Duty Field -->
        <div class="form-group">
            <label for="officer_on_duty">Officer on Duty:</label>
            <input type="text" class="form-control" id="officer_on_duty" name="officer_on_duty" value="<?php echo $_SESSION["user_name"]; ?>" readonly>
        </div>

        <!-- Reason Field -->
        <div class="form-group">
            <label for="reason">Reason:</label>
            <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<?php
// Close the database connection
$conn->close();
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $('#rfid_number').focus(); // Focus on the RFID number input field when the page loads

    // Perform an action when the user types in the RFID number input field
    $('#rfid_number').on('change', function(){
        var rfid_card = $(this).val();
        $.ajax({
            url: 'check_rfid.php',
            type: 'POST',
            data: {rfid_number: rfid_card},
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    $('#name').val(response.data.name);
                    $('#program').val(response.data.program);
                    $('#year_level').val(response.data.year_level);
                    $('#sr_code').val(response.data.sr_code);
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('An error occurred while processing the request.');
            }
        });
        // Clear the RFID input field after processing
        $(this).val("");
    });
});

</script>



<!-- /.container-fluid -->


            </div>
            <!-- End of Main Content -->


        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="index.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>