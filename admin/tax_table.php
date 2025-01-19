
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
        <h3 class="card-title">Tax Table</h3>
        <div class="card-tools align-middle">
            <a class="btn btn-dark btn-sm py-1 rounded-0" href="javascript:void(0)" id="create_new">Add New</a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-hover table-striped table-bordered">
            <colgroup>
                <col width="5%">
                <col width="20%">
                <col width="20%">
                <col width="20%">
                <col width="20%">
                <col width="15%">
            </colgroup>
            <thead>
                <tr>
                    <th class="text-center p-0">#</th>
                    <th class="text-center p-0">Payroll Type</th>
                    <th class="text-center p-0">Compensation Range</th>
                    <th class="text-center p-0">Prescribed Withholding Tax</th>
                    <th class="text-center p-0">Effective Date</th>
                    <th class="text-center p-0">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sql = "SELECT * FROM tax_table_list order by unix_timestamp(effective_date) desc, range_from asc,range_to asc";
                $qry = $conn->query($sql);
                $i = 1;
                    while($row = $qry->fetch_assoc()):
                ?>
                <tr>
                    <td class="text-center p-0"><?php echo $i++; ?></td>
                    <td class="py-0 px-1"><?php echo $row['payroll_type'] == 1 ? "Monthly" : "Semi-Monthly" ?></td>
                    <td class="py-0 px-1 text-end"><?php echo number_format($row['range_from']). ' - '. number_format($row['range_to']) ?></td>
                    <td class="py-0 px-1 text-end"><?php echo number_format($row['fixed_tax']). ' + '. $row['percentage_over']."% above ".$row['range_from'] ?></td>
                    <td class="py-0 px-1 text-end"><?php echo date("Y-m-d",strtotime($row['effective_date'])) ?></td>
                    <td class="text-center py-0 px-1">
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle btn-sm rounded-0 py-0" data-bs-toggle="dropdown" aria-expanded="false">
                            Action
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <li><a class="dropdown-item edit_data" data-id = '<?php echo $row['tax_id'] ?>' href="javascript:void(0)">Edit</a></li>
                            <li><a class="dropdown-item delete_data" data-id = '<?php echo $row['tax_id'] ?>' href="javascript:void(0)">Delete</a></li>
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
        $('#create_new').click(function(){
            uni_modal('Add Tax Bracket',"manage_tax.php",'mid-large')
        })
        $('.edit_data').click(function(){
            uni_modal('Edit tax Details',"manage_tax.php?id="+$(this).attr('data-id'),'mid-large')
        })
        $('.view_data').click(function(){
            uni_modal('tax Details',"view_tax.php?id="+$(this).attr('data-id'),'large')
        })
        $('.delete_data').click(function(){
            _conf("Are you sure to delete this item from tax List?",'delete_data',[$(this).attr('data-id')])
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
            url:'./../Actions.php?a=delete_tax',
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