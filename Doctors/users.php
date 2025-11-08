<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "doctor_appointment";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- Bootstrap CSS -->
    <style>
        body {
            background-color: #e9ecef; /* Lighter background color */
            font-family: 'Poppins', sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        h1 {
            margin-bottom: 30px;
            color: #343a40;
            font-weight: 600;
        }
        .card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-size: 24px;
            text-align: center;
            padding: 20px;
            border-bottom: 2px solid #0056b3; /* Darker border for emphasis */
        }
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }
        .btn {
            margin: 0 5px;
            border-radius: 20px; /* Rounded button edges */
            transition: all 0.3s ease;
        }
        .btn-primary {
            background-color: #28a745; /* Change primary button color */
            border: none;
        }
        .btn-primary:hover {
            background-color: #218838; /* Darker green on hover */
        }
        .btn-secondary {
            background-color: #6c757d; /* Add color for secondary buttons */
            border: none;
        }
        .btn-secondary:hover {
            background-color: #5a6268; /* Darker grey on hover */
        }
        .btn-success {
            background-color: #007bff; /* Keep the original blue for success buttons */
            border: none;
        }
        .btn-success:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }
        .action-buttons {
            text-align: center;
        }
        .header-buttons {
            text-align: center;
            margin-bottom: 20px;
        }
        .header-buttons .btn {
            margin: 5px;
            padding: 10px 20px; /* Increased button padding */
            font-weight: 600; /* Bold text */
        }
        .alert {
            margin-bottom: 20px;
        }
        /* Responsive design for smaller screens */
        @media (max-width: 576px) {
            .header-buttons .btn {
                width: 100%;
                margin-bottom: 10px;
            }
        }
        /* Additional spacing and style improvements for the table */
        .table th {
            background-color: #007bff; /* Table header background */
            color: white; /* Header text color */
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f2f2f2; /* Light grey for odd rows */
        }
        .table-striped tbody tr:nth-of-type(even) {
            background-color: #ffffff; /* White for even rows */
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center">User List</h1>

    <?php
    // Display success or error messages
    if (isset($_GET['message'])) {
        echo "<div class='alert alert-success text-center'>" . htmlentities($_GET['message']) . "</div>";
    } elseif (isset($_GET['error'])) {
        echo "<div class='alert alert-danger text-center'>" . htmlentities($_GET['error']) . "</div>";
    }
    ?>

    <div class="header-buttons">
        <a href="register.php" class="btn btn-primary">Add New User</a>
        <a href="add-admin.php" class="btn btn-secondary">Add New Admin</a>
        <a href="doctor_registration.php" class="btn btn-success">Add Doctor</a> <!-- Add Doctor Button -->
        <a href="dashboard.php" class="btn btn-info">Go Back to Dashboard</a> <!-- Go Back to Dashboard Button -->
    </div>

    <div class="card">
        <div class="card-header">
            All Users
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch users
                    $sql = "SELECT * FROM users";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlentities($row['id']) . "</td>
                                    <td>" . htmlentities($row['name']) . "</td>
                                    <td>" . htmlentities($row['email']) . "</td>
                                    <td>" . htmlentities($row['phone']) . "</td>
                                    <td>" . htmlentities($row['role']) . "</td>
                                    <td>" . htmlentities($row['created_at']) . "</td>
                                    <td class='action-buttons'>
                                        <a href='edit-user.php?id=" . htmlentities($row['id']) . "' class='btn btn-warning'>Edit</a>
                                        <a href='delete-user.php?id=" . htmlentities($row['id']) . "' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this user?\");'>Delete</a>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No users found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
