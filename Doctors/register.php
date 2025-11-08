<?php
// Start session
session_start();

// Database connection
$servername = "localhost";  
$username = "root"; 
$password = "";  
$dbname = "doctor_appointment";  

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define variables and initialize with empty values
$name = $email = $phone = $password = $confirm_password = "";
$name_err = $email_err = $phone_err = $password_err = $confirm_password_err = "";

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter your name.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else {
        $sql = "SELECT id FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_email);
            $param_email = trim($_POST["email"]);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $email_err = "This email is already taken.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Error checking email. Please try again later.";
            }
            $stmt->close();
        }
    }

    // Validate phone
    if (empty(trim($_POST["phone"]))) {
        $phone_err = "Please enter your phone number.";
    } else {
        $phone = trim($_POST["phone"]);
        // Optional: Validate phone number format (you can customize the regex)
        if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
            $phone_err = "Please enter a valid phone number.";
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm your password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if ($password != $confirm_password) {
            $confirm_password_err = "Passwords did not match.";
        }
    }

// ... (rest of your existing code)

// Check for errors before inserting into database
if (empty($name_err) && empty($email_err) && empty($phone_err) && empty($password_err) && empty($confirm_password_err)) {
    // Prepare an insert statement with role as 'user'
    $sql = "INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, 'user')";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssss", $param_name, $param_email, $param_phone, $param_password);

        // Set parameters
        $param_name = $name;
        $param_email = $email;
        $param_phone = $phone;
        $param_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password

        if ($stmt->execute()) {
            // Redirect to login page after successful registration
            header("location: login.php");
            exit();
        } else {
            echo "Error registering. Please try again later.";
        }
        $stmt->close();
    }
}

// ... (rest of your existing code)

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 400px;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 50px auto;
            border-radius: 10px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }
        .error {
            color: red;
            font-size: 0.9em;
        }
        .submit-btn {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        .submit-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Create an Account</h2>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>">
            <span class="error"><?php echo $name_err; ?></span>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>">
            <span class="error"><?php echo $email_err; ?></span>
        </div>

        <div class="form-group">
            <label>Phone Number</label>
            <input type="tel" name="phone" class="form-control" value="<?php echo htmlspecialchars($phone); ?>">
            <span class="error"><?php echo $phone_err; ?></span>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control">
            <span class="error"><?php echo $password_err; ?></span>
        </div>

        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control">
            <span class="error"><?php echo $confirm_password_err; ?></span>
        </div>

        <div class="form-group">
            <input type="submit" class="submit-btn" value="Register">
        </div>

        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </form>
</div>

</body>
</html>
