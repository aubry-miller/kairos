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

function get_product_type_label_by_id($id){
    $pdo=connect();

    $verify = $pdo->prepare("select pt_label from product_type where pt_id=? limit 1");
    $verify->execute(array($id));
    $results = $verify->fetchAll();
    $pdo=null;
    
    return $results[0]['pt_label'];
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

function select_temp_orders_by_id($id){
    $pdo=connect();

    $verify = $pdo->prepare("select * from temp_order where temp_id=?");
    $verify->execute(array($id));
    $results = $verify->fetchAll();
    $pdo=null;
    
    return $results;
}

function get_grinding_time($diam, $sleeve_length){
    $pdo=connect();
    $pi=pi();
    $dev=ceil($diam*$pi); // ceil => ounded up to the nearest whole number

    $test=0;
    while($test==0){
        $verify_dev = $pdo->prepare("select * from abacus_grinding_dev where agd_dev=?");
        $verify_dev->execute(array($dev));
        $dev_results = $verify_dev->fetchAll();
        if($dev_results ==[]){
            $dev++;
        } else {
            $test=1;
        }
    }
    
    $sleeve_length=ceil($sleeve_length/100)*100;
    
    $test2=0;
    while($test2==0){
        $verify_lz = $pdo->prepare("select * from abacus_table where ab_diam=?");
        $verify_lz->execute(array($sleeve_length));
        $lz_results = $verify_lz->fetchAll();
        if($lz_results ==[]){
            $sleeve_length=ceil($sleeve_length/100+1)*100;
        } else {
            $test2=1;
        }
    }
    
    $pdo=null;

    $ref=$dev_results[0]['agd_time'];
    $multiplicateur=$lz_results[0]['ab_multipl'];
    $constante=$dev_results[0]['agd_const'];


    $temps= round(($ref+($multiplicateur*$constante)),2);
    
    return $temps;
}

function get_lining_time($diam, $sleeve_length){
    $pdo=connect();
    
    $pi=pi();
    $dev=ceil($diam*$pi); // ceil => ounded up to the nearest whole number

    $test=0;
    while($test==0){
        $verify_dev = $pdo->prepare("select * from abacus_lining_dev where ald_dev=?");
        $verify_dev->execute(array($dev));
        $dev_results = $verify_dev->fetchAll();
        if($dev_results ==[]){
            $dev++;
        } else {
            $test=1;
        }
    }

    $sleeve_length=ceil($sleeve_length/100)*100;
    
    $test2=0;
    while($test2==0){
        $verify_lz = $pdo->prepare("select * from abacus_table where ab_diam=?");
        $verify_lz->execute(array($sleeve_length));
        $lz_results = $verify_lz->fetchAll();
        if($lz_results ==[]){
            $sleeve_length=ceil($sleeve_length/100+1)*100;
        } else {
            $test2=1;
        }
    }
    
    $pdo=null;

    $ref=$dev_results[0]['ald_time'];
    $multiplicateur=$lz_results[0]['ab_multipl'];
    $constante=$dev_results[0]['ald_const'];


    $temps= round((($ref/1.1)+($multiplicateur*$constante))*1.1,2);
    
    return $temps;
}

function select_all_product_type(){
    $pdo=connect();

    $verify = $pdo->prepare("select * from product_type order by pt_id ASC");
    $verify->execute();
    $results = $verify->fetchAll();
    $pdo=null;
    
    return $results;
}

function select_image_profil_by_id_user($id){
    $pdo=connect();

    $sqlQuery = $pdo->prepare("select imgp_adresse from profil_image where imgp_user=? and imgp_active='1'");
    $sqlQuery->execute(array($id));
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result; 

 }

 function select_all_photo_profil_by_id_user($id){
    $pdo=connect();

    $sqlQuery = $pdo->prepare("select * from profil_image where imgp_user=?");
    $sqlQuery->execute(array($id));
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result; 

 }

 function select_all_users_their_rights_and_their_function(){
    $pdo=connect();

    $sqlQuery = $pdo->prepare("select * from user, function,rights where fc_id=us_function_id and rg_user=us_id order by us_status , us_name");
    $sqlQuery->execute();
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result; 

 }

 function select_one_user_his_rights_and_his_function($id){
    $pdo=connect();

    $sqlQuery = $pdo->prepare("select * from user, function,rights where us_id=? and fc_id=us_function_id and rg_user=us_id order by us_name ASC");
    $sqlQuery->execute(array($id));
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result[0]; 

 }

 function select_all_functions(){
    $pdo=connect();

    $sqlQuery = $pdo->prepare("select * from function order by fc_label ASC");
    $sqlQuery->execute();
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result; 

 }

 function select_jobs_by_day_and_step_id($date,$step){
    $pdo=connect();

    $sqlQuery = $pdo->prepare("select * from planning_task, piece, rubber, user, orders, fiber where fb_id=pc_fiber_id and pc_order_id=od_millnet_id and pc_rubber_id=rb_id and pt_piece_id=pc_id and pt_operator_id=us_id and pt_date = ? and pt_step_id=? order by pt_planned_start_date ASC");
    $sqlQuery->execute(array($date,$step));
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result; 

 }

 function select_planning_task_by_plan_id($id){
    $pdo=connect();

    $sqlQuery = $pdo->prepare("select * from planning_task where pt_id=?");
    $sqlQuery->execute(array($id));
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result[0]; 

 }

function get_piece_informations_by_id($id){
    $pdo=connect();

    $sqlQuery = $pdo->prepare("select * from piece, orders, notch, rubber, fiber where pc_order_id=od_millnet_id and pc_notch_id= nt_id and pc_rubber_id=rb_id and pc_fiber_id=fb_id and pc_id=?");
    $sqlQuery->execute(array($id));
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result[0]; 

 }

 function select_orphelan_by_specifications($mandrel_diameter, $mandrel_from, $rubber_id, $fiber_id, $fiber_thickness, $developement, $sleeve_length){
    $pdo=connect();

    $sqlQuery = $pdo->prepare("select * from piece where pc_order_id = '00000000-001-000' and pc_status = 'orphan' and pc_mandrel_diameter=? and  pc_mandrel_form=? and  pc_rubber_id=? and  pc_fiber_id=? and pc_fiber_thickness=? and pc_developement>=? and pc_sleeve_length>=?");
    $sqlQuery->execute(array($mandrel_diameter, $mandrel_from, $rubber_id, $fiber_id, $fiber_thickness, $developement, $sleeve_length));
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result; 

 }

 function select_planning_task_by_piece_id($piece_id){
    $pdo=connect();

    $sqlQuery = $pdo->prepare("select * from planning_task, step where stp_id=pt_step_id and pt_piece_id=? order by pt_planned_start_date ASC");
    $sqlQuery->execute(array($piece_id));
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result; 

 }

 function select_planning_task_by_piece_id_and_step_id($piece_id,$step){
 $pdo=connect();

 $sqlQuery = $pdo->prepare("select * from planning_task where pt_piece_id=? and pt_step_id=?");
 $sqlQuery->execute(array($piece_id,$step));
 $result = $sqlQuery->fetchAll();
 $pdo=null;

 return $result; 

}


function select_planning_task_by_piece_id_and_status_is_not($id,$status){
    $pdo=connect();

    $sqlQuery = $pdo->prepare("select * from planning_task where pt_piece_id=? and pt_status!=?");
    $sqlQuery->execute(array($id,$status));
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result; 

 }

 function select_temp_order_pieces_number($millnet_id, $millnet_part_id){
    $pdo=connect();

    $sqlQuery = $pdo->prepare("select temp_pieces_number from temp_order where temp_millnet_id=? and temp_millnet_part_id=?");
    $sqlQuery->execute(array($millnet_id, $millnet_part_id));
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result[0]; 

 }

 function select_piece_id_by_order_id($order_id){
    $pdo=connect();

    $sqlQuery = $pdo->prepare("select * from piece where pc_order_id = ?");
    $sqlQuery->execute(array($order_id));
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result; 

 }

 function select_furthest_granding_plan_date(){
    $pdo=connect();

    $sqlQuery = $pdo->prepare("select max(pt_date) from planning_task where  pt_step_id = 4 and (pt_status='Planned' or pt_status='In Progress')");
    $sqlQuery->execute();
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result; 

 }

 function select_furthest_roughing_plan_date(){
    $pdo=connect();

    $sqlQuery = $pdo->prepare("select max(pt_date) from planning_task where  pt_step_id = 3 and (pt_status='Planned' or pt_status='In Progress')");
    $sqlQuery->execute();
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result; 

 }

 function select_furthest_fiber_plan_date(){
    $pdo=connect();

    $sqlQuery = $pdo->prepare("select max(pt_date) from planning_task where  pt_step_id = 1 and (pt_status='Planned' or pt_status='In Progress')");
    $sqlQuery->execute();
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result; 

 }

 function select_furthest_lining_plan_date(){
    $pdo=connect();

    $sqlQuery = $pdo->prepare("select max(pt_date) from planning_task where  (pt_step_id = 2 or pt_step_id = 6) and (pt_status='Planned' or pt_status='In Progress')");
    $sqlQuery->execute();
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result; 

 }

 function select_estimated_workload_at_date_by_step($date,$step){
    $pdo=connect();

    $sqlQuery = $pdo->prepare("select ((HOUR(pt_expected_duration)*60)+ MINUTE(pt_expected_duration)) AS nb_minute from planning_task where pt_date = ? and pt_step_id = ? and (pt_status='Planned' or pt_status='In Progress')");
    $sqlQuery->execute(array($date,$step));
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result; 

 }

 function select_over_time_at_date($date){
    $pdo=connect();

    $sqlQuery = $pdo->prepare("select * from overtime_operator, link_operator_sector, user where oo_operator_id=us_id and oo_operator_id=los_operator and oo_date = ?");
    $sqlQuery->execute(array($date));
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result; 

 }

 function select_operator_default_time_by_sector($sector){
    $pdo=connect();

    $sqlQuery = $pdo->prepare("select * from operator_default_time, link_operator_sector where odt_user_id=los_operator and los_sector = ?");
    $sqlQuery->execute(array($sector));
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result; 
 }

 function select_operator_absence_at_date_and_by_sector($date,$sector){
    $pdo=connect();
    $date_start=$date.' 23:59:59';
    $date_end=$date.' 00:00:00';

    $sqlQuery = $pdo->prepare("select * from operator_absences, link_operator_sector where oa_operator_id=los_operator and oa_start_hour_date <= ? and oa_end_hour_date>=? and los_sector = ?");
    $sqlQuery->execute(array($date_start, $date_end, $sector));
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result; 
 }

 function select_operators_absence_at_date($date){
    $pdo=connect();
    $date_start=$date.' 23:59:59';
    $date_end=$date.' 00:00:00';

    $sqlQuery = $pdo->prepare("select * from operator_absences, user where oa_operator_id=us_id and oa_start_hour_date <= ? and oa_end_hour_date>=?");
    $sqlQuery->execute(array($date_start, $date_end));
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result; 
 }

 function select_maintenance_at_date($date){
    $pdo=connect();
    $date_start=$date.' 23:59:59';
    $date_end=$date.' 00:00:00';

    $sqlQuery = $pdo->prepare("select * from machine_stop, machine where ms_machine_id=mc_id and ms_start_hour_date <= ? and ms_end_hour_date>=?");
    $sqlQuery->execute(array($date_start, $date_end));
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result; 
 }

 function select_late_orders($date){
    $pdo=connect();

    $sqlQuery = $pdo->prepare("select * from planning_task, step where pt_step_id=stp_id and pt_date <=? and (pt_status='In progress' or pt_status='Planned')");
    $sqlQuery->execute(array($date));
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result; 
 }

 function select_furthest_date_of_use_of_mandrel(){
    $pdo=connect();

    $sqlQuery = $pdo->prepare("select max(pt_date) from planning_task where  pt_mandrel_id is not null");
    $sqlQuery->execute();
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result; 

 }

 function select_all_mandrels(){
    $pdo=connect();

    $sqlQuery = $pdo->prepare("select * from mandrel, sector where mn_sector_id=sc_id order by mn_diameter, mn_id");
    $sqlQuery->execute();
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result; 

 }

 function get_mandrel_use_by_id_at_date($mandrel_id,$date){
    $pdo=connect();

    $verify = $pdo->prepare("select * from planning_task where pt_mandrel_id=? and pt_date=?");
    $verify->execute(array($mandrel_id,$date));
    $results = $verify->fetchAll();
    $pdo=null;
    
    return $results;
}

function get_mandrel_use_by_id_after_date($mandrel_id,$date){
    $pdo=connect();

    $verify = $pdo->prepare("select * from planning_task where pt_mandrel_id=? and pt_date>=? order by pt_date ASC");
    $verify->execute(array($mandrel_id,$date));
    $results = $verify->fetchAll();
    $pdo=null;
    
    return $results;
}

function select_furthest_date_of_use_of_mandrel_by_id($mandrel_id){
    $pdo=connect();

    $sqlQuery = $pdo->prepare("select max(pt_date) from planning_task where pt_mandrel_id = ?");
    $sqlQuery->execute(array($mandrel_id));
    $result = $sqlQuery->fetchAll();
    $pdo=null;

    return $result; 

 }