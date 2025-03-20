<?php
session_start();
$user = $_SESSION['user']; // Assuming the session data is stored like this

// Delete Payment Logic
if (isset($_POST['delete_payment_id'])) {
    $paymentId = $_POST['delete_payment_id'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'listing-platform'); // Update with your DB credentials

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Delete the payment from the database
    $stmt = $conn->prepare("DELETE FROM payments WHERE id = ?");
    if ($stmt === false) {
        die("Error preparing SQL statement for DELETE: " . $conn->error); // Display detailed MySQL error
    }

    $stmt->bind_param('i', $paymentId);

    if ($stmt->execute()) {
        // Success: Redirect or show success message
        echo "<script>alert('Payment deleted successfully'); window.location.href='payments.php';</script>";
    } else {
        // Error: Show error message
        echo "<script>alert('Error deleting payment. Please try again later.');</script>";
    }

    $stmt->close();
    $conn->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['amount'], $_POST['status'])) {
    // Handle form submission and insert into database

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'listing-platform'); // Update with your DB credentials

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $userId = $user['id'];
    $amount = $_POST['amount'];
    $status = $_POST['status'];
    $paymentDate = date('Y-m-d H:i:s');
    $createdAt = date('Y-m-d H:i:s');
    
    // Fetch client name from users table
    $sql = "SELECT name FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        // If preparation failed, display the error
        die("Error preparing SQL statement for SELECT: " . $conn->error); // Display detailed MySQL error
    }

    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $userRow = $result->fetch_assoc();
        $clientName = $userRow['name'];
    } else {
        die("User not found.");
    }

    // Insert data into payments table
    $stmt = $conn->prepare("INSERT INTO `payments`(`user_id`, `client_name`, `amount`, `status`, `payment_date`, `created_at`) VALUES (?, ?, ?, ?, ?, ?)");

    if ($stmt === false) {
        // If preparation failed, display the error
        die("Error preparing SQL statement for INSERT: " . $conn->error); // Display detailed MySQL error
    }

    $stmt->bind_param('isssss', $userId, $clientName, $amount, $status, $paymentDate, $createdAt);

    if ($stmt->execute()) {
        // Success: Redirect or show success message
        echo "<script>alert('Payment submitted successfully'); window.location.href='payments.php';</script>";
    } else {
        // Error: Show error message
        echo "<script>alert('Error submitting payment. Please try again later.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Payments</h1>

        <!-- Toggle Payment Form Button -->
        <button id="togglePaymentFormBtn" class="btn btn-primary">Make Payment</button>

        <!-- Payment Form -->
        <div class="payment-form mt-3" id="paymentForm" style="display:none;">
            <h2>Make Payment</h2>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="clientName" class="form-label">Client Name</label>
                    <input type="text" class="form-control" id="clientName" name="clientName" value="<?php echo htmlspecialchars($user['name']); ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" class="form-control" id="amount" name="amount" required>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Submit Payment</button>
            </form>
        </div>

        <!-- Search Form -->
        <form method="GET" action="#" class="mt-4">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Search payments...">
                <button type="submit" class="btn btn-outline-secondary">Search</button>
            </div>
        </form>

        <!-- Payments Table -->
        <h2 class="mt-5">Your Payments</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Client Name</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="paymentsTableBody">
                <!-- Rows will be dynamically populated here by PHP -->
                <?php
                // Fetch payments for the logged-in user
                $conn = new mysqli('localhost', 'root', '', 'listing-platform'); // Update with your DB credentials

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $userId = $user['id'];
                $sql = "SELECT * FROM payments WHERE user_id = $userId ORDER BY payment_date DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>" . htmlspecialchars($row['client_name']) . "</td>
                            <td>$" . number_format($row['amount'], 2) . "</td>
                            <td>" . htmlspecialchars($row['payment_date']) . "</td>
                            <td>" . htmlspecialchars($row['status']) . "</td>
                            <td>
                                <form action='' method='POST' style='display:inline;'>
                                    <input type='hidden' name='delete_payment_id' value='" . $row['id'] . "'>
                                    <button type='submit' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this payment?\");'>Delete</button>
                                </form>
                                <button class='btn btn-secondary btn-sm' onclick='window.print();'>Print Receipt</button>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No payments found</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Payment Form
        document.getElementById('togglePaymentFormBtn').onclick = function() {
            var form = document.getElementById('paymentForm');
            form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
        };
    </script>
</body>
</html>
