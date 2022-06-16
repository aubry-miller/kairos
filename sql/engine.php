<?php

    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////// DECLARATION OF CONSTANTS //////////////////////////////////////
    ////////////////////////////////////////////// Beggining //////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////

// Constant of the safety margin to keep before the deadline to plan a production
define('MARGIN_DEADLINE',2);

// Table of days of the week
define('DAYSWEEK',array('1', '2', '3', '4', '5', '6', '7')); //7=>Sunday, 1=>Monday , 2=>Tuesday , 3=>Wednesday, 4=>Thursday , 5=>Friday, 6=>Saturday



    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////// DECLARATION OF CONSTANTS //////////////////////////////////////
    ///////////////////////////////////////////////// End /////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    







    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////// FUNCTION test_availability_mandrel //////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////// Beggining ///////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// If this function returns something, an availability of mandrel has been found, if it returns false, no availability
function test_availability_mandrel($possibily,$mandrel_ids,$date_with_margin){
    foreach($mandrel_ids as $mandrel_id){
        $j=0;
                                        
        while ($j<$possibily){
            $dt=date('Y-m-d', strtotime($date_with_margin. ' - '.$j.' days'));
            
            $dispo=count_task_at_date_with_mandrel_id($dt, $mandrel_id['mn_id']);
            if($dispo == 0){
                $response['mandrel_id']=$mandrel_id['mn_id'];
                $response['date']=$dt;
                return $response;
            }
            $j++;
        }
    }
    return false;
}

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////// FUNCTION test_availability_mandrel //////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////// End //////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////// FUNCTION calculate_task_duration ///////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////// Beggining ///////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


function calculate_task_duration($step, $mandrel_diameter, $sleeve_length){
   
    if($step=='4'){//grinding time
       $time=get_grinding_time($mandrel_diameter, $sleeve_length);
    } else if($step=='2' || $step=='6'){//lining time
        $time=get_lining_time($mandrel_diameter, $sleeve_length);
    } else {
        $time='0.5'; //TODO mettre les autres abaques
    }
   return $time;
}

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////// FUNCTION calculate_task_duration ///////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////// End //////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////// FUNCTION dateDiff /////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////// Beggining //////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// This function allows you to calculate the number of days between two dates
function dateDiff($date1, $date2){
    $diff = abs($date1 - $date2); // abs to have the absolute value, thus avoiding to have a negative difference
    $retour = array();
 
    $tmp = $diff;
    $back['second'] = $tmp % 60;
 
    $tmp = floor( ($tmp - $back['second']) /60 );
    $back['minute'] = $tmp % 60;
 
    $tmp = floor( ($tmp - $back['minute'])/60 );
    $back['hour'] = $tmp % 24;
 
    $tmp = floor( ($tmp - $back['hour'])  /24 );
    $back['day'] = $tmp;
 
    return $back;
}
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////// FUNCTION dateDiff /////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////// End /////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////// FUNCTION possibility_for_step /////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////// Beggining ////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function possibility_for_step($steps,$mandrel_diameter, $form,$sleeve_length,$deadline_task,$i,$minimum_time,$now,$session_key){
    
    // We check if the step minimum time is differente than 0
    if($steps[$i]['stp_minimum_time'] != 0){
        // The step needs a mandrel

        //////////////////////////////////////////////////////////////////////////////////
        ////////////////////////// THE STEP REQUIRES A MANDREL ///////////////////////////
        //////////////////////////////////////////////////////////////////////////////////

        if($steps[$i]['stp_needs_mandrel'] == 1){
            // The step needs a mandrel

            // We select the mandrels id
            $mandrel_ids = get_mandrel_id_by_specifications($mandrel_diameter, $form,$sleeve_length,$steps[$i]['stp_sector_id']);
        
            // We look if a carrier of the right diameter is used in the period
            $others=get_mandrel_use_in_period($mandrel_diameter, $steps[$i]['stp_id'],date('Y-m-d'),$deadline_task);
            
            // We loop to see if we still have time, depending on the day of the week
            foreach ($others as $other){
                // We get the date on which the start of production is planned, and we remove the time
                // $other_date=substr($other['pt_planned_start_date'], 0, 10);//CHANGER LE 6 MAI
                $other_date=$other['pt_date'];
                $tomorrow=new DateTime($other_date);
                $tomorrow->modify('+1 day');
                $tomorrow_string = $tomorrow->format('Y-m-d H:i:s');
                $yesterday=new DateTime($other_date);
                $yesterday->modify('-1 day');
                $yesterday_string = $yesterday->format('Y-m-d H:i:s');
                // Extraction of the day, month, year of the date
                list($day, $month, $year) = explode('-', $other_date);// a voir si les variables ne sont pas dans un mauvais ordre
                // Timestamp calculation
                $timestamp = mktime (0, 0, 0, $month, $day, $year);
                // Day of the week
                $day_number = DAYSWEEK[date("w",$timestamp)];
                // echo 'day =>'.$day_number.'<br>';
                if($day_number==1 || $day_number == 7){
                    
                    foreach($mandrel_ids as $mandrel_id){
                        // Look in planning task if this day (n+1) the mandrel with this id is occupied or not
                        $dispo=count_task_at_date_with_mandrel_id($tomorrow_string, $mandrel_id['mn_id']);
                        if($dispo == 0){
                            // If time ok we continue the programming
                            $interval = $now->diff($tomorrow);
                            $available_time= $interval->format('%a');

                            // We recover the sign (- if previous date)
                            $available_sign= $interval->format('%R');
                            
                            // We check if the delay is sufficient
                            if($available_time >= $minimum_time && $available_sign=='+'){ 
                                // echo 'Délai ok <br>';
                            } else {
                                // echo 'délai KO, on continu <br>';
                            }
                        } else {
                            // echo 'non dispo'.$tomorrow_string.' <br>';
                            // echo 'On continue la boucle  <br>';
                        }
                    }

                } else if ($day_number==2 || $day_number == 3 || $day_number == 4){
                    foreach($mandrel_ids as $mandrel_id){
                        //  Look in planning task if this day (n+-1) the mandrel with this id is occupied or not
                        $dispo_tomorrow=count_task_at_date_with_mandrel_id($tomorrow_string, $mandrel_id['mn_id']);
                        if($dispo_tomorrow == 0){
                            // If time ok we continue the programming
                            $interval = $now->diff($tomorrow);
                            $available_time= $interval->format('%a');

                            // We recover the sign (- if previous date)
                            $available_sign= $interval->format('%R');
                            
                            // We check if the delay is sufficient
                            if($available_time >= $minimum_time && $available_sign=='+'){
                            } else {
                                // echo 'délai KO, on continu <br>';
                            }
                        } else {
                            $dispo_yesterday=count_task_at_date_with_mandrel_id($yesterday_string, $mandrel_id['mn_id']);
                            if($dispo_yesterday == 0){
                                // If time ok we continue the programming
                                $interval = $now->diff($yesterday);
                                $available_time= $interval->format('%a');

                                // We recover the sign (- if previous date)
                                $available_sign= $interval->format('%R');
                                
                                // We check if the delay is sufficient
                                if($available_time >= $minimum_time && $available_sign=='+'){
                                    // echo 'Délai ok <br>';
                                } else {
                                    // echo 'délai KO, on continu <br>';
                                }                                       
                            } else {
                                // echo 'non dispo le '.$yesterday_string.' <br>';
                                // echo 'On continue la boucle <br>';
                            }
                        }

                        
                    }
                } else if ($day_number=5 || $day_number == 6){

                    foreach($mandrel_ids as $mandrel_id){
                        // Look in planning task if this day (n-1) the mandrel with this id is busy or not
                        
                        $dispo_yesterday=count_task_at_date_with_mandrel_id($yesterday_string, $mandrel_id['mn_id']);
                        if($dispo_yesterday == 0){
                            // If time ok we continue the programming
                            $interval = $now->diff($yesterday);
                            $available_time= $interval->format('%a');

                            // We recover the sign (- if previous date)
                            $available_sign= $interval->format('%R');
                            
                            // We check if the delay is sufficient
                            if($available_time >= $minimum_time && $available_sign=='+'){
                                // echo 'Délai ok <br>';
                            } else {
                                // echo 'délai KO, on continu <br>';
                            } 
                        } else {
                            // echo 'non dispo le '.$yesterday_string.' <br>';
                            // echo 'On continue la boucle <br>';
                        }
                    }
                }
            }


            // When this code is played, it means that we can't program the manufacturing on a support already released in the workshop
            // We must therefore search for the date when a mandrel is available
            
            // We calculate the deadline with my safety margin excluding weekends
            
            // Extraction of the day, month, year of the date
            list($day, $month, $year) = explode('-', $deadline_task);
            // Timestamp calculation
            $timestamp = mktime (0, 0, 0, $month, $day, $year);
            // Day of the week
            $day_number = DAYSWEEK[date("w",$timestamp)];
            if($day_number == 1 || $day_number ==2){
                $margin = MARGIN_DEADLINE + 2 ;
                
            } else {
                $margin = MARGIN_DEADLINE;
            }
            $date_with_margin=date('Y-m-d', strtotime($deadline_task. ' - '.$margin.' days'));
            $first_end_date= date('Y-m-d', strtotime($now. ' - '.$minimum_time.' days'));
            $date_with_margin_calc = new DateTime($date_with_margin);
            $first_end_date = new DateTime($first_end_date);
            $possibily= $first_end_date->diff($date_with_margin_calc);
            $possibily= $possibily->format('%a');

        
            
            $response=test_availability_mandrel($possibily,$mandrel_ids,$date_with_margin);
            if ($response['date'] == false){
                // We return the fact that the order is not feasible in this period due to lack of mandrel
                return 'Impossible : no mandrel available';
            } else {                
                $_SESSION[$session_key]['mandrel_id']=$response['mandrel_id'];
                return $response['date'];
            }

            
        } else {
        
        //////////////////////////////////////////////////////////////////////////////////
        ////////////////////// THE STEP DOES NOT REQUIRE A MANDREL ///////////////////////
        //////////////////////////////////////////////////////////////////////////////////

            // echo ' does not need a mandrel';//TODO
        }
        
    }

    $minimum_time=$minimum_time-$steps[$i]['stp_minimum_time'];
}
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////// FUNCTION possibility_for_step /////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////// End //////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////// FUNCTION check_machine_availability //////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////// Beggining ////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function check_machine_availability($sector_id,$date,$mandrel_diameter,$session_key,$duration,$sleeve_length){
    // We go back to find the characteristics of the mandrel
    $mandrel=get_mandrel_by_id($_SESSION[$session_key]['mandrel_id']);
    // We look for the machines of the stage corresponding to the criterian return to look for the characteristics of the mandrel
    $machines=select_machines_by_sector_diameter_and_length($sector_id, $mandrel_diameter, $mandrel['mn_length']);

    if($machines != []){
        foreach($machines as $machine){
                                            
            // Depending on the type of lining, the right machine is chosen
            if($sector_id !='2' && $sector_id !='6' || ($machine['mc_id'] == 10 && $sector_id =='2') || ($machine['mc_id'] == 11 && $sector_id =='6')){
                
                // Check if the machine is not stopped (machine_stop)
                $machine_stop=verification_machine_stop_at_date($machine['mc_id'],$date);
                if($machine_stop == []){
                    // check si des heures sup sont prévues dans le secteur ce jour TODO
                
                    // Check the availability of the machine this day
                    $day_machine_jobs= select_job_duration_by_date_and_machine_id($date,$machine['mc_id']);
                    $machine_day_charge=0;
                    foreach($day_machine_jobs as $day_machine_job){
                        $day_machine_hours=date_create($day_machine_job['pt_expected_duration'])->format('H');
                        $day_machine_minutes=date_create($day_machine_job['pt_expected_duration'])->format('i');
                        $machine_day_charge=$machine_day_charge+$day_machine_hours*60+$day_machine_minutes;

                    }

                    // The machine capacity is converted into minutes to facilitate the comparison
                    $machine_capacity_hours=date_create($machine['mc_daily_hourly_capacity'])->format('H');
                    $machine_capacity_minutes=date_create($machine['mc_daily_hourly_capacity'])->format('i');
                    $machine_capacity=$machine_capacity_hours*60+$machine_capacity_minutes;
                    
                    if($machine_capacity >= ($machine_day_charge + $duration)){
                        // echo 'OK la machine est disponible, Id machine : '.$machine['mc_label'];
                        return $machine['mc_id'];
                    } else{
                        // echo 'machine indispo, on continu la boucle<br>';
                    }

                } else{
                    //TODO la machine est arreter, voir si c'est toute la journée ou qu'une partie
                }
                return false;
            }

        }
    } else {
        // No machine in production corresponding to the criteria, we return the impossibility of manufacturing
        return false;
    }
}

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////// FUNCTION check_machine_availability //////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////// End ///////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////// FUNCTION first_planningSimulation ///////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////// Beggining ///////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function first_planningSimulation($millnet_id,$customer_number,$customer_name,$csr,$piece_number,$deadline,$status,$saving_date,$product_type,$rubber,$sleeve_length,$table_length,$sleeve_offset,$mandrel_diameter,$notch,$notch_position,$developement,$fiber,$fiber_thickness,$chip,$cutback,$cutback_diameter,$form){
    
    
    $session_key=random_int(0,999999999);
    //recovery of id from Millnet values
    $product_type_id=get_product_type_id_by_label($product_type);
    $flow_id=get_flow_id_by_product_id($product_type_id);
    $rubber_id=get_rubber_id_by_label($rubber);
    $notch_id=get_notch_id_by_label($notch);
    $fiber_id=get_fiber_id_by_label($fiber);

    // Conversation of the possible commas in points for data of decimal type
    $sleeve_length=str_replace(",", ".", $sleeve_length);
    $table_length=str_replace(",", ".", $table_length);
    $sleeve_offset=str_replace(",", ".", $sleeve_offset);
    $mandrel_diameter=str_replace(",", ".", $mandrel_diameter);
    $developement=str_replace(",", ".", $developement);
    $fiber_thickness=str_replace(",", ".", $fiber_thickness);
    $cutback_diameter=str_replace(",", ".", $cutback_diameter);

    $planning['product_type_id']=$product_type_id;
    $planning['flow_id']=$flow_id;
    $planning['rubber_id']=$flow_id;
    $planning['notch_id']=$notch_id;
    $planning['fiber_id']=$fiber_id;
    $planning['sleeve_length']=$sleeve_length;
    $planning['mandrel_diameter']=$mandrel_diameter;
    $planning['developement']=$developement;
    $planning['fiber_thickness']=$fiber_thickness;
    $planning['cutback_diameter']=$cutback_diameter;


    // Create a new order with the status "awaiting validation" in database
    new_order($millnet_id,$customer_number,$customer_name,$csr,$piece_number,$deadline,'awaiting validation',$saving_date);


    // We test the feasibility within the deadline
    // For each piece
    for($n=1;$n<=$piece_number;$n++){
        // We build the piece ID from the Millnet number and the number of pieces
        if($n<10){
            $piece_id=$millnet_id.'_00'.$n;
            
        } else if($n<100){
            $piece_id=$millnet_id.'_0'.$n;
        } else {
            $piece_id=$millnet_id.'_'.$n;
        }

        
        // We create the piece in the database
        new_piece($piece_id, $millnet_id, $product_type_id, $rubber_id, $sleeve_length, $table_length, $sleeve_offset, $mandrel_diameter, $notch, $notch_position, $developement, $fiber_id, $fiber_thickness, $chip, $cutback, $cutback_diameter, $flow_id);

        // We want to check if we have the minimum required time available to manufacture the piece
        // We will look for the minimum time according to the workflow and the rubber
        $minimum_time=get_minimum_time_by_id_rubber_and_flow($rubber_id,$flow_id);

        // We make the difference between the desired date and the current date
        $now_base=date('Y-m-d');
        $now = new DateTime($now_base);
        $deadline_calc = new DateTime($deadline);
        $interval = $now->diff($deadline_calc);
        $available_time= $interval->format('%a');

        // We recover the sign (- if previous date)
        $available_sign= $interval->format('%R');
        
        // We check if the delay is sufficient
        if($available_time >= $minimum_time && $available_sign=='+'){
            // The minimum time required is respected, we can continue
            

            $deadline_task=$deadline;


            // We look at the manufacturing steps through the workflow
            $steps=get_steps_by_flow_id($flow_id);
            //echo '--------<br>PIECE '.$n.'=> <br>';
            //for each piece we look step by step since the end of the process
            
            

            //////////////////////////////////////////////////////////////////////////////////
            ////////////////////////////////// STEP BY STEP //////////////////////////////////
            /////////////////////////////////// Beggining ////////////////////////////////////

            for($i=20;$i>0;$i--){
                //only if the part exists
                if(isset($steps[$i])){
                    $result[$piece_number][$i]=possibility_for_step($steps,$mandrel_diameter, $form,$sleeve_length,$deadline_task,$i,$minimum_time,$now_base,$session_key);
                    

                    ///////////////////////////////////////////////////////////////////////////////////
                    ///////////////////////////// STAFF AVAILABILITY CHECK ////////////////////////////
                    //////////////////////////////////// Beggining ////////////////////////////////////

                    // Check availability of personnel on  $result[$piece_number][$i] at the workshop  $steps[$i]['stp_sector_id']
                    // Recovery of the number of available time per person of the sector at the date

                    //select operateur du secteur
                    $operators=select_operators_by_sector($steps[$i]['stp_sector_id']);
                    
                    if(isset($_SESSION[$session_key]['mandrel_id']) && $_SESSION[$session_key]['mandrel_id'] != null){

                        //verifier si le secteur du porteur correspond au secteur du step TODO
                        $mandrel=get_mandrel_by_id($_SESSION[$session_key]['mandrel_id']);
                        
                            if($steps[$i]['stp_sector_id'] == $mandrel['mn_sector_id']){
                                $planning[$piece_id][$steps[$i]['stp_label']]['mandrel_id']=$_SESSION[$session_key]['mandrel_id'];
                            } else {
                                $planning[$piece_id][$steps[$i]['stp_label']]['mandrel_id']=null;
                            }
                        

                        
                    }

                    // Task duration
                    $duration=calculate_task_duration($steps[$i]['stp_id'],$mandrel_diameter,$sleeve_length);
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

                    $planning[$piece_id][$steps[$i]['stp_label']]['during']=$duration_time;

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
                                
                                $planning[$piece_id][$steps[$i]['stp_label']]['date']=$result[$piece_number][$i];//TODO changer label en id
                                $planning[$piece_id][$steps[$i]['stp_label']]['operator']=$operator['us_id'];//TODO changer label en id
                                

                                ///////////////////////////////////////////////////////////////////////////////////
                                //////////////////////////// MACHINE AVAILABILITY CHECK ///////////////////////////
                                //////////////////////////////////// Beggining ////////////////////////////////////
                                    
                                    // We check the machine availability at the date
                                    $verif_machine=check_machine_availability($steps[$i]['stp_id'],$result[$piece_number][$i],$mandrel_diameter,$session_key,$duration,$sleeve_length);
                                    if($verif_machine == false){
                                        $planning['status']=false;
                                        $planning['reasons']='No machine available, it is therefore impossible to manufacture the order within this period';
                                        return $planning;
                                    }
                                    
                                    $planning[$piece_id][$steps[$i]['stp_label']]['machine']=$verif_machine;
                                    
                                    if($result[$piece_number][$i] != null){
                                        $d = strtotime($result[$piece_number][$i]);
                                        $deadline_task= date("Y-m-d", mktime(0,0,0,date("m", $d),date("d", $d)-1,date("Y", $d)));
                                    }
                                    // Save the tasks in planning task
                                    if(strpos($steps[$i]['stp_id'], 'stock') == false){
                                        if(!isset($planning[$piece_id][$steps[$i]['stp_label']]['mandrel_id'])){
                                            $mandrel_id=null;               
                                        } else {
                                            $mandrel_id=$planning[$piece_id][$steps[$i]['stp_label']]['mandrel_id'];
                                        }
                                        
                                        new_planning_task($piece_id, $steps[$i]['stp_id'], $planning[$piece_id][$steps[$i]['stp_label']]['during'], $result[$piece_number][$i], $planning[$piece_id][$steps[$i]['stp_label']]['machine'], $planning[$piece_id][$steps[$i]['stp_label']]['operator'],  $mandrel_id);//TODO changer la durée
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


                    
                }
            }

            

            //////////////////////////////////////////////////////////////////////////////////
            ////////////////////////////////// STEP BY STEP //////////////////////////////////
            ////////////////////////////////////// End ///////////////////////////////////////

        } else{
            // The minimum time required is not respected, we return the fact that the time is impossible
            $planning['status']=false;
            $planning['reasons']= trad('minimum_time_required_not_respected_impossible_to_manufacture',$_SESSION["language"]);
            return $planning;
        }

        $ebauche_date=null;

        if(isset($planning[$piece_id]['garnissage machine']) && isset($planning[$piece_id]['ebauche'])){
            $d = strtotime($planning[$piece_id]['garnissage machine']['date']);
            $ebauche_date = date("Y-m-d", mktime(0,0,0,date("m", $d),date("d", $d)+1,date("Y", $d)));
        }

        if(isset($planning[$piece_id]['garnissage manuel']) && isset($planning[$piece_id]['ebauche'])){
            $d = strtotime($planning[$piece_id]['garnissage manuel']['date']);
            $ebauche_date = date("Y-m-d", mktime(0,0,0,date("m", $d),date("d", $d)+1,date("Y", $d)));
        }
        $planning[$piece_id]['ebauche']['date']=$ebauche_date;

        // We create the piece

        //TODO verifier avant si une piece avec cette id n'exite pas déjà
        new_piece($piece_id, $millnet_id, $product_type_id, $rubber_id, $sleeve_length, $table_length, $sleeve_offset, $mandrel_diameter, $notch, $notch_position, $developement, $fiber_id, $fiber_thickness, $chip, $cutback, $cutback_diameter, $flow_id);

        
    }
    
    $planning['status']=true;
    return $planning;
}
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////// FUNCTION first_planningSimulation ///////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////// End //////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////// FUNCTION plan /////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////// Beggining ///////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    function plan($session_key,$order,$millnet_id,$steps,$step_date){
        $planning=null;
        // At each step (in order)
        $nb_step=0;
        foreach($steps as $step){
            $nb_step++;
            // For each piece
            for($n=1;$n<=$order['temp_pieces_number'];$n++){
                
                // We build the piece ID from the Millnet number and the number of pieces
                if($n<10){
                    $piece_id=$millnet_id.'_00'.$n;
                    
                } else if($n<100){
                    $piece_id=$millnet_id.'_0'.$n;
                } else {
                    $piece_id=$millnet_id.'_'.$n;
                }
                // echo '<br>'.$piece_id. ' => '.$step['stp_label'].'<br>';
    
                // Selection of the carrier corresponding to the technical characteristics if the stage requires one
                if($step['stp_needs_mandrel']== 1){
                    $mandrels=get_mandrel_id_by_specifications($order['temp_mandrel_diameter'], $order['temp_mandrel_form'],$order['temp_sleeve_length'],$step['stp_sector_id']);

                    $test_mandrel=0;
                    while($test_mandrel==0){
                        $test_day=0;
                        while( $test_day==0){
                            $tomorrow=new DateTime($step_date);
                            $tomorrow->modify('+1 day');
                            $tomorrow_string = $tomorrow->format('Y-m-d');
                            $step_date=$tomorrow_string;
    
                            
                            list($year, $month, $day) = explode('-', $tomorrow_string);
                            $timestamp = mktime (0, 0, 0, $month, $day, $year);
                        
                            $day_number = DAYSWEEK[date("w",$timestamp)];
                            if($day_number!=1 && $day_number != 7){
                                // It is not a Saturday or a Sunday so the new date to test is the right one
                                $test_day=1;
                            } else{
                                // Otherwise we continue to loop to test another day
                                $test_day=0;
                            }
                        }
                        
                        foreach($mandrels as $mandrel){
                            $dispo=count_task_at_date_with_mandrel_id($step_date,$mandrel['mn_id']);
                            if($dispo == 0){
                                //mandrel available
    
                                $_SESSION[$session_key]['mandrel_id']=$mandrel['mn_id'];

                                $operators=select_operators_by_sector($step['stp_sector_id']);
    
                                $duration=calculate_task_duration($step['stp_id'],$order['temp_mandrel_diameter'],$order['temp_sleeve_length']);
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
                                $planning[$piece_id][$step['stp_label']]['during']=$duration_time;
                                
                                foreach($operators as $operator){
                                    $presence=verification_operator_presence_at_date($operator['us_id'],$step_date);
                                    $operator_default_time=select_operator_default_time_by_id($operator['us_id']);
                                    if($presence == []){
                                        // Check if present in overtime
                                        $overtime=verification_operator_overtime_at_date($operator['us_id'],$step_date);
                                        if($overtime == []){
                                            $additional_time=0;
                                        } else {
                                            $additional_time = $overtime[0]['oo_during'];
                                        }
                                        $operation_production_time= $operator_default_time[0]['otd_production_time']+$additional_time;
    
    
                                        // Work assigned this day
                                        $day_jobs= select_job_duration_by_date_and_user_id($step_date,$operator['us_id']);
    
                                        $dispo_time_hours=floor($operation_production_time);
                                        $dispo_time_minutes=($operation_production_time-$dispo_time_hours)*60;
                                        $dispo_time=$dispo_time_hours*60+$dispo_time_minutes;
                                        
                                        foreach($day_jobs as $day_job){
                                            $day_jobs_hours=date_create($day_job['pt_expected_duration'])->format('H');
                                            $day_jobs_minutes=date_create($day_job['pt_expected_duration'])->format('i');
                                            $day_jobs_during=$day_jobs_hours*60+$day_jobs_minutes;
                                            $dispo_time=$dispo_time-$day_jobs_during;
                                        }
                                        if(($duration+$dispo_time)>=0){
                                            $planning[$piece_id][$step['stp_label']]['date']=$step_date;//TODO changer label en id
                                            $planning[$piece_id][$step['stp_label']]['operator']=$operator['us_id'];//TODO changer label en id    
                                            
                                            $verif_machine=check_machine_availability($step['stp_sector_id'],$step_date,$order['temp_mandrel_diameter'],$session_key,$duration,$order['temp_sleeve_length']);
                                            
                                            if($verif_machine != false){
                                                $planning[$piece_id][$step['stp_label']]['machine']=$verif_machine;
                                            }
                                            
                                            if($step_date != null){
                                                $d = strtotime($step_date);
                                                $deadline_task= date("Y-m-d", mktime(0,0,0,date("m", $d),date("d", $d)-1,date("Y", $d)));
                                            }
    
                                            
                                            // Save the tasks in planning task
                                            if(strpos($step['stp_id'], 'stock') == false){
                                                if(!isset($planning[$piece_id][$step['stp_label']]['mandrel_id'])){
                                                    $mandrel_id=null;               
                                                } else {
                                                    $mandrel_id=$planning[$piece_id][$step['stp_label']]['mandrel_id'];
                                                }
                                                
                                                new_planning_task($piece_id, $step['stp_id'], $duration_time, $step_date, $planning[$piece_id][$step['stp_label']]['machine'], $planning[$piece_id][$step['stp_label']]['operator'],  $mandrel_id);
                                            }
                                        
                                        
                                        
                                        }
    
    
    
    
                                    } else{
                                        // echo 'opérateur absent'; //TODO
                                    }
                                }
    
                                $test_mandrel=1;
                                break;
                            } else{
                                // echo '<br>Porteur indispo<br>';//TODO
                            }
                        }
                        
                    }
    
    
    
                } else {
                    if($step['stp_label'] == 'ebauche'){
                        $last_step=new DateTime($step_date);
                        $last_step->modify('+3 day');
                        $last_step_string = $last_step->format('Y-m-d');
                        $step_date=$last_step_string;
                    }
    
                    $test=0;
                    while($test==0){
                        // Select operator of the sector
                        $operators=select_operators_by_sector($step['stp_sector_id']);
                        foreach($operators as $operator){
                            // $presence=verification_operator_presence_at_date($operator['us_id'],$date); //TODO
                            // $operator_default_time=select_operator_default_time_by_id($operator['us_id']); //TODO
    
                        
                        }
    
                        $test=1;
    
                    }
                }
            }
        }
        $deadline= new DateTime($planning[$piece_id]['rectification']['date']);
        $deadline->modify('+1 day');
        $deadline_string = $deadline->format('Y-m-d');
        $planning['deadline']=$deadline_string;
        return $planning;
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////// FUNCTION plan /////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////// End //////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////