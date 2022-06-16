<?php
include('../sql/connect.php');
include('../sql/get.php');
include('../sql/update.php');
include('../sql/set.php');
include('../sql/delete.php');
/*
orphan status :
    • 1 => directly usable
    • 2 => rectification started but not finished, check where the production stopped physically
    • 3 => the next task is not finished
    • 4 => it is necessary to rework the piece to adapt it
*/
var_dump($_GET);


if($_GET['orphan_status'] == '1'){ //directly usable
    // We check that there is nothing left in the planning task that is not in finished status
    $result=select_planning_task_by_piece_id_and_status_is_not($_GET['pc_id'],'Finished');
    if($result == []){
        // We change the pc_order_id by the order millnet id
        // We change the pc_status
        attribute_orphan_to_new_job($_GET['pc_id'],$_GET['millnet_id'],'manufactured');
        
        // If the order contains only one piece we can change its status
        $millnet_id=substr($_GET['millnet_id'], 0 ,8);
        $millnet_part_id=substr($_GET['millnet_id'], 9);
        $pieces_number=select_temp_order_pieces_number($millnet_id, $millnet_part_id);

        if($pieces_number["temp_pieces_number"] == '1'){
                // We get all the information from temp_order
                $order_informations=select_temp_orders_by_id($_GET['temp_id']);
                var_dump($order_informations);
            
                // Creation of the order
                new_order($_GET['millnet_id'],$order_informations['temp_customer_number'],$order_informations['temp_customer_name'],$order_informations['temp_csr_name'],$order_informations['temp_pieces_number'],$order_informations['temp_deadline'],'confirmed',$order_informations['temp_saving_date']);

                // Delete in temp_order
                delete_temp_order($_GET['temp_id']);

                //Il faut supprimer la piece créée à l'étape d'avant


                //Reste le déplacement physique de la pièce
                //Ajouter le stock de destination
                

        } else {
            ///////////////////// TODO /////////////////////
            //Si plus d'une pièce que faire ? Utilisation du plus d'orphelin possible ? D'un, on fois si le délai est tenable, puis de deux etc ?
        }


    } else {
       //TODO : a voir ce que l'on fait, alerte ?
    }
        

} else if($_GET['orphan_status'] == '2'){//rectification started but not finished

} else if($_GET['orphan_status'] == '3'){//the next task is not finished

} else if($_GET['orphan_status'] == '4'){//it is necessary to rework the piece to adapt it

}
