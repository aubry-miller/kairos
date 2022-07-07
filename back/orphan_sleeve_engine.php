<?php
function orphan_sleeve($temp_id, $number_piece_possible,$flow_id){
    // Recover the informations of the reference command
    $technical_informations=select_temp_orders_by_id($temp_id);
    if($technical_informations != []){
        $technical_informations=$technical_informations[0];
    }

    // We get the ids corresponding to the labels to be able to compare
    // $product_type_id=get_product_type_id_by_label($technical_informations['temp_product_type']);
    $rubber_id=get_rubber_id_by_label($technical_informations['temp_rubber']);
    $notch_id=get_notch_id_by_label($technical_informations['temp_notch']);
    $fiber_id=get_fiber_id_by_label($technical_informations['temp_fiber']);



    // Change any ',' to '.' to compare
    $technical_informations['temp_sleeve_length']=str_replace(",", ".", $technical_informations['temp_sleeve_length']);
    $technical_informations['temp_table_length']=str_replace(",", ".", $technical_informations['temp_table_length']);
    $technical_informations['temp_sleeve_offset']=str_replace(",", ".", $technical_informations['temp_sleeve_offset']);
    $technical_informations['temp_mandrel_diameter']=str_replace(",", ".", $technical_informations['temp_mandrel_diameter']);
    $technical_informations['temp_developement']=str_replace(",", ".", $technical_informations['temp_developement']);
    $technical_informations['temp_fiber_thickness']=str_replace(",", ".", $technical_informations['temp_fiber_thickness']);
    $technical_informations['temp_cutback_diameter']=str_replace(",", ".", $technical_informations['temp_cutback_diameter']);

    // We make a first selection of the orphaned sleeves that could correspond (without worrying about the stage of manufacture at which they were when production was stopped)
    $orphans=select_orphelan_by_specifications($technical_informations['temp_mandrel_diameter'], $technical_informations['temp_mandrel_form'], $rubber_id, $fiber_id, $technical_informations['temp_fiber_thickness'], $technical_informations['temp_developement'], $technical_informations['temp_sleeve_length']);

    if($technical_informations['temp_pieces_number'] == 1){
        $n=0;
        // If the rectification step is in finished or In progress status it is necessary to check the table, the cutbacks, the notches and the chips
        foreach($orphans as $orphan){
            
            $grinding=select_planning_task_by_piece_id_and_step_id($orphan['pc_id'],'4');
            
            /*
            orphan status :
                • 1 => directly usable
                • 2 => rectification started but not finished, check where the production stopped physically
                • 3 => the next task is not finished
                • 4 => it is necessary to rework the piece to adapt it
            */

            if(isset($grinding[0]['pt_status']) && ($grinding[0]['pt_status'] =='Finished' || $grinding[0]['pt_status'] =='In progress')){
                
                
                // The rectification step has been started, so we have to compare the above mentioned elements
                if($technical_informations['temp_table_length'] == $orphan['pc_table_length'] && $technical_informations['temp_sleeve_offset'] == $orphan['pc_sleeve_offset'] && $technical_informations['temp_cutback'] == $orphan['pc_cutback'] && $technical_informations['temp_cutback_diameter'] == $orphan['pc_cutback_diameter'] && $technical_informations['temp_notch'] == $orphan['pc_notch_id'] && $technical_informations['temp_notch_position'] == $orphan['pc_notch_position'] && $technical_informations['temp_chip'] == $orphan['pc_chip']){
                    
                    // The orphan fits perfectly but you have to check if the manufacturing is finished or not
                    if($grinding[0]['pt_status'] =='Finished'){
                        // The orphan can be directly used for the command
                        // We add it to the object we will return
                        $orphan_return[$n]['pc_id']= $orphan['pc_id'];
                        $orphan_return[$n]['step']= '4';
                        $orphan_return[$n]['status']= '1';
                        $n++;

                    } else {

                        // The orphan can be matched, see the finishing steps to be completed
                        // We add it to the object we will return
                        $orphan_return[$n]['pc_id']= $orphan['pc_id'];
                        $orphan_return[$n]['step']= '4';
                        $orphan_return[$n]['status']= '2';
                        $n++;
                    }
                } else{
                    // It is necessary to rework the piece to adapt it
                    // We add it to the object we will return
                    $orphan_return[$n]['pc_id']= $orphan['pc_id'];
                    $orphan_return[$n]['step']= '4';
                    $orphan_return[$n]['status']= '4';
                    $n++;
                }

            } else {
                // The correction is not started, we can use the orphan without problem
                // We are looking for the stage at which the production has stopped
                $plans=select_planning_task_by_piece_id($orphan['pc_id']);
                foreach($plans as $plan){
                    if($plan['pt_status'] == "Finished"){
                        // We add it to the object we will return
                        $orphan_return[$n]['pc_id']= $orphan['pc_id'];
                        $orphan_return[$n]['step']= $plan['pt_step_id'];
                        $orphan_return[$n]['status']= '3 '.$plan['pt_step_id'];
                    } else{
                        // When the task is not Finished, we stop the loop because the manufacturing was stopped there
                        break;
                    }
                }
            
                $n++;
            }
        }
        $orphan_return['number']=$n;

        return $orphan_return;
    } else {
       
        //Si plus d'une pièce
        // We look at the number of orphans needed
        $orphans_number_to_be_found=$technical_informations['temp_pieces_number']-$_GET['number_piece_possible'];
        
        // On regarde si le nombre d'orphelin trouvé est au moins egal au nombre necessaire
        $number_orphans_available=count($orphans);
        
        // L> Il n'y en a pas assez => fabrication impossible dans les délais
        if($orphans_number_to_be_found>$number_orphans_available){
            // echo 'fabrication impossible';
            return false;
        }
        // L> On en trouve assez
        else{
        
            // On verifie si le planning passe
            $millnet_id= $technical_informations['temp_millnet_id'].'-'.$technical_informations['temp_millnet_part_id'];
            $status='awaiting validation';

            $result=orphan_planningSimulation($millnet_id,$technical_informations['temp_customer_number'],$technical_informations['temp_customer_name'],$technical_informations['temp_csr_name'],$technical_informations['temp_pieces_number'],$technical_informations['temp_deadline'],$status,$technical_informations['temp_saving_date'],$technical_informations['temp_product_type'],$technical_informations['temp_rubber'],$technical_informations['temp_sleeve_length'],$technical_informations['temp_table_length'],$technical_informations['temp_sleeve_offset'],$technical_informations['temp_mandrel_diameter'],$technical_informations['temp_notch'],$technical_informations['temp_notch_position'],$technical_informations['temp_developement'],$technical_informations['temp_fiber'],$technical_informations['temp_fiber_thickness'],$technical_informations['temp_chip'],$technical_informations['temp_cutback'],$technical_informations['temp_cutback_diameter'],$technical_informations['temp_mandrel_form'], $number_piece_possible, $orphans_number_to_be_found, $orphans, $number_orphans_available,$flow_id);
            
            while($result===false && $number_piece_possible>1){
                //fabrication impossible avec autant de manchons a fabriquer entierement, on ajoute donc un orphelin à la place d'une piece a fabriquer en entier et on voit si ca passe
                //dans le cas ou il n'y a pas que des machons orphelin utilisé
                $result=orphan_planningSimulation($millnet_id,$technical_informations['temp_customer_number'],$technical_informations['temp_customer_name'],$technical_informations['temp_csr_name'],$technical_informations['temp_pieces_number'],$technical_informations['temp_deadline'],$status,$technical_informations['temp_saving_date'],$technical_informations['temp_product_type'],$technical_informations['temp_rubber'],$technical_informations['temp_sleeve_length'],$technical_informations['temp_table_length'],$technical_informations['temp_sleeve_offset'],$technical_informations['temp_mandrel_diameter'],$technical_informations['temp_notch'],$technical_informations['temp_notch_position'],$technical_informations['temp_developement'],$technical_informations['temp_fiber'],$technical_informations['temp_fiber_thickness'],$technical_informations['temp_chip'],$technical_informations['temp_cutback'],$technical_informations['temp_cutback_diameter'],$technical_informations['temp_mandrel_form'], $number_piece_possible, $orphans_number_to_be_found, $orphans, $number_orphans_available,$flow_id);
                if($result===false){
                    $number_piece_possible--;
                    $orphans_number_to_be_found++;
                } else if($result != false){
                    break;
                }
            
            }
            // L> planning OK => on soumet la solution
                // L> planning impossible
                    // On regarde si certains manchons devaient être fabriqués entièrement
                        // L> Non, uniquement des orphelins => fabrication impossible dans les délais
                        // L> Oui => On recommande le processus avec un orphelin supplémentaire à la place d'une piece a fabriquer entierement
        }
    }
}


function orphan_planningSimulation($millnet_id,$customer_number,$customer_name,$csr,$piece_number,$deadline,$status,$saving_date,$product_type,$rubber,$sleeve_length,$table_length,$sleeve_offset,$mandrel_diameter,$notch,$notch_position,$developement,$fiber,$fiber_thickness,$chip,$cutback,$cutback_diameter,$form, $number_piece_possible, $orphans_number_to_be_found,$orphans,$number_orphans_available, $flow_id){
    //On verifie que le planning fonctionne avec le nombre de piece a fabriquer entierement =>
        $plan_result=first_planningSimulation($millnet_id,$customer_number,$customer_name,$csr,$number_piece_possible,$deadline,$status,$saving_date,$product_type,$rubber,$sleeve_length,$table_length,$sleeve_offset,$mandrel_diameter,$notch,$notch_position,$developement,$fiber,$fiber_thickness,$chip,$cutback,$cutback_diameter,$form);

        
        if($plan_result['status'] == false){
            //La fabrication est possible on planifie donc la fabrication des orphelins
            //nombre d'orphelins a finir => $orphans_number_to_be_found
            //nombre d'orphalins pouvant correspondre => $number_orphans_available

                //on doit recuperer le nombre d'étapes encore necessaire
                $n=0;
                $orphan_return=[];
                // If the rectification step is in finished or In progress status it is necessary to check the table, the cutbacks, the notches and the chips
                foreach($orphans as $orphan){
                    $grinding=select_planning_task_by_piece_id_and_step_id($orphan['pc_id'],'4');
                    
                    /*
                    orphan status :
                        • 1 => directly usable
                        • 2 => rectification started but not finished, check where the production stopped physically
                        • 3 => the next task is not finished
                        • 4 => it is necessary to rework the piece to adapt it
                    */

                    if(isset($grinding[0]['pt_status']) && ($grinding[0]['pt_status'] =='Finished' || $grinding[0]['pt_status'] =='In progress')){
                        // The rectification step has been started, so we have to compare the above mentioned elements
                        if($table_length == $orphan['pc_table_length'] && $sleeve_offset == $orphan['pc_sleeve_offset'] && $cutback == $orphan['pc_cutback'] && $cutback_diameter == $orphan['pc_cutback_diameter'] && $notch == $orphan['pc_notch_id'] && $notch_position == $orphan['pc_notch_position'] && $chip == $orphan['pc_chip']){                           
                            // The orphan fits perfectly but you have to check if the manufacturing is finished or not
                            if($grinding[0]['pt_status'] =='Finished'){
                                // The orphan can be directly used for the command
                                // We add it to the object we will return
                                $orphan_return[$n]['pc_id']= $orphan['pc_id'];
                                $orphan_return[$n]['step']= '4';
                                $orphan_return[$n]['status']= '1';
                                $n++;
                            } else {
                                // The orphan can be matched, see the finishing steps to be completed
                                // We add it to the object we will return
                                $orphan_return[$n]['pc_id']= $orphan['pc_id'];
                                $orphan_return[$n]['step']= '4';
                                $orphan_return[$n]['status']= '2';
                                $n++;
                            }
                        } else{
                            // It is necessary to rework the piece to adapt it
                            // We add it to the object we will return
                            $orphan_return[$n]['pc_id']= $orphan['pc_id'];
                            $orphan_return[$n]['step']= '4';
                            $orphan_return[$n]['status']= '4';
                            $n++;
                        }

                    } else {
                        // The correction is not started, we can use the orphan without problem
                        // We are looking for the stage at which the production has stopped
                        $plans=select_planning_task_by_piece_id($orphan['pc_id']);
                        var_dump($plans);
                        foreach($plans as $plan){
                            if($plan['pt_status'] == "Finished"){
                                // We add it to the object we will return
                                $orphan_return[$n]['pc_id']= $orphan['pc_id'];
                                $orphan_return[$n]['step']= $plan['pt_step_id'];
                                $orphan_return[$n]['status']= '3 '.$plan['pt_step_id'];
                            } else{
                                // When the task is not Finished, we stop the loop because the manufacturing was stopped there
                                break;
                            }
                        }
                        $n++;
                    }
                }

                
                $session_key=random_int(0,999999999);
                $orphan_return['number']=$n;
                $deadline_task=$deadline;
                $now_base=date('Y-m-d');
                $now = new DateTime($now_base);
                $rubber_id=get_rubber_id_by_label($rubber);
                $minimum_time=get_minimum_time_by_id_rubber_and_flow($rubber_id,$flow_id);

                //On boucle sur le resultat et on plannifie selon le status
                for($i=0; $i<$orphan_return['number'];$i++){
                    $id=$i+$number_orphans_available;
                    if($id<10){
                        
                        $piece_id=$millnet_id.'_00'.$id;
                        
                    } else if($i<100){
                        $piece_id=$millnet_id.'_0'.$id;
                    } else {
                        $piece_id=$millnet_id.'_'.$id;
                    }

                    if($orphan_return[$i]['status'] == 1){
                        $st=1;
                        return $st;
                        //rien à plannifier, le manchon étant directement utilisable
                    } else if($orphan_return[$i]['status'] == 2){
                        $st=2;
                        return $st;
                        //ce cas ne doit pas arriver, mais a voir comment on le gère
                    } else if($orphan_return[$i]['status'] == 3){
                        $st=3;
                        return $st;
                        //voir a quelle étape on en été mais aucun adaptation ne sera necessaire
                        // $orphan_return[$i]['step'] == étape à laquelle on s'est arrété
                        // il faut donc comparé au flux de prod de la piece a fabriquer entierement et reprendre les étapes
                        $initial_steps=get_steps_by_flow_id($plan_result['flow_id']);
                        $ref ='off';
                        $j=0;
                        foreach($initial_steps as $initial_step){
                            if($ref == 'off' && $initial_step['stp_id'] != $orphan_return[$i]['step']){
                                //étape déjà faite, on ne fait rien
                            } else if($ref == 'off' && $initial_step['stp_id'] == $orphan_return[$i]['step']){
                                //On n'est à la dernière étape effectuée
                                $ref = 'on';
                            } else if($ref == 'on' && $initial_step['stp_id'] != $orphan_return[$i]['step']){
                                //On est après la dernière étape effectuée, il faut planifier
                                $orphan_flow['$i'][$j]['stp_id']= $initial_step['stp_id'];
                                $orphan_flow['$i'][$j]['stp_minimum_time']=$initial_step['stp_minimum_time'];
                                $orphan_flow['$i'][$j]['stp_needs_mandrel']=$initial_step['stp_needs_mandrel'];
                                $orphan_flow['$i'][$j]['stp_sector_id']=$initial_step['stp_sector_id'];
                                $orphan_flow['$i'][$j]['stp_label']=$initial_step['stp_label'];
                                $j++;
                            }
                        }
                        $orphan[$i]['step_number']=$j;

                        // On a maintenant les étapes a réaliser pour cette pièce et leur ordre, reste a plannifier
                        $result[$piece_number][$i]=possibility_for_step($orphan_flow,$mandrel_diameter, $form,$sleeve_length,$deadline_task,$i,$minimum_time,$now_base,$session_key);

                        if($result[$piece_number][$i]=== false || $result[$piece_number][$i]=='Impossible : no mandrel available'){
                            $planning['status']=false;
                            $planning['reasons']='Not possible in this time';
                            // Return the number of parts that can be fully manufactured for the management of orphans
                            $planning['nb_piece_ok']=$i-1;
                            return $planning;
                        } 
    
                        ///////////////////////////////////////////////////////////////////////////////////
                        ///////////////////////////// STAFF AVAILABILITY CHECK ////////////////////////////
                        //////////////////////////////////// Beggining ////////////////////////////////////
    
                        // Check availability of personnel on  $result[$piece_number][$i] at the workshop  $$orphan_flow['stp_sector_id']
                        // Recovery of the number of available time per person of the sector at the date
    
                        //select operateur du secteur
                        $operators=select_operators_by_sector($orphan_flow[$i]['stp_sector_id']);
                        
                        if(isset($_SESSION[$session_key]['mandrel_id']) && $_SESSION[$session_key]['mandrel_id'] != null){
    
                            //verifier si le secteur du porteur correspond au secteur du step TODO
                            $mandrel=get_mandrel_by_id($_SESSION[$session_key]['mandrel_id']);
                            
                                if($orphan_flow[$i]['stp_sector_id'] == $mandrel['mn_sector_id']){
                                    $planning[$piece_id][$orphan_flow[$i]['stp_label']]['mandrel_id']=$_SESSION[$session_key]['mandrel_id'];
                                } else {
                                    $planning[$piece_id][$orphan_flow[$i]['stp_label']]['mandrel_id']=null;
                                }
                            
    
                            
                        }
    
                        // Task duration
                        $duration=calculate_task_duration($orphan_flow[$i]['stp_id'],$mandrel_diameter,$sleeve_length);
                        $wip=strstr($duration, '.');
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
    
                        $planning[$piece_id][$orphan_flow[$i]['stp_label']]['during']=$duration_time;
    
                        // Check if present this day in the absence table
                        foreach($operators as $operator){
                            $presence=verification_operator_presence_at_date($operator['us_id'],$result[$piece_number][$i]);
                            $operator_default_time=select_operator_default_time_by_id($operator['us_id']);
    
                            if($presence == []){
                                // Check if present this day in the overtime table
                                $overtime=verification_operator_overtime_at_date($operator['us_id'],$result[$piece_number][$i]);
                                if($overtime == []){
                                    $additional_time=0;
                                } else {
                                    $additional_time = $overtime[0]['oo_during'];
                                }
    
                                $operation_production_time= $operator_default_time[0]['otd_production_time']+$additional_time;
    
                                // Work assigned at this day
                                $day_jobs= select_job_duration_by_date_and_user_id($result[$piece_number][$i],$operator['us_id']);
    
                                $dispo_time_hours=floor($operation_production_time);
                                $dispo_time_minutes=($operation_production_time-$dispo_time_hours)*60;
                                $dispo_time=$dispo_time_hours*60+$dispo_time_minutes;
                                
                                foreach($day_jobs as $day_job){
                                    $day_jobs_hours=date_create($day_job['pt_expected_duration'])->format('H');
                                    $day_jobs_minutes=date_create($day_job['pt_expected_duration'])->format('i');
                                    $day_jobs_during=$day_jobs_hours*60+$day_jobs_minutes;
                                    $dispo_time=$dispo_time-$day_jobs_during;
                                }
                                if(($dispo_time+$duration)>=0){
                                    
                                    $planning[$piece_id][$orphan_flow[$i]['stp_label']]['date']=$result[$piece_number][$i];//TODO changer label en id
                                    $planning[$piece_id][$orphan_flow[$i]['stp_label']]['operator']=$operator['us_id'];//TODO changer label en id
                                    
    
                                    ///////////////////////////////////////////////////////////////////////////////////
                                    //////////////////////////// MACHINE AVAILABILITY CHECK ///////////////////////////
                                    //////////////////////////////////// Beggining ////////////////////////////////////
                                        
                                        // We check the machine availability at the date
                                        $verif_machine=check_machine_availability($orphan_flow[$i]['stp_id'],$result[$piece_number][$i],$mandrel_diameter,$session_key,$duration,$sleeve_length);
                                        if($verif_machine == false){
                                            $planning['status']=false;
                                            $planning['reasons']='No machine available, it is therefore impossible to manufacture the order within this period';
                                            // Return the number of parts that can be fully manufactured for the management of orphans
                                            $planning['nb_piece_ok']=$i-1;
                                            return $planning;
                                        }
                                        
                                        $planning[$piece_id][$orphan_flow[$i]['stp_label']]['machine']=$verif_machine;
                                        var_dump($result[$piece_number][$i]);
                                        if($result[$piece_number][$i] != null){
                                            $d = strtotime($result[$piece_number][$i]);
                                            $deadline_task= date("Y-m-d", mktime(0,0,0,date("m", $d),date("d", $d)-1,date("Y", $d)));
                                        }
                                        // Save the tasks in planning task
                                        if(strpos($orphan_flow[$i]['stp_id'], 'stock') == false){
                                            if(!isset($planning[$piece_id][$orphan_flow[$i]['stp_label']]['mandrel_id'])){
                                                $mandrel_id=null;               
                                            } else {
                                                $mandrel_id=$planning[$piece_id][$orphan_flow[$i]['stp_label']]['mandrel_id'];
                                            }
                                            
                                            new_planning_task($piece_id, $orphan_flow[$i]['stp_id'], $planning[$piece_id][$orphan_flow[$i]['stp_label']]['during'], $d, $planning[$piece_id][$orphan_flow[$i]['stp_label']]['machine'], $planning[$piece_id][$orphan_flow[$i]['stp_label']]['operator'],  $mandrel_id);//TODO changer la durée
                                        }
    
                                    ///////////////////////////////////////////////////////////////////////////////////
                                    //////////////////////////// MACHINE AVAILABILITY CHECK ///////////////////////////
                                    /////////////////////////////////////// End ///////////////////////////////////////
    
    
    
    
    
                                } else{
                                    // echo '<br>opérateur non dispo, on continu la boucle<br>';//TODO
                                }
                                
    
                                
    
                            } else{
                                // If the start date contains 00:00:00 and the end date contains 23:59:59 then the operator is absent during the day
                               
                                if( (strpos($presence[0]['oa_start_hour_date'], '00:00:00') !== false) && (strpos($presence[0]['oa_end_hour_date'], '23:59:59')!== false)){
                                    //TODO
                                    // echo "<br>absent toute la journée<br>";
                                } else{
                                    //TODO
                                    // echo '<br>absence partielle<br>';
                                    // temps de travail normal moins temps d'absence
                                }
                            }
    
                            ///////////////////////////////////////////////////////////////////////////////////
                            ///////////////////////////// STAFF AVAILABILITY CHECK ////////////////////////////
                            /////////////////////////////////////// End ///////////////////////////////////////
                        }

                         return $planning;

                    } else if($orphan_return[$i]['status'] == 4){
                        $st=4;
                        return $st;
                        //des adapations sont a faire, voir si ebauche et rectif ou juste rectif (si diffrence diametre >= 6mm (épaisseur de gomme >=3mm) on passe à la rectif en plus de l'ébauche) //TODO
                    }
                    
                
                }
        return 'blao';//TODO
        


        } else if($plan_result['status'] != false){
            //TODO
            //La fabrication n'est pas possible, on refait une simulation avec un orphelin de plus et donc une piece de moins a fabriquer (si nombre de piece a fabriquer entierement durant le dernier test >1)
        }
}