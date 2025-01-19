
<style>
    .logo-img{
        width:45px;
        height:45px;
        object-fit:scale-down;
        background : var(--bs-light);
        object-position:center center;
        border:1px solid var(--bs-dark);
        border-radius:50% 50%;
    }
</style>
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Employee Payroll</h3>
        <div class="card-tools align-middle">
            <a class="btn btn-dark btn-sm py-1 rounded-0" href="./?page=manage_payroll" id="create_new">Add New</a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-hover table-striped table-bordered">
            <colgroup>
                <col width="5%">
                <col width="15%">
                <col width="20%">
                <col width="20%">
                <col width="15%">
                <col width="15%">
                <col width="10%">
            </colgroup>
            <thead>
                <tr>
                    <th class="text-center p-0">#</th>
                    <th class="text-center p-0">Date Created</th>
                    <th class="text-center p-0">Employee</th>
                    <th class="text-center p-0">Payroll Type</th>
                    <th class="text-center p-0">Month</th>
                    <th class="text-center p-0">Net Pay</th>
                    <th class="text-center p-0">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sql = "SELECT p.*,CONCAT(e.lastname , ', ' , e.firstname , ' ' , COALESCE(e.middlename,'')) as name,e.code FROM payroll_list p inner join employee_list e on p.employee_id = e.employee_id order by p.payroll_id desc";
                $qry = $conn->query($sql);
                $i = 1;
                    while($row = $qry->fetch_assoc()):
                ?>
                <tr>
                    <td class="text-center p-0"><?php echo $i++; ?></td>
                    <td class="py-0 px-1 text-end"><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
                    <td class="py-0 px-1 lh-1">
                        <small><?php echo $row['code'] ?></small><br>
                        <small><?php echo $row['name'] ?></small>
                    </td>
                    <td class="py-0 px-1"><?php echo $row['payroll_type'] == 1 ? "Monthly" : "Semi-Monthly" ?></td>
                    <td class="py-0 px-1 text-end"><?php echo date("F, Y",strtotime($row['payroll_month'])) ?></td>
                    <td class="py-0 px-1 text-end"><?php echo number_format($row['net_pay'],2) ?></td>
                    <td class="text-center py-0 px-1">
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle btn-sm rounded-0 py-0" data-bs-toggle="dropdown" aria-expanded="false">
                            Action
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <li><a class="dropdown-item view_data" href="./?page=view_payroll&id=<?php echo $row['payroll_id'] ?>" data-id = '<?php echo $row['payroll_id'] ?>'>View Details</a></li>
                            <li><a class="dropdown-item" data-id = '<?php echo $row['payroll_id'] ?>' href="./?page=manage_payroll&id=<?php echo $row['payroll_id'] ?>">Edit</a></li>
                            <li><a class="dropdown-item delete_data" data-id = '<?php echo $row['payroll_id'] ?>' data-name='<?php echo $row['name'] ?>' href="javascript:void(0)">Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(function(){
        $('.edit_data').click(function(){
            uni_modal('Edit payroll Details',"manage_payroll.php?id="+$(this).attr('data-id'),'large')
        })
        $('.view_data').click(function(){
            uni_modal('payroll Details',"view_payroll.php?id="+$(this).attr('data-id'),'large')
        })
        $('.delete_data').click(function(){
            _conf("Are you sure to delete <b>"+$(this).attr('data-name')+"</b>\'s from payslip?",'delete_data',[$(this).attr('data-id')])
        })
        $('table td,table th').addClass('align-middle py-1')
        $('table').dataTable({
            columnDefs: [
                { orderable: false, targets:3 }
            ]
        })
    })
    function delete_data($id){
        $('#confirm_modal button').attr('disabled',true)
        $.ajax({
            url:'./../Actions.php?a=delete_payroll',
            method:'POST',
            data:{id:$id},
            dataType:'JSON',
            error:err=>{
                console.log(err)
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