<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedInUser'])) {
    header('Location: login.php');
    exit();
}

// Load user data from users.xml
function loadUsersXML() {
    $xml = simplexml_load_file('users.xml');
    return $xml;
}

// Get the logged-in user's details
$userData = loadUsersXML();
$loggedInUser = $_SESSION['loggedInUser'];
$currentUser = null;

foreach ($userData->user as $user) {
    if ((string)$user->username === $loggedInUser) {
        $currentUser = $user;
        break;
    }
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUsername = $_POST['username'] ?? '';
    $newEmail = $_POST['email'] ?? '';
    $newNIC = $_POST['nic'] ?? '';

    if ($currentUser) {
        $currentUser->username = htmlspecialchars($newUsername);
        $currentUser->email = htmlspecialchars($newEmail);
        $currentUser->nic = htmlspecialchars($newNIC);

        // Save changes back to users.xml
        $userData->asXML('users.xml');
        echo "<script>alert('Profile updated successfully!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Update Profile</title>
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

        <section class="update">
            <div class="form-box-update">
                <div class="form-value-update">
                    <h2 style="color: black;">Update Your Account</h2>
                    <form method="POST" action="">
                        <div class="inputbox-update">
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($currentUser->username); ?>" required>
                            <label><b>Username</b></label>
                        </div>
                        <div class="inputbox-update">
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($currentUser->email); ?>" required>
                            <label><b>Email</b></label>
                        </div>
                        <div class="inputbox-update">
                        <input type="text" id="nic" name="nic" value="<?php echo htmlspecialchars($currentUser->nic); ?>" required>
                            <label><b>NIC Number</b></label>
                        </div>
                        <button type="submit">Update Profile</button>
                    </form>
                    <br>
                    <button onclick="logout()"><a class="dropdown-item" href="logout.php">Logout</a></button>
                </div>
            </div>
        </section>
    </section>
    <script src="auth.js" type="module"></script>
</body>

</html>
