<?php
session_start(); // Start the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Doctor Appointment Management System</title>
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
            background-image: url('background-image.jpg'); /* Background image */
            background-size: cover;
            background-attachment: fixed;
            color: #333;
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
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 1px;
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
            border-bottom: 2px solid #ffdd57;
            transform: scale(1.05);
        }
        header nav a.active {
            color: #ffdd57; /* Highlight color for active link */
            font-weight: bold; /* Make the active link bold */
            border-bottom: 2px solid #ffdd57; /* Add underline to active link */
        }

        /* Hero Section */
        .hero {
            height: 300px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            background: url('hero-image.jpg') center no-repeat; /* Hero background image */
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
            background: rgba(0, 0, 0, 0.5); /* Overlay */
        }
        .hero h2 {
            font-size: 40px;
            position: relative;
            z-index: 1;
            margin-bottom: 10px;
        }
        .hero p {
            font-size: 20px;
            position: relative;
            z-index: 1;
        }

        /* About Us Section */
        .about {
            padding: 80px 20px;
            text-align: center;
            background-color: #fff;
        }
        .about h2 {
            font-size: 38px;
            margin-bottom: 20px;
            color: #333;
        }
        .about p {
            max-width: 800px;
            margin: auto;
            font-size: 18px;
            color: #555;
            line-height: 1.8;
        }

        /* Team Section */
        .team {
            display: flex;
            justify-content: center;
            margin-top: 40px;
            flex-wrap: wrap; /* Responsive team section */
        }
        .team-member {
            margin: 20px;
            text-align: center;
            background-color: #f7f7f7;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .team-member:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        .team-member h4 {
            font-size: 22px;
            color: #333;
            margin-bottom: 8px;
        }
        .team-member p {
            font-size: 16px;
            color: #777;
        }

        /* Core Values Section */
        .core-values {
            background-color: #f5f5f5;
            padding: 60px 20px;
            text-align: center;
        }
        .core-values ul {
            list-style-type: none;
        }
        .core-values li {
            font-size: 18px;
            margin-bottom: 15px;
            color: #555;
        }
        .core-values li strong {
            color: #333;
        }

        /* Our Creators Section */
        .creators {
            padding: 80px 20px;
            text-align: center;
        }
        .creators h2 {
            font-size: 38px;
            margin-bottom: 20px;
            color: #333;
        }

        /* Creator Boxes */
        .creator-boxes {
            display: flex;
            justify-content: center;
            flex-wrap: wrap; /* Responsive creator boxes */
        }
        .creator-box {
            margin: 20px;
            text-align: center;
            background-color: #e8e8e8;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            width: 250px; /* Fixed width for boxes */
        }
        .creator-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        .creator-box h4 {
            font-size: 22px;
            color: #333;
            margin-bottom: 8px;
        }
        .creator-box p {
            font-size: 16px;
            color: #777;
        }

        /* Footer */
        footer {
            padding: 60px 20px;
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
            color: #ffdd57;
            transition: all 0.3s ease;
        }
        footer .socials a:hover {
            color: white;
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
                <a href="about.php" class="active">About</a>
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
        <h2>About Us</h2>
        <p>Your Health, Our Priority</p>
    </section>

    <!-- About Us Section -->
    <section class="about">
        <h2>Welcome to Our System</h2>
        <p>We aim to provide a seamless appointment booking experience with access to the best doctors across specialties.</p>
    </section>

    <!-- Mission Section -->
    <section class="about">
        <h2>Our Mission</h2>
        <p>Our mission is to make healthcare accessible and efficient for everyone by simplifying the appointment process.</p>
    </section>

    <!-- Core Values Section -->
    <section class="core-values">
        <h2>Our Core Values</h2>
        <ul>
            <li><strong>Integrity:</strong> We uphold the highest standards of integrity in all our actions.</li>
            <li><strong>Compassion:</strong> We treat everyone with kindness and compassion.</li>
            <li><strong>Excellence:</strong> We strive for excellence in our services and care.</li>
        </ul>
    </section>

    <!-- Our Creators Section -->
    <section class="creators">
        <h2>Our Creators</h2>
        <div class="creator-boxes">
            <div class="creator-box">
                <h4>Sahil Panjwani</h4>
                <p>Lead Developer</p>
            </div>
            <div class="creator-box">
                <h4>Khalani Mahir</h4>
                <p>Project Manager</p>
            </div>
            <div class="creator-box">
                <h4>Nayan Dobariya</h4>
                <p>UX/UI Designer</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div>
                <p>&copy; 2024 Doctor Appointment Management System</p>
                <p>Contact: info@doctorappointmentsystem.com</p>
            </div>
            <div class="socials">
                <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </footer>

</body>
</html>
