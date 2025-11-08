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
        $doctor = $_POST['doctor'];
        $appointment_date = $_POST['appointment_date'];
        $appointment_time = $_POST['appointment_time'];

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO appointments (user_id, patient_name, email, phone, doctor, appointment_date, appointment_time) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $user_id, $patient_name, $email, $phone, $doctor, $appointment_date, $appointment_time);

        if ($stmt->execute()) {
            // Success message
            $success_message = "Appointment booked successfully. Your appointment ID is: " . $stmt->insert_id;
        } else {
            $error_message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    // Fetch doctors for dropdown
    $doctor_sql = "SELECT name, specialization FROM doctors";
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

     
    </style>
</head>
<body>

    <!-- Header -->
   


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
                <select name="doctor" required>
                    <option value="">Select Doctor</option>
                    <?php if ($doctor_result->num_rows > 0): ?>
                        <?php while ($doctor_row = $doctor_result->fetch_assoc()): ?>
                            <option value="<?php echo htmlentities($doctor_row['name']); ?>">
                                <?php echo htmlentities($doctor_row['name']); ?> - <?php echo htmlentities($doctor_row['specialization']); ?>
                            </option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                    <!--<option value="Don't know where to go? Meet our specialist!">Don't know where to go? Meet our specialist!</option>-->
                </select>

                <input type="date" name="appointment_date" placeholder="Appointment Date" required>
                <input type="time" name="appointment_time" placeholder="Appointment Time" required>
                <button type="submit">Book Appointment</button>
            </form>
        <?php endif; ?>
    </section>

</body>
</html>
