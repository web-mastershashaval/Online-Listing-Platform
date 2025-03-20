<?php
include_once("./conn.php");
include_once("auth_function.php");

$email = $password = "";
$loginError = "";

// Handle Login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = loginUser($email, $password);

    if ($user) {
        // Start the session and store user info
        session_start();
        $_SESSION['user'] = $user;
        $_SESSION['role'] = $user['role'];

        // Redirect based on the role
        if ($user['role'] == 'admin') {
            header("Location: admin/admin_dash.php");
        }
        elseif($user['role'] == 'professional' || $user['role'] == 'professional' ){
            header("Location: professional/prof_dash.php");
        }
        
        else {
            header("Location: client/client_Dash.php");
        }
        exit();
    } else {
        $loginError = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Online Finder Platform</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('./images/backg.jpg'); /* Add your background image URL */
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .auth-section {
            background: rgba(255, 255, 255, 0.9); /* Semi-transparent white background */
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        .auth-section h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #333;
        }
        .form-control {
            margin-bottom: 1rem;
        }
        .btn-primary {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
        }
        .error {
            color: red;
            text-align: center;
            margin-top: 1rem;
        }
        .login-link {
            text-align: center;
            margin-top: 1rem;
        }
        .login-link a {
            color: #007BFF;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <main>
        <section class="auth-section">
            <h2>Login</h2>
            <form method="POST" action="login.php">
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <?php if ($loginError): ?>
                    <div class="error"><?= $loginError ?></div>
                <?php endif; ?>
                <button type="submit" name="login" class="btn btn-primary">Login</button>
                <div class="login-link">
                        <a href="register.php">Have no account? Sign Up</a>
                    </div>
            </form>
        </section>
    </main>

    <!-- Bootstrap 5 JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>