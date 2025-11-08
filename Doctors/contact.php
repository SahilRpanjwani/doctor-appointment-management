<?php
session_start();
$success_message = '';
$error_message = '';

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "doctor_appointment";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Validate required fields
    if (!empty($name) && !empty($email) && !empty($message)) {
        // Prepare and insert data into contacts table
        $stmt = $conn->prepare("INSERT INTO contacts (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $phone, $subject, $message);

        if ($stmt->execute()) {
            $success_message = "Thank you for contacting us, $name. We will get back to you shortly!";
        } else {
            $error_message = "There was an error submitting your query. Please try again.";
        }

        $stmt->close();
    } else {
        $error_message = "Please fill out all required fields.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Doctor Appointment Management System</title>
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
             color: #ffdd57;
            font-weight: bold;
            border-bottom: 2px solid #ffdd57;
        }

        /* Contact Section Styling */
        .contact {
            padding: 120px 20px;
            max-width: 800px;
            margin: auto;
            text-align: center;
        }
        .contact h2 {
            font-size: 36px;
            margin-bottom: 40px;
            color: #333;
        }
        .contact form {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }
        .contact input, .contact textarea, .contact button {
            width: 100%;
            padding: 14px;
            margin-bottom: 20px;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-size: 16px;
        }
        .contact textarea {
            resize: none;
        }
        .contact input:focus, .contact textarea:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.2);
        }
        .contact button {
            background-color: #007bff;
            color: white;
            border: none;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .contact button:hover {
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
            <a href="booking.php">Book Appointment</a>
            <a href="contact.php" class="active">Contact</a>
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

<!-- Contact Section -->
<section class="contact">
    <h2>Contact Us</h2>
    <?php if ($success_message): ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
    <?php endif; ?>
    <?php if ($error_message): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form action="contact.php" method="POST">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <input type="tel" name="phone" placeholder="Your Phone Number" required>
        <input type="text" name="subject" placeholder="Subject" required>
        <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
        <button type="submit">Send Message</button>
    </form>
</section>

<!-- Footer -->
<footer>
    <div class="footer-content">
        <div>
            <p>&copy; 2024 Doctor Appointment Management System</p>
            <p><a href="privacy.php">Privacy Policy</a></p>
        </div>
        <div class="socials">
            <a href="#">Facebook</a>
            <a href="#">Twitter</a>
            <a href="#">Instagram</a>
        </div>
    </div>
</footer>

</body>
</html>
