<?php
require_once("./../DBConnection.php");
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM `tax_table_list` where tax_id = '{$_GET['id']}'");
    foreach($qry->fetch_array() as $k => $v){
        $$k = $v;
    }
}
?>
<div class="container-fluid">
    <form action="" id="tax-form">
        <input type="hidden" name="id" value="<?php echo isset($tax_id) ? $tax_id : '' ?>">
        <div class="col-12">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="payroll_type" class="control-label">Payroll Type</label>
                        <select name="payroll_type" id="payroll_type" class="form-select form-select-sm rounded-0">
                            <option value="1" <?php echo (isset($payroll_type) && $payroll_type == 1 ) ? 'selected' : '' ?>>Monthly</option>
                            <option value="2" <?php echo (isset($payroll_type) && $payroll_type == 2 ) ? 'selected' : '' ?>>Semi-Monthly</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="form-group">
                        <label for="" class="text-muted"><b>Compensation Range</b></label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="range_from" class="control-label">From</label>
                    <input type="number" min="0" max="9999999999" maxlength="10" name="range_from" id="range_from" class="form-control form-control-sm rounded 0 text-end" value="<?php echo isset($range_from) ? $range_from : 0 ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="range_to" class="control-label">To</label>
                    <input type="number" min="0" max="9999999999" maxlength="10" name="range_to" id="range_to" class="form-control form-control-sm rounded 0 text-end" value="<?php echo isset($range_to) ? $range_to : 0 ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="form-group">
                        <label for="" class="text-muted"><b>Prescribed Withholding Tax</b></label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="fixed_tax" class="control-label">Fixed</label>
                    <input type="number" min="0" max="9999999999" maxlength="10" name="fixed_tax" id="fixed_tax" class="form-control form-control-sm rounded 0 text-end" value="<?php echo isset($fixed_tax) ? $fixed_tax : 0 ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="percentage_over" class="control-label">+ Percentage for Above Minimum</label>
                    <input type="number" min="0" max="100" maxlength="3" name="percentage_over" id="percentage_over" class="form-control form-control-sm rounded 0 text-end" value="<?php echo isset($percentage_over) ? $percentage_over : 0 ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="effective_date" class="control-label">Effective Date</label>
                    <input type="date" name="effective_date" id="effective_date" class="form-control form-control-sm rounded 0 text-end" value="<?php echo isset($effective_date) ? $effective_date : 0 ?>" required>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $(function(){
        $('#tax-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            $('#uni_modal button').attr('disabled',true)
            $('#uni_modal button[type="submit"]').text('submitting form...')
            $.ajax({
                url:'./../Actions.php?a=save_tax',
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error:err=>{
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("An error occurred.")
                    _this.prepend(_el)
                    _el.show('slow')
                     $('#uni_modal button').attr('disabled',false)
                     $('#uni_modal button[type="submit"]').text('Save')
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        _el.addClass('alert alert-success')
                        $('#uni_modal').on('hide.bs.modal',function(){
                            location.reload()
                        })
                        if("<?php echo isset($employee_id) ?>" != 1)
                        _this.get(0).reset();
                    }else{
                        _el.addClass('alert alert-danger')
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                     $('#uni_modal button').attr('disabled',false)
                     $('#uni_modal button[type="submit"]').text('Save')
                }
            })
        })
    })
</script>