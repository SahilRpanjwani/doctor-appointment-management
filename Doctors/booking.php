<?php
session_start(); // Start the session

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "doctor_appointment"; // Your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize messages
$success_message = '';
$error_message = '';
$appointment_status = ''; // To store the appointment status

// Check if the user is logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];

    // Query to check if the user has an appointment
    $status_sql = "SELECT status, appointment_date, appointment_time FROM appointments WHERE user_id = ? ORDER BY appointment_date DESC LIMIT 1";
    $status_stmt = $conn->prepare($status_sql);
    $status_stmt->bind_param("i", $user_id);
    $status_stmt->execute();
    $status_result = $status_stmt->get_result();

    if ($status_result->num_rows > 0) {
        $row = $status_result->fetch_assoc();
        $appointment_status = "Your latest appointment is on " . $row['appointment_date'] . " at " . $row['appointment_time'] . " and the status is: " . $row['status'];
    } else {
        $appointment_status = "You have no appointments booked yet.";
    }
    $status_stmt->close();

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $patient_name = $_POST['patient_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $doctor_id = $_POST['doctor_id']; // Changed to doctor_id
        $appointment_date = $_POST['appointment_date'];
        $appointment_time = $_POST['appointment_time'];

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO appointments (user_id, patient_name, email, phone, doctor_id, appointment_date, appointment_time) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $user_id, $patient_name, $email, $phone, $doctor_id, $appointment_date, $appointment_time);

        if ($stmt->execute()) {
            // Success message
            $success_message = "Appointment booked successfully. Your appointment ID is: " . $stmt->insert_id;
        } else {
            $error_message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    // Fetch doctors for dropdown
    $doctor_sql = "SELECT id, name, specialization FROM doctors"; // Added id to the query
    $doctor_result = $conn->query($doctor_sql);
} else {
    $error_message = "You must be logged in to book an appointment.";
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking - Doctor Appointment Management System</title>
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
        .booking {
            padding: 120px 20px;
            max-width: 800px;
            margin: auto;
            text-align: center;
        }
        .booking h2 {
            font-size: 36px;
            margin-bottom: 40px;
            color: #333;
        }
        .booking form {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }
        .booking input, .booking select, .booking button {
            width: 100%;
            padding: 14px;
            margin-bottom: 20px;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-size: 16px;
        }
        .booking input:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.2);
        }
        .booking button {
            background-color: #007bff;
            color: white;
            border: none;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .booking button:hover {
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
            <a href="check_appointment.php">Check Appointment</a>
            <a href="booking.php" class="active">Book Appointment</a>
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

    <!-- Booking Section -->
    <section class="booking">
        <h2>Book Your Appointment</h2>
        <?php if ($success_message): ?>
            <p style="color: green;"><?php echo $success_message; ?></p>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <p style="color: blue;"><?php echo $appointment_status; ?></p> <!-- Display the appointment status -->
        
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <form action="booking.php" method="POST"> <!-- Form action to itself -->
                <input type="text" name="patient_name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <input type="tel" name="phone" placeholder="Your Phone Number" required>
                
                <!-- Dynamic Doctor Dropdown -->
                <select name="doctor_id" required> <!-- Changed to doctor_id -->
                    <option value="">Select Doctor</option>
                    <?php if ($doctor_result->num_rows > 0): ?>
                        <?php while ($doctor_row = $doctor_result->fetch_assoc()): ?>
                            <option value="<?php echo htmlentities($doctor_row['id']); ?>">
                                <?php echo htmlentities($doctor_row['name']); ?> - <?php echo htmlentities($doctor_row['specialization']); ?>
                            </option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>

                <input type="date" name="appointment_date" required>
                <input type="time" name="appointment_time" required>
                <button type="submit">Book Appointment</button>
            </form>
        <?php else: ?>
            <p>Please <a href="login.php">log in</a> to book an appointment.</p>
        <?php endif; ?>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p>&copy; 2024 Doctor Appointment Management System. All rights reserved.</p>
            <div class="socials">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </footer>
    
</body>
</html>
