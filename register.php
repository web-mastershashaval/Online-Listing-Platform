<?php
include_once("./auth_function.php");

// Define variables
$custName = $custLocation = $custPhone = $custEmail = $custPassword = "";
$proName = $proLocation = $proPhone = $proService = $proEmail = $proPassword = "";
$custError = $proError = "";

// Handle Customer Registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['customer-signup'])) {
    $userData = [
        'name' => $_POST['cust-name'],
        'location' => $_POST['cust-location'],
        'phone' => $_POST['cust-phone'],
        'email' => $_POST['cust-email'],
        'password' => $_POST['cust-password']
    ];

    // Register the customer
    $result = registerUser($userData, 'customer');
    if ($result === true) {
        header("Location: success.php");
        exit();
    } else {
        $custError = $result;
    }
}

// Handle Professional Registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['professional-signup'])) {
    $userData = [
        'name' => $_POST['pro-name'],
        'location' => $_POST['pro-location'],
        'phone' => $_POST['pro-phone'],
        'email' => $_POST['pro-email'],
        'password' => $_POST['pro-password']
    ];

    // Register the professional
    $result = registerUser($userData, 'professional');
    if ($result === true) {
        header("Location: success.php");
        exit();
    } else {
        $proError = $result;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Online Finder Platform</title>
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
        .tab-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .tab-button {
            flex: 1;
            padding: 10px;
            border: none;
            background: #007BFF;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 4px;
            margin: 0 5px;
        }
        .tab-button.active {
            background: #0056b3;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
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
            <h2>Create Your Account</h2>
            <!-- <div class="tab-header">
                <button class="tab-button active" data-tab="customer-signup">Customer</button>
                <button class="tab-button" data-tab="professional-signup">Professional</button>
            </div> -->
            
            <!-- Customer Sign Up Form -->
            <div id="customer-signup" class="tab-content active">
                <form id="customer-signup-form" method="POST">
                    <input type="text" name="cust-name" class="form-control" value="<?= htmlspecialchars($custName) ?>" placeholder="Username" required>
                    <input type="text" name="cust-location" class="form-control" value="<?= htmlspecialchars($custLocation) ?>" placeholder="Location" required>
                    <input type="text" name="cust-phone" class="form-control" value="<?= htmlspecialchars($custPhone) ?>" placeholder="Phone number" required>
                    <input type="email" name="cust-email" class="form-control" value="<?= htmlspecialchars($custEmail) ?>" placeholder="Email" required>
                    <input type="password" name="cust-password" class="form-control" value="<?= htmlspecialchars($custPassword) ?>" placeholder="Password" required>
                    <div class="error"><?= $custError ?></div>
                    <button type="submit" name="customer-signup" class="btn btn-primary">Register</button>
                    <div class="login-link">
                        <a href="login.php">Already have an account? Sign In</a>
                    </div>
                </form>
            </div>
            
            <!-- Professional Sign Up Form -->
            <div id="professional-signup" class="tab-content">
                <form id="professional-signup-form" method="POST">
                    <input type="text" name="pro-name" class="form-control" value="<?= htmlspecialchars($proName) ?>" placeholder="Username" required>
                    <input type="text" name="pro-location" class="form-control" value="<?= htmlspecialchars($proLocation) ?>" placeholder="Location" required>
                    <input type="text" name="pro-phone" class="form-control" value="<?= htmlspecialchars($proPhone) ?>" placeholder="Phone number" required>
                    <input type="text" name="pro-service" class="form-control" value="<?= htmlspecialchars($proService) ?>" placeholder="Service" required>
                    <input type="email" name="pro-email" class="form-control" value="<?= htmlspecialchars($proEmail) ?>" placeholder="Email" required>
                    <input type="password" name="pro-password" class="form-control" value="<?= htmlspecialchars($proPassword) ?>" placeholder="Password" required>
                    <div class="error"><?= $proError ?></div>
                    <button type="submit" name="professional-signup" class="btn btn-primary">Register</button>
                    <div class="login-link">
                        <a href="login.php">Already have an account? Sign In</a>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <!-- Bootstrap 5 JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const tabButtons = document.querySelectorAll(".tab-button");
            const tabs = document.querySelectorAll(".tab-content");
            
            tabButtons.forEach(button => {
                button.addEventListener("click", () => {
                    tabButtons.forEach(btn => btn.classList.remove("active"));
                    tabs.forEach(tab => tab.classList.remove("active"));
                    
                    button.classList.add("active");
                    document.getElementById(button.dataset.tab).classList.add("active");
                });
            });
        });
    </script>
</body>
</html>