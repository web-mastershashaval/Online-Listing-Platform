<?php
// db.php
$host = 'localhost'; // Database host
$dbname = 'listing-platform'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle form submission to add a new user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $name = htmlspecialchars($_POST['name']);
    $location = htmlspecialchars($_POST['location']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $role = htmlspecialchars($_POST['role']);

    try {
        $sql = "INSERT INTO users (name, location, phone, email, password, role) VALUES (:name, :location, :phone, :email, :password, :role)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'name' => $name,
            'location' => $location,
            'phone' => $phone,
            'email' => $email,
            'password' => $password,
            'role' => $role
        ]);
        header("Location: users.php"); // Redirect to refresh the page
        exit();
    } catch (PDOException $e) {
        die("Error adding user: " . $e->getMessage());
    }
}

// Handle form submission to update a user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
    $id = intval($_POST['user_id']);
    $name = htmlspecialchars($_POST['name']);
    $location = htmlspecialchars($_POST['location']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = htmlspecialchars($_POST['email']);
    $role = htmlspecialchars($_POST['role']);

    try {
        $sql = "UPDATE users SET name = :name, location = :location, phone = :phone, email = :email, role = :role WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'name' => $name,
            'location' => $location,
            'phone' => $phone,
            'email' => $email,
            'role' => $role
        ]);
        header("Location: users.php"); // Redirect to refresh the page
        exit();
    } catch (PDOException $e) {
        die("Error updating user: " . $e->getMessage());
    }
}

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);

    try {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        header("Location: users.php"); // Redirect to refresh the page
        exit();
    } catch (PDOException $e) {
        die("Error deleting user: " . $e->getMessage());
    }
}

// Fetch users based on the search term
$search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
$sql = !empty($search) 
    ? "SELECT * FROM users WHERE name LIKE :search OR email LIKE :search" 
    : "SELECT * FROM users";
$stmt = $conn->prepare($sql);
if (!empty($search)) {
    $stmt->execute(['search' => "%$search%"]);
} else {
    $stmt->execute();
}
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>