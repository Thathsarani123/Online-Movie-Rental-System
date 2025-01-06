<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['loggedInUser']) || $_SESSION['userRole'] !== 'admin') {
    die('Error: You must be logged in as admin to access this page.');
}

// Load the movies.xml file
$moviesXml = simplexml_load_file('../../movies.xml') or die('Error: Cannot load movie data');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle delete movie
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $movieId = $_POST['movie_id'];
        $index = 0;
        foreach ($moviesXml->movie as $movie) {
            if ((string)$movie['id'] === $movieId) {
                unset($moviesXml->movie[$index]); // Remove the movie from XML
                $moviesXml->asXML('../../movies.xml'); // Save the updated XML
                header('Location: index.php'); // Refresh the page
                exit;
            }
            $index++;
        }
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
                <img src="./img/Logo.png" width="60px" height="60px">MovieHub</div>
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

            <!-- Nav Item - Pages Collapse Menu -->
        
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
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            
                        <a href="logout.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" style="background-color:#990000;"> Logout</a>

                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">All Movie Details</h1>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Movie ID</th>
                                
                                <th>Movie Title</th>
                                <th>Genre</th>
                                <th>Release year</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($moviesXml->movie as $movie): ?>
                            <tr>
                                <td><?= $movie['id'] ?></td>
                                <td><?= $movie->title ?></td>
                                <td><?= $movie->genre ?></td>
                                <td><?= $movie->release_year ?></td>
                                <td>
                                    <?php if ($movie->rented === 'true'): ?>
                                        <span class="badge badge-warning">Rented</span>
                                    <?php elseif ($movie->rented === 'false'): ?>
                                        <span class="badge badge-success">Returned</span>
                                    <?php else: ?>
                                        <span class="badge badge-info">Available</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                <a href="editmovie.php?movie_id=<?= $movie['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="movie_id" value="<?= $movie['id'] ?>">
                                        <button type="submit" name="action" value="delete" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this movie?');">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    </div> 

                    </div>

                </div>   

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


    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

   <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

  

</body>
</html>