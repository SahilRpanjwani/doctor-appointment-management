<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // User is not logged in; redirect to login page or show message
    header("Location: login.php");
    exit; // Stop further script execution
}

// Database configuration
$servername = "localhost"; // Your database server name
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "doctor_appointment"; // Your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize appointment details variable
$appointment_details = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointment_id = $_POST['appointment_id'];
    $email = $_POST['email'];

    // Prepare and bind statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT appointments.id, users.name AS patient_name, doctors.name AS doctor, appointments.appointment_date, appointments.status 
                             FROM appointments 
                             JOIN users ON appointments.user_id = users.id 
                             JOIN doctors ON appointments.doctor_id = doctors.id 
                             WHERE appointments.id = ? AND users.email = ?");
    $stmt->bind_param("is", $appointment_id, $email);
    
    // Execute the statement
    if ($stmt->execute()) {
        $result = $stmt->get_result(); // Get the result of the query

        // Check if any appointment is found
        if ($result->num_rows > 0) {
            // Fetch the appointment details
            $appointment = $result->fetch_assoc();
            $appointment_details = "
                Appointment found: <br>
                <strong>Patient Name:</strong> " . htmlspecialchars($appointment['patient_name']) . "<br>
                <strong>Doctor:</strong> " . htmlspecialchars($appointment['doctor']) . "<br>
                <strong>Date:</strong> " . htmlspecialchars($appointment['appointment_date']) . "<br>
                <strong>Status:</strong> " . htmlspecialchars($appointment['status']) . "<br>
            ";
        } else {
            $appointment_details = "No appointment found with the provided ID and email.";
        }
    } else {
        $appointment_details = "Error executing query: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Appointment - Doctor Appointment Management System</title>
    <style>
        /* General Styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            line-height: 1.6;
            scroll-behavior: smooth;
        }
        a {
            text-decoration: none;
            color: inherit;
        }

        /* Header Styling */
        header {
            background: rgba(0, 123, 255, 0.8);
            padding: 20px 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            z-index: 999;
        }
        header nav {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            margin: auto;
            align-items: center;
        }
        header h1 {
            color: white;
            font-size: 20px;
            font-weight: 700;
        }
        header nav a {
            color: white;
            font-size: 18px;
            margin-left: 20px;
            padding: 10px;
            transition: all 0.3s ease;
        }
        header nav a:hover {
            color: #ffdd57;
        }
        header nav a.active {
             color: #ffdd57; /* Highlight color for active link */
            font-weight: bold; /* Make the active link bold */
            border-bottom: 2px solid #ffdd57; /* Add underline to active link */
        }

        /* Main Section Styling */
        .check-appointment {
            padding: 120px 20px;
            max-width: 800px;
            margin: auto;
            text-align: center;
        }
        .check-appointment h2 {
            font-size: 36px;
            margin-bottom: 40px;
            color: #333;
        }
        .check-appointment form {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }
        .check-appointment input {
            width: 100%;
            padding: 14px;
            margin-bottom: 20px;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-size: 16px;
        }
        .check-appointment button {
            background-color: #007bff;
            color: white;
            border: none;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 14px;
        }
        .check-appointment button:hover {
            background-color: #ffdd57;
            color: #333;
            transform: translateY(-3px);
        }

        /* Footer */
        footer {
            padding: 80px 20px;
            background-color: #333;
            color: white;
            text-align: center;
        }
        footer .footer-content {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            margin: auto;
            flex-wrap: wrap;
        }
        footer p, footer a {
            color: #ccc;
            margin-bottom: 8px;
            font-size: 14px;
        }
        footer .socials a {
            margin-right: 15px;
            font-size: 24px;
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>

    <!-- Header -->
  <header>
    <nav>
        <h1>Doctor Appointment</h1>
        <div>
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="check_appointment.php" class="active">Check Appointment</a>
            <a href="booking.php">Book Appointment</a>
            <a href="contact.php">Contact</a>
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                <?php if ($_SESSION['role'] === 'admin'): ?> 
                    <a href="dashboard.php">Dashboard</a> <!-- Admin's dashboard -->
                <?php elseif ($_SESSION['role'] === 'doctor'): ?> 
                    <a href="doctor_dashboard.php">Dashboard</a> <!-- Doctor's dashboard -->
                <?php endif; ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </nav>
</header>


    <!-- Check Appointment Section -->
    <section class="check-appointment">
        <h2>Check Your Appointment</h2>
        <?php if (!empty($appointment_details)): ?>
            <p><?php echo $appointment_details; ?></p>
        <?php endif; ?>
        <form action="check_appointment.php" method="POST"> <!-- Form action to itself -->
            <input type="number" name="appointment_id" placeholder="Appointment ID" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <button type="submit">Check Appointment</button>
        </form>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p>&copy; 2024 Doctor Appointment Management System</p>
            <div class="socials">
                <a href="#">Facebook</a>
                <a href="#">Twitter</a>
                <a href="#">Instagram</a>
            </div>
        </div>
    </footer>

</body>
</html>
