<?php
include('../sql/connect.php');
include('../sql/engine.php');
include('../sql/set.php');

if($_GET['submit'] == 'Delete'){
    include('../sql/delete.php');
    delete_temp_order($_GET['temp_id']);

    header("location:../new_order.php");
} else {
    include('../sql/get.php');
    // We need to get the information of the temp command with its ID


    $session_key=random_int(0,999999999);



    
    $order=select_temp_orders_by_id($_GET['temp_id']);
    if($order != []){
        $order=$order[0];
    }

    $millnet_id=$order['temp_millnet_id'].'-'.$order['temp_millnet_part_id'];
    // We will look for the workflow that corresponds to the type of product
    $product_type_id=get_product_type_id_by_label($order['temp_product_type']);
    // We will search for the workflow steps
    $steps=get_steps_by_flow_id($product_type_id);


    $step_date=date('Y-m-d');

    $proposition_plan=plan($session_key,$order,$millnet_id,$steps,$step_date);

    echo 'Manufacturing is possible for the '.$proposition_plan['deadline'];
    
}