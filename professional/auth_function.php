<?php
include_once("./conn.php");  

// Function to handle registration (for both customer and professional)
function registerUser($userData, $role) {
    global $conn;

    $username = $userData['name'];
    $location = $userData['location'];
    $phone = $userData['phone'];
    $email = $userData['email'];
    $password = $userData['password'];

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the user data into the users table
    $query = "INSERT INTO users (name, location, phone, email, password, role) 
              VALUES ('$username', '$location', '$phone', '$email', '$hashedPassword', '$role')";

    if (mysqli_query($conn, $query)) {
        return true;
    } else {
        return "Error: " . mysqli_error($conn);
    }
}

// Function to handle login for both customer and professional
function loginUser($email, $password) {
    global $conn;

    // SQL query to find the user by email
    $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        // Login successful
        return $user; // Returning user data for session management
    } else {
        return false; // Invalid email or password
    }
}
?>
