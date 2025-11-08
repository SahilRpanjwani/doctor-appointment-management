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

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['id'])) {
    echo "User not logged in.";
    exit();
}

// Fetch doctors
$sqlDoctors = "SELECT d.id, d.name, d.specialization, d.image 
                FROM doctors d";
$resultDoctors = $conn->query($sqlDoctors);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #74ebd5, #ACB6E5); /* Softer gradient from light to dark blue */
            color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        h1 {
            margin-bottom: 40px;
            color: #ffffff; /* White text for the title */
        }
        .container {
            margin-top: 50px;
        }
        .doctor-card {
            background-color: rgba(0, 0, 0, 0.6); /* Increased opacity for better contrast */
            border-radius: 10px;
            overflow: hidden;
            text-align: center;
            color: white;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); /* Softer shadow */
            transition: transform 0.3s ease, background-color 0.3s ease;
        }
        .doctor-card:hover {
            transform: scale(1.05);
            background-color: rgba(0, 0, 0, 0.8); /* Slightly darker on hover */
        }
        .doctor-image-wrapper {
            width: 100%;
            aspect-ratio: 1/1;
            overflow: hidden;
            border-radius: 10px;
        }
        .doctor-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .doctor-name {
            font-size: 24px;
            margin-top: 15px;
            color: #ffffff; /* Brighter text for better visibility */
        }
        .doctor-specialization {
            font-size: 18px;
            margin-top: 10px;
            color: #FFD700; /* Gold for specialization */
        }
        a {
            text-decoration: none;
            color: inherit;
        }
        .btn-back {
            background-color: #0056b3; /* Darker blue button */
            color: white;
            border-radius: 20px;
            padding: 10px 20px;
            font-weight: bold;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-back:hover {
            background-color: #003f7f; /* Even darker on hover */
        }
        .btn-container {
            text-align: center; /* Center the button */
            margin-top: 20px; /* Space between doctors grid and button */
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center">Doctors</h1>

    <div class="row">
        <?php if ($resultDoctors->num_rows > 0): ?>
            <?php while ($row = $resultDoctors->fetch_assoc()): ?>
                <div class="col-md-4">
                    <a href="doctor_details.php?id=<?php echo htmlentities($row['id']); ?>"> <!-- Make card clickable -->
                        <div class="doctor-card">
                            <div class="doctor-image-wrapper">
                                <img src="uploads/<?php echo htmlentities($row['image']); ?>" alt="Doctor Image" class="doctor-image">
                            </div>
                            <h2 class="doctor-name"><?php echo htmlentities($row['name']); ?></h2>
                            <p class="doctor-specialization"><?php echo htmlentities($row['specialization']); ?></p>
                        </div>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-warning text-center">No doctors found.</div>
        <?php endif; ?>
    </div>

    <!-- Button placed below the doctors grid -->
    <div class="btn-container">
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
