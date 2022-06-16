<?php

function update_piece_statut($piece_id, $status){
    $pdo=connect();

    $upd = $pdo->prepare("update piece SET pc_status=? WHERE pc_id=?");
    $upd->execute(array($status,$piece_id));
    $pdo=null;
}


function update_order_statut($order_id, $status){
    $pdo=connect();

    $upd = $pdo->prepare("update orders SET od_status=? WHERE od_millnet_id=?");
    $upd->execute(array($status,$order_id));
    $pdo=null;
}

function update_language($id_user, $language){
    $pdo=connect();

    $upd = $pdo->prepare("update user SET us_language=? WHERE us_id=?");
    $upd->execute(array($language, $id_user));
    $pdo=null;
}

function change_image_profil($id_user,$image){
    $pdo=connect();

    $upd = $pdo->prepare("update profil_image SET imgp_active='0' WHERE imgp_user=?");
    $upd->execute(array($id_user));

    $upd = $pdo->prepare("update profil_image SET imgp_active='1' WHERE imgp_user=? and imgp_id=?");
    $upd->execute(array($id_user,$image));
    $pdo=null;
}

function update_mode_affichage($id_user, $mode){
    $pdo=connect();

    $upd = $pdo->prepare("update user SET us_screen_mode=? WHERE us_id=?");
    $upd->execute(array($mode, $id_user));
    $pdo=null;
}

function update_user($user_id, $firstname, $name, $login, $language, $function, $homepage, $status){
    $pdo=connect();

    $upd = $pdo->prepare("update user SET us_firstname=?, us_name=?, us_login=?, us_language=?, us_function_id=?, us_homepage=?, us_status=? WHERE us_id=?");
    $upd->execute(array($firstname, $name, $login, $language, $function, $homepage, $status, $user_id));
    $pdo=null;
}

function update_rights($user_id ,$rg_csr_right, $rg_task_plan_right, $rg_global_plan_right, $rg_mechanical_manager_right, $rg_site_management_right){
    $pdo=connect();

    $upd = $pdo->prepare("update rights SET rg_csr_right=?, rg_task_plan_right=?, rg_global_plan_right=?, rg_mechanical_manager_right=?, rg_site_management_right=? WHERE rg_user=?");
    $upd->execute(array($rg_csr_right, $rg_task_plan_right, $rg_global_plan_right, $rg_mechanical_manager_right, $rg_site_management_right, $user_id));
    $pdo=null;
}

function update_task_during($plan_id,$during,$status){
    $pdo=connect();

    $upd = $pdo->prepare("update planning_task SET pt_real_time=?, pt_status=? WHERE pt_id=?");
    $upd->execute(array($during, $status,$plan_id));
    $pdo=null;
}

function update_task_during_and_started_date($plan_id,$during,$status,$date){
    $pdo=connect();

    $upd = $pdo->prepare("update planning_task SET pt_real_time=?, pt_status=?, pt_real_start_date=? WHERE pt_id=?");
    $upd->execute(array($during, $status, $date, $plan_id));
    $pdo=null;
}

function update_task_during_and_finished_date($plan_id,$during,$status,$date){
    $pdo=connect();

    $upd = $pdo->prepare("update planning_task SET pt_real_time=?, pt_status=?, pt_real_end_date=? WHERE pt_id=?");
    $upd->execute(array($during, $status, $date, $plan_id));
    $pdo=null;
}

function add_real_material_consumption_to_planning_task_by_id($time, $id){
    $pdo=connect();

    $upd = $pdo->prepare("update planning_task SET pt_real_material_consumption=? WHERE pt_id=?");
    $upd->execute(array($time, $id));
    $pdo=null;
}

function attribute_orphan_to_new_job($pc_id,$millnet_id,$status){
    $pdo=connect();

    $upd = $pdo->prepare("update piece SET pc_order_id=?, pc_status=? WHERE pc_id=?");
    $upd->execute(array($millnet_id,$status,$pc_id));
    $pdo=null;
}