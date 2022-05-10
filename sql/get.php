<?php
 
 function trad($key,$language){
    $pdo=connect();

    $sqlQuery = $pdo->prepare("select * from trad where trad_key=?");
    $sqlQuery->execute(array($key));
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result[0][$language]; 

 }

function get_user_by_pseudo_and_password($pseudo, $pass_crypt){
    $pdo=connect();

    $verify = $pdo->prepare("select * from user where us_login=? and us_password=? limit 1");
    $verify->execute(array($pseudo, $pass_crypt));
    $user = $verify->fetchAll();
    $pdo=null;
    return $user;
}

function get_all_consumption(){
    $pdo=connect();

    $verify = $pdo->prepare("select * from consumption");
    $verify->execute();
    $results = $verify->fetchAll();
    $pdo=null;
    return $results;
}

function get_all_customer_stock(){
    $pdo=connect();

    $verify = $pdo->prepare("select * from customer_stock");
    $verify->execute();
    $results = $verify->fetchAll();
    $pdo=null;
    return $results;
}

function get_all_default_machine_operator_link(){
    $pdo=connect();

    $verify = $pdo->prepare("select * from default_machine_operator_link");
    $verify->execute();
    $results = $verify->fetchAll();
    $pdo=null;
    return $results;
}

function get_all_exceptional_machine_operator_link (){
    $pdo=connect();

    $verify = $pdo->prepare("select * from exceptional_machine_operator_link ");
    $verify->execute();
    $results = $verify->fetchAll();
    $pdo=null;
    return $results;
}

function get_all_fiber(){
    $pdo=connect();

    $verify = $pdo->prepare("select * from fiber");
    $verify->execute();
    $results = $verify->fetchAll();
    $pdo=null;
    return $results;
}

function get_all_fiber_reference(){
    $pdo=connect();

    $verify = $pdo->prepare("select * from fiber_reference");
    $verify->execute();
    $results = $verify->fetchAll();
    $pdo=null;
    return $results;
}

function get_all_flow(){
    $pdo=connect();

    $verify = $pdo->prepare("select * from flow");
    $verify->execute();
    $results = $verify->fetchAll();
    $pdo=null;
    return $results;
}

function get_all_function(){
    $pdo=connect();

    $verify = $pdo->prepare("select * from function");
    $verify->execute();
    $results = $verify->fetchAll();
    $pdo=null;
    return $results;
}

function get_all_homepage(){
    $pdo=connect();

    $verify = $pdo->prepare("select * from homepage");
    $verify->execute();
    $results = $verify->fetchAll();
    $pdo=null;
    return $results;
}

function get_all_language(){
    $pdo=connect();

    $verify = $pdo->prepare("select * from language");
    $verify->execute();
    $results = $verify->fetchAll();
    $pdo=null;
    return $results;
}

function get_all_machine(){
    $pdo=connect();

    $verify = $pdo->prepare("select * from machine");
    $verify->execute();
    $results = $verify->fetchAll();
    $pdo=null;
    return $results;
}

function get_all_user(){
    $pdo=connect();

    $verify = $pdo->prepare("select * from user");
    $verify->execute();
    $results = $verify->fetchAll();
    $pdo=null;
    return $results;
}

function get_product_type_id_by_label($label){
    $pdo=connect();

    $verify = $pdo->prepare("select pt_id from product_type where pt_label=? limit 1");
    $verify->execute(array($label));
    $results = $verify->fetchAll();
    $pdo=null;
    
    return $results[0]['pt_id'];
}

function get_flow_id_by_product_id($product_id){
    $pdo=connect();

    $verify = $pdo->prepare("select fl_id from flow, link_product_flow where fl_id=lpf_flow_id and lpf_product_id=? and fl_status='enabled' limit 1");
    $verify->execute(array($product_id));
    $results = $verify->fetchAll();
    $pdo=null;
    return $results[0]['fl_id'];
}

function get_rubber_id_by_label($label){
    $pdo=connect();

    $verify = $pdo->prepare("select rb_id from rubber where rb_label=? and rb_status='enabled' limit 1");
    $verify->execute(array($label));
    $results = $verify->fetchAll();
    $pdo=null;
    
    return $results[0]['rb_id'];
}

function get_notch_id_by_label($label){
    $pdo=connect();

    $verify = $pdo->prepare("select nt_id from notch where nt_label=? limit 1");
    $verify->execute(array($label));
    $results = $verify->fetchAll();
    $pdo=null;
    
    return $results[0]['nt_id'];
}

function get_fiber_id_by_label($label){
    $pdo=connect();

    $verify = $pdo->prepare("select fb_id from fiber where fb_label=? and fb_status='enabled'limit 1");
    $verify->execute(array($label));
    $results = $verify->fetchAll();
    $pdo=null;
    
    return $results[0]['fb_id'];
}

function get_minimum_time_by_id_rubber_and_flow($id_rubber, $id_flow){
    $pdo=connect();

    $verify = $pdo->prepare("select mt_time from minimum_time where mt_rubber_id=? and mt_flow_id= ?");
    $verify->execute(array($id_rubber, $id_flow));
    $results = $verify->fetchAll();
    $pdo=null;
    
    return $results[0]['mt_time'];
}

function get_steps_by_flow_id($fl_id){
    $pdo=connect();

    $verify = $pdo->prepare("select * from flow where fl_id=?");
    $verify->execute(array($fl_id));
    $results = $verify->fetchAll();
    

    for($i=1;$i<=20;$i++){
        if($results[0]['fl_step_'.$i] != null){
            $verify = $pdo->prepare("select * from step where stp_id=?");
            $verify->execute(array($results[0]['fl_step_'.$i]));
            $step = $verify->fetchAll();
            $steps[$i]=$step[0];
        }
    }
    
    return $steps;
}

function get_mandrel_use_in_period($mandrel_diameter, $step_id, $beggining_date, $end_date){
    $pdo=connect();

    $verify = $pdo->prepare("select * from planning_task, piece where pt_piece_id=pc_id and pc_mandrel_diameter = ? and pt_step_id = ? and pt_planned_start_date > ? and pt_planned_start_date < ?");
    $verify->execute(array($mandrel_diameter, $step_id, $beggining_date, $end_date));
    $results = $verify->fetchAll();
    $pdo=null;
    
    return $results;
}

function get_mandrel_id_by_specifications($diameter, $form,$length,$sector){
    $pdo=connect();

    $verify = $pdo->prepare("select mn_id from mandrel where mn_diameter=? and mn_form=? and mn_length>? and mn_sector_id=?");
    $verify->execute(array($diameter, $form,$length,$sector));
    $results = $verify->fetchAll();
    $pdo=null;
    
    return $results;
}

function count_task_at_date_with_mandrel_id($date, $mandrel){
    $pdo=connect();

    $verify = $pdo->prepare("select count(*) from planning_task where DATEDIFF(pt_date,?) = 0 and pt_mandrel_id=?");
    $verify->execute(array($date, $mandrel));
    $results = $verify->fetchAll();
    $pdo=null;
    
    return $results[0]['count(*)'];
}

function select_operators_by_sector($sector){
    $pdo=connect();

    $verify = $pdo->prepare("select * from user, link_operator_sector where los_operator=us_id and los_sector=?");
    $verify->execute(array($sector));
    $results = $verify->fetchAll();
    $pdo=null;
    
    return $results;
}

function select_operators_by_id($id){
    $pdo=connect();

    $verify = $pdo->prepare("select * from user where us_id=?");
    $verify->execute(array($id));
    $results = $verify->fetchAll();
    $pdo=null;
    
    return $results[0];
}

function verification_operator_presence_at_date($id_operator, $date){
    $pdo=connect();

    $verify = $pdo->prepare("select * from operator_absences where oa_operator_id=? and (oa_start_hour_date like ? or oa_end_hour_date like ?)");
    $verify->execute(array($id_operator, $date.'%', $date.'%'));
    $results = $verify->fetchAll();
    $pdo=null;
    
    return $results;
}

function verification_operator_overtime_at_date($id_operator, $date){
    $pdo=connect();

    $verify = $pdo->prepare("select * from overtime_operator where oo_operator_id=? and oo_date = ?");
    $verify->execute(array($id_operator, $date));
    $results = $verify->fetchAll();
    $pdo=null;
    
    return $results;
}

function select_operator_default_time_by_id($id){
    $pdo=connect();

    $verify = $pdo->prepare("select * from operator_default_time where odt_user_id=?");
    $verify->execute(array($id));
    $results = $verify->fetchAll();
    $pdo=null;
    
    return $results;
}

function select_job_duration_by_date_and_user_id($date,$id_operator){
    $pdo=connect();

    $verify = $pdo->prepare("select pt_expected_duration from planning_task where pt_planned_start_date like ? and pt_operator_id=?");
    $verify->execute(array($date.'%',$id_operator));
    $results = $verify->fetchAll();
    $pdo=null;
    
    return $results;
}

function select_machines_by_sector_diameter_and_length($sector, $diameter, $length){
    $pdo=connect();

    $verify = $pdo->prepare("select * from machine where mc_sector_id=? and (mc_mandrel_diameter_max>=? or mc_mandrel_diameter_max is null) and (mc_max_length>=? or mc_max_length is null) and mc_status='enabled'");
    $verify->execute(array($sector, $diameter, $length));
    $results = $verify->fetchAll();
    $pdo=null;

    return $results;
}

function select_machines_by_id($id){
    $pdo=connect();

    $verify = $pdo->prepare("select * from machine where mc_id=?");
    $verify->execute(array($id));
    $results = $verify->fetchAll();
    $pdo=null;

    return $results[0];
}

function get_mandrel_by_id($id){
    $pdo=connect();

    $verify = $pdo->prepare("select * from mandrel where mn_id=?");
    $verify->execute(array($id));
    $results = $verify->fetchAll();
    $pdo=null;
    
    return $results[0];
}

function verification_machine_stop_at_date($id,$date){
    $pdo=connect();

    $verify = $pdo->prepare("select * from machine_stop where ms_machine_id=? and ( ms_start_hour_date like ? or ms_end_hour_date like ?)");
    $verify->execute(array($id, $date.'%',$date.'%'));
    $results = $verify->fetchAll();
    $pdo=null;
    
    return $results;
}

function select_job_duration_by_date_and_machine_id($date,$id_machine){
    $pdo=connect();

    $verify = $pdo->prepare("select pt_expected_duration from planning_task where pt_date like ? and pt_machine_id=?");
    $verify->execute(array($date.'%',$id_machine));
    $results = $verify->fetchAll();
    $pdo=null;
    
    return $results;
}

function select_all_temp_orders(){
    $pdo=connect();

    $verify = $pdo->prepare("select * from temp_order order by temp_millnet_id ASC");
    $verify->execute();
    $results = $verify->fetchAll();
    $pdo=null;
    
    return $results;
}

function select_all_orders_awainting(){
    $pdo=connect();

    $verify = $pdo->prepare("select * from orders where od_status = 'awaiting validation' order by od_millnet_id ASC");
    $verify->execute();
    $results = $verify->fetchAll();
    $pdo=null;
    
    return $results;
}