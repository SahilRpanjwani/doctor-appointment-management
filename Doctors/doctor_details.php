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

// Check if the doctor ID is passed
if (!isset($_GET['id'])) {
    echo "Doctor ID not provided.";
    exit();
}

$doctor_id = $_GET['id'];

// Fetch doctor details
$sqlDoctor = "SELECT d.name, d.specialization, d.image, d.phone, d.email, d.address, d.experience_years 
              FROM doctors d
              WHERE d.id = ?";
$stmtDoctor = $conn->prepare($sqlDoctor);
$stmtDoctor->bind_param("i", $doctor_id);
$stmtDoctor->execute();
$resultDoctor = $stmtDoctor->get_result();
$doctor = $resultDoctor->fetch_assoc();

if (!$doctor) {
    echo "Doctor not found.";
    exit();
}

// Fetch doctor appointments
$sqlAppointments = "SELECT a.id, a.appointment_date, u.name as patient_name 
                    FROM appointments a
                    JOIN users u ON a.user_id = u.id
                    WHERE a.doctor_id = ?";
$stmtAppointments = $conn->prepare($sqlAppointments);
$stmtAppointments->bind_param("i", $doctor_id);
$stmtAppointments->execute();
$resultAppointments = $stmtAppointments->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #74ebd5, #ACB6E5); /* Softer gradient for the background */
            color: #f8f9fa; /* Light text color */
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .doctor-details {
            background-color: rgba(0, 0, 0, 0.6); /* Darker transparent background */
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            color: white;
        }
        .doctor-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white; /* White border around the image */
        }
        h1 {
            margin-bottom: 20px;
            color: #ffffff; /* White for the title */
        }
        h2 {
            margin-top: 30px;
            color: #ffffff; /* White for subheadings */
        }
        .appointments-table {
            margin-top: 30px;
        }
        .appointments-table th, .appointments-table td {
            color: #000; /* Dark text for better visibility */
            background-color: rgba(255, 255, 255, 0.85); /* Lighter background for cells */
        }
        .appointments-table th {
            font-weight: bold;
            background-color: rgba(0, 123, 255, 0.8); /* Blue background for headers */
            color: white; /* White text for headers */
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
    <div class="doctor-details">
        <img src="uploads/<?php echo htmlentities($doctor['image']); ?>" alt="Doctor Image" class="doctor-image">
        <h1><?php echo htmlentities($doctor['name']); ?></h1>
        <p>Specialization: <?php echo htmlentities($doctor['specialization']); ?></p>
        <p>Phone: <?php echo htmlentities($doctor['phone']); ?></p>
        <p>Email: <?php echo htmlentities($doctor['email']); ?></p>
        <p>Address: <?php echo htmlentities($doctor['address']); ?></p>
        <p>Experience: <?php echo htmlentities($doctor['experience_years']); ?> years</p>
    </div>

    <h2 class="text-center">Appointments</h2>
    <table class="table appointments-table text-center">
        <thead>
            <tr>
                <th>Appointment ID</th>
                <th>Patient Name</th>
                <th>Appointment Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultAppointments->num_rows > 0): ?>
                <?php while ($appointment = $resultAppointments->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlentities($appointment['id']); ?></td>
                        <td><?php echo htmlentities($appointment['patient_name']); ?></td>
                        <td><?php echo htmlentities($appointment['appointment_date']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">No appointments found for this doctor.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="text-center">
        <a href="dashboard.php" class="btn btn-back">Go Back to Dashboard</a>
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
