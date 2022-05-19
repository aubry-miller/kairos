<?php
include('../sql/connect.php');
include('../sql/update.php');

if(isset($_GET['status']) && $_GET['status'] == 'on'){
    $_GET['status']='enabled';
} else {
    $_GET['status'] = 'disabled';
}

if(isset($_GET['csr_right']) && $_GET['csr_right'] == 'on'){
    $_GET['csr_right']=1;
} else {
    $_GET['csr_right'] = 0;
}

if(isset($_GET['task_plan_right']) && $_GET['task_plan_right'] == 'on'){
    $_GET['task_plan_right']=1;
} else {
    $_GET['task_plan_right'] = 0;
}

if(isset($_GET['global_plan_right']) && $_GET['global_plan_right'] == 'on'){
    $_GET['global_plan_right']=1;
} else {
    $_GET['global_plan_right'] = 0;
}

if(isset($_GET['mechanical_manager_right']) && $_GET['mechanical_manager_right'] == 'on'){
    $_GET['mechanical_manager_right']=1;
} else {
    $_GET['mechanical_manager_right'] = 0;
}

if(isset($_GET['site_management_right']) && $_GET['site_management_right'] == 'on'){
    $_GET['site_management_right']=1;
} else {
    $_GET['site_management_right'] = 0;
}

var_dump($_GET);

//update user
update_user($_GET['user_id'], $_GET['firstname'], $_GET['name'], $_GET['login'], $_GET['language'], $_GET['function'], $_GET['homepage'], $_GET['status']);

//update rights
update_rights($_GET['user_id'],$_GET['csr_right'],$_GET['task_plan_right'],$_GET['global_plan_right'],$_GET['mechanical_manager_right'],$_GET['site_management_right']);


header("location:../user_edit.php?user=".$_GET['user_id']);