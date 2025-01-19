<?php
session_start();
require_once('DBConnection.php'); // Include your database connection file

// Fetch employee details from the database
$employee_id = $_SESSION['employee_id'];
$sql = "SELECT e.*, d.name AS department_name, des.name AS designation_name 
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
                    <div class="card-header">Employee Details</div>
                    <div class="card-body">
                        <p><strong>Employee Code:</strong> <?php echo htmlspecialchars($employee['code']); ?></p>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($employee['name']); ?></p>
                        <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($employee['dob']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($employee['email']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($employee['contact']); ?></p>
                        <p><strong>Status:</strong> <?php echo htmlspecialchars($employee['status'] ? 'Active' : 'Inactive'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Department & Designation</div>
                    <div class="card-body">
                        <p><strong>Department:</strong> <?php echo htmlspecialchars($employee['department_name']); ?></p>
                        <p><strong>Designation:</strong> <?php echo htmlspecialchars($employee['designation_name']); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">User List</h3>
                <div class="card-tools align-middle">
                    <button class="btn btn-dark btn-sm py-1 rounded-0" type="button" id="create_new">Add New</button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover table-striped table-bordered">
                    <colgroup>
                        <col width="5%">
                        <col width="30%">
                        <col width="25%">
                        <col width="25%">
                        <col width="15%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="text-center p-0">#</th>
                            <th class="text-center p-0">Name</th>
                            <th class="text-center p-0">Username</th>
                            <th class="text-center p-0">Type</th>
                            <th class="text-center p-0">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $sql = "SELECT * FROM `admin_list` WHERE admin_id != 1 ORDER BY `fullname` ASC";
                        $qry = $conn->query($sql);
                        $i = 1;
                        if ($qry->num_rows > 0) {
                            while ($row = $qry->fetch_array()):
                        ?>
                        <tr>
                            <td class="text-center p-0"><?php echo $i++; ?></td>
                            <td class="py-0 px-1"><?php echo htmlspecialchars($row['fullname']); ?></td>
                            <td class="py-0 px-1"><?php echo htmlspecialchars($row['username']); ?></td>
                            <td class="py-0 px-1"><?php echo ($row['type'] == 1) ? "Administrator" : 'Staff'; ?></td>
                            <td class="text-center py-0 px-1">
                                <div class="btn-group" role="group">
                                    <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle btn-sm rounded-0 py-0" data-bs-toggle="dropdown" aria-expanded="false">
                                        Action
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <li><a class="dropdown-item edit_data" data-id='<?php echo $row['admin_id']; ?>' href="javascript:void(0)">Edit</a></li>
                                        <li><a class="dropdown-item delete_data" data-id='<?php echo $row['admin_id']; ?>' data-name='<?php echo htmlspecialchars($row['fullname']); ?>' href="javascript:void(0)">Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php 
                            endwhile; 
                        } else {
                        ?>
                        <tr>
                            <td class="text-center p-0" colspan="5">No data to display.</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script>
        $(function() {
            $('#create_new').click(function() {
                uni_modal('Add New User', "manage_admin.php");
            });

            $('.edit_data').click(function() {
                uni_modal('Edit User Details', "manage_admin.php?id=" + $(this).attr('data-id'));
            });

            $('.delete_data').click(function() {
                const userName = $(this).attr('data-name');
                _conf("Are you sure you want to delete <b>" + userName + "</b> from the list?", 'delete_data', [$(this).attr('data-id')]);
            });
        });

        function delete_data(id) {
            $('#confirm_modal button').attr('disabled', true);
            $.ajax({
                url: './../Actions.php?a=delete_admin',
                method: 'POST',
                data: { id: id },
                dataType: 'JSON',
                error: function(err) {
                    console.error(err);
                    alert("An error occurred while deleting the user.");
                    $('#confirm_modal button').attr('disabled', false);
                },
                success: function(resp) {
                    if (resp.status === 'success') {
                        location.reload();
                    } else {
                        alert("An error occurred: " + resp.message);
                        $('#confirm_modal button').attr('disabled', false);
                    }
                }
            });
        }
    </script>
</body>
</html>
