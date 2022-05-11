<?php
include('../sql/connect.php');
include('../sql/engine.php');

if($_GET['submit'] == 'No'){
    include('../sql/delete.php');
    delete_temp_order($_GET['temp_id']);

    header("location:../new_order.php");
} else {
    include('../sql/get.php');
    echo 'calculer le meilleur planning possible <br>';
    //Il faut récupérer les informations de la commande temp avec son ID

    $order=select_temp_orders_by_id($_GET['temp_id']);

    $millnet_id=$order['temp_millnet_id'].'-'.$order['temp_millnet_part_id'];
    // On va chercher le workflow qui correspond au type de produit
    $product_type_id=get_product_type_id_by_label($order['temp_product_type']);
    // On va chercher les step du workflow
    $steps=get_steps_by_flow_id($product_type_id);


    $step_date=date('Y-m-d');

    // creation de la commande pour enregistrer la plannification des tâches ????????????

    // A chaque étape (dans l'ordre)
    foreach($steps as $step){
        
        //Pour chaque pièce
        for($n=1;$n<=$order['temp_pieces_number'];$n++){
            // We build the piece ID from the Millnet number and the number of pieces
            if($n<10){
                $piece_id=$millnet_id.'_00'.$n;
                
            } else if($n<100){
                $piece_id=$millnet_id.'_0'.$n;
            } else {
                $piece_id=$millnet_id.'_'.$n;
            }
            echo '<br>'.$piece_id. ' => '.$step['stp_label'].'<br>';

            //selection du porteur correspondant aux caractéritiques techniques si l'étape en necessite un
            if($step['stp_needs_mandrel']== 1){
                $mandrels=get_mandrel_id_by_specifications($order['temp_mandrel_diameter'], $order['temp_mandrel_form'],$order['temp_sleeve_length'],$step['stp_sector_id']);
                var_dump($mandrels);
                /////////////////////////////////////
                /////////////////////////////////////
                /////////////////////////////////////
                /////////////////////////////////////
                /////////////////////////////////////
                /////////////////////////////////////
                //TODO tester dispo mandrel à la date
                /////////////////////////////////////
                /////////////////////////////////////
                /////////////////////////////////////
                /////////////////////////////////////
                /////////////////////////////////////
                /////////////////////////////////////
                /////////////////////////////////////
                $test_mandrel=0;
                while($test_mandrel==0){
                    $test_day=0;
                    while( $test_day==0){
                        // $tomorrow=new DateTime($step_date);
                        // $tomorrow->modify('+1 day');
                        // $tomorrow_string = $tomorrow->format('Y-m-d H:i:s');
                        
                        list($year, $month, $day) = explode('-', $step_date);
                        echo '<br>day='.$day.'<br>month='.$month.'<br>year='.$year.'<br>';
                        $timestamp = mktime (0, 0, 0, $month, $day, $year);

                        var_dump($timestamp);
                        // // $step_date=$tomorrow;//Vérifier qu'on ne soit pas un weekend
                        // // $tomorrow_string = $tomorrow->format('Y-m-d H:i:s');
                        // list($year, $month, $day) = explode('-', $tomorrow_string);
                        // var_dump($day);
                        // // Timestamp calculation
                        // $timestamp = mktime (0, 0, 0, $month, $day, $year);
                        // $day_number = DAYSWEEK[date("w",$timestamp)];
                        // // echo 'day =>'.$day_number.'<br>';
                        // if($day_number!=1 || $day_number != 7){
                            $test_day=1;
                        // }
                    }
                    
                    foreach($mandrels as $mandrel){
                        $dispo=count_task_at_date_with_mandrel_id($step_date,$mandrel['mn_id']);
                        if($dispo == 0){
                        //mandrel dispo
                        echo'<br>Porteur dispo<br>';
                        $test_mandrel=1;
                        break;
                        } else{
                            echo '<br>Porteur indispo<br>';
                        }
                    }
                    
                }






                $test=0;
                while($test==0){
                    //select operateur du secteur
                    $operators=select_operators_by_sector($step['stp_sector_id']);
                    var_dump($operators);
                    $test=1;

                }


            } else {
                echo "Pas besoin de d'attribuer un porteur<br>";
                $mandrels=get_mandrel_id_by_specifications($order['temp_mandrel_diameter'], $order['temp_mandrel_form'],$order['temp_sleeve_length'],$step['stp_sector_id']);
                var_dump($mandrels);
                $test=0;
                while($test==0){
                    //select operateur du secteur
                    $operators=select_operators_by_sector($step['stp_sector_id']);
                    foreach($operators as $operator){
                        // $presence=verification_operator_presence_at_date($operator['us_id'],$date);
                        // $operator_default_time=select_operator_default_time_by_id($operator['us_id']);

                    
                    }

                    $test=1;

                }
            }
            echo '<br>';
        }
    }
    
    /*
    A chaque étape (dans l'ordre)
    
    => Si porteur nécessaire
        =>On cherche la première date à laquelle un porteur correspondant aux catactéristiques techniques est disponible.
            =>À la date trouvé on regarde si un opérateur est dispo
                =>Pas d'opérateur on passe au lendemain
                => Un opérateur on regarde si une machine est dispo
                    =>Machine dispo, on passe au step suivant
                    =>Machine non dispo, on passe au jour suivant
    Si pas de porteur on passe directement à la recherche d'un opérateur libre puis d'une machine

    */
}
