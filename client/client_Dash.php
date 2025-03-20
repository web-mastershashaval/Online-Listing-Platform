<?php
session_start(); 

//debugging code 
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
    echo "Connected successfully!<br>"; // Debugging: Confirm connection
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch user profile data
$user = null;
if (isset($_SESSION['user']['id'])) {
    $userId = $_SESSION['user']['id'];
    echo "User ID in session: $userId<br>"; // Debugging: Confirm user ID
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Favicon link -->
    <link rel="icon" href="media/dash.png" type="image/png">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/static/styles.css">

    <style>
        /* Your existing CSS styles */
        :root {
            --primary-color: #007bff;
            --primary-dark: #0056b3;
            --background-color: #f4f4f4;
            --white: #ffffff;
            --text-color: #333;
        }

        body {
            display: flex;
            font-family: Arial, sans-serif;
            margin: 0;
            height: 100vh;
            flex-direction: column;
            background-color: var(--background-color);
            overflow-x: hidden;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--primary-color);
            padding: 10px 20px;
            color: var(--white);
            position: fixed;
            width: 100%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        header .logo {
            display: flex;
            align-items: center;
            font-size: 24px;
            font-weight: bold;
        }

        header .logo img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .profile {
            display: flex;
            align-items: center;
        }

        .profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-left: 15px;
            border: 2px solid var(--white);
        }

        nav {
            width: 235px;
            background: var(--primary-dark);
            color: var(--white);
            padding: 20px;
            position: fixed;
            height: calc(100% - 60px);
            top: 60px;
            overflow-y: auto;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
            transition: width 0.3s;
        }

        nav h2 {
            color: var(--white);
        }

        nav ul {
            list-style: none;
            padding: 0;
        }

        nav ul li {
            display: flex;
            align-items: center;
            margin: 15px 0;
        }

        nav ul li img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }

        nav ul li a {
            color: var(--white);
            text-decoration: none;
            font-weight: bold;
            padding: 10px;
            display: flex;
            align-items: center;
            width: 100%;
        }

        nav ul li a:hover {
            background: var(--primary-color);
            transform: scale(1.05);
        }

        main {
            margin-left: 260px;
            padding: 20px;
            width: calc(100% - 260px);
            margin-top: 60px;
            transition: margin-left 0.3s, width 0.3s;
        }

        .section {
            margin-bottom: 40px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background: var(--white);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {

            /* Adjust layout for smaller screens */
            nav {
                position: relative;
                width: 100%;
                height: auto;
                top: 0;
                padding: 10px;
                box-shadow: none;
                margin-bottom: 20px;
            }

            nav ul li {
                justify-content: center;
            }

            main {
                margin-left: 0;
                width: 100%;
            }

            header {
                flex-direction: column;
                align-items: flex-start;
            }

            .profile {
                margin-top: 10px;
            }

            .logo img {
                width: 40px;
                height: 40px;
            }

            nav h2 {
                text-align: center;
            }
        }

        @media (max-width: 480px) {

            /* For extra small screens like phones in portrait mode */
            header {
                padding: 5px 15px;
            }

            .profile img {
                width: 30px;
                height: 30px;
            }

            nav {
                padding: 5px;
            }

            nav ul li {
                font-size: 14px;
            }

            .section {
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="logo">
            <img src="images/dashboard.png" alt="favicon"> Dashboard
        </div>
        <div class="profile">
            <span>Username</span>
            <img src="media/profile.png" alt="Profile Picture"> <!-- Default Profile Image -->
        </div>
    </header>

    <nav>
        <h2>Menu</h2>
        <ul>
            <li><img src="../images/dashboard.png" alt="favicon"><a href="./dash.php" data-target="main-content">Dashboard</a></li>
            <li><img src="../images/prof.png" alt="favicon"><a href="./professionals.php" data-target="main-content">Professionals</a></li>
            <li><img src="../images/appointmen1.png" alt="favicon"><a href="./appointment.php" data-target="main-content">Book Appointment</a></li>
            <li><img src="../images/profile.png" alt="favicon"><a href="./profile.php" data-target="main-content">Profile Management</a></li>
            <!-- <li><img src="./images/team.png" alt="favicon"><a href="users.php" data-target="main-content">User Management</a></li> -->
            <li><img src="../images/money.jpg" alt="favicon"><a href="./payment.php" data-target="main-content">Payments</a></li>
            <!-- <li><img src="./images/messages.png" alt="favicon"><a href="messages.html" data-target="main-content">Messages</a></li> -->
            <li><img src="../images/sign.png" alt="favicon"><a href="#" onclick="confirmSignout()">Signout</a></li>
        </ul>
    </nav>

    <main id="main-content">
        <div class="section" id="content">
            <h2>Welcome, Username!</h2>
            <p>Select a Profession first before booking an appointment and making payments.</p>
        </div>
    </main>

    <!-- Bootstrap JS (Required for Modal) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      // Function to load content dynamically using AJAX
function loadContent(url, target) {
    fetch(url)
        .then(response => response.text())
        .then(data => {
            document.getElementById(target).innerHTML = data;
            // Reinitialize any scripts or event listeners in the loaded content
            initializeScripts();
        })
        .catch(error => {
            console.error('Error loading content:', error);
        });
}

// Add event listeners to nav links
document.querySelectorAll('nav a').forEach(link => {
    link.addEventListener('click', (e) => {
        if (link.getAttribute('data-target')) {
            e.preventDefault(); // Prevent default link behavior
            const url = link.getAttribute('href');
            const target = link.getAttribute('data-target');
            loadContent(url, target); // Load the content into the target element
        }
    });
});

// Signout confirmation
function confirmSignout() {
    if (confirm("Are you sure you want to sign out?")) {
        window.location.href = "../Home.php"; // Replace with actual logout URL
    }
}

// Function to initialize scripts in dynamically loaded content
function initializeScripts() {
    // Handle the search form submission
    const searchForm = document.querySelector('form[method="GET"]');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            const searchInput = this.querySelector('input[name="search"]');
            const searchTerm = searchInput.value;

            // Load the user management content with the search term
            loadContent(`users.php?search=${encodeURIComponent(searchTerm)}`, 'main-content');
        });
    }

    // Handle the add user form submission
    const addUserForm = document.getElementById('addUserForm');
    if (addUserForm) {
        addUserForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent form submission

            const formData = new FormData(this);

            fetch('users.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    // Close the modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
                    modal.hide();

                    // Reload the user management content after adding a user
                    loadContent('users.php', 'main-content');
                })
                .catch(error => {
                    console.error('Error adding user:', error);
                });
        });
    }

    // Toggle Appointment Form
    const toggleAppointmentFormBtn = document.getElementById('toggleAppointmentFormBtn');
    if (toggleAppointmentFormBtn) {
        toggleAppointmentFormBtn.onclick = function() {
            var form = document.getElementById('appointmentForm');
            form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
            this.setAttribute('aria-expanded', form.style.display === 'block');
        };
    }

    // JavaScript to populate the edit modal
    const editUserModal = document.getElementById('editUserModal');
    if (editUserModal) {
        editUserModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            document.getElementById('editUserId').value = button.getAttribute('data-id');
            document.getElementById('editName').value = button.getAttribute('data-name');
            document.getElementById('editLocation').value = button.getAttribute('data-location');
            document.getElementById('editPhone').value = button.getAttribute('data-phone');
            document.getElementById('editEmail').value = button.getAttribute('data-email');
            document.getElementById('editRole').value = button.getAttribute('data-role');
        });
    }

    // Function to confirm user deletion
    function confirmDelete(userId) {
        if (confirm("Are you sure you want to delete this user?")) {
            window.location.href = `users.php?delete_id=${userId}`;
        }
    }
}
 // Toggle Payment Form
 document.getElementById('togglePaymentFormBtn').onclick = function() {
            var form = document.getElementById('paymentForm');
            form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
        };

// Initialize scripts on page load
initializeScripts();
    </script>
</body>

</html>