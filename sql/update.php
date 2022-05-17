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