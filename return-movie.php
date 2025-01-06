    <?php
    session_start();

    // Check if user is logged in
    if (!isset($_SESSION['loggedInUser'])) {
        die('Error: You must be logged in to return a movie.');
    }

    // Get the rental_id and user_id from the POST request
    $rental_id = isset($_GET['rental_id']) ? $_GET['rental_id'] : null;
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

if (!$rental_id || !$user_id) {
    die('Error: Missing rental_id or user_id.');
}   

    // Load the users.xml file
    $xml = simplexml_load_file('users.xml') or die('Error: Cannot load user data.');

    // Find the user by user_id
    $userFound = false;
    foreach ($xml->user as $user) {
        if ((string) $user['id'] === $user_id) {
            $userFound = true;

            // Find the rental by rental_id
            foreach ($user->rentals->rental as $rental) {
                if ((string) $rental['rental_id'] === $rental_id && (string) $rental->returned === 'false') {
                    $rental->returned = 'pending'; // Mark as returned
                    break;
                }
            }

            // Save the updated XML file
            $xml->asXML('users.xml');
            
                        
            echo "<script>
            alert('Movie Returned successfully!');
            window.location.href = 'rental-history.php'; // Redirect to return-movie.php
        </script>";
        exit;
        }
    }

    // If user is not found
    if (!$userFound) {
        die('Error: User not found.');
    }
    ?>
