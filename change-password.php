<?php
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Load the users.xml file
    $usersXml = simplexml_load_file('users.xml') or die('Error: Cannot load user data');

    // Get the logged-in username from the session
    $loggedInUser = $_SESSION['loggedInUser']; // Assuming you have stored the logged-in user in the session

    // Initialize variables
    $userFound = false;

    // Loop through each user in the XML to find the logged-in user by username
    foreach ($usersXml->user as $user) {
        if ((string) $user->username === $loggedInUser) {
            // User found
            $userFound = true;

            // Verify the current password matches the one in XML
            if ((string) $user->password !== $currentPassword) {
                $_SESSION['message'] = 'Error: Current password is incorrect.';
                header('Location: change-password.php');
                exit();
            }

            // Check if new password and confirm password match
            if ($newPassword !== $confirmPassword) {
                $_SESSION['message'] = 'Error: New password and confirm password do not match.';
                header('Location: change-password.php');
                exit();
            }

            // Update the password in the XML
            $user->password = $newPassword;

            // Save the updated XML file
            $usersXml->asXML('users.xml');

           // Set a success message in the session
        $_SESSION['message'] = 'Movie rented successfully!';
        header('Location: rent-confirm.php');
        exit;
        }
    }

    // If the user wasn't found in the XML
    if (!$userFound) {
        $_SESSION['message'] = 'Error: User not found.';
        header('Location: change-password.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Change Password</title>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel='stylesheet' href='css/style.css'>
</head>

<body>
    <!-- Show the popup message -->
    <?php
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        echo "<script>alert('$message');</script>";
        unset($_SESSION['message']); // Clear the message after showing it
    }
    ?>

    <section class="account-page">
        <!-- Horizontal Menu at the top -->
        <nav class="top-menu">
            <ul class="menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="update-profile.php">Update Profile</a></li>
                <li><a href="change-password.php">Change Password</a></li>
                <li><a href="rental-history.php">Rental History</a></li>
                <li><a href="return-history.php">Return History</a></li>
            </ul>
        </nav>

        <section class="update">
            <div class="form-box-update">
                <div class="form-value-update">
                    <h2 style="color: black;">Change Your Password</h2>
                    <form action="change-password.php" method="POST">
                        <div class="inputbox-update">
                            <input type="password" name="currentPassword" id="currentPassword" required>
                            <label>Current Password</label>
                        </div>
                        <div class="inputbox-update">
                            <input type="password" name="newPassword" id="newPassword" required>
                            <label>New Password</label>
                        </div>
                        <div class="inputbox-update">
                            <input type="password" name="confirmPassword" id="confirmPassword" required>
                            <label>Confirm Password</label>
                        </div>
                        <button type="submit">Save New Password</button>
                    </form>
                </div>
            </div>
        </section>
    </section>
</body>

</html>
