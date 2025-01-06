<?php
session_start();

if (!isset($_SESSION['loggedInUser'])) {
    die('Error: You must be logged in to view return history.');
}

// Load the users.xml and movies.xml files
$usersXml = simplexml_load_file('users.xml') or die('Error: Cannot load user data');
$moviesXml = simplexml_load_file('movies.xml') or die('Error: Cannot load movie data');
$userReturns = [];

// Find the user by username from the session
foreach ($usersXml->user as $user) {
    if ((string) $user->username === $_SESSION['loggedInUser']) {
        // Check if rentals exist for the user
        if (isset($user->rentals)) {
            foreach ($user->rentals->rental as $rental) {
                if ((string) $rental->returned === 'true') {
                    // Get movie ID
                    $movieId = (string) $rental->movie_id;

                    // Find the movie name from movies.xml using movie_id
                    $movieName = '';
                    foreach ($moviesXml->movie as $movie) {
                        if ((string) $movie['id'] === $movieId) { // Change to movie['id'] to match attribute
                            $movieName = (string) $movie->title; // Use <title> instead of <name>
                            break; // Exit the loop once the movie is found
                        }
                    }

                    // Add rental details to the returns array
                    $userReturns[] = [
                        'movie_id' => $movieId,
                        'movie_name' => $movieName, // Set the movie name
                        'rented_on' => (string) $rental->rented_on,
                        'return_date' => (string) $rental->return_date,
                    ];
                }
            }
        }
        break; // Exit loop once user is found
    }
}
?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Return History</title>
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
                    <h2 style="color: white;">Returned Movies History</h2>
                    <table class="rent"  border="1" cellspacing="0" cellpadding="10" style="width: 100%; text-align: left;">
                        <thead>
                            <tr>
                                <th>Movie ID</th>
                                <th>Movie Name</th>
                                <th>Rented On</th>
                                <th>Return Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($userReturns)): ?>
                                <tr>
                                    <td colspan="4">No returned movies found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($userReturns as $return): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($return['movie_id']); ?></td>
                                        <td><?php echo htmlspecialchars($return['movie_name']); ?></td>
                                        <td><?php echo htmlspecialchars($return['rented_on']); ?></td>
                                        <td><?php echo htmlspecialchars($return['return_date']); ?></td>
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
