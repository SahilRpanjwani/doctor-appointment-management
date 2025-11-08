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

// Handle appointment approval
if (isset($_GET['approve_id'])) {
    $approve_id = $_GET['approve_id'];
    $sql = "UPDATE appointments SET status = 'Approved' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $approve_id);
    $stmt->execute();
    $stmt->close();
    header("Location: appointments.php?success=Appointment approved successfully!");
    exit();
}

// Handle appointment cancellation
if (isset($_GET['cancel_id'])) {
    $cancel_id = $_GET['cancel_id'];
    $sql = "UPDATE appointments SET status = 'Cancelled' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cancel_id);
    $stmt->execute();
    $stmt->close();
    header("Location: appointments.php?success=Appointment cancelled successfully!");
    exit();
}

// Fetch appointments
$sql = "SELECT a.*, u.name AS patient_name, u.email, u.phone, d.name AS doctor_name 
        FROM appointments a 
        LEFT JOIN users u ON a.user_id = u.id 
        LEFT JOIN doctors d ON a.doctor_id = d.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #89f7fe, #66a6ff); /* Soft blue gradient */
            color: #333; /* Dark text color for better readability */
            font-family: 'Arial', sans-serif;
        }
        h1 {
            margin-bottom: 40px;
            color: white;
        }
        .container {
            margin-top: 50px;
        }
        .btn {
            margin-right: 10px;
        }
        table {
            background-color: #f8f9fa; /* Light gray background for table */
            border-radius: 10px;
            color: #333; /* Darker text color */
        }
        th, td {
            padding: 15px;
        }
        th {
            background-color: #007bff; /* Blue background for table header */
            color: white; /* White text for header */
        }
        .text-muted {
            color: #666; /* Softer gray for muted text */
        }
        .table-actions {
            display: flex;
            gap: 10px;
        }
        .btn-success, .btn-danger {
            color: white;
        }
        .alert {
            background-color: rgba(255, 255, 255, 0.8); /* Light translucent alert */
            color: #333;
            border-radius: 5px;
        }
        .back-to-dashboard {
            margin-top: 30px;
            text-align: center;
        }
        .btn-back {
            background-color: #ff5722; /* Orange button */
            color: white;
            border-radius: 20px;
            padding: 10px 20px;
            font-weight: bold;
        }
        .btn-back:hover {
            background-color: #e64a19; /* Darker orange on hover */
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="text-center">Appointments</h1>

    <div class="text-center mb-4">
        <a href="add-appointment.php" class="btn btn-primary">Add New Appointment</a>
        <a href="approved-appointments.php" class="btn btn-success">View Approved Appointments</a>
        <a href="cancelled-appointments.php" class="btn btn-danger">View Cancelled Appointments</a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success text-center">
            <?php echo $_GET['success']; ?>
        </div>
    <?php endif; ?>

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Doctor</th>
                <th>Appointment Date</th>
                <th>Appointment Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlentities($row['id']); ?></td>
                        <td><?php echo htmlentities($row['patient_name']); ?></td>
                        <td><?php echo htmlentities($row['email']); ?></td>
                        <td><?php echo htmlentities($row['phone']); ?></td>
                        <td><?php echo htmlentities($row['doctor_name']); ?></td>
                        <td><?php echo htmlentities($row['appointment_date']); ?></td>
                        <td><?php echo htmlentities($row['appointment_time']); ?></td>
                        <td><?php echo htmlentities($row['status']); ?></td>
                        <td class="table-actions">
                            <?php if ($row['status'] === 'Pending'): ?>
                                <a href="?approve_id=<?php echo $row['id']; ?>" class="btn btn-success" onclick="return confirm('Are you sure you want to approve this appointment?');">Approve</a>
                                <a href="?cancel_id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this appointment?');">Cancel</a>
                            <?php else: ?>
                                <span class="text-muted">No actions available</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center">No appointments found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="back-to-dashboard">
        <a href="dashboard.php" class="btn btn-back">Go Back to Dashboard</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
