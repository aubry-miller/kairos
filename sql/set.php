<?php

function new_order($millnet_id,$customer_number,$customer_name,$csr,$piece_number,$deadline,$status,$saving_date){
    $pdo=connect();
    
    $ins = $pdo->prepare("INSERT INTO `orders`(`od_millnet_id`, `od_customer_number`, `od_customer_name`, `od_csr_name`, `od_pieces_number`, `od_deadline`, `od_status`, `od_saving_date`) VALUES (?,?,?,?,?,?,?,?)");
    $ins->execute(array($millnet_id,$customer_number,$customer_name,$csr,$piece_number,$deadline,$status,$saving_date));
    $pdo=null;
}

function new_piece($pc_id, $millnet_id, $product_type_id, $rubber_id, $sleeve_length, $table_length, $sleeve_offset, $mandrel_diameter, $pc_notch, $notch_position, $developement, $fiber_id, $fiber_thickness, $chip, $cutback, $cutback_diameter, $flow_id){
    $pdo=connect();
    
    $ins = $pdo->prepare("INSERT INTO `piece`(`pc_id`, `pc_order_id`, `pc_product_type_id`, `pc_rubber_id`, `pc_sleeve_length`, `pc_table_length`, `pc_sleeve_offset`, `pc_mandrel_diameter`, `pc_notch_id`, `pc_notch_position`, `pc_developement`, `pc_fiber_id`, `pc_fiber_thickness`, `pc_chip`, `pc_cutback`, `pc_cutback_diameter`, `pc_flow_id`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $ins->execute(array($pc_id, $millnet_id, $product_type_id, $rubber_id, $sleeve_length, $table_length, $sleeve_offset, $mandrel_diameter, $pc_notch, $notch_position, $developement, $fiber_id, $fiber_thickness, $chip, $cutback, $cutback_diameter, $flow_id));
    $pdo=null;
}

function new_planning_task($piece,$step,$duration,$date,$machine,$operator,$mandrel){
    $pdo=connect();
    
    $ins = $pdo->prepare("INSERT INTO `planning_task`(`pt_piece_id`,`pt_step_id`,`pt_expected_duration`,`pt_date`,`pt_machine_id`,`pt_operator_id`,`pt_mandrel_id`) VALUES (?,?,?,?,?,?,?)");
    $ins->execute(array($piece,$step,$duration,$date,$machine,$operator,$mandrel));
    $pdo=null;
}

function new_image_profil($image, $id_user){
    $pdo=connect();
    
    $ins = $pdo->prepare("INSERT INTO profil_image(imgp_user, imgp_adresse, imgp_active) VALUES(?,?,?)");
    $ins->execute(array($id_user, $image, '0'));
    $pdo=null;
}