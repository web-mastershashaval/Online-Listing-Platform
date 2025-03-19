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
                margin-left: 1;
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