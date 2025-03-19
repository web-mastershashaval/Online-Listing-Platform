<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="dashboard.css">
    <style>
        /* Add your custom CSS here */
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .grid-item {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .grid-item img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }
        .username {
            color: #007BFF;
        }
        .calendar table {
            width: 100%;
            border-collapse: collapse;
        }
        .calendar th, .calendar td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .calendar th {
            background-color: #f2f2f2;
        }
        .image-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 150px; /* Adjust height as needed */
        }
    </style>
</head>
<body>
    <!-- <h1>Welcome <span class="username">JohnDoe!</span></h1>
    <hr><br> -->

    <!-- Grid Container -->
    <div class="grid-container">
        <!-- Appointments Section -->
        <div class="grid-item">
            <div class="image-container">
                <img src="./images/appointmen1.png" style="width: 150px; height: 150px; border-radius: 50px;">
            </div>
            <i class="fas fa-calendar-alt"></i>
            <small>Your Appointments:</small><br>
            <strong>John Doe</strong> - <span>October 25, 2023, 10:00 AM</span><br>
            <strong>Jane Smith</strong> - <span>October 26, 2023, 2:00 PM</span><br>
            <!-- If no appointments -->
            <!-- <strong>No appointments booked yet.</strong> -->
        </div>

        <!-- Lawyer Section -->
        <div class="grid-item">
            <div class="image-container">
                <img src="./images/prof.png" style="width: 150px; height: 150px; border-radius: 50px;">
            </div>
            <small>Your Professional:</small><br>
            <strong>Capeter John</strong>
            <!-- If no lawyer selected -->
            <!-- <strong>No lawyer selected.</strong> -->
        </div>

        <!-- Cases Section -->
        <!-- <div class="grid-item">
            <div class="image-container">
                <img src="./images/case.png" style="width: 150px; height: 150px; border-radius: 50px;">
            </div> -->
            <!-- <small>Your Cases:</small><br>
            <strong>Case Title 1</strong><br>
            <strong>Case Title 2</strong><br> -->
            <!-- If no cases -->
            <!-- <strong>No cases available.</strong> -->
        <!-- </div> -->

        <!-- Payments Section -->
        <div class="grid-item">
            <div class="image-container">
                <img id="money" src="./images/money.jpg" style="width: 100px; height: auto;">
            </div>
            <small>Your Payments:</small><br>
            <strong>ksh 5000</strong><br>
            <strong>Paid</strong><br>
            <!-- If no payments -->
            <!-- <strong>No payments made yet.</strong> -->
        </div>

        <!-- Profile Section -->
        <div class="grid-item">
            <div class="image-container">
                <img src="./images/profile.png" alt="Profile Picture" style="width: 150px; height: 150px; border-radius: 50px;">
            </div>
            <h2>Your Profile</h2>
            <strong>Name:</strong> John Doe<br>
            <strong>Email:</strong> john.doe@example.com<br>
            <strong>About:</strong> No information available.<br>
        </div>

        <!-- Notifications Section -->
        <div class="grid-item">
            <div class="notifications">
                <h2>Notifications</h2>
                <p>You have upcoming appointments!</p>
                <p>You have 3 new messages!</p>
                <!-- If no notifications -->
                <!-- <p>No upcoming appointments.</p> -->
                <!-- <p>No new messages.</p> -->
            </div>
        </div>

        <!-- Basic Information Section -->
        <div class="grid-item">
            <div class="image-container">
                <img src="./images/info.png" style="width: 150px; height: 150px; border-radius: 50px;">
            </div>
            <h2>Basic Information</h2>
            <p>To make an appointment, payment or sending a message choose a Professional first.</p>
        </div>

        <!-- Calendar Section -->
        <div class="grid-item">
            <div class="calendar">
                <h2>Appointment Calendar</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Sun</th>
                            <th>Mon</th>
                            <th>Tue</th>
                            <th>Wed</th>
                            <th>Thu</th>
                            <th>Fri</th>
                            <th>Sat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>5</td>
                            <td>6</td>
                            <td>7</td>
                            <td>8</td>
                            <td>9</td>
                            <td>10</td>
                        </tr>
                        <tr>
                            <td>11</td>
                            <td>12</td>
                            <td>13</td>
                            <td>14</td>
                            <td>15</td>
                            <td>16</td>
                            <td>17</td>
                        </tr>
                        <tr>
                            <td>18</td>
                            <td>19</td>
                            <td>20</td>
                            <td>21</td>
                            <td>22</td>
                            <td>23</td>
                            <td>24</td>
                        </tr>
                        <tr>
                            <td>25</td>
                            <td>26</td>
                            <td>27</td>
                            <td>28</td>
                            <td>29</td>
                            <td>30</td>
                            <td>31</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>