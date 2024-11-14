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

// Check if user is logged in
if(isset($_SESSION['user_id'])) {
    // Get user's ID from session
    $user_id = $_SESSION['user_id'];

    // Prepare SQL statement to fetch user data
    $sql = "SELECT firstname, lastname, avatar FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch user data
        $row = $result->fetch_assoc();
        // Store user's first name and last name in session
        $_SESSION['user_name'] = $row['firstname'] . ' ' . $row['lastname'];
        // Store user's avatar in session
        $_SESSION['user_avatar'] = $row['avatar'];
    } else {
        // Handle error if user data is not found
        echo "User data not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Tables</title>

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

            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="admin.php">
                <div class="sidebar-brand-icon">
                    <img class="img-profile rounded-circle" src="img/bsulogo.jpg" class="img-fluid" alt="BSU Logo" style="max-width: 60px;">
                </div>
                <div class="sidebar-brand-text mx-1">DCVS</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="admin.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="studentlist.php">
                    <i class="fas fa-id-card"></i>
                    <span>Students List</span></a>
            </li>
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReports"
        aria-expanded="true" aria-controls="collapseReports">
        <i class="fas fa-file"></i>
        <span>Reports</span>
    </a>
                <div id="collapseReports" class="collapse" aria-labelledby="headingReports" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="reports.php">Student srecords</a>
                        <a class="collapse-item" href="violation_record.php">Violation records</a>
                        <a class="collapse-item" href="history.php">History</a>
                    </div>
                </div>
</li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Maintenance
            </div>
            <li class="nav-item">
                <a class="nav-link" href="violationlist.php">
                    <i class="fa fa-exclamation-triangle"></i>
                    <span>Violation List</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="userlist.php">
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
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

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
                        <div class="topbar-divider d-none d-sm-block"></div>

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
        <a class="dropdown-item" href="profile.php">
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

        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <!-- Topbar content here -->

                <!-- End of Topbar -->

                <!-- Begin Page Content -->

<?php
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

// Check if user is logged in and if session variables are set
if(isset($_SESSION['user_id'])) {
    // Get user's ID from session
    $user_id = $_SESSION['user_id'];

    // Prepare SQL statement to fetch user data
    $sql = "SELECT firstname, lastname, email, avatar FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch user data
        $row = $result->fetch_assoc();
        // Store user's data in variables
        $firstname = $row['firstname'];
        $lastname = $row['lastname'];
        $email = $row['email'];
        $avatar = $row['avatar'];
    } else {
        // Handle error if user data is not found
        echo "User data not found.";
    }
}

// Close database connection
$conn->close();
?>

<div class="container-fluid">
    <!-- Update Profile -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Update Profile</h6>
        </div>
        <div class="card-body">
            <div class="text-center mb-4">
                <img class="img-profile rounded-circle mb-2" src="<?php echo isset($avatar) ? 'img/profile/' . $avatar : 'img/undraw_profile.svg'; ?>" alt="Avatar" style="width: 80px; height: 80px;">
                <h4 class="text-gray-900" style="font-size: 18px;"><?php echo isset($firstname) && isset($lastname) ? $firstname . ' ' . $lastname : 'John Doe'; ?></h4>
            </div>
            <form method="POST" action="controller/update_profile.php" enctype="multipart/form-data">
                <!-- Avatar -->
                <div class="form-group">
                    <label for="avatar">Change Avatar:</label>
                    <input type="file" class="form-control-file" id="avatar" name="avatar">
                </div>
                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="text" class="form-control" id="email" name="email" value="<?php echo isset($email) ? $email : 'user@example.com'; ?>">
                </div>
                <!-- First Name -->
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="firstName">First Name:</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo isset($firstname) ? $firstname : 'John'; ?>">
                    </div>
                    <!-- Last Name -->
                    <div class="col-md-6">
                        <label for="lastName">Last Name:</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo isset($lastname) ? $lastname : 'Doe'; ?>">
                    </div>
                </div>
                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
    </div>
</div>


                <!-- /.container-fluid -->
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
