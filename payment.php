<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
</head>
<body>
    <div class="container mt-5">
        <h1>Payments</h1>

        <!-- Toggle Payment Form Button -->
        <button id="togglePaymentFormBtn" class="btn btn-primary">Make Payment</button>

        <!-- Payment Form -->
        <div class="payment-form mt-3" id="paymentForm">
            <h2>Make Payment</h2>
            <form method="post" action="#">
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

        <!-- Lawyer Payments Table -->
        <h2 class="mt-5">Your Payments</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>$100</td>
                    <td>2023-10-01</td>
                    <td>Completed</td>
                    <td>
                        <form action="#" method="POST" style="display:inline;">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this payment?');">Delete</button>
                        </form>
                        <button class="btn btn-secondary btn-sm" onclick="window.print();">Print Receipt</button>
                    </td>
                </tr>
                <tr>
                    <td>$200</td>
                    <td>2023-10-05</td>
                    <td>Pending</td>
                    <td>
                        <form action="#" method="POST" style="display:inline;">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this payment?');">Delete</button>
                        </form>
                        <button class="btn btn-secondary btn-sm" onclick="window.print();">Print Receipt</button>
                    </td>
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>

        <!-- Client Payments Table -->
        <h2 class="mt-5">Client Payments</h2>
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
            <tbody>
                <tr>
                    <td>John Doe</td>
                    <td>$150</td>
                    <td>2023-10-03</td>
                    <td>Completed</td>
                    <td>
                        <form action="#" method="POST" style="display:inline;">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this payment?');">Delete</button>
                        </form>
                        <button class="btn btn-secondary btn-sm" onclick="window.print();">Print Receipt</button>
                    </td>
                </tr>
                <tr>
                    <td>Jane Smith</td>
                    <td>$250</td>
                    <td>2023-10-07</td>
                    <td>Pending</td>
                    <td>
                        <form action="#" method="POST" style="display:inline;">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this payment?');">Delete</button>
                        </form>
                        <button class="btn btn-secondary btn-sm" onclick="window.print();">Print Receipt</button>
                    </td>
                </tr>
                <!-- Add more rows as needed -->
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