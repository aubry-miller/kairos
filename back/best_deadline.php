<?php
include('../sql/connect.php');
include('../sql/engine.php');
include('../sql/set.php');

if($_GET['submit'] == 'No'){
    include('../sql/delete.php');
    delete_temp_order($_GET['temp_id']);

    header("location:../new_order.php");
} else {
    include('../sql/get.php');
    //Il faut récupérer les informations de la commande temp avec son ID


    $session_key=random_int(0,999999999);



    
    $order=select_temp_orders_by_id($_GET['temp_id']);

    $millnet_id=$order['temp_millnet_id'].'-'.$order['temp_millnet_part_id'];
    // On va chercher le workflow qui correspond au type de produit
    $product_type_id=get_product_type_id_by_label($order['temp_product_type']);
    // On va chercher les step du workflow
    $steps=get_steps_by_flow_id($product_type_id);


    $step_date=date('Y-m-d');

    $proposition_plan=plan($session_key,$order,$millnet_id,$steps,$step_date);

    echo 'La fabrication est possible pour le '.$proposition_plan['deadline'];
    
}