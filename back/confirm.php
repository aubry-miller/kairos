<?php
var_dump($_GET);
include('../sql/connect.php');


if($_GET['submit'] == 'Yes'){
    // Change the status of the order and of the parts
    include('../sql/update.php');
    for($i=1;$i<=$_GET['piece_number'];$i++){
        if($i<10){
            $piece_id=$_GET['millnet_id'].'_00'.$i;
            
        } else if($i<100){
            $piece_id=$_GET['millnet_id'].'_0'.$i;
        } else {
            $piece_id=$_GET['millnet_id'].'_'.$i;
        }
        echo $piece_id.'<br>';

        update_piece_statut($piece_id, 'confirmed');
    }
    update_order_statut($_GET['millnet_id'], 'confirmed');

} else if($_GET['submit'] == 'No'){
    include('../sql/delete.php');
    // We look for the number of the parts to delete the planning_task
    for($i=1;$i<=$_GET['piece_number'];$i++){
        if($i<10){
            $piece_id=$_GET['millnet_id'].'_00'.$i;
            
        } else if($i<100){
            $piece_id=$_GET['millnet_id'].'_0'.$i;
        } else {
            $piece_id=$_GET['millnet_id'].'_'.$i;
        }
        echo $piece_id.'<br>';

        // We delete the tasks
        delete_planning_task($piece_id);
        delete_piece($piece_id);
    }
    delete_order($_GET['millnet_id']);
    // We do nothing, the temporary order is deleted and the rest should be kept with the same status
    
} else if($_GET['submit'] == 'Hold the date'){
    // We do nothing, 
}
header("location:../new_order.php");


