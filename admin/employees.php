
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Employee List</h3>
        <div class="card-tools align-middle">
            <button class="btn btn-dark btn-sm py-1 rounded-0" type="button" id="create_new">Add New</button>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-hover table-striped table-bordered">
            <colgroup>
                <col width="5%">
                <col width="15%">
                <col width="20%">
                <col width="20%">
                <col width="20%">
                <col width="10%">
                <col width="10%">
            </colgroup>
            <thead>
                <tr>
                    <th class="text-center p-0">#</th>
                    <th class="text-center p-0">Employee Code</th>
                    <th class="text-center p-0">Name</th>
                    <th class="text-center p-0">Department/Designation</th>
                    <th class="text-center p-0">Info</th>
                    <th class="text-center p-0">Status</th>
                    <th class="text-center p-0">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sql = "SELECT e.*,CONCAT(e.lastname, ', ', e.firstname, ' ', COALESCE(e.middlename,'')) as `name`,d.name as dept, dd.name as desg FROM `employee_list`e INNER JOIN `department_list` d on e.department_id = d.department_id inner join `designation_list` dd on e.designation_id = dd.designation_id  order by CONCAT(e.lastname, ', ', e.firstname, ' ', COALESCE(e.middlename,'')) asc";
                $qry = $conn->query($sql);
                $i = 1;
                    while($row = $qry->fetch_assoc()):
                ?>
                <tr>
                    <td class="text-center p-0"><?php echo $i++; ?></td>
                    <td class="py-0 px-1"><?php echo $row['code'] ?></td>
                    <td class="py-0 px-1"><?php echo $row['name'] ?>
                    <?php if($row['gender'] == "Male"): ?>
                        <span class="fa fa-mars mx-1 text-primary opacity-50" title="Male"></span>
                    <?php else: ?>
                        <span class="fa fa-venus mx-1 text-danger opacity-50" title="Female"></span>
                    <?php endif; ?>
                    </td>
                    <td class="py-0 px-1">
                        <small>Department: <?php echo $row['dept'] ?></small><br>
                        <small>Designation: <?php echo $row['desg'] ?></small>
                    </td>
                    <td class="py-0 px-1">
                        <small>DoB: <?php echo date("M d, Y",strtotime($row['dob'])) ?></small><br>
                        <small>Email: <?php echo $row['email'] ?></small><br>
                        <small>Contact: <?php echo $row['contact'] ?></small>
                    </td>
                    <td class="py-0 px-1 text-center">
                    <?php if($row['status'] == 1): ?>
                        <span class="badge bg-success rounded-pill">Active</span>
                    <?php else: ?>
                        <span class="badge bg-danger rounded-pill">Inactive</span>
                    <?php endif; ?>
                    </td>
                    <th class="text-center py-0 px-1">
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle btn-sm rounded-0 py-0" data-bs-toggle="dropdown" aria-expanded="false">
                            Action
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <li><a class="dropdown-item view_data" data-id = '<?php echo $row['employee_id'] ?>' href="javascript:void(0)">View</a></li>
                            <li><a class="dropdown-item edit_data" data-id = '<?php echo $row['employee_id'] ?>' href="javascript:void(0)">Edit</a></li>
                            <li><a class="dropdown-item delete_data" data-id = '<?php echo $row['employee_id'] ?>' data-name = '<?php echo $row['code']." - ".$row['name'] ?>' href="javascript:void(0)">Delete</a></li>
                            </ul>
                        </div>
                    </th>
                </tr>
                <?php endwhile; ?>
               
            </tbody>
        </table>
    </div>
</div>
<script>
    $(function(){
        $('#create_new').click(function(){
            uni_modal('Add New Employee',"manage_employee.php",'mid-large')
        })
        $('.edit_data').click(function(){
            uni_modal('Edit Employee Details',"manage_employee.php?id="+$(this).attr('data-id'),'mid-large')
        })
        $('.view_data').click(function(){
            uni_modal('Employee Details',"view_employee.php?id="+$(this).attr('data-id'),'mid-large')
        })
        $('.delete_data').click(function(){
            _conf("Are you sure to delete <b>"+$(this).attr('data-name')+"</b> from list?",'delete_data',[$(this).attr('data-id')])
        })
        $('table td,table th').addClass('align-middle')
        $('table').dataTable({
            columnDefs: [
                { orderable: false, targets:6 }
            ]
        })
    })
    function delete_data($id){
        $('#confirm_modal button').attr('disabled',true)
        $.ajax({
            url:'./../Actions.php?a=delete_employee',
            method:'POST',
            data:{id:$id},
            dataType:'JSON',
            error:err=>{
                consolre.log(err)
                alert("An error occurred.")
                $('#confirm_modal button').attr('disabled',false)
            },
            success:function(resp){
                if(resp.status == 'success'){
                    location.reload()
                }else{
                    alert("An error occurred.")
                    $('#confirm_modal button').attr('disabled',false)
                }
            }
        })
    }
</script>