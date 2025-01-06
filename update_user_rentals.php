<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $movieId = $_POST['movieId'];
    $rentedOn = $_POST['rentedOn'];
    $returned = $_POST['returned'];

    // Load the users.xml file
    $xml = new DOMDocument();
    $xml->load('users.xml'); // Adjust the path as necessary

    // Assuming you are storing the current logged-in user in session
    session_start();
    $currentUser = $_SESSION['username']; // Example: Use username to identify user

    // Find the user node in users.xml
    $users = $xml->getElementsByTagName('user');
    foreach ($users as $user) {
        if ($user->getElementsByTagName('username')[0]->nodeValue === $currentUser) {
            // Create the new rental entry for this user
            $rental = $xml->createElement("rental");

            $movieIdElem = $xml->createElement("movie_id", $movieId);
            $rentedOnElem = $xml->createElement("rented_on", $rentedOn);
            $returnedElem = $xml->createElement("returned", $returned);

            $rental->appendChild($movieIdElem);
            $rental->appendChild($rentedOnElem);
            $rental->appendChild($returnedElem);

            // Append the rental to the user's rental list
            $user->getElementsByTagName('rentals')[0]->appendChild($rental);
            break;
        }
    }

    // Save changes to the XML file
    $xml->save('users.xml');

    echo "Rental details added successfully!";
}
?>
