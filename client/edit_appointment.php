<?php
session_start(); // Start session to manage user authentication

// Debugging: Print session data
// echo "<pre>Session Data: ";
// print_r($_SESSION);
// echo "</pre>";

// Database connection
$host = 'localhost';
$dbname = 'listing-platform';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully!<br>"; // Debugging: Confirm connection
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch appointment details
$appointment = null;
if (isset($_GET['id'])) {
    $appointmentId = $_GET['id'];
   // echo "Appointment ID: $appointmentId<br>"; // Debugging: Confirm appointment ID

    // Fetch appointment details from the database
    $sql = "SELECT appointments.*, users.name, users.email, users.phone 
            FROM appointments 
            JOIN users ON appointments.user_id = users.id 
            WHERE appointments.id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $appointmentId]);
    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$appointment) {
        echo "No appointment found with ID: $appointmentId<br>"; // Debugging: Confirm appointment data
    }
} else {
    echo "No appointment ID provided.<br>"; // Debugging: Confirm missing ID
}

// Handle form submission to update appointment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_appointment'])) {
    $appointmentId = $_POST['appointment_id'];
    $appointmentDate = $_POST['appointment_date'];
    $stateProblem = $_POST['state_problem'];

    try {
        $sql = "UPDATE appointments SET appointment_date = :appointment_date, state_problem = :state_problem WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'id' => $appointmentId,
            'appointment_date' => $appointmentDate,
            'state_problem' => $stateProblem
        ]);
        header("Location: appointment.php"); // Redirect to the appointments page
        exit();
    } catch (PDOException $e) {
        die("Error updating appointment: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appointment</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Appointment</h1>

        <!-- Display appointment details -->
        <?php if ($appointment): ?>
            <form method="post" action="edit_appointment.php">
                <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                <div class="mb-3">
                    <label for="appointmentDate" class="form-label">Appointment Date</label>
                    <input type="datetime-local" class="form-control" id="appointmentDate" name="appointment_date" value="<?php echo date('Y-m-d\TH:i', strtotime($appointment['appointment_date'])); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="stateProblem" class="form-label">State of Problem</label>
                    <textarea class="form-control" id="stateProblem" name="state_problem" rows="3" required><?php echo $appointment['state_problem']; ?></textarea>
                </div>
                <button type="submit" name="update_appointment" class="btn btn-primary">Update Appointment</button>
            </form>
        <?php else: ?>
            <div class="alert alert-danger" role="alert">
                No appointment found.
            </div>
        <?php endif; ?>
    </div>
 <a href="customer_dash.php"> Back</a>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>