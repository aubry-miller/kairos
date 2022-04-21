<?php

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

    $verify = $pdo->prepare("select * from planning_task, piece where pt_piece_id=pc_id and pc_mandrel_diameter = ?
    and pt_step_id = ? and pt_planned_start_date > ? and pt_planned_start_date < ?");
    $verify->execute(array($mandrel_diameter, $step_id, $beggining_date, $end_date));
    $results = $verify->fetchAll();
    $pdo=null;
    
    return $results;
}