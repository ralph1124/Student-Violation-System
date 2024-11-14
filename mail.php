<?php
session_start();

// Database connection settings
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
    $sql = "SELECT firstname, lastname, email, avatar FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['user_name'] = $row['firstname'] . ' ' . $row['lastname'];
        $_SESSION['user_email'] = $row['email']; // Store user's email in session variable
        $_SESSION['user_avatar'] = $row['avatar'];
    } else {
        echo "User data not found.";
    }
}

// Retrieve the sr_code from the violation_entry table
$srCode = '';
if(isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
    $sql = "SELECT sr_code FROM violation_entry WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $srCode = $row['sr_code'];
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

                    <!-- Topbar Search -->
                    

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                       
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

<?php
// Kunin ang SR-Code mula sa URL
if(isset($_GET['sr_code'])) {
    $srCode = $_GET['sr_code'];
} else {
    $srCode = "";
}
?>

<div class="container">
    <h1 class="h3 mb-4 text-gray-800">Mail</h1>
    <div class="row">
        <div class="col-lg-6">
            <form id="contact" action="controller/mail_function.php" method="post">
                <div class="form-group">
                    <input placeholder="Your name" name="name" type="text" class="form-control" tabindex="1" autofocus required value="<?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; ?>" readonly>
                </div>
                <div class="form-group">
                    <input placeholder="Your Email Address" name="email" type="email" class="form-control" tabindex="2" required value="<?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : ''; ?>" readonly>
                </div>
                <div class="form-group">
                    <input placeholder="Recipient's Email Address" name="recipient_email" type="email" class="form-control" tabindex="3" required value="<?php echo $srCode; ?>" readonly>
                </div>
                <div class="form-group">
                    <input placeholder="Type your subject line" type="text" name="subject" class="form-control" tabindex="4">
                </div>
                <div class="form-group">
                    <textarea name="message" placeholder="Type your Message Details Here..." class="form-control" tabindex="5" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <button class="btn btn-success btn-sm" type="submit" name="send" id="contact-submit">Send Mail</button>
                </div>
            </form>
        </div>
    </div>
</div>

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