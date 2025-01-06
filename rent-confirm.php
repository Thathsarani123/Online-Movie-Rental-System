<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedInUser'])) {
    die('Error: You must be logged in to rent a movie.');
}

// Get the movie_id and user_id from the URL
$movie_id = isset($_GET['movie_id']) ? $_GET['movie_id'] : null;
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

if (!$movie_id || !$user_id) {
    die('Error: Missing movie_id or user_id.');
}

// Load the users.xml file
$xml = simplexml_load_file('users.xml') or die('Error: Cannot load user data');

// Find the user by user_id
$userFound = false;
foreach ($xml->user as $user) {
    if ((string) $user['id'] === $user_id) {
        $userFound = true;

        // Check if rentals node exists, if not, create it
        if (!isset($user->rentals)) {
            $user->addChild('rentals');
        }

        // Check if there are any existing rentals
        if (count($user->rentals->rental) > 0) {
            // Get the last rental's ID
            $lastRental = $user->rentals->rental[count($user->rentals->rental) - 1];
            $lastRentalId = (int) $lastRental['rental_id'];
        } else {
            // If no rentals, start the rental_id from 1
            $lastRentalId = 0;
        }

        // Generate a new rental_id
        $newRentalId = $lastRentalId + 1;

        // Prepare rental information
        $rental = $user->rentals->addChild('rental');
        $rental->addAttribute('rental_id', $newRentalId);
        $rental->addChild('movie_id', $movie_id);
        $rental->addChild('rented_on', date('Y-m-d'));
        $rental->addChild('returned', 'false');

        // Save the updated XML file
        $xml->asXML('users.xml');
        
        echo "<script>
                alert('Movie rented successfully!');
                window.location.href = 'movies.php';
              </script>";
        exit;
    }
}

// If user is not found
if (!$userFound) {
    die('Error: User not found.');
}
?>
