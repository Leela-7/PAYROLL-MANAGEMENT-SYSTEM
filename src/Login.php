<?php
// Start session to persist user login state
session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Database connection settings
    $servername = 'localhost';
    $username = 'root'; // Default username for XAMPP
    $password = ''; // Default password for XAMPP
    $dbname = 'project'; // Replace with your database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Retrieve user input from the form (sanitize if necessary)
    $username = $_POST['username'];
    $password = $_POST['password'];
    $type = $_POST['type']; // Assuming you have a form field named 'type' for user/admin selection

    // Determine which table to query based on $type
    if ($type == 'user') {
        $table = 'user_credentials'; // Table where passwords are not hashed
        $password_field = 'password'; // Field name for plain text password
        $hash_password = false; // Flag to indicate no password hashing
    } elseif ($type == 'admin') {
        $table = 'admin_credentials'; // Table where passwords are not hashed
        $password_field = 'password'; // Field name for plain text password
        $hash_password = false; // Flag to indicate no password hashing
    } else {
        die('Invalid user type.'); // Handle invalid cases (optional)
    }

    // Prepare SQL query to fetch user data
    $stmt = $conn->prepare("SELECT username, password FROM $table WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        // Bind result variables
        $stmt->bind_result($db_username, $db_password);
        $stmt->fetch();

        // Verify password
        if (!$hash_password && $password === $db_password) {
            // Authentication successful
            $_SESSION['username'] = $db_username; // Store username in session variable

            // Redirect to appropriate dashboard
            if ($type == 'user') {
                header('Location: EmployeeDashboard.html');
            } elseif ($type == 'admin') {
                header('Location: AdminDashboard.html');
            }
            exit;
        } else {
            // Authentication failed
            echo 'Invalid username or password.';
        }
    } else {
        // Authentication failed
        echo 'Invalid username or password.';
    }

    // Close statement
    $stmt->close();

    // Close connection
    $conn->close();
}
?>
