<?php
// Start session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Appointment Management System</title>
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
            background-image: url('background-image.jpg');
            background-size: cover;
            background-attachment: fixed;
        }
        a {
            text-decoration: none;
            color: inherit;
        }

        /* Header */
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
            letter-spacing: 1px;
        }
        header nav a {
            color: white;
            font-size: 18px;
            margin-left: 15px;
            padding: 10px;
            transition: all 0.3s ease;
        }
        header nav a:hover {
            color: #ffdd57;
            border-bottom: 2px solid #ffdd57;
            transform: scale(1.05);
        }
        header nav a.active {
            color: #ffdd57; /* Highlight color for active link */
            font-weight: bold; /* Make the active link bold */
            border-bottom: 2px solid #ffdd57; /* Add underline to active link */
        }

        /* Logout button aligned to the top right */
        .logout-container {
            display: flex;
            align-items: center;
            position: relative; /* Changed to relative */
            margin-left: auto; /* This ensures it pushes to the right */
        }
        .logout {
            color: white; /* Ensure visibility */
            margin-left: 10px; /* Space from Dashboard link */
        }

        /* Hero Section */
        .hero {
            height: 400px; /* Reduced height */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            background: url('banner-image.jpg') center no-repeat;
            background-size: cover;
            margin-bottom: 50px;
            color: white;
            padding: 0 20px;
        }
        .hero:before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
        }
        .hero h1 {
            font-size: 36px; /* Reduced font size */
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }
        .hero p {
            font-size: 20px; /* Reduced font size */
            color: #ffdd57;
            position: relative;
            z-index: 1;
        }

        /* Image Section */
        .image-section {
            text-align: center;
            margin: 40px 0;
        }
        .image-section img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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
        footer .footer-content div {
            flex: 1;
            padding: 10px;
            text-align: left;
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
        footer .socials a:hover {
            color: #ffdd57;
            transform: scale(1.1);
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            header nav {
                flex-direction: column;
            }
            footer .footer-content {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
    <!-- Add Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

    <!-- Header -->
    <header>
    <nav>
        <h1>Doctor Appointment</h1>
        <div>
            <a href="index.php"  class="active">Home</a>
            <a href="about.php">About</a>
            <a href="check_appointment.php">Check Appointment</a>
            <a href="booking.php">Book Appointment</a>
            <a href="contact.php">Contact</a>
           <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['role'] === "admin"): ?>
    <a href="dashboard.php">Dashboard</a> <!-- Dashboard link for admin -->
<?php elseif (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['role'] === "doctor"): ?>
    <a href="doctor_dashboard.php">Dashboard</a> <!-- Dashboard link for doctor -->
<?php endif; ?>

            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                <a href="logout.php">Logout</a> <!-- Logout link if logged in -->
            <?php else: ?>
                <a href="login.php">Login</a> <!-- Login link if not logged in -->
            <?php endif; ?>
        </div>
    </nav>
</header>


    <!-- Hero Section -->
    <section class="hero">
        <h1>Your Health, Our Priority</h1>
        <p>Expert Doctors at Your Service</p>
    </section>

    <!-- Image Section -->
    <div class="image-section">
        <img src="doc.png" alt="Doctor">
    </div>

    <!-- Footer Section -->
    <footer>
        <div class="footer-content">
            <div>
                <p>Timings: 10:30 AM - 7:30 PM</p>
                <p>Email: Doctors@example.com</p>
                <p>Contact: 7896541239</p>
            </div>
            <div>
                <p>Our Clinic</p>
                <p>890, Sector 62, Noida, India</p>
            </div>
            <div class="socials">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <!-- Add Project By line here -->
        <p style="margin-top: 20px; font-size: 14px; color: #ccc;">Project by Sahil, Mahir, and Nayan</p>
    </footer>

</body>
</html>
