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
    echo 'calculer le meilleur planning possible <br>';
    //Il faut récupérer les informations de la commande temp avec son ID


    $session_key=random_int(0,999999999);
    session_start();

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
                // var_dump($mandrels);
                
                $test_mandrel=0;
                while($test_mandrel==0){
                    $test_day=0;
                    while( $test_day==0){
                        $tomorrow=new DateTime($step_date);
                        $tomorrow->modify('+1 day');
                        $tomorrow_string = $tomorrow->format('Y-m-d');
                        $step_date=$tomorrow_string;

                        
                        list($year, $month, $day) = explode('-', $tomorrow_string);
                        echo 'day='.$day.'<br>month='.$month.'<br>year='.$year.'<br>';
                        $timestamp = mktime (0, 0, 0, $month, $day, $year);
                    
                        $day_number = DAYSWEEK[date("w",$timestamp)];
                        echo 'day =>'.$day_number.'<br>';
                        if($day_number!=1 && $day_number != 7){
                            //On n'est pas un samedi ou un dimanche donc la nouvelle date a tester est la bonne
                            $test_day=1;
                            echo '<br>semaine<br>';
                        } else{
                            //Sinon on continu a boucler pour tester un autre jour
                            $test_day=0;
                            echo '<br>week-end<br>';
                        }
                    }
                    
                    foreach($mandrels as $mandrel){
                        $dispo=count_task_at_date_with_mandrel_id($step_date,$mandrel['mn_id']);
                        if($dispo == 0){
                        //mandrel dispo

                        $_SESSION[$session_key]['mandrel_id']=$mandrel['mn_id'];
                        echo'Porteur dispo<br>';
                        
                        /////////////////////////////////////
                        /////////////////////////////////////
                        /////////////////////////////////////
                        /////////////////////////////////////
                        /////////////////////////////////////
                        /////////////////////////////////////
                        /////////////////////////////////////
                        //On test la présence opérateur
                        /////////////////////////////////////
                        /////////////////////////////////////
                        /////////////////////////////////////
                        /////////////////////////////////////
                        /////////////////////////////////////
                        /////////////////////////////////////
                        /////////////////////////////////////
                        $operators=select_operators_by_sector($step['stp_sector_id']);

                        $duration=calculate_task_duration($steps[$i]['stp_id'],$mandrel_diameter,$sleeve_length);
                        $wip=strstr($duration, '.');
                        // echo $wip;
                        if(strlen($wip) == 1){
                            $wip='00';
                        }if(strlen($wip) == 2){
                            $wip=$wip.'0';
                        }if(strlen($wip) == 3){
                            $wip=substr(strstr($wip, '.'),1);
                        }
                        $duration_minutes=round(intval($wip)/100*60,0);
                        $duration_hours=strstr($duration, '.', true);
                        if(strlen($duration_hours)==1){
                            $duration_hours='0'.$duration_hours;
                        }
                        $duration_time=$duration_hours.':'.$duration_minutes.':00';

                        
                        foreach($operators as $operator){
                            $presence=verification_operator_presence_at_date($operator['us_id'],$step_date);
                            $operator_default_time=select_operator_default_time_by_id($operator['us_id']);
                            if($presence == []){
                                echo 'opérateur présent';
                                //check si present dans overtime
                                $overtime=verification_operator_overtime_at_date($operator['us_id'],$step_date);
                                if($overtime == []){
                                    $additional_time=0;
                                } else {
                                    $additional_time = $overtime[0]['oo_during'];
                                }
                                $operation_production_time= $operator_default_time[0]['otd_production_time']+$additional_time;


                                //travail attribué ce jour
                                $day_jobs= select_job_duration_by_date_and_user_id($step_date,$operator['us_id']);

                                $dispo_time_hours=floor($operation_production_time);
                                $dispo_time_minutes=($operation_production_time-$dispo_time_hours)*60;
                                $dispo_time=$dispo_time_hours*60+$dispo_time_minutes;
                                
                                foreach($day_jobs as $day_job){
                                    $day_jobs_hours=date_create($day_job['pt_expected_duration'])->format('H');
                                    $day_jobs_minutes=date_create($day_job['pt_expected_duration'])->format('i');
                                    $day_jobs_during=$day_jobs_hours*60+$day_jobs_minutes;
                                    $dispo_time=$dispo_time-$day_jobs_during;//TODO ajouter durée de la tache via abaques
                                }
                                if($dispo_time>=0){
                                    $planning[$piece_id][$step['stp_label']]['date']=$step_date;//TODO changer label en id
                                    $planning[$piece_id][$step['stp_label']]['operator']=$operator['us_id'];//TODO changer label en id
                                    
                                    echo '<br>l\'opérateur a le temps de fabriquer, voir la dispo machine<br>';
                                    

                                    //Test Machine $sector_id,$date,$mandrel_diameter,$session_key,$duration,$sleeve_length
                                    $verif_machine=check_machine_availability($step['stp_sector_id'],$step_date,$order['temp_mandrel_diameter'],$session_key,$duration,$order['temp_sleeve_lenght']);
                                    
                                    if($verif_machine == false){
                                        $planning['status']=false;
                                        $planning['reasons']='No machine available, it is therefore impossible to manufacture the order within this period';
                                    }

                                    $planning[$piece_id][$step['stp_label']]['machine']=$verif_machine;
                                    
                                    if($step_date != null){
                                        $d = strtotime($step_date);
                                        $deadline_task= date("Y-m-d", mktime(0,0,0,date("m", $d),date("d", $d)-1,date("Y", $d)));
                                    }

                                    //Enregistrer les tash dans planning tash
                                    if(strpos($step['stp_id'], 'stock') == false){
                                        if(!isset($planning[$piece_id][$step['stp_label']]['mandrel_id'])){
                                            $mandrel_id=null;               
                                        } else {
                                            $mandrel_id=$planning[$piece_id][$step['stp_label']]['mandrel_id'];
                                        }

                                        new_planning_task($piece_id, $step['stp_id'], '01:00:00', $step_date, $planning[$piece_id][$step['stp_label']]['machine'], $planning[$piece_id][$step['stp_label']]['operator'],  $mandrel_id);//TODO changer la durée
                                    }
                                
                                
                                
                                } else {
                                    echo '<br>opérateur non dispo, on continu la boucle<br>';
                                }




                            } else{
                                echo 'opérateur absent';
                            }
                        }

                        $test_mandrel=1;
                        break;
                        } else{
                            echo '<br>Porteur indispo<br>';
                        }
                    }
                    
                }






            


            } else {
                echo "Pas besoin de d'attribuer un porteur<br>";
                $mandrels=get_mandrel_id_by_specifications($order['temp_mandrel_diameter'], $order['temp_mandrel_form'],$order['temp_sleeve_length'],$step['stp_sector_id']);
                // var_dump($mandrels);
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
