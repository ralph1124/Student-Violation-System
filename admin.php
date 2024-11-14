<?php
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

    <title>TEAM ISIDRO</title>

    <!-- Custom fonts for this template-->
   <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Charts.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Script for pie chart customization -->
    <script>
        // Kunin ang reference sa canvas element
        var ctx = document.getElementById("myPieChart").getContext('2d');

        // Itakda ang kulay ng bawat segment ng pie chart
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ["First Year", "Second Year", "Third Year", "Fourth Year"],
                datasets: [{
                    data: [25, 25, 25, 25], // Halimbawa lamang ng data
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',  // First Year - Pink
                        'rgba(54, 162, 235, 0.5)',   // Second Year - Blue
                        'rgba(255, 206, 86, 0.5)',   // Third Year - Yellow
                        'rgba(255, 0, 0, 0.5)'       // Fourth Year - Red
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(255, 0, 0, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                legend: {
                    position: 'right'
                }
            }
        });
    </script>

    
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
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
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
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>


                    <ul class="navbar-nav ml-auto">


                        <!-- Nav Item - User Information -->
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
        <a class="dropdown-item" href="add_student.php">
            <i class="fas fa-plus fa-sm fa-fw mr-2 text-gray-400"></i>
            Add
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

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Student Lists</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php
                        // Database connection settings
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "itpm";

                        // Create a database connection
                        $conn = new mysqli($servername, $username, $password, $dbname);

                        // Check connection
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        // Fetch data from the database
                        $sql = "SELECT COUNT(*) AS total FROM violation_entry";
                        $result = $conn->query($sql);

                        // Check if there are rows returned
                        if ($result->num_rows > 0) {
                            // Output data of the first row
                            $row = $result->fetch_assoc();
                            echo $row["total"];
                        } else {
                            echo "0"; // If no rows found, display 0
                        }

                        // Close the database connection
                        $conn->close();
                        ?>
                    </div>
                </div>
                <div class="col-auto">
                    <a href="studentlist.php" class="btn btn-primary">
                        <i class="fas fa-id-card"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Earnings (Monthly) Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                     Violation Lists</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php
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

                        // Query to fetch the count of violation records
                        $sql = "SELECT COUNT(*) AS total_violations FROM violation";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // Output data
                            $row = $result->fetch_assoc();
                            echo $row["total_violations"];
                        } else {
                            echo "0";
                        }

                        // Close database connection
                        $conn->close();
                        ?>
                    </div>
                </div>
                <div class="col-auto">
                    <a href="violationlist.php" class="btn btn-success">
                        <i class="fa fa-exclamation-triangle"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Lists -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">User</div>
                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                        <?php
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

                        // Query to fetch the count of user records
                        $sql = "SELECT COUNT(*) AS total_users FROM users";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // Output data
                            $row = $result->fetch_assoc();
                            echo $row["total_users"];
                        } else {
                            echo "0";
                        }

                        // Close database connection
                        $conn->close();
                        ?>
                    </div>
                </div>
                <div class="col-auto">
                    <a href="userlist.php" class="btn btn-info">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>



                    <!-- Content Row -->

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "itpm";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT year_level, COUNT(*) AS count FROM violation_entry GROUP BY year_level";
$result = $conn->query($sql);
$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[$row["year_level"]] = $row["count"];
    }
}

// Close database connection
$conn->close();
?>
        <div class="col-xl-6 mb-4">
            <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Year Level</h6>
                </div>
                <div class="card-body">
                    <div class="bagong-chart pt-4 pb-2"> <!-- Bagong pangalan para sa bagong chart -->
                        <canvas id="bagoChart"></canvas> <!-- Bagong ID para sa bagong chart -->
                    </div>
                </div>
            </div>
        </div>


<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "itpm";

// Bagong connection para sa pagkuha ng data ng programa at bilang ng mga paglabag
$conn_program = new mysqli($servername, $username, $password, $dbname);

if ($conn_program->connect_error) {
    die("Connection failed: " . $conn_program->connect_error);
}

$sql_program = "SELECT program, COUNT(*) AS count FROM violation_entry GROUP BY program";
$result_program = $conn_program->query($sql_program);

$data_program = array();
if ($result_program->num_rows > 0) {
    while ($row_program = $result_program->fetch_assoc()) {
        $data_program[$row_program["program"]] = $row_program["count"];
    }
}

// Isara ang connection para sa data ng programa
$conn_program->close();
?>

        <div class="col-xl-6 mb-4">
            <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Programs</h6>
                </div>
                <div class="card-body">
                    <div class="bar-graph pt-4 pb-2"> <!-- Bagong pangalan para sa bar graph -->
                        <canvas id="barGraph"></canvas> <!-- Bagong ID para sa bar graph -->
                    </div>
                </div>
            </div>
        </div>

    </div>


                            </div>
                        </div>
            <!-- End of Main Content -->

            <!-- Footer -->
          
            <!-- End of Footer -->

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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var ctx = document.getElementById('bagoChart').getContext('2d'); // Ipalit ang 'myPieChart' sa 'bagoChart'
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode(array_keys($data)); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_values($data)); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true,
                    position: 'right'
                }
            }
        });
    });
</script>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                var ctx = document.getElementById('barGraph').getContext('2d');
                var myBarChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: <?php echo json_encode(array_keys($data_program)); ?>,
                        datasets: [{
                            label: 'Number of Violations',
                            data: <?php echo json_encode(array_values($data_program)); ?>,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            display: false
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    stepSize: 1 // Pagpapakita ng mga ticks sa 1-step intervals
                                }
                            }],
                            xAxes: [{
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Program' // Pangalan ng x-axis
                                },
                                ticks: {
                                    autoSkip: false // Ito ay para maiwasan ang pag-skip sa mga label
                                }
                            }]
                        }
                    }
                });
            });
        </script>
<script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>