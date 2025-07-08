<?php
// Assuming your database connection details
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your actual password
$dbname = "project"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$employeeName = "";
$paySlipContent = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if employeeName is set in $_POST
    if (isset($_POST['employeeName'])) {
        // Sanitize and validate input data
        $employeeName = mysqli_real_escape_string($conn, $_POST['employeeName']);

        // Prepare SQL statement
        $sql = "SELECT * FROM employee_details WHERE employee_name = ?";

        // Prepare and bind parameters
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $employeeName);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if employee exists
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Extract salary from the database
            $salary = $row['employee_salary'];

            // Calculate other allowances (assuming 10%)
            $otherAllowance = $salary * 0.1;

            // Calculate deductions (assuming 5%)
            $deductions = $salary * 0.05;

            // Calculate net salary
            $netSalary = $salary + $otherAllowance - $deductions;

            // Prepare pay slip content in HTML table format
            $paySlipContent = "
                <html>
                <head>
                    <style>
                        table {
                            width: 100%;
                            border-collapse: collapse;
                        }
                        th, td {
                            padding: 8px;
                            text-align: left;
                            border-bottom: 1px solid #ddd;
                        }
                        th {
                            background-color: #f2f2f2;
                        }
                    </style>
                </head>
                <body>
                    <h2>Pay Slip for $employeeName</h2>
                    <table>
                        <tr>
                            <th>Item</th>
                            <th>Amount (INR)</th>
                        </tr>
                        <tr>
                            <td>Basic Salary</td>
                            <td>$salary</td>
                        </tr>
                        <tr>
                            <td>Other Allowance (10%)</td>
                            <td>$otherAllowance</td>
                        </tr>
                        <tr>
                            <td>Deductions (5%)</td>
                            <td>$deductions</td>
                        </tr>
                        <tr style='font-weight:bold'>
                            <td>Net Salary</td>
                            <td>$netSalary</td>
                        </tr>
                    </table>
                </body>
                </html>
            ";

            // Include TCPDF library
            require_once('tcpdf/tcpdf.php');

            // Create new PDF document
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // Set document information
            $pdf->SetTitle('Pay Slip');
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            $pdf->SetFont('helvetica', '', 10);

            // Add a page
            $pdf->AddPage();

            // Output the HTML content into PDF
            $pdf->writeHTML($paySlipContent, true, false, true, false, '');

            // Close and output PDF document
            $pdf->Output('payslip.pdf', 'D');
            exit;
        } else {
            $paySlipContent = "Employee not found in the database.";
        }
    } else {
        $paySlipContent = "Employee name is not provided.";
    }
}

// Display the pay slip HTML content if not generating PDF
echo $paySlipContent;

// Close statement and database connection
if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>