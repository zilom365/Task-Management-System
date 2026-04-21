<?php
include 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' OR email='$email'");

        if (mysqli_num_rows($check) > 0) {
            $error = "Username or email already exists!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            mysqli_query($conn, "INSERT INTO users (username, email, password)
            VALUES ('$username', '$email', '$hashed_password')");

            header("Location: login.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - TaskFlow</title>
    <link rel="stylesheet" href="assets/css/style.css?v=10">
    <script src="assets/js/script.js"></script>
</head>
<body class="auth-page">

    <header class="topbar">
        <div class="brand">TaskFlow</div>
        <nav class="top-links">
            <a href="login.php">Sign in</a>
            <a href="register.php" class="top-btn">Try it free</a>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-left">
            <h1>Manage your work with <span>clarity.</span></h1>
            <p>Create your account and start organizing tasks beautifully.</p>
            <div class="hero-buttons">
                <a href="register.php" class="primary-btn">Create account</a>
                <a href="login.php" class="secondary-btn">Already have account</a>
            </div>
        </div>

        <div class="auth-panel">
            <h2>Register</h2>

            <?php if ($error != "") { ?>
                <div class="error"><?php echo $error; ?></div>
            <?php } ?>

            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>

                <div class="password-box">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <span onclick="togglePassword()">👁</span>
                </div>

                <input type="password" name="confirm_password" placeholder="Confirm Password" required>

                <button type="submit" class="primary-btn full-btn">Register</button>
            </form>

            <p class="switch-text">Already have an account? <a href="login.php">Login</a></p>
        </div>
    </section>

</body>
</html>