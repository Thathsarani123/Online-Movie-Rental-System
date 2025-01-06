<?php
// Start the session to access user information
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedInUser'])) {
    die('Error: You must be logged in to view rental history.');
}

// Load the users.xml file
$usersXml = simplexml_load_file('users.xml') or die('Error: Cannot load user data');
// Load the movies.xml file
$moviesXml = simplexml_load_file('movies.xml') or die('Error: Cannot load movie data');

// Get the logged-in username from the session
$loggedInUser = $_SESSION['loggedInUser'];

// Initialize a variable to store the user's ID
$userId = null;

// Loop through each user in the XML to find the logged-in user by username
foreach ($usersXml->user as $user) {
    if ((string) $user->username === $loggedInUser) {
        $userId = (string) $user['id']; // Store the user ID from the XML
        break; // Exit loop once user is found
    }
}

// If the user ID wasn't found, throw an error
if ($userId === null) {
    die('Error: User not found in the system.');
}

// Now we can use $userId to get the rental history
$userRentals = [];

// Loop through each user in the XML and get the user's rentals
foreach ($usersXml->user as $user) {
    if ((string) $user['id'] === $userId) {
        // Check if rentals exist for the user
        if (isset($user->rentals)) {
            foreach ($user->rentals->rental as $rental) {
                $movieId = (string) $rental->movie_id;
                $rentedOn = (string) $rental->rented_on;
                $returned = (string) $rental->returned;

                // Find the movie title by movie ID
                $movieTitle = '';
                foreach ($moviesXml->movie as $movie) {
                    if ((string) $movie['id'] === $movieId) {
                        $movieTitle = (string) $movie->title;
                        break;
                    }
                }

                // Store rental details, including rental_id
                $userRentals[] = [
                    'rental_id' => (string) $rental['rental_id'], // Rental ID
                    'movie_id' => $movieId,
                    'title' => $movieTitle,
                    'rented_on' => $rentedOn,
                    'returned' => $returned,
                ];
            }
        }
        break; // Exit loop after processing the rentals for this user
    }
}
?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Account Page</title>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel='stylesheet' href='css/style.css'>
</head>
<body>
    <section class="account-page">
        <nav class="top-menu">
            <ul class="menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="update-profile.php">Update Profile</a></li>
                <li><a href="change-password.php">Change Password</a></li>
                <li><a href="rental-history.php">Rental History</a></li>
                <li><a href="return-history.php">Return History</a></li>
            </ul>
        </nav>
     
        <section class="rent">
            <div class="form-box-rent">
                <div class="form-value-rent">
                 
                    <h2 style="color: white;">Rental Movies History</h2>
                    <table class ="rent" border="1" cellspacing="0" cellpadding="10" style="width: 100%; text-align: left;">
                        <thead>
                            <tr>
                                <th>Movie ID</th>
                                <th>Movie Title</th>
                                <th>Rented On</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
    <?php if (empty($userRentals)): ?>
        <tr>
            <td colspan="5">No rentals found.</td>
        </tr>
    <?php else: ?>
        <?php foreach ($userRentals as $rental): ?>
            <tr>
                <td><?php echo htmlspecialchars($rental['movie_id'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($rental['title'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($rental['rented_on'] ?? ''); ?></td>
                <td>
    <?php
    if ($rental['returned'] === 'pending') {
        echo 'Pending for Administrator approval';
    } elseif ($rental['returned'] === 'false') {
        echo 'Not Returned';
    } else {
        echo 'Returned';
    }
    ?>
</td>
<td>
    <?php
    if ($rental['returned'] === 'pending' || $rental['returned'] === 'true') {
        // Don't display the button if status is 'pending' or 'returned'
        echo 'No action available';
    } else {
    ?>
        <a href="return-movie.php?rental_id=<?php echo htmlspecialchars($rental['rental_id'] ?? ''); ?>&user_id=<?php echo htmlspecialchars($userId); ?>" class="return-btn">Return</a>
    <?php
    }
    ?>
</td>

            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        </section>
</body>
</html>
