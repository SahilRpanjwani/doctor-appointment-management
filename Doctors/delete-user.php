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

// Check if ID is provided
if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Check if the user is a doctor
        $stmt_check = $conn->prepare("SELECT * FROM doctors WHERE user_id = ?");
        $stmt_check->bind_param("i", $user_id);
        $stmt_check->execute();
        $check_result = $stmt_check->get_result();

        // If the user is found in the doctors table, delete from there
        if ($check_result->num_rows > 0) {
            $stmt_doctor = $conn->prepare("DELETE FROM doctors WHERE user_id = ?");
            $stmt_doctor->bind_param("i", $user_id);
            $stmt_doctor->execute();
        }

        // Now delete from users table
        $stmt_user = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt_user->bind_param("i", $user_id);
        $stmt_user->execute();

        // Commit the transaction
        $conn->commit();

        // Redirect to users page with success message
        header("Location: users.php?message=User deleted successfully.");
        exit();
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        // Redirect to users page with error message
        header("Location: users.php?error=Error deleting user: " . $e->getMessage());
        exit();
    }
} else {
    // Redirect to users page if no ID is provided
    header("Location: users.php?error=No user ID provided.");
    exit();
}

// Close the database connection
$conn->close();
?>
