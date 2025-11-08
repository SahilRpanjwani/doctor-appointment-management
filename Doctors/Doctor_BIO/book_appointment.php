<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Get the doctor type from the URL
$doctorType = isset($_GET['doctor']) ? $_GET['doctor'] : '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $appointmentDate = $_POST['appointment_date'];
    $appointmentTime = $_POST['appointment_time'];

    // Here you would insert the booking information into the database
    // Example: Insert into appointments table (doctor type, user, date, time)

    // Redirect to confirmation page or show a success message
    echo "Appointment booked successfully for " . htmlspecialchars($doctorType) . " on " . htmlspecialchars($appointmentDate) . " at " . htmlspecialchars($appointmentTime) . ".";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
</head>
<body>

    <h1>Book an Appointment with a <?php echo htmlspecialchars(ucfirst($doctorType)); ?></h1>

    <form method="POST" action="">
        <label for="appointment_date">Choose a Date:</label>
        <input type="date" id="appointment_date" name="appointment_date" required>

        <label for="appointment_time">Choose a Time:</label>
        <input type="time" id="appointment_time" name="appointment_time" required>

        <button type="submit">Book Appointment</button>
    </form>

</body>
</html>
