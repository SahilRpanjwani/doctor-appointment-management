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

// Fetch patients and their doctor details
$sql = "SELECT u.name AS patient_name, u.email AS patient_email, u.phone AS patient_phone, 
               d.name AS doctor_name, d.specialization, a.appointment_date
        FROM appointments a
        JOIN users u ON a.user_id = u.id 
        JOIN doctors d ON a.doctor_id = d.id"; // Use INNER JOIN to ensure valid matches

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #4a90e2, #50e3c2);
            color: white;
            font-family: 'Arial', sans-serif;
        }
        .table-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            margin-top: 50px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }
        .table th, .table td {
            color: #333; /* Dark text to contrast against white background */
        }
        .table th {
            background-color: #007bff; /* Table header background */
            color: white; /* Header text color */
        }
        .btn-back {
            background-color: #f5f5f5;
            color: #333;
            border-radius: 20px;
            padding: 10px 20px;
            font-weight: bold;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-back:hover {
            background-color: #e0e0e0;
        }
        .btn-container {
            text-align: center; /* Center the button */
            margin-top: 20px; /* Space between table and button */
        }
        h2 {
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="table-container">
        <h2 class="text-center mb-4">Patients</h2>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Doctor Name</th>
                    <th>Doctor Specialization</th>
                    <th>Appointment Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlentities($row['patient_name']); ?></td>
                            <td><?php echo htmlentities($row['patient_email']); ?></td>
                            <td><?php echo htmlentities($row['patient_phone']); ?></td>
                            <td><?php echo htmlentities($row['doctor_name']); ?></td>
                            <td><?php echo htmlentities($row['specialization']); ?></td>
                            <td><?php echo htmlentities($row['appointment_date']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No patients found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Button placed below the table -->
        <div class="btn-container">
            <a href="dashboard.php" class="btn btn-back">Go Back to Dashboard</a>
        </div>
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
