<?php
session_start();
include 'db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Wrong password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TaskFlow</title>
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
            <h1>All your tasks on <span>one platform.</span></h1>
            <p>Simple, efficient, and attractive task management for your varsity project.</p>
            <div class="hero-buttons">
                <a href="register.php" class="primary-btn">Start now - It's free</a>
                <a href="login.php" class="secondary-btn">Sign in</a>
            </div>
        </div>

        <div class="auth-panel">
            <h2>Login</h2>

            <?php if ($error != "") { ?>
                <div class="error"><?php echo $error; ?></div>
            <?php } ?>

            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>

                <div class="password-box">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <span onclick="togglePassword()">👁</span>
                </div>

                <button type="submit" class="primary-btn full-btn">Login</button>
            </form>

            <p class="switch-text">Don't have an account? <a href="register.php">Register</a></p>
        </div>
    </section>

</body>
</html>