<!DOCTYPE html>
<html lang="en">
<head>
    <title>MovieHub</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Add your CSS file link here -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: black; /* Black background */
            color: white; /* Change text color to white for contrast */
        }

        /* Style the navigation bar */
        .navbar {
            position: absolute;
            top: 0;
            width: 100%;
            background: transparent;
            z-index: 10;
            padding: 15px;
        }

        .navbar-brand img {
            height: 50px;
            margin-right: 10px;
        }

        .navbar-brand span {
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        .navbar-nav .nav-link {
            color: white;
            font-size: 16px;
            margin-right: 15px;
        }

        .navbar-nav .nav-link:hover {
            color: #f8f9fa;
        }

        /* Hero section with background image */
        .hero-wrap {
            background-image: url('images/bg_1.png');
            height: 100vh;
            background-size: cover;
            background-position: center;
            position: relative;
            z-index: 1;
        }

        .hero-wrap .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2;
        }

        .hero-wrap .text {
            position: relative;
            z-index: 3;
            color: white;
            text-align: center;
        }

        .hero-wrap h1 {
            font-size: 50px;
            font-weight: 700;
            line-height: 1.2;
            color: #fff;
        }

        .btn-primary {
            background-color: #ff8c00;
            border-color: #ff8c00;
            padding: 10px 20px;
            font-size: 16px;
        }

        .btn-darken {
            background-color: #343a40;
            border-color: #343a40;
            padding: 10px 20px;
            font-size: 16px;
        }

        /* Additional styling for content sections */
        .content-section {
            padding: 60px 0;
        }

        .section-heading {
            font-size: 36px;
            margin-bottom: 30px;
            text-align: center;
        }

        footer {
            background-color: #343a40;
            color: white;
            padding: 40px 0;
            text-align: center;
        }

        footer a {
            color: #ff8c00;
        }

        footer a:hover {
            color: #fff;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            margin-top: 10px; /* Add space between text and button */
        }

        .btn-success {
            background-color: green;
        }

        .btn-danger {
            background-color: red;
        }

    </style>
</head>

<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="images/logo.png" alt="MovieHub Logo"> <!-- Logo next to the text -->
            <span>MovieHub</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="movies.php">Movies</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <li class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="images/undraw_profile.svg" alt="Profile Icon" style="height: 30px; width: 30px;"> Account
                    </a>
                    <ul class="dropdown-content">
                        <li><a class="dropdown-item" href="account.php">Account</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Search Bar Section -->
<div class="search-container">
    <div class="row">
        <div class="col-md-8 mb-4">
            <form class="search-form" id="search-form">
                <input type="text" class="search-input" id="search-input" placeholder="Search Movies...">
                <button type="submit" class="search-button">
                    <i class="fa fa-search"></i> <!-- FontAwesome search icon -->
                </button>
            </form>
        </div>
    </div>
</div>


<br><br>

<!-- Movie Display Section -->
<section class="ftco-section ftco-services-section">
    <div class="container-xl">
        <div class="row" id="allmovies-container">
        <?php
    // Load movies.xml and display movie details
    $xml = simplexml_load_file('movies.xml') or die('Error: Cannot load movie data');
    
    foreach ($xml->movie as $movie) {
        echo '
        <div class="col-md-4">
            <div class="movie-card">
            <img src="' . $movie->image . '" alt="' . $movie->title . '" class="img-fluid">
            <h3 class="card-title">' . $movie->title . '</h3> <!-- Added class card-title -->
            <p class="card-genre">Genre: ' . $movie->genre . '</p> <!-- Added class card-genre -->
            <p class="card-release-year">Release Year: ' . $movie->release_year . '</p> <!-- Added class card-release-year -->
            <p>Rating: ' . $movie->rating . '</p>
            <p>Available: ' . $movie->available . '</p>';
                
        // Option 3: Hide the rent button when the movie is unavailable
        if ($movie->available == 'Yes') {
            echo '<a href="rent-details.php?id=' . $movie['id'] . '" class="btn btn-primary">View More Details</a>';
        } else {
            echo '<p>Not Available</p>';
        }

        echo '</div></div>';
    }
?>
        </div>
<br>
        <!-- Pagination (Optional) -->
        <div class="row mt-5">
            <div class="col text-center">
                <br> <br>
                <div class="block-27">
                    <ul>
                        <li class="active"><a href="#">&lt;</a></li>
                        <li class="active"><span>1</span></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">5</a></li>
                        <li class="active"><a href="#">&gt;</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <div style="text-align: center;">
        <img src="images/logo.png" alt="MovieHub Logo" style="height: 100px; display: block; margin: 0 auto;">
        <p>&copy; 2024 MovieHub. All rights reserved.<br>
            Follow us on <a href="#">Twitter</a>, <a href="#">Facebook</a>, and <a href="#">Instagram</a>.
        </p>
    </div>
</footer>

<script src="js/app.js"></script>
<script src="js/movies.js"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'UA-23581568-13');
</script>

</body>
</html>
