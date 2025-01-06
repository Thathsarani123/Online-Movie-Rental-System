<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Registration</title>
    <link rel='stylesheet' href='css/style.css'>
    <script src="auth.js" defer></script>
</head>
<body>
    <section class="login">
        <div class="form-box-register">
            <div class="form-value">
                <form id="registerForm" method="POST" action="register.php">
                    <h2>Create Your Account</h2>
                    <h5>Sign up to start renting movies!</h5>
                    <div class="inputbox">
                        <input type="text" id="username" name="username" required>
                        <label>Username</label>
                    </div>
                    <div class="inputbox">
                        <input type="email" id="email" name="email" required>
                        <label>Email</label>
                    </div>
                    <div class="inputbox">
                        <input type="text" id="nic" name="nic" required>
                        <label>NIC Number</label>
                    </div>
                    <div class="inputbox">
                        <input type="password" id="password" name="password" required>
                        <label>Password</label>
                    </div>
                    <button type="submit">Register</button>
                    <div class="register">
                        <p>Already have an account? <a href="login.php">Login here.</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $nic = $_POST['nic'];
        $password = $_POST['password'];

        // Load the users.xml file
        $xml = simplexml_load_file("users.xml");

        // Check if email is already registered
        foreach ($xml->user as $user) {
            if ($user->email == $email) {
                echo "<script>alert('Email already exists!'); window.location.href='register.php';</script>";
                exit;
            }
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Add new user to XML
        $newUser = $xml->addChild('user');
        $newUser->addAttribute('id', count($xml->user) + 1);
        $newUser->addChild('username', $username);
        $newUser->addChild('email', $email);
        $newUser->addChild('nic', $nic);
        $newUser->addChild('password', $hashedPassword); // Store the hashed password
        $newUser->addChild('role', 'user'); // Default role is 'user'

        // Save the updated XML file
        $xml->asXML('users.xml');
        echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
    }
    ?>
</body>
</html>
