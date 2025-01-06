<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['loggedInUser']) || $_SESSION['userRole'] !== 'admin') {
    die('Error: You must be logged in as admin to access this page.');
}

// Check if the movie ID is provided in the URL
if (!isset($_GET['movie_id'])) {
    die('Error: Movie ID not provided.');
}

$movieId = $_GET['movie_id'];

// Load the movies.xml file
$moviesXml = simplexml_load_file('../../movies.xml') or die('Error: Cannot load movie data');

// Find the movie with the given ID
$selectedMovie = null;
foreach ($moviesXml->movie as $movie) {
    if ((string)$movie['id'] === $movieId) {
        $selectedMovie = $movie;
        break;
    }
}

// If the movie is not found, display an error message
if ($selectedMovie === null) {
    die('Error: Movie not found.');
}

// If the form is submitted, update the movie details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedMovie->title = $_POST['title'];
    $selectedMovie->genre = $_POST['genre'];
    $selectedMovie->release_year = $_POST['release_year'];
    $selectedMovie->rented = isset($_POST['rented']) ? 'true' : 'false';

    // Save the updated XML
    $moviesXml->asXML('../../movies.xml');
    header('Location: index.php'); // Redirect to the main page after updating
    exit;
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
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-text mx-3">
                    <img src="./img/Logo.png" width="50px" height="50px"> MovieHub 
                </div>
            </a>

            <hr class="sidebar-divider my-0">
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Interface</div>
            <li class="nav-item">
                <a class="nav-link" href="addmovie.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Add New Movie</span>
                </a>
            </li>
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Addons</div>
            <li class="nav-item">
                <a class="nav-link" href="editmovie.php">
                    <i class="fa fa-plus"></i>
                    <span>Edit Movie Details</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="return.php">
                    <i class="fa fa-plus"></i>
                    <span>Returned Movie Details</span>
                </a>
            </li>
            <hr class="sidebar-divider d-none d-md-block">
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
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
                        <li class="nav-item dropdown no-arrow">
                            <a href="logout.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" style="background-color:#990000;"> Logout</a>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1>Edit Movie: <?= $selectedMovie->title ?></h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-xl-9">
                            <form method="post" enctype="multipart/form-data">
                                <div class="card mb-5 shadow-sm">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-xl-4">Movie Title</div>
                                            <div class="col-xl-8">
                                            <input type="text" name="title" value="<?= $selectedMovie->title ?>" required><br>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-xl-4">Movie Genre</div>
                                            <div class="col-xl-8">
                                            <textarea class="form-control" name="genre" id="genre" rows="3" required><?= htmlspecialchars($movie->genre) ?></textarea>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-xl-4">Release Year</div>
                                            <div class="col-xl-8">
                                            <input type="number" class="form-control" name="release_year" id="release_year" value="<?= htmlspecialchars($movie->release_year) ?>" required>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-xl-4">Rating</div>
                                            <div class="col-xl-8">
                                            <input type="number" class="form-control" name="rating" id="rating" step="0.1" min="0" max="10" value="<?= htmlspecialchars($movie->rating) ?>" required>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-xl-4">Available</div>
                                            <div class="col-xl-8">
                                                <select class="form-control" name="available" id="available">
                                                <option value="Yes" <?= $movie->available == 'Yes' ? 'selected' : '' ?>>Yes</option>
                                                <option value="No" <?= $movie->available == 'No' ? 'selected' : '' ?>>No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-xl-4">Copies</div>
                                            <div class="col-xl-8">
                                            <input type="number" class="form-control" name="copies" id="copies" value="<?= htmlspecialchars($movie->copies) ?>" required>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-xl-4">Movie Image</div>
                                            <div class="col-xl-8">
                                                <input type="file" name="movie_image" accept="image/*">
                                                <?php if (!empty($movie->image)): ?>
                                                    <br><img src="<?= htmlspecialchars($movie->image) ?>" alt="Movie Image" width="100">
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-xl-4"></div>
                                            <div class="col-xl-8">
                                            <button type="submit" class="btn btn-primary">Update Movie</button>
                                            </div>
                                            </div>
                                        </div>
                                        <a href="index.php">Back to Movie List</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>  </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="text-center my-auto copyright">
                        <span>Copyright &copy; MovieHub 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
