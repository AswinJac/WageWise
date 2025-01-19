<?php
session_start();
require_once('DBConnection.php'); // Include your database connection file

// Check if the employee is logged in
if (!isset($_SESSION['employee_id'])) {
    header("Location: ./login.php");
    exit;
}

// Fetch employee details from the database
$employee_id = $_SESSION['employee_id'];
$sql = "SELECT e.*, d.name, des.name 
        FROM employee_list e 
        LEFT JOIN department_list d ON e.department_id = d.department_id 
        LEFT JOIN designation_list des ON e.designation_id = des.designation_id 
        WHERE e.employee_id = '{$employee_id}'";

$result = $conn->query($sql);
$employee = $result->fetch_assoc();

// Check if employee details were retrieved
if (!$employee) {
    echo "Employee details not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h3>Welcome, <?php echo htmlspecialchars($employee['name']); ?></h3>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Employee Details
                    </div>
                    <div class="card-body">
                        <p><strong>Employee Code:</strong> <?php echo htmlspecialchars($employee['code']); ?></p>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($employee['name']); ?></p>
                        <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($employee['dob']); ?></p>
                        <p><strong>Department:</strong> <?php echo htmlspecialchars($employee['name']); ?></p>
                        <p><strong>Designation:</strong> <?php echo htmlspecialchars($employee['name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($employee['email']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($employee['contact']); ?></p>
                        <p><strong>Status:</strong> <?php echo htmlspecialchars($employee['status'] ? 'Active' : 'Inactive'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
