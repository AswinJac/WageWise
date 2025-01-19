<?php 
session_start();
require_once('DBConnection.php');

Class Actions extends DBConnection{
    function __construct(){
        parent::__construct();
    }
    function __destruct(){
        parent::__destruct();
    }
    function login(){
        extract($_POST);
        $sql = "SELECT * FROM admin_list where username = '{$username}' and `password` = '".md5($password)."' ";
        @$qry = $this->db->query($sql)->fetch_array();
        if(!$qry){
            $resp['status'] = "failed";
            $resp['msg'] = "Invalid username or password.";
        }else{
            $resp['status'] = "success";
            $resp['msg'] = "Login successfully.";
            foreach($qry as $k => $v){
                if(!is_numeric($k))
                $_SESSION[$k] = $v;
            }
        }
        return json_encode($resp);
    }
    function logout(){
        session_destroy();
        header("location:./admin");
    }
    function save_admin(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k => $v){
        if(!in_array($k,array('id'))){
            if(!empty($id)){
                if(!empty($data)) $data .= ",";
                $data .= " `{$k}` = '{$v}' ";
                }else{
                    $cols[] = $k;
                    $values[] = "'{$v}'";
                }
            }
        }
        if(empty($id)){
            $cols[] = 'password';
            $values[] = "'".md5($username)."'";
        }
        if(isset($cols) && isset($values)){
            $data = "(".implode(',',$cols).") VALUES (".implode(',',$values).")";
        }
        

       
        @$check= $this->db->query("SELECT count(admin_id) as `count` FROM admin_list where `username` = '{$username}' ".($id > 0 ? " and admin_id != '{$id}' " : ""))->fetch_array()['count'];
        if(@$check> 0){
            $resp['status'] = 'failed';
            $resp['msg'] = "Username already exists.";
        }else{
            if(empty($id)){
                $sql = "INSERT INTO `admin_list` {$data}";
            }else{
                $sql = "UPDATE `admin_list` set {$data} where admin_id = '{$id}'";
            }
            @$save = $this->db->query($sql);
            if($save){
                $resp['status'] = 'success';
                if(empty($id))
                $resp['msg'] = 'New User successfully saved.';
                else
                $resp['msg'] = 'User Details successfully updated.';
            }else{
                $resp['status'] = 'failed';
                $resp['msg'] = 'Saving User Details Failed. Error: '.$this->error;
                $resp['sql'] =$sql;
            }
        }
        return json_encode($resp);
    }
    function delete_admin(){
        extract($_POST);

        @$delete = $this->db->query("DELETE FROM `admin_list` where rowid = '{$id}'");
        if($delete){
            $resp['status']='success';
            $_SESSION['flashdata']['type'] = 'success';
            $_SESSION['flashdata']['msg'] = 'User successfully deleted.';
        }else{
            $resp['status']='failed';
            $resp['error']=$this->error;
        }
        return json_encode($resp);
    }
    function update_credentials(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k => $v){
            if(!in_array($k,array('id','old_password')) && !empty($v)){
                if(!empty($data)) $data .= ",";
                if($k == 'password') $v = md5($v);
                $data .= " `{$k}` = '{$v}' ";
            }
        }
        if(!empty($password) && md5($old_password) != $_SESSION['password']){
            $resp['status'] = 'failed';
            $resp['msg'] = "Old password is incorrect.";
        }else{
            $sql = "UPDATE `admin_list` set {$data} where admin_id = '{$_SESSION['admin_id']}'";
            @$save = $this->db->query($sql);
            if($save){
                $resp['status'] = 'success';
                $_SESSION['flashdata']['type'] = 'success';
                $_SESSION['flashdata']['msg'] = 'Credential successfully updated.';
                foreach($_POST as $k => $v){
                    if(!in_array($k,array('id','old_password')) && !empty($v)){
                        if(!empty($data)) $data .= ",";
                        if($k == 'password') $v = md5($v);
                        $_SESSION[$k] = $v;
                    }
                }
            }else{
                $resp['status'] = 'failed';
                $resp['msg'] = 'Updating Credentials Failed. Error: '.$this->error;
                $resp['sql'] =$sql;
            }
        }
        return json_encode($resp);
    }
    function save_settings(){
        extract($_POST);
        $update = file_put_contents('./about.html',htmlentities($about));
        if($update){
            $resp['status'] = "success";
            $resp['msg'] = "Settings successfully updated.";
        }else{
            $resp['status'] = "failed";
            $resp['msg'] = "Failed to update settings.";
        }
        return json_encode($resp);
    }
    function save_department(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k => $v){
            if(!in_array($k,array('id'))){
                $v = trim($v);
                $v = $this->db->real_escape_string($v);
                $$k = $v;
            if(empty($id)){
                $cols[] = "`{$k}`";
                $vals[] = "'{$v}'";
            }else{
                if(!empty($data)) $data .= ", ";
                $data .= " `{$k}` = '{$v}' ";
            }
            }
        }
        if(isset($cols) && isset($vals)){
            $cols_join = implode(",",$cols);
            $vals_join = implode(",",$vals);
        }
        
        if(empty($id)){
            $sql = "INSERT INTO `department_list` ({$cols_join}) VALUES ($vals_join)";
        }else{
            $sql = "UPDATE `department_list` set {$data} where department_id = '{$id}'";
        }

        $check = $this->db->query("SELECT count(department_id) as `count` FROM `department_list` where `name` = '{$name}' ".($id > 0 ? " and department_id != '{$id}'" : ""))->fetch_array()['count'];
        if($check >0){
            $resp['status']="failed";
            $resp['msg'] = "Department name is already exists.";
        }else{
            @$save = $this->db->query($sql);
            if($save){
                $resp['status']="success";
                if(empty($id))
                    $resp['msg'] = "Department successfully saved.";
                else
                    $resp['msg'] = "Department successfully updated.";
            }else{
                $resp['status']="failed";
                if(empty($id))
                    $resp['msg'] = "Saving New Department Failed.";
                else
                    $resp['msg'] = "Updating Department Failed.";
                    $resp['error']=$this->error;
            }
        }

        return json_encode($resp);
    }
    function delete_department(){
        extract($_POST);

        @$delete = $this->db->query("DELETE FROM `department_list` where department_id = '{$id}'");
        if($delete){
            $resp['status']='success';
            $_SESSION['flashdata']['type'] = 'success';
            $_SESSION['flashdata']['msg'] = 'Department successfully deleted.';
        }else{
            $resp['status']='failed';
            $resp['error']=$this->error;
        }
        return json_encode($resp);
    }
    function update_stat_dept(){
        extract($_POST);

        $update = $this->db->query("UPDATE department_list set status = '{$status}' where department_id = '{$id}'");
        if($update){
            $resp['status'] = 'success';
            $resp['msg'] = 'Department\'s status successfully updated';
            $_SESSION['flashdata']['type'] = $resp['status'];
            $_SESSION['flashdata']['msg'] = $resp['msg'];
        }else{
            $resp['status'] = 'failed';
            $resp['msg'] = 'Department\'s status has failed to update.';
            $resp['error'] = $this->error;
        }
        return json_encode($resp);
    }
    function save_designation(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k => $v){
            if(!in_array($k,array('id'))){
                $v = trim($v);
                $v = $this->db->real_escape_string($v);
                $$k = $v;
            if(empty($id)){
                $cols[] = "`{$k}`";
                $vals[] = "'{$v}'";
            }else{
                if(!empty($data)) $data .= ", ";
                $data .= " `{$k}` = '{$v}' ";
            }
            }
        }
        if(isset($cols) && isset($vals)){
            $cols_join = implode(",",$cols);
            $vals_join = implode(",",$vals);
        }
        
        if(empty($id)){
            $sql = "INSERT INTO `designation_list` ({$cols_join}) VALUES ($vals_join)";
        }else{
            $sql = "UPDATE `designation_list` set {$data} where designation_id = '{$id}'";
        }

        $check = $this->db->query("SELECT count(designation_id) as `count` FROM `designation_list` where `name` = '{$name}' ".($id > 0 ? " and designation_id != '{$id}'" : ""))->fetch_array()['count'];
        if($check >0){
            $resp['status']="failed";
            $resp['msg'] = "Designation name is already exists.";
        }else{
            @$save = $this->db->query($sql);
            if($save){
                $resp['status']="success";
                if(empty($id))
                    $resp['msg'] = "Designation successfully saved.";
                else
                    $resp['msg'] = "Designation successfully updated.";
            }else{
                $resp['status']="failed";
                if(empty($id))
                    $resp['msg'] = "Saving New Designation Failed.";
                else
                    $resp['msg'] = "Updating Designation Failed.";
                    $resp['error']=$this->error;
            }
        }

        return json_encode($resp);
    }
    function delete_designation(){
        extract($_POST);

        @$delete = $this->db->query("DELETE FROM `designation_list` where designation_id = '{$id}'");
        if($delete){
            $resp['status']='success';
            $_SESSION['flashdata']['type'] = 'success';
            $_SESSION['flashdata']['msg'] = 'Designation successfully deleted.';
        }else{
            $resp['status']='failed';
            $resp['error']=$this->error;
        }
        return json_encode($resp);
    }
    function update_stat_desg(){
        extract($_POST);

        $update = $this->db->query("UPDATE designation_list set status = '{$status}' where designation_id = '{$id}'");
        if($update){
            $resp['status'] = 'success';
            $resp['msg'] = 'Designation\'s status successfully updated';
            $_SESSION['flashdata']['type'] = $resp['status'];
            $_SESSION['flashdata']['msg'] = $resp['msg'];
        }else{
            $resp['status'] = 'failed';
            $resp['msg'] = 'Designation\'s status has failed to update.';
            $resp['error'] = $this->error;
        }
        return json_encode($resp);
    }
    function save_employee(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k => $v){
            if(!in_array($k,array('id'))){
                $v = trim($v);
                $v = $this->db->real_escape_string($v);
                $$k = $v;
            if(empty($id)){
                $cols[] = "`{$k}`";
                $vals[] = "'{$v}'";
            }else{
                if(!empty($data)) $data .= ", ";
                $data .= " `{$k}` = '{$v}' ";
            }
            }
        }
        if(isset($cols) && isset($vals)){
            $cols_join = implode(",",$cols);
            $vals_join = implode(",",$vals);
        }
        
        if(empty($id)){
            $sql = "INSERT INTO `employee_list` ({$cols_join}) VALUES ($vals_join)";
        }else{
            $sql = "UPDATE `employee_list` set {$data} where employee_id = '{$id}'";
        }

        $check = $this->db->query("SELECT count(employee_id) as `count` FROM `employee_list` where `code` = '{$code}' ".($id > 0 ? " and employee_id != '{$id}'" : ""))->fetch_array()['count'];
        if($check >0){
            $resp['status']="failed";
            $resp['msg'] = "Employee Code is already exists.";
        }else{
            @$save = $this->db->query($sql);
            if($save){
                $resp['status']="success";
                if(empty($id)){
                    $resp['msg'] = "Employee successfully saved.";
                    $eid = $this->db->insert_id;
                }else{
                    $resp['msg'] = "Employee successfully updated.";
                    $eid = $id;
                }
                if(isset($_FILES['photo']['tmp_name']) && !empty($_FILES['photo']['tmp_name'])){
                    $upload = $_FILES['photo']['tmp_name'];
                    $type= mime_content_type($upload);
                    $dir = __DIR__."/upload/users/";
                    if(!is_dir($dir)){
                        mkdir($dir);
                    }
                    if(!in_array($type,array('image/png','image/jpeg'))){
                        $resp['msg'] .= " But image was failed to upload due to invalid file type.";
                    }else{
                       $gdImg = ($type == 'image/png')? imagecreatefrompng($upload) : imagecreatefromjpeg($upload);
                       if($gdImg){
                           imagepng($gdImg,$dir.'/'.$eid.'.png');
                           imagedestroy($gdImg);
                       }else{
                        $resp['msg'] .= " But image was failed to upload due to invalid unknown reason.";
                       }
                    }
                }
            }else{
                $resp['status']="failed";
                if(empty($id))
                    $resp['msg'] = "Saving New Employee Failed.";
                else
                    $resp['msg'] = "Updating Employee Failed.";
                    $resp['error']=$this->error;
            }
        }

        return json_encode($resp);
    }
    function delete_employee(){
        extract($_POST);

        @$delete = $this->db->query("DELETE FROM `employee_list` where employee_id = '{$id}'");
        if($delete){
            $resp['status']='success';
            $_SESSION['flashdata']['type'] = 'success';
            $_SESSION['flashdata']['msg'] = 'Employee successfully deleted.';
        }else{
            $resp['status']='failed';
            $resp['error']=$this->error;
        }
        return json_encode($resp);
    }
    function save_tax(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k => $v){
            if(!in_array($k,array('id'))){
                $v = trim($v);
                $v = $this->db->real_escape_string($v);
                $$k = $v;
            if(empty($id)){
                $cols[] = "`{$k}`";
                $vals[] = "'{$v}'";
            }else{
                if(!empty($data)) $data .= ", ";
                $data .= " `{$k}` = '{$v}' ";
            }
            }
        }
        if(isset($cols) && isset($vals)){
            $cols_join = implode(",",$cols);
            $vals_join = implode(",",$vals);
        }
        
        if(empty($id)){
            $sql = "INSERT INTO `tax_table_list` ({$cols_join}) VALUES ($vals_join)";
        }else{
            $sql = "UPDATE `tax_table_list` set {$data} where tax_id = '{$id}'";
        }

        
        @$save = $this->db->query($sql);
        if($save){
            $resp['status']="success";
            if(empty($id)){
                $resp['msg'] = "Tax Bracket successfully saved.";
            }else{
                $resp['msg'] = "Tax Bracket successfully updated.";
            }
        }else{
            $resp['status']="failed";
            if(empty($id))
                $resp['msg'] = "Saving New Tax Bracket Failed.";
            else
                $resp['msg'] = "Updating Tax Bracket Failed.";
                $resp['error']=$this->error;
        }

        return json_encode($resp);
    }
    function delete_tax(){
        extract($_POST);

        @$delete = $this->db->query("DELETE FROM `tax_table_list` where tax_id = '{$id}'");
        if($delete){
            $resp['status']='success';
            $_SESSION['flashdata']['type'] = 'success';
            $_SESSION['flashdata']['msg'] = 'Tax Bracket successfully deleted.';
        }else{
            $resp['status']='failed';
            $resp['error']=$this->error;
        }
        return json_encode($resp);
    }
    function get_rate(){
        extract($_POST);
        $sql = "SELECT monthly_salary FROM `employee_list` where employee_id = '{$employee_id}' ";
        $result = $this->db->query($sql)->fetch_array();
        if($result){
            $resp['status'] ='success';
            $salary = $result['monthly_salary'];
            $resp['monthly'] = $salary;
            $resp['daily'] = round($salary / 22,3);
            $resp['hourly'] = round($resp['daily'] / 8,3);
            $resp['per_minute'] = round($resp['hourly'] / 60,3);
        }else{
            $resp['status']='failed';
            $resp['sql']=$sql;
            $resp['error']=$this->error;
        }
        return json_encode($resp);
    }
    function get_tax(){
        extract($_POST);

        $sql = "SELECT fixed_tax,percentage_over FROM `tax_table_list` where '{$amount}' BETWEEN range_from and range_to and payroll_type = '{$ptype}' order by unix_timestamp(effective_date) desc limit 1";
        $result = $this->db->query($sql)->fetch_array();
        if($result){
            $tax = (float)$result['fixed_tax'];
            if($result['percentage_over'] > 0){
                $tax = $tax + ($amount * ($result['percentage_over'] / 100));
            }
            $resp['status'] = 'success';
            $resp['tax'] = $tax;
            $resp['sql']=$sql;
        }else{
            $resp['status'] = 'failed';
            $resp['sql']=$sql;
            $resp['error']=$this->error;
        }
        return json_encode($resp);
    }
    function save_payroll(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k => $v){
            if(is_array($_POST[$k]))
            continue;
            if(!in_array($k,array('id'))){
                $v = trim($v);
                $v = $this->db->real_escape_string($v);
                $$k = $v;
            if(empty($id)){
                $cols[] = "`{$k}`";
                $vals[] = "'{$v}'";
            }else{
                if(!empty($data)) $data .= ", ";
                $data .= " `{$k}` = '{$v}' ";
            }
            }
        }
        if(isset($cols) && isset($vals)){
            $cols_join = implode(",",$cols);
            $vals_join = implode(",",$vals);
        }
        
        if(empty($id)){
            $sql = "INSERT INTO `payroll_list` ({$cols_join}) VALUES ($vals_join)";
        }else{
            $sql = "UPDATE `payroll_list` set {$data} where payroll_id = '{$id}'";
        }
        
        @$save = $this->db->query($sql);
        if($save){
            $resp['status']="success";
            if(empty($id)){
                $resp['msg'] = "Employee\'s Payroll successfully saved.";
                $payroll_id = $this->db->insert_id;
            }else{
                $resp['msg'] = "Employee\'s Payroll successfully updated.";
                $payroll_id = $id;
            }
            $resp['id'] = $payroll_id;
            $this->db->query("DELETE FROM earning_list where payroll_id = '{$payroll_id}'");
            $this->db->query("DELETE FROM deduction_list where payroll_id = '{$payroll_id}'");
            if(isset($earning_amount)){
                $data = "";
                foreach($earning_amount as $k => $v){
                    if(!empty($data)) $data.=", ";
                    $data .= "('{$payroll_id}','{$earning_name[$k]}','{$v}','{$earning_tax[$k]}')";
                }
                if(!empty($data)){
                    $this->db->query("INSERT INTO earning_list (`payroll_id`,`name`,`amount`,`taxable`) VALUES {$data}");
                }
            }
            if(isset($deduction_amount)){
                $data = "";
                foreach($deduction_amount as $k => $v){
                    if(!empty($data)) $data.=", ";
                    $data .= "('{$payroll_id}','{$deduction_name[$k]}','{$v}')";
                }
                if(!empty($data)){
                    $this->db->query("INSERT INTO deduction_list (`payroll_id`,`name`,`amount`) VALUES {$data}");
                }
            }
        }else{
            $resp['status']="failed";
            if(empty($id))
                $resp['msg'] = "Saving New Employee\'s Payroll Failed.";
            else
                $resp['msg'] = "Updating Employee\'s Payroll Failed.";
                $resp['error']=$this->error;
        }

        return json_encode($resp);
    }
    function delete_payroll(){
        extract($_POST);

        @$delete = $this->db->query("DELETE FROM `payroll_list` where payroll_id = '{$id}'");
        if($delete){
            $resp['status']='success';
            $_SESSION['flashdata']['type'] = 'success';
            $_SESSION['flashdata']['msg'] = 'Employee\'s Payroll successfully deleted.';
        }else{
            $resp['status']='failed';
            $resp['error']=$this->error;
        }
        return json_encode($resp);
    }
}
$a = isset($_GET['a']) ?$_GET['a'] : '';
$action = new Actions();
switch($a){
    case 'login':
        echo $action->login();
    break;
    case 'logout':
        echo $action->logout();
    break;
    case 'save_admin':
        echo $action->save_admin();
    break;
    case 'delete_admin':
        echo $action->delete_admin();
    break;
    case 'update_credentials':
        echo $action->update_credentials();
    break;
    case 'save_settings':
        echo $action->save_settings();
    break;
    case 'save_department':
        echo $action->save_department();
    break;
    case 'delete_department':
        echo $action->delete_department();
    break;
    case 'update_stat_dept':
        echo $action->update_stat_dept();
    break;
    case 'save_designation':
        echo $action->save_designation();
    break;
    case 'delete_designation':
        echo $action->delete_designation();
    break;
    case 'update_stat_desg':
        echo $action->update_stat_desg();
    break;
    case 'save_employee':
        echo $action->save_employee();
    break;
    case 'delete_employee':
        echo $action->delete_employee();
    break;
    case 'save_tax':
        echo $action->save_tax();
    break;
    case 'delete_tax':
        echo $action->delete_tax();
    break;
    case 'get_rate':
        echo $action->get_rate();
    break;
    case 'get_tax':
        echo $action->get_tax();
    break;
    case 'save_payroll':
        echo $action->save_payroll();
    break;
    case 'delete_payroll':
        echo $action->delete_payroll();
    break;
    default:
    // default action here
    break;
}