<!DOCTYPE html>
<html lang="en">
<head>
    <title>MovieHub</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Add your CSS file link here -->
    <link rel="stylesheet" href="css/style.css">

    <style>
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

        /* Additional styling for content sections */
        .content-section {
            padding: 60px 0;
        }

        .section-heading {
            font-size: 36px;
            margin-bottom: 30px;
            text-align: center;
            color: white;
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
        .movies-wrapper {
    display: flex;
    align-items: center;
    position: relative;
    width: 100%;
}

.movies-container {
    display: flex;
    overflow-x: hidden; /* Hide scrollbar */
    scroll-behavior: smooth;
    width: 90%; /* Adjust the width as per your requirement */
    padding: 10px;
    gap: 10px; /* Space between movie cards */
    white-space: nowrap; /* Ensure items stay in a single row */
    margin: 0 auto; /* Center align */
}

.movie-card {
    background-size: cover;
    background-position: center;
    height: 300px;
    width: 200px; /* Set a fixed width for each movie card */
    position: relative;
    border: 1px solid #ddd;
    display: flex;
    justify-content: flex-end;
    flex-direction: column;
}

.movie-title {
    background-color: white;
    text-align: center;
    padding: 10px;
    width: 100%;
}

.card-title {
    color: black;
    margin: 0;
}

.scroll-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
    z-index: 1000;
    font-size: 24px;
    width: 50px;
    height: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.scroll-btn.left {
    left: 0;
}

.scroll-btn.right {
    right: 0;
}


.card-footer {
    position: absolute;
    bottom: 0;
    width: 100%;
    text-align: center;
    background-color: white;
    padding: 10px;
}

/* Button styles */
.btn {
    font-weight: bold; /* Makes button text bold */
}

.btn-success {
    background-color: #28a745; /* Green color for available */
    border-color: #28a745; /* Match border color */
}

.btn-danger {
    background-color: #dc3545; /* Red color for not available */
    border-color: #dc3545; /* Match border color */
}

/* Hover effects for buttons */
.btn-success:hover {
    background-color: #218838; /* Darker green on hover */
}

.btn-danger:hover {
    background-color: #c82333; /* Darker red on hover */
}


        .card-footer {
            position: relative; /* Positioning for the footer */
            z-index: 2; /* Bring the footer above the background */
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
            <ul class="dropdown-content" >
                <li><a class="dropdown-item" href="account.php">Account</a></li>
              
                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
            
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<section class="hero-wrap">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center text-center">
            <div class="col-lg-9">
                <div class="text" data-aos="fade-up" data-aos-delay="300" data-aos-duration="1000">
                    <h1 class="mb-5">Your Ultimate Guide to Rent, Stream, and Enjoy the Latest Movies</h1>
                    <p>
                        <a href="register.php" class="btn btn-primary p-4 py-3">Register Now 
                            <span class="ion-ios-arrow-round-forward"></span>
                        </a> 
                        <a href="login.php" class="btn btn-darken p-4 py-3">Login
                            <span class="ion-ios-arrow-round-forward"></span>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>



<section class="ftco-section testimony-section" style="background-color: black;">
    <div class="container-fluid">
        <div class="row justify-content-center pb-5">
            <div class="col-md-7 text-center heading-section" data-aos="fade-up" data-aos-duration="1000">
                <span class="subheading">Our Movies</span>
                <h2 class="mb-3"> Top Movies <span></span></h2>
            </div>
        </div>
        <div class="movies-wrapper">
            <button class="scroll-btn left" onclick="scrollLeft('trend-container')">&#8249;</button> <!-- Left arrow -->

            <div id="trend-container" class="movies-container">
                <!-- Movie items will be appended here dynamically -->
            </div>

            <button class="scroll-btn right" onclick="scrollRight('trend-container')">&#8250;</button> <!-- Right arrow -->
        </div>
    </div>
</section>

<section class="ftco-section testimony-section" style="background-color: black;">
    <div class="container-fluid">
        <div class="row justify-content-center pb-5">
            <div class="col-md-7 text-center heading-section" data-aos="fade-up" data-aos-duration="1000">
                <span class="subheading">Our Movies</span>
                <h2 class="mb-3"> Trending <span></span></h2>
            </div>
        </div>
        <div class="movies-wrapper">
            <button class="scroll-btn left" onclick="scrollLeft('movies-container')">&#8249;</button> <!-- Left arrow -->

            <div id="movies-container" class="movies-container">
                <!-- Movie items will be appended here dynamically -->
            </div>

            <button class="scroll-btn right" onclick="scrollRight('movies-container')">&#8250;</button> <!-- Right arrow -->
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


<!-- Scripts -->
<script src="js/app.js"></script>
<script src="js/movies.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
