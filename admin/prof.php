<?php
// Start session to keep track of user login
session_start();

// Check if the user ID is passed in the URL
if (!isset($_GET['id'])) {
    die("Professional ID is required.");
}

$professionalId = $_GET['id'];

// Database connection
$conn = new mysqli('localhost', 'root', '', 'listing-platform'); // Update with your DB credentials

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the professional's profile data
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $professionalId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $professional = $result->fetch_assoc();
} else {
    die("Professional not found.");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($professional['name']); ?>'s Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-details {
            margin-top: 20px;
        }
        .profile-image {
            width: 200px;
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Profile of <?php echo htmlspecialchars($professional['name']); ?></h1>
        
        <div class="profile-details">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($professional['name']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($professional['location']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($professional['phone']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($professional['email']); ?></p>
            <p><strong>Role:</strong> <?php echo htmlspecialchars($professional['role']); ?></p>
            <p><strong>Service:</strong> <?php echo htmlspecialchars($professional['service']); ?></p>
            <p><strong>Joined on:</strong> <?php echo htmlspecialchars($professional['created_at']); ?></p>
            <p><strong>Profile Picture:</strong></p>
            <!-- Ensure the correct path for profile pictures -->
            <?php if (!empty($professional['profile_picture'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($professional['profile_picture']); ?>" alt="Profile Picture" class="img-thumbnail profile-image">
            <?php else: ?>
                <p>No profile picture available.</p>
            <?php endif; ?>
        </div>
        
        <a href="admin_dash.php" class="btn btn-primary mt-3">Back to Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
