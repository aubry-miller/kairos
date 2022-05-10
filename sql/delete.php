<?php

function delete_planning_task($piece_id){
    $pdo=connect();
    
    $ins = $pdo->prepare("DELETE FROM `planning_task` WHERE `pt_piece_id`=?");
    $ins->execute(array($piece_id));
    $pdo=null;
}

function delete_piece($piece_id){
    $pdo=connect();
    
    $ins = $pdo->prepare("DELETE FROM `piece` WHERE `pc_id`=?");
    $ins->execute(array($piece_id));
    $pdo=null;
}

function delete_order($order_id){
    $pdo=connect();
    
    $ins = $pdo->prepare("DELETE FROM `orders` WHERE `od_millnet_id`=?");
    $ins->execute(array($order_id));
    $pdo=null;
}

function delete_temp_order($id){
    $pdo=connect();
    
    $ins = $pdo->prepare("DELETE FROM `temp_order` WHERE `temp_id`=?");
    $ins->execute(array($id));
    $pdo=null;
}