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