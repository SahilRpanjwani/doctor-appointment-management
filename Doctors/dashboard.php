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

if (!isset($_SESSION['loggedin']) || !isset($_SESSION['id'])) {
    echo "User not logged in.";
    exit();
}

$userId = $_SESSION['id']; // Retrieve the user ID

// Count total users
$sqlUsers = "SELECT COUNT(*) as total FROM users";
$resultUsers = $conn->query($sqlUsers);
$totUsers = $resultUsers->fetch_assoc()['total'];

// Count total patients (users who have booked at least one appointment)
$sqlPatients = "SELECT COUNT(DISTINCT user_id) as total FROM appointments";
$resultPatients = $conn->query($sqlPatients);
$totPatients = $resultPatients->fetch_assoc()['total'];

// Count total doctors
$sqlDoctors = "SELECT COUNT(*) as total FROM users WHERE role = 'doctor'"; // Assuming 'role' column distinguishes doctors
$resultDoctors = $conn->query($sqlDoctors);
$totDoctors = $resultDoctors->fetch_assoc()['total'];

// Count total appointments for all users
$sqlAppointments = "SELECT COUNT(*) as total FROM appointments";
$resultAppointments = $conn->query($sqlAppointments);
$totAppointments = $resultAppointments->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #4a90e2, #50e3c2);
            color: #333;
            font-family: 'Arial', sans-serif;
        }
        h1 {
            margin-bottom: 40px;
            font-weight: 700;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.5);
            color: #ffffff;
        }
        .card {
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            transition: transform 0.2s, box-shadow 0.2s;
            height: 250px;
            position: relative;
            overflow: hidden;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.5);
        }
        .card-title {
            font-size: 1.5rem;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .card-text {
            font-size: 3rem;
            font-weight: bold;
        }
        .btn {
            background-color: #f5f5f5;
            color: #333;
            border: none;
            transition: background-color 0.3s, transform 0.3s;
            margin-top: auto;
        }
        .btn:hover {
            background-color: #e0e0e0;
            transform: scale(1.05);
        }
        .container {
            margin-top: 50px;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        @media (max-width: 768px) {
            .card-text {
                font-size: 2rem;
            }
            h1 {
                font-size: 1.8rem;
            }
        }
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-align: center;
            padding: 20px;
        }
        .card-users {
            background: linear-gradient(135deg, #1abc9c, #16a085);
            color: white;
        }
        .card-patients {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }
        .card-doctors {
            background: linear-gradient(135deg, #e67e22, #d35400);
            color: white;
        }
        .card-appointments {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }
        .back-to-home {
            margin-top: 30px;
            text-align: center;
        }
        .btn-home {
            padding: 10px 20px;
            font-size: 1.2rem;
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            transition: background-color 0.3s, transform 0.3s;
            background-color: #ffffff;
            color: #333;
        }
        .btn-home:hover {
            background-color: #f0f0f0;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center">Dashboard</h1>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card card-users">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <h2 class="card-text"><?php echo htmlentities($totUsers); ?></h2>
                    <a href="users.php" class="btn">View Detail</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card card-patients">
                <div class="card-body">
                    <h5 class="card-title">Total Patients</h5>
                    <h2 class="card-text"><?php echo htmlentities($totPatients); ?></h2>
                    <a href="patients.php" class="btn">View Detail</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card card-doctors">
                <div class="card-body">
                    <h5 class="card-title">Total Doctors</h5>
                    <h2 class="card-text"><?php echo htmlentities($totDoctors); ?></h2>
                    <a href="doctors.php" class="btn">View Detail</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card card-appointments">
                <div class="card-body">
                    <h5 class="card-title">Total Appointments</h5>
                    <h2 class="card-text"><?php echo htmlentities($totAppointments); ?></h2>
                    <a href="appointments.php" class="btn">View Detail</a>
                </div>
            </div>
        </div>
    </div>

    <div class="back-to-home">
        <a href="index.php" class="btn btn-home">Go Back to Home</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
