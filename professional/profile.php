<?php
session_start(); // Ensure this is at the very top of the file

// Database connection
$host = 'localhost';
$dbname = 'listing-platform';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully!<br>"; // Debugging: Confirm connection
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch user profile data
$user = null;
if (isset($_SESSION['user']['id'])) {
    $userId = $_SESSION['user']['id'];
    // echo "User ID in session: $userId<br>"; // Debugging: Confirm user ID
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "No user found with ID: $userId<br>"; // Debugging: Confirm user data
    }
} else {
    echo "No user ID in session. Please log in.<br>"; // Debugging: Confirm session issue
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = htmlspecialchars($_POST['name']);
    $location = htmlspecialchars($_POST['location']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = htmlspecialchars($_POST['email']);
    $role = htmlspecialchars($_POST['role']);
    $service = htmlspecialchars($_POST['service']);

    // Handle profile picture upload
    $profilePicture = $user['profile_picture']; // Keep existing picture by default
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/'; // Directory to store uploaded files
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); // Create directory if it doesn't exist
        }
        $fileName = basename($_FILES['profile_picture']['name']);
        $filePath = $uploadDir . uniqid() . '_' . $fileName; // Unique file name
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $filePath)) {
            $profilePicture = $filePath; // Update profile picture path
        } else {
            die("Error uploading file.");
        }
    }

    try {
        $sql = "UPDATE users SET name = :name, location = :location, phone = :phone, email = :email, service = :service, role = :role, profile_picture = :profile_picture WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'id' => $userId,
            'name' => $name,
            'location' => $location,
            'phone' => $phone,
            'email' => $email,
            'role' => $role,
            'service'=> $service,
            'profile_picture' => $profilePicture
        ]);
        header("Location: prof_dash.php"); // Refresh the page
        exit();
    } catch (PDOException $e) {
        die("Error updating profile: " . $e->getMessage());
    }
}

// Handle profile deletion
if (isset($_POST['delete_profile'])) {
    try {
        // Delete profile picture file if it exists
        if ($user['profile_picture'] && file_exists($user['profile_picture'])) {
            unlink($user['profile_picture']);
        }

        // Delete user from database
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $userId]);
        session_destroy(); // Destroy session after deletion
        header("Location: ../login.php"); // Redirect to login page
        exit();
    } catch (PDOException $e) {
        die("Error deleting profile: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Profile Management</h1>

        <!-- Display User Profile -->
        <?php if ($user): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Profile Details</h5>
                    <!-- Display Profile Picture -->
                    <?php if ($user['profile_picture']): ?>
                        <div class="text-center mb-3">
                            <img src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture" class="img-fluid rounded-circle" style="width: 150px; height: 150px;">
                        </div>
                    <?php endif; ?>
                    <p><strong>Name:</strong> <?php echo $user['name']; ?></p>
                    <p><strong>Location:</strong> <?php echo $user['location']; ?></p>
                    <p><strong>Phone:</strong> <?php echo $user['phone']; ?></p>
                    <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                    <p><strong>Role:</strong> <?php echo $user['role']; ?></p>
                    <p><strong>Service:</strong> <?php echo $user['service']; ?></p>
                </div>
            </div>

            <!-- Update Profile Form -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Update Profile</h5>
                    <form method="POST" action="profile.php" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" name="location" value="<?php echo $user['location']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $user['phone']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="customer" <?php echo $user['role'] === 'customer' ? 'selected' : ''; ?>>Customer</option>
                                <option value="professional" <?php echo $user['role'] === 'professional' ? 'selected' : ''; ?>>Professional</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="service" class="form-label">Service</label>
                            <input type="text" class="form-control" id="service" name="service" value="<?php echo $user['service']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="profile_picture" class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*">
                        </div>
                        <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>

            <!-- Delete Profile Form -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Delete Profile</h5>
                    <form method="POST" action="profile.php" onsubmit="return confirm('Are you sure you want to delete your profile? This action cannot be undone.');">
                        <button type="submit" name="delete_profile" class="btn btn-danger">Delete Profile</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning" role="alert">
                No user profile found. Please <a href="login.php">log in</a>.
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
