<?php
session_start();

// Check if admin is logged in


// Load the users.xml file
$xml = simplexml_load_file('../../users.xml') or die('Error: Cannot load user data');

// Array to store pending rentals
$pendingRentals = [];

// Loop through each user in the XML
foreach ($xml->user as $user) {
    if (isset($user->rentals)) {
        foreach ($user->rentals->rental as $rental) {
            // Check if rental is pending
            if ((string)$rental->returned === 'pending') {
                // Find the corresponding movie details
                $movieId = (string)$rental->movie_id; // Use movie_id from rental
                
                // Load movies.xml to get title, genre, release year, etc.
                $moviesXml = simplexml_load_file('../../movies.xml') or die('Error: Cannot load movie data');

                // Loop through movies to find details
                foreach ($moviesXml->movie as $movie) {
                    if ((string)$movie['id'] === $movieId) {
                        $pendingRentals[] = [
                            'movie_id' => (string)$movie['id'],
                            'user_id' => (string)$user['id'],
                            'user_name' => (string)$user->username,
                            'movie_title' => (string)$movie->title,
                            'genre' => (string)$movie->genre,
                            'release_year' => (string)$movie->release_year,
                        ];
                        break; // Exit loop after finding the movie
                    }
                }
            }
        }
    }
}

// Handle rental status updates (Accept/Reject)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rental_id = $_POST['rental_id'] ?? null;
    $action = $_POST['action'] ?? null;

    if ($rental_id && $action) {
        foreach ($xml->user as $user) {
            if (isset($user->rentals)) {
                foreach ($user->rentals->rental as $rental) {
                    if ((string)$rental['rental_id'] === $rental_id) {
                        if ($action === 'accept') {
                            $rental->returned = 'true'; // Mark as returned
                        } elseif ($action === 'reject') {
                            $rental->returned = 'rejected'; // Mark as rejected
                        }
                        break 2; // Break out of both loops
                    }
                }
            }
        }

        // Save the updated XML file
        $xml->asXML('../../users.xml');

        // Redirect to the same page to refresh the list
        header('Location: return.php');
        exit;
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
    <title>MovieHub</title>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

</head>
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                
                <div class="sidebar-brand-text mx-3"> 
                <img src="./img/Logo.png" width="50px" height="50px"> MovieHub</div>
            </a>
            <!-- Divider -->
            <hr class="sidebar-divider my-0">
             <!-- Nav Item - Dashboard -->
             <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            <li class="nav-item ">
                <a class="nav-link" href="addmovie.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Add New Movie</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Addons
            </div>

           
            

           
            <li class="nav-item">
                <a class="nav-link" href="editmovie.php">
                    <i class="fa fa-plus"></i>
                    <span>Edit Movie Details</span></a>
                    
            </li>
            <li class="nav-item">
                <a class="nav-link" href="return.php">
                    <i class="fa fa-plus"></i>
                    <span>Reterned Movie Details</span></a>
                    
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
                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
                     

                       
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            
                        <a href="logout.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" style="background-color:#990000;"> Logout</a>

                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Return Movie Details</h1>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>User Name</th>
                                <th>Movie Title</th>
                                <th>Genre</th>
                                <th>Release Year</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($pendingRentals)): ?>
                            <tr>
                                <td colspan="5">No pending returns found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pendingRentals as $rental): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($rental['user_name']); ?></td>
                                    <td><?php echo htmlspecialchars($rental['movie_title']); ?></td>
                                    <td><?php echo htmlspecialchars($rental['genre']); ?></td>
                                    <td><?php echo htmlspecialchars($rental['release_year']); ?></td>
                                    <td>
                                        <form method="post" action="">
                                            <input type="hidden" name="rental_id" value="<?php echo htmlspecialchars($rental['movie_id']); ?>">
                                            <button type="submit" name="action" value="accept" class="accept-btn">Accept</button>
                                            <button type="submit" name="action" value="reject" class="reject-btn">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    </div>
                    </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy;  MovieHub- All rights reserved | Site By P.M.Thathsarani</span>
                    </div>
                </div>
            </footer>
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
                <a class="btn btn-primary" href="logout.php">Logout</a>
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
    <script src="vendor/chart.js/Chart.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
</body>
</html>