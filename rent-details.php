<?php
// Start the session to access user ID
session_start();

// Get the movie ID from the URL
$movie_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!isset($_SESSION['loggedInUser'])) {
    // If not logged in, redirect to login page or show an error message
    echo "<script>alert('You must be logged in to rent a movie.'); window.location.href='login.php';</script>";
    exit; // Stop further execution
}

$xml = simplexml_load_file('users.xml') or die('Error: Cannot load users data.');

// Get the logged-in username from the session
$loggedInUser = $_SESSION['loggedInUser'];

// Variable to store the user's ID
$userId = null;

foreach ($xml->user as $user) {
    if ((string)$user->username == $loggedInUser) {
        $userId = (string)$user['id']; // Get the user ID
        break; // Exit the loop once the user is found
    }
}

if ($userId === null) {
    die("Error: Unable to find user ID for logged in user.");
}

// Now you can use the $userId for further processing
//echo "User ID of logged-in user: " . $userId;

// Load movies.xml

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>MovieHub</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Add your CSS file link here -->
    <link rel="stylesheet" href="css/style.css">

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



<br><br>
<?php
// Get the movie ID from the URL
$movie_id = isset($_GET['id']) ? $_GET['id'] : null;

// Load movies.xml
$xml = simplexml_load_file('movies.xml') or die('Error: Cannot load movie data');

// Search for the movie with the matching ID
$selected_movie = null;
foreach ($xml->movie as $movie) {
    if ((string)$movie['id'] === $movie_id) {
        $selected_movie = $movie;
        break;
    }
}

// If no matching movie is found, show an error
if (!$selected_movie) {
    echo '<p>Error: Movie not found</p>';
    exit;
}
?>
<!-- Movie Display Section -->
<section class="rentmovie">
    <div class="form-box-rentmovie">
        <div class="row" id="movie-details-container">
            <div class="col-md-6">
                <!-- Image Section -->
                <img src="<?php echo $selected_movie->image; ?>" alt="<?php echo $selected_movie->title; ?>" class="img-fluid rounded">
            </div>
            <div class="col-md-6">
                <!-- Movie Details Section -->
                <div class="movie-details">
                    <h3 class="card-title"><?php echo $selected_movie->title; ?></h3>
                    <p><strong>Genre:</strong> <?php echo $selected_movie->genre; ?></p>
                    <p><strong>Release Year:</strong> <?php echo $selected_movie->release_year; ?></p>
                    <p><strong>Rating:</strong> <?php echo $selected_movie->rating; ?></p>
                    <p><strong>Available:</strong> <?php echo $selected_movie->available; ?></p>
                    <p><strong>Copies Available:</strong> <?php echo $selected_movie->copies; ?></p>

                    <!-- Rent button if available -->
                    <?php if ($selected_movie->available == 'Yes') { ?>
                        <a href="rent-confirm.php?movie_id=<?php echo $selected_movie['id']; ?>&user_id=<?php echo $userId; ?>" class="btn btn-primary">Rent Now</a>
                    <?php } else { ?>
                        <p class="text-danger">Not Available</p>
                    <?php } ?>
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
