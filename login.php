<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <section class="login">
        <div class="form-box">
            <div class="form-value">
                <form id="loginForm" method="POST" action="login.php">
                    <h2>Login</h2>
                    <div class="inputbox">
                        <input type="text" id="loginUsername" name="loginUsername" required>
                        <label>Username</label>
                    </div>
                    <div class="inputbox">
                        <input type="password" id="loginPassword" name="loginPassword" required>
                        <label>Password</label>
                    </div>
                    <button type="submit">Log In</button>
                    <div class="register">
                        <p>Don't have an account? <a href="register.php">Sign Up</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <?php
session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['loginUsername'];
    $password = $_POST['loginPassword'];

    // Load users.xml file
    $xml = simplexml_load_file("users.xml") or die('Error: Cannot load users data.');

    // Check user credentials
    $user_found = false;
    foreach ($xml->user as $user) {
        if ((string) $user->username === $username) {
            // Verify the entered password against the hashed password
            if (password_verify($password, (string) $user->password)) {
                // Start session and store user details
                $_SESSION['loggedInUser'] = (string) $user->username; // Username
                $_SESSION['userRole'] = (string) $user->role; // User role
                $_SESSION['userId'] = (string) $user['id']; // Store user ID

            $user_found = true;

            // Redirect based on role
            if ($_SESSION['userRole'] == 'admin') {
                echo "<script>alert('Login successful! Redirecting to admin dashboard.'); window.location.href='backend/Admin/index.php';</script>";
            } else {
                echo "<script>alert('Login successful! Redirecting to movies page.'); window.location.href='movies.php';</script>";
            }
            exit; // Stop further script execution after redirect
        }
    }
    }
    if (!$user_found) {
        // If login credentials are invalid
        echo "<script>alert('Invalid login credentials!'); window.location.href='login.php';</script>";
    }
}
?>

</body>
</html>
