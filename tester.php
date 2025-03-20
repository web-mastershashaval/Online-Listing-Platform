<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="customer_dash.css">
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2 class="sidebar-title">Dashboard</h2>
        <ul class="nav-links">
            <li><a href="profile.php">Profile Setup</a></li>
            <li><a href="service_search.php">Service Search</a></li>
            <li><a href="professional_profiles.php">View Professionals</a></li>
            <li><a href="feedback.php">Leave Feedback</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="content">
        <!-- Service Search Section -->
        <section id="service-search">
            <h3>Search Services</h3>
            <form method="GET" action="search_results.php">
                <input type="text" name="service" placeholder="Search for services..." required>
                <button type="submit">Search</button>
            </form>
        </section>

        <!-- View Professional Profile -->
        <section id="professional-profiles">
            <h3>Professional Profiles</h3>
            <ul>
                <?php
                $result = mysqli_query($conn, "SELECT * FROM professionals");
                while ($professional = mysqli_fetch_assoc($result)) {
                    echo "<li><a href='professional_profile.php?id=" . $professional['id'] . "'>" . $professional['name'] . "</a></li>";
                }
                ?>
            </ul>
        </section>

        <!-- Feedback System -->
        <section id="feedback">
            <h3>Leave Feedback</h3>
            <form method="POST" action="submit_feedback.php">
                <select name="professional_id" required>
                    <option value="">Select Professional</option>
                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM professionals");
                    while ($professional = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $professional['id'] . "'>" . $professional['name'] . "</option>";
                    }
                    ?>
                </select>
                <textarea name="feedback" placeholder="Leave your feedback here..." required></textarea>
                <button type="submit">Submit Feedback</button>
            </form>
        </section>
    </div>

</body>

</html>



<style>
        /* Custom CSS for the table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        thead {
            background-color: #007bff;
            color: white;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            font-weight: bold;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        h1 {
            color: #333;
        }

        .payment-form {
            display: none;  /* Initially hide the form */
            margin-top: 20px;
        }

        @media (max-width: 600px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            th {
                display: none;
            }

            td {
                text-align: right;
                position: relative;
                padding-left: 50%;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                width: 50%;
                padding-left: 10px;
                font-weight: bold;
                text-align: left;
            }
        }
    </style>