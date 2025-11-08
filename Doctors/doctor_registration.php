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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST['name'];
    $specialization = $_POST['specialization'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $experience_years = $_POST['experience_years'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Secure password

    // Handle the image upload
    $target_dir = "uploads/"; // Folder to store images
    $image_name = basename($_FILES["image"]["name"]); // Get image name
    $target_file = $target_dir . $image_name; // Full path for storing
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a real image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $error_message = "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (limit to 2MB)
    if ($_FILES["image"]["size"] > 2000000) {
        $error_message = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats (only JPEG, PNG, and GIF)
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $error_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $error_message = "Sorry, your file was not uploaded.";
    } else {
        // Try to upload file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // File uploaded successfully, now insert into the database

            // Insert into users table first
            $stmt_user = $conn->prepare("INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, 'doctor')");
            $stmt_user->bind_param("ssss", $name, $email, $phone, $password);

            if ($stmt_user->execute()) {
                // Get the last inserted user ID
                $user_id = $stmt_user->insert_id;

                // Now insert into doctors table using the user_id
                $stmt_doctor = $conn->prepare("INSERT INTO doctors (name, specialization, phone, email, gender, experience_years, address, image, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt_doctor->bind_param("ssssisssi", $name, $specialization, $phone, $email, $gender, $experience_years, $address, $image_name, $user_id);

                if ($stmt_doctor->execute()) {
                    // Success message
                    $success_message = "Doctor registered successfully and added to users and doctors tables.";
                } else {
                    // Error message for doctor table insertion
                    $error_message = "Error in adding doctor to doctors table: " . $stmt_doctor->error;
                }

                $stmt_doctor->close();
            } else {
                // Error message for user table insertion
                $error_message = "Error in adding doctor to users table: " . $stmt_user->error;
            }

            $stmt_user->close(); // Close the prepared statement
        } else {
            $error_message = "Sorry, there was an error uploading your file.";
        }
    }
}

$conn->close(); // Close the database connection
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Registration - Doctor Appointment Management System</title>
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
        .registration {
            padding: 50px 20px;
            max-width: 800px;
            margin: auto;
            text-align: center;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }
        .registration h2 {
            font-size: 36px;
            margin-bottom: 40px;
            color: #333;
        }
        .registration form {
            padding: 20px;
        }
        .registration input, .registration select, .registration button {
            width: 100%;
            padding: 14px;
            margin-bottom: 20px;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-size: 16px;
        }
        .registration input:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.2);
        }
        .registration button {
            background-color: #007bff;
            color: white;
            border: none;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .registration button:hover {
            background-color: #ffdd57;
            color: #333;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>

    <!-- Doctor Registration Section -->
    <section class="registration">
        <h2>Register a New Doctor</h2>
        <?php if ($success_message): ?>
            <p style="color: green;"><?php echo $success_message; ?></p>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

      <form action="doctor_registration.php" method="POST" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Doctor's Name" required>
    <input type="text" name="specialization" placeholder="Specialization" required>
    <input type="tel" name="phone" placeholder="Phone Number" required>
    <input type="email" name="email" placeholder="Email" required>
    <select name="gender" required>
        <option value="">Select Gender</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
    </select>
    <input type="number" name="experience_years" placeholder="Years of Experience" required min="0">
    <input type="text" name="address" placeholder="Address" required>
    <input type="password" name="password" placeholder="Password" required>
    
    <!-- New image input field -->
    <input type="file" name="image" required accept="image/*">

    <button type="submit">Register Doctor</button>
</form>

        <a href="dashboard.php" style="display: inline-block; margin-top: 20px;">
            <button type="button">Go Back to Dashboard</button>
        </a>
    </section>

</body>
</html>
