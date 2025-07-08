<?php
// Assuming your database connection details
$servername = "localhost";
$username = "root";  // Your MySQL username
$password = "";      // Your MySQL password
$dbname = "project"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables to store form data
$currentPassword = $newPassword = $confirmNewPassword = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $currentPassword = mysqli_real_escape_string($conn, $_POST['currentPassword']);
    $newPassword = mysqli_real_escape_string($conn, $_POST['newPassword']);
    $confirmNewPassword = mysqli_real_escape_string($conn, $_POST['confirmNewPassword']);

    // Retrieve the current password from the database based on your session or logged-in user
    session_start();
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $sql = "SELECT password FROM admin_credentials WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $storedPassword = $row['password'];

            // Verify if the entered current password matches the stored password (without hashing)
            if ($currentPassword === $storedPassword) {
                // Update the password in the database (store it directly without hashing)
                $updateSql = "UPDATE admin_credentials SET password = '$newPassword' WHERE username = '$username'";

                if ($conn->query($updateSql) === TRUE) {
                    echo "Password updated successfully";
                } else {
                    echo "Error updating password: " . $conn->error;
                }
            } else {
                echo "Current password entered is incorrect.";
            }
        } else {
            echo "User not found.";
        }
    } else {
        echo "Session not set or user not logged in.";
    }
}

// Close database connection
$conn->close();
?>
