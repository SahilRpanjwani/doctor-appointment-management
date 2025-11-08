<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pediatricians - Doctor Appointment Management</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            display: flex; /* Flex for body */
            flex-direction: column; /* Column layout */
            height: 100vh; /* Full height */
        }
        header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%; /* Full width header */
        }
        header h1 {
            margin: 0;
        }
        nav a {
            margin: 0 10px;
            text-decoration: none;
            color: white;
            font-weight: bold;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .container {
            display: flex; /* Flex layout for sidebar and content */
            flex: 1; /* Allow container to grow */
        }
        .sidebar {
            width: 250px;
            background-color: #333;
            padding: 20px;
            color: white;
            min-height: 100vh; /* Full height sidebar */
        }
        .sidebar h3 {
            color: #ffdf00;
            font-size: 24px; /* Consistent size */
            margin-bottom: 15px; /* Space below heading */
        }
        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }
        .sidebar ul li {
            padding: 10px 0;
            font-size: 18px; /* Consistent font size */
        }
        .sidebar ul li a {
            color: white;
            text-decoration: none;
        }
        .sidebar ul li a:hover {
            color: #ffdf00;
            text-decoration: underline;
        }
        .content {
            flex: 1;
            padding: 20px;
            position: relative;
            background: url('pediatrician-background.jpg') no-repeat center center;
            background-size: cover;
            color: white;
        }
        .content-overlay {
            background: rgba(0, 0, 0, 0.6); /* Darker overlay for better text contrast */
            padding: 30px;
            border-radius: 10px;
        }
        .content h2, .content p, .content h3, .content ul {
            color: white;
        }
        .highlight {
            color: #ffdf00;
        }
        .services, .reviews, .book-section {
            margin-top: 20px;
        }
        .services ul {
            list-style-type: disc;
            padding-left: 20px;
        }
        .book-btn {
            background-color: #ffdf00;
            color: #333;
            padding: 10px 20px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s; /* Smooth transition */
        }
        .book-btn:hover {
            background-color: #ffc107;
        }
        footer {
            background-color: #007bff;
            color: white;
            padding: 10px;
            text-align: center;
            width: 100%;
        }
    </style>
</head>
<body>

    <header>
        <h1>Pediatrician Specialists</h1>
        <nav>
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                <a href="dashboard.php">Dashboard</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>

    <div class="container">
        <!-- Sidebar Section for Doctor Categories -->
        <aside class="sidebar">
            <h3>Doctor Categories</h3>
            <ul>
                <li><a href="dentist.php">🦷 Dentist</a></li>
                <li><a href="cardiologist.php">❤️ Cardiologist</a></li>
                <li><a href="dermatologist.php">🌟 Dermatologist</a></li>
                <li><a href="pediatrician.php">👶 Pediatrician</a></li>
                <li><a href="general_surgeon.php">🩺 General Surgeon</a></li>
                <li><a href="neurologist.php">🧠 Neurologist</a></li>
                <li><a href="orthopedic.php">🦴 Orthopedic</a></li>
            </ul>
        </aside>

        <!-- Main Content Section -->
        <div class="content">
            <div class="content-overlay">
                <h2>Top Pediatricians in Your Area</h2>
                <p>Looking for expert child healthcare? Our platform offers top-rated pediatricians specializing in child wellness, immunizations, and more.</p>

                <!-- Services Section -->
                <section class="services">
                    <h3 class="highlight">Pediatrician Services Offered</h3>
                    <ul>
                        <li>Child Wellness Checkups</li>
                        <li>Immunizations and Vaccinations</li>
                        <li>Newborn and Infant Care</li>
                        <li>Developmental Screenings</li>
                        <li>Treatment for Childhood Illnesses</li>
                    </ul>
                </section>

                <!-- Patient Reviews Section -->
                <section class="reviews">
                    <h3 class="highlight">What Parents Say</h3>
                    <p>"Dr. Linda Johnson is amazing with kids. My son loves his visits, and the care is excellent!" - <em>Susan Carter</em></p>
                    <p>"Dr. Robert Smith helped us through our newborn’s first year with incredible support!" - <em>John Davis</em></p>
                </section>

                <!-- Booking Section -->
                <section class="book-section">
                    <h3 class="highlight">Ready to Book an Appointment?</h3>
                    <p>Click the button below to find a pediatrician and schedule your child's appointment today.</p>
                    <a href="book_appointment.php?doctor=pediatrician" class="book-btn">Book a Pediatrician Appointment</a>
                </section>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Doctor Appointment Management System</p>
    </footer>

</body>
</html>
