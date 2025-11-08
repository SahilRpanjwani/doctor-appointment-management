<?php
// Start session
session_start();

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .container {
            margin-top: 50px;
        }
        .welcome-message {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .logout-btn, .main-page-btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            margin: 5px;
        }
        .logout-btn:hover, .main-page-btn:hover {
            background-color: #0056b3;
        }
        .navigation {
            margin-top: 20px;
        }
        a {
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="welcome-message">Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h1>
    <p>You are now logged in.</p>
    
    <div class="navigation">
        <a href="appointments.php">View Appointments</a> | 
        <a href="profile.php">Edit Profile</a>
    </div>
    
    <p>
        <a href="index.php" class="main-page-btn">Back to Main Page</a>
    </p>

    <p>
        <a href="logout.php" class="logout-btn">Logout</a>
    </p>
</div>

</body>
</html>
