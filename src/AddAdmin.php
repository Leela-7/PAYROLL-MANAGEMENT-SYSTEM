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
$username = $password = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Perform SQL query to insert data into admin_credentials table
    $sql = "INSERT INTO admin_credentials (username, password) VALUES ('$username', '$password')";
    
    if ($conn->query($sql) === TRUE) {
        $message = "New record created successfully";
        echo "<script>alert('$message');</script>";
    
    } else {
        $message = "Error: ".$sql. "<br>".$conn->error;
        echo "<script>alert('$message');</script>";
    }
}

// Close database connection
$conn->close();
?>
