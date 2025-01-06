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
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
            <link href="css/stlye.css" rel="stylesheet">

</head>
<?php

// Initialize message variable
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
  
    $movieTitle = $_POST['m_title'];
    $genre = $_POST['Genre'];
    $releaseYear = $_POST['rel_year'];
    $rating = $_POST['rating'];
    $available = isset($_POST['Available']) ? $_POST['Available'] : 'No';
    $copies = $_POST['copies'];

    // Handle image upload
    $imagePath = '';
    if (isset($_FILES['movie_image']) && $_FILES['movie_image']['error'] == 0) {
        $imagePath = '../../images/' . basename($_FILES['movie_image']['name']);
        $inserImgepath = 'images/' . basename($_FILES['movie_image']['name']);
        move_uploaded_file($_FILES['movie_image']['tmp_name'], $imagePath);
    }

    // Path to the XML file
    $xmlFile = '../../movies.xml';

    // Check if the XML file exists
    if (file_exists($xmlFile)) {
        // Load existing XML file
        $xml = simplexml_load_file($xmlFile);
    } else {
        // Create a new XML document if the file doesn't exist
        $xml = new SimpleXMLElement('<?xml version="1.0"?><movies></movies>');
    }

    // Get the last movie's ID and generate a new one
    $lastMovie = $xml->xpath("//movie[last()]");
    $newId = ($lastMovie && isset($lastMovie[0]['id'])) ? intval($lastMovie[0]['id']) + 1 : 1;

    // Add new movie data with ID and image
    $movie = $xml->addChild('movie');
    $movie->addAttribute('id', $newId);
    $movie->addChild('title', $movieTitle);
    $movie->addChild('genre', $genre);
    $movie->addChild('release_year', $releaseYear);
    $movie->addChild('rating', $rating);
    $movie->addChild('available', $available);
    $movie->addChild('copies', $copies);
    $movie->addChild('image', $inserImgepath);

    // Save the updated XML content to the file
    $xml->asXML($xmlFile);

    // Success message
    $message = "Movie added successfully!";
}

?>

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
                        <h1 class="h3 mb-0 text-gray-800">Add New Movies Dashboard</h1>
                        
                    </div>
                    
                    <!-- Content Row -->
                    <div class="row">
                        <!-- Area Chart -->
                        <div class="col-xl-8">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Add Movie</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            
                                        </a>
                                        
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                <form method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-xl-4">Movie Title</div>
                <div class="col-xl-8">
                    <input type="text" class="form-control" name="m_title" id="m_title" placeholder="Enter Movie Title" required>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xl-4">Movie Genre</div>
                <div class="col-xl-8">
                    <textarea class="form-control" name="Genre" id="Genre" rows="4" placeholder="Enter Genre" required></textarea>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xl-4">Release Year</div>
                <div class="col-xl-8">
                    <input type="text" class="form-control" name="rel_year" id="rel_year" placeholder="Enter release year" required>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xl-4">Rating</div>
                <div class="col-xl-8">
                    <input type="text" class="form-control" name="rating" id="rating" placeholder="Enter rating" required>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xl-4">Available</div>
                <div class="col-xl-8">
                    <input type="radio" name="Available" value="Yes" id="true" required> Yes
                    <input type="radio" name="Available" value="No" id="false" required> No
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xl-4">Copies</div>
                <div class="col-xl-8">
                    <input type="text" class="form-control" name="copies" id="copies" placeholder="Number of Copies" required>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xl-4">Movie Image</div>
                <div class="col-xl-8">
                    <input type="file" name="movie_image" id="movie_image" required>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xl-10"></div>
                <div class="col-xl-2">
                    <input type="submit" name="submit" value="Submit" class="btn btn-success">
                </div>
            </div>
        </form>
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