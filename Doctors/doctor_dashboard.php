<?php
// Start session
session_start();

// Check if the doctor is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'doctor') {
    // Redirect to login page if not logged in as doctor
    header("Location: login.php");
    exit;
}

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "doctor_appointment";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get logged-in doctor's user_id from the session
$doctor_user_id = $_SESSION['id'];

// Fetch the doctor's id based on the user_id from the 'doctors' table
$doctor_sql = "SELECT id, name FROM doctors WHERE user_id = ?";
$doctor_stmt = $conn->prepare($doctor_sql);
$doctor_stmt->bind_param("i", $doctor_user_id);
$doctor_stmt->execute();
$doctor_result = $doctor_stmt->get_result();

if ($doctor_result->num_rows === 1) {
    $doctor_row = $doctor_result->fetch_assoc();
    $doctor_id = $doctor_row['id'];
    $doctor_name = $doctor_row['name'];
} else {
    echo "Doctor not found!";
    exit;
}

// Fetch appointments for the doctor based on the doctor's id
$appointment_sql = "SELECT id, patient_name, appointment_date, appointment_time, status FROM appointments WHERE doctor_id = ?";
$appointment_stmt = $conn->prepare($appointment_sql);
$appointment_stmt->bind_param("i", $doctor_id);
$appointment_stmt->execute();
$appointment_result = $appointment_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #74ebd5, #ACB6E5); /* Softer gradient for the background */
            font-family: 'Arial', sans-serif; /* Consistent font */
            color: #f8f9fa; /* Light text color */
        }
        .container {
            margin-top: 50px;
        }
        .card {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            border: none;
            border-radius: 10px;
            overflow: hidden;
            background-color: rgba(255, 255, 255, 0.85); /* Semi-transparent white */
        }
        .card-header {
            background-color: rgba(0, 123, 255, 0.8); /* Semi-transparent blue */
            color: white;
            font-size: 24px;
            text-align: center;
            font-weight: bold;
            padding: 20px;
        }
        .table {
            background-color: white; /* White background for table */
            margin-bottom: 0;
        }
        .table th {
            background-color: rgba(0, 123, 255, 0.8); /* Semi-transparent blue for headers */
            color: white; /* White text for headers */
            text-transform: uppercase;
        }
        .table tbody tr:hover {
            background-color: rgba(240, 248, 255, 0.7); /* Light blue on hover */
            cursor: pointer;
        }
        .status-approved {
            color: green;
            font-weight: bold;
        }
        .status-cancelled {
            color: red;
            font-weight: bold;
        }
        .status-pending {
            color: orange;
            font-weight: bold;
        }
        .btn-back {
            background-color: #0056b3; /* Darker blue for button */
            color: white;
            border-radius: 20px;
            padding: 10px 20px;
            font-weight: bold;
            border: none;
            transition: all 0.3s ease;
            margin-top: 20px; /* Margin for spacing */
        }
        .btn-back:hover {
            background-color: #003f7f; /* Darker on hover */
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header">
            Doctor's Upcoming Appointments
        </div>
        <div class="card-body">
            <a href="index.php" class="btn btn-back mb-3">Back to Home</a> <!-- Back button -->
            <?php if ($appointment_result->num_rows > 0): ?>
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Patient Name</th>
                        <th>Appointment Date</th>
                        <th>Appointment Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $appointment_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                        <td><?php echo htmlspecialchars(date('F j, Y', strtotime($row['appointment_date']))); ?></td>
                        <td><?php echo htmlspecialchars(date('h:i A', strtotime($row['appointment_time']))); ?></td>
                        <td>
                            <?php 
                            switch ($row['status']) {
                                case 'Approved':
                                    echo '<span class="status-approved">Approved</span>';
                                    break;
                                case 'Cancelled':
                                    echo '<span class="status-cancelled">Cancelled</span>';
                                    break;
                                default:
                                    echo '<span class="status-pending">Pending</span>';
                                    break;
                            }
                            ?>
                        </td>
                        <td>
                            <?php if ($row['status'] == 'Pending'): ?>
                                <form method="post" action="update_status.php" style="display:inline;">
                                    <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="status" value="Approved" class="btn btn-success">Approve</button>
                                    <button type="submit" name="status" value="Cancelled" class="btn btn-danger">Cancel</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p class="text-center">No appointments found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<?php
// Close statements and connection
$doctor_stmt->close();
$appointment_stmt->close();
$conn->close();
?>
