<?php
session_start(); // Start session to manage user authentication

// Debugging: Print session data
//   echo "<pre>Session Data: ";
//   print_r($_SESSION);
//  echo "</pre>";

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

// Handle appointment deletion
if (isset($_GET['delete_appointment'])) {
    $appointmentId = $_GET['delete_appointment'];

    try {
        // Delete the appointment from the database
        $sql = "DELETE FROM appointments WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $appointmentId]);

        // Redirect to refresh the page after deletion
        header("Location: customer_dash.php");
        exit();
    } catch (PDOException $e) {
        die("Error deleting appointment: " . $e->getMessage());
    }
}

// Fetch logged-in user data from the users table
$user = null;
if (isset($_SESSION['user']['id'])) {
    $userId = $_SESSION['user']['id'];
    //echo "User ID in session: $userId<br>"; // Debugging: Confirm user ID
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

// Handle appointment creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_appointment'])) {
    if (isset($_SESSION['user']['id'])) {
        $userId = $_SESSION['user']['id']; // Client ID
        $appointmentDate = $_POST['appointment_date'];
        $stateProblem = $_POST['state_problem'];

        try {
            $sql = "INSERT INTO appointments (user_id, appointment_date, state_problem) VALUES (:user_id, :appointment_date, :state_problem)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'user_id' => $userId,
                'appointment_date' => $appointmentDate,
                'state_problem' => $stateProblem
            ]);
            header("Location: customer_dash.php"); // Redirect to refresh the page
            exit();
        } catch (PDOException $e) {
            die("Error creating appointment: " . $e->getMessage());
        }
    } else {
        echo "User not logged in. Please log in to book an appointment.<br>";
    }
}

// Fetch appointments for the logged-in user (client)
$clientAppointments = [];
if (isset($_SESSION['user']['id'])) {
    $userId = $_SESSION['user']['id'];
    $sql = "SELECT appointments.*, users.name, users.email, users.phone 
            FROM appointments 
            JOIN users ON appointments.user_id = users.id 
            WHERE user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['user_id' => $userId]);
    $clientAppointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch all appointments for the professional
$professionalAppointments = [];
$sql = "SELECT appointments.*, users.name, users.email, users.phone 
        FROM appointments 
        JOIN users ON appointments.user_id = users.id";
$stmt = $conn->prepare($sql);
$stmt->execute();
$professionalAppointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .notifications {
            background: #ffecb3;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .appointment-form {
            display: none;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Book an Appointment</h1>

        <!-- Notifications -->
        <!-- <div class="notifications">
             Display messages here if needed -->
            <!-- <p>No messages to display.</p> -->
        </div> 

        <!-- Toggle Appointment Form Button -->
        <button id="toggleAppointmentFormBtn" class="btn btn-primary" aria-expanded="false">Appointment Form</button>

        <!-- Appointment Form -->
        <div class="appointment-form mt-3" id="appointmentForm">
            <form method="post" action="appointment.php">
                <div class="mb-3">
                    <label for="appointmentDate" class="form-label">Appointment Date</label>
                    <input type="datetime-local" class="form-control" id="appointmentDate" name="appointment_date" required>
                </div>
                <div class="mb-3">
                    <label for="stateProblem" class="form-label">State of Problem</label>
                    <textarea class="form-control" id="stateProblem" name="state_problem" rows="3" required></textarea>
                </div>
                <button type="submit" name="create_appointment" class="btn btn-success">Book Appointment</button>
            </form>
        </div>

        <br>

        <div class="section mt-5">
            <h2>Appointments from Professional's Side</h2>

            <!-- Professional Appointments Table -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Appointment Date</th>
                        <th>State of Problem</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($professionalAppointments)): ?>
                        <?php foreach ($professionalAppointments as $appointment): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($appointment['name']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['email']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['phone']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['state_problem']); ?></td>
                                <td>
                                    <a href="edit_appointment.php?id=<?php echo $appointment['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="appointment.php?delete_appointment=<?php echo $appointment['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this appointment?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No appointments found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Appointment Form
        document.getElementById('toggleAppointmentFormBtn').onclick = function() {
            var form = document.getElementById('appointmentForm');
            form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
            this.setAttribute('aria-expanded', form.style.display === 'block');
        };
    </script>
</body>
</html>