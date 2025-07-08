<?php
// Assuming your database connection details
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your actual password
$dbname = "project"; // Replace with your actual database name

// Initialize variables to store form data
$name = $email = $message = "";
$message_display = ""; // Variable to hold success message

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Prepare SQL statement to insert data into 'grievance' table
    $sql = "INSERT INTO grievance (Name, Email, Message) VALUES (?, ?, ?)";

    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $message);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Close statement
        $stmt->close();
        // Close database connection
        $conn->close();
        
        // Set success message to be displayed
         
        
        // Scroll to the form section using JavaScript
        echo '<script>alert("Thank you for submitting your grievance. We will get back to you as soon as possible.")</script>';
    } else {
        // Redirect back to the contact form with an error message
        header("Location: contact_us.php?status=error");
        exit();
    }
}
?>