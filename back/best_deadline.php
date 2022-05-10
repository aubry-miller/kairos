<?php
include('../sql/connect.php');
include('../sql/delete.php');
if($_GET['submit'] == 'No'){
    delete_temp_order($_GET['temp_id']);

    header("location:../new_order.php");
} else {
    echo 'calculer le meilleur planning possible';
    //TODO calculer le meilleur planning possible
}
