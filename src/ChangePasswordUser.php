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
    // Sanitize and validate input data (you may not need mysqli_real_escape_string with prepared statements)
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];

    // Retrieve the current password from the database based on your session or logged-in user
    session_start();
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        
        // Prepare SQL query
        $sql = "SELECT password FROM user_credentials WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $storedPassword = $row['password'];

            // Verify if the entered current password matches the stored hashed password
            if ($currentPassword === $storedPassword) {
                // Update the password in the database (store it directly without hashing)
                $updateSql = "UPDATE user_credentials SET password = ? WHERE username = ?";
                $stmtUpdate = $conn->prepare($updateSql);
                
                // Bind parameters for update query
                $stmtUpdate->bind_param('ss', $newPassword, $username);
                
                // Execute update query
                if ($stmtUpdate->execute()) {
                    echo "Password updated successfully";
                } else {
                    echo "Error updating password: " . $conn->error;
                }
                
                $stmtUpdate->close(); // Close update statement
            } else {
                echo "Current password entered is incorrect.";
            }
        } else {
            echo "User not found.";
        }
        
        $stmt->close(); // Close select statement
    } else {
        echo "Session not set or user not logged in.";
    }
}

// Close database connection
$conn->close();
?>
