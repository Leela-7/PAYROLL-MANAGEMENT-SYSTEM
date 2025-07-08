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
$name = $address = $mobile = $email = $degree = $designation = $department = $password = $salary = $username = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $degree = mysqli_real_escape_string($conn, $_POST['degree']);
    $designation = mysqli_real_escape_string($conn, $_POST['designation']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $salary = mysqli_real_escape_string($conn, $_POST['salary']);

    // Perform SQL queries to insert data into employee_details and user_credentials tables
    $sql_employee = "INSERT INTO employee_details (employee_name, employee_address, employee_number, employee_email, employee_degree, employee_designation, employee_department, employee_password, employee_salary)
                     VALUES ('$name', '$address', '$mobile', '$email', '$degree', '$designation', '$department', '$password', '$salary')";
                     
    $sql_user = "INSERT INTO user_credentials (username, password)
                 VALUES ('$username', '$password')";

    // Execute the first query
    if ($conn->query($sql_employee) === TRUE) {
        // Execute the second query
        if ($conn->query($sql_user) === TRUE) {
            // Success message
            $message = "New record created successfully";
            echo "<script>alert('$message');</script>";
        } else {
            // Error message for user credentials insertion
            $message = "Error inserting user credentials: " . $conn->error;
            echo "<script>alert('$message');</script>";
        }
    } else {
        // Error message for employee details insertion
        $message = "Error inserting employee details: " . $conn->error;
        echo "<script>alert('$message');</script>";
    }
}

// Close database connection
$conn->close();
?>
