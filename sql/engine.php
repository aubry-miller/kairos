<?php
function first_planningSimulation($millnet_id,$customer_number,$customer_name,$csr,$piece_number,$deadline,$status,$saving_date,$product_type,$rubber,$sleeve_lenght,$table_lenght,$sleeve_offset,$mandrel_diameter,$notch,$notch_position,$developement,$fiber,$fiber_thickness,$chip,$cutback,$cutback_diameter,$form){
    // Table of days of the week
    $Daysweek = array('1', '2', '3', '5', '5', '6', '7'); //7=>Sunday, 1=>Monday , 2=>Tuesday , 3=>Wednesday, 4=>Thursday , 5=>Friday, 6=>Saturday
    
    // Create a new order with the status "awaiting validation" in database
    //new_order($millnet_id,$customer_number,$customer_name,$csr,$piece_number,$deadline,$status,$saving_date);

    //recovery of id from Millnet values
    $product_type_id=get_product_type_id_by_label($product_type);
    $flow_id=get_flow_id_by_product_id($product_type_id);
    $rubber_id=get_rubber_id_by_label($rubber);
    $notch_id=get_notch_id_by_label($notch);
    $fiber_id=get_fiber_id_by_label($fiber);

    // Conversation of the possible commas in points for data of decimal type
    $sleeve_lenght=str_replace(",", ".", $sleeve_lenght);
    $table_lenght=str_replace(",", ".", $table_lenght);
    $sleeve_offset=str_replace(",", ".", $sleeve_offset);
    $mandrel_diameter=str_replace(",", ".", $mandrel_diameter);
    $developement=str_replace(",", ".", $developement);
    $fiber_thickness=str_replace(",", ".", $fiber_thickness);
    $cutback_diameter=str_replace(",", ".", $cutback_diameter);

    // Creation of as many pieces as necessary in database
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
        //new_piece($piece_id, $millnet_id, $product_type_id, $rubber_id, $sleeve_lenght, $table_lenght, $sleeve_offset, $mandrel_diameter, $notch, $notch_position, $developement, $fiber_id, $fiber_thickness, $chip, $cutback, $cutback_diameter, $flow_id);

        // We want to check if we have the minimum required time available to manufacture the piece
        // We will look for the minimum time according to the workflow and the rubber
        $minimum_time=get_minimum_time_by_id_rubber_and_flow($rubber_id,$flow_id);

        // We make the difference between the desired date and the current date
        $now=date('Y-m-d');
        $now = new DateTime($now);
        $deadline_calc = new DateTime($deadline);
        $interval = $now->diff($deadline_calc);
        $available_time= $interval->format('%a');

        // We recover the sign (- if previous date)
        $available_sign= $interval->format('%R');
        
        // We check if the delay is sufficient
        if($available_time >= $minimum_time && $available_sign=='+'){
            // The minimum time required is respected, we can continue
            
            //Once the data is recorded, we look at the manufacturing steps through the workflow
            $steps=get_steps_by_flow_id($flow_id);
            echo '--------<br>PIECE '.$n.'=> <br>';
            //for each piece we look step by step since the end of the process
            for($i=20;$i>0;$i--){
                //only if the part exists
                if(isset($steps[$i])){
                    echo $steps[$i]['stp_label'];
                    // We check if the step minimum time is differente than 0
                    if($steps[$i]['stp_minimum_time'] != 0){
                        // The step needs a mandrel
                        echo ' minimum during '.$steps[$i]['stp_minimum_time'].' day(s),';

                        // We check if the step requires a mandrel
                        if($steps[$i]['stp_needs_mandrel'] == 1){
                            // The step needs a mandrel
                            echo ' needs a mandrel <br>';

                            // We select the mandrels id
                            $mandrel_ids = get_mandrel_id_by_specifications($mandrel_diameter, $form,$sleeve_lenght,$steps[$i]['stp_sector_id']);
                           
                            // We look if a carrier of the right diameter is used in the period
                            $others=get_mandrel_use_in_period($mandrel_diameter, $steps[$i]['stp_id'],date('Y-m-d'),$deadline);
                            
                            // We loop to see if we still have time, depending on the day of the week
                            foreach ($others as $other){
                                // We get the date on which the start of production is planned, and we remove the time
                                $other_date=substr($other['pt_planned_start_date'], 0, 10);
                                var_dump($other_date);
                                $tomorrow=new DateTime($other_date);
                                $tomorrow->modify('+1 day');
                                $tomorrow_string = $tomorrow->format('Y-m-d H:i:s');
                                $yesterday=new DateTime($other_date);
                                $yesterday->modify('-1 day');
                                $yesterday_string = $yesterday->format('Y-m-d H:i:s');
                                // Extraction of the day, month, year of the date
                                list($day, $month, $year) = explode('-', $other_date);
                                // Timestamp calculation
                                $timestamp = mktime (0, 0, 0, $month, $day, $year);
                                // Day of the week
                                $day_number = $Daysweek[date("w",$timestamp)];
                                echo 'day =>'.$day_number.'<br>';
                                if($day_number==1 || $day_number == 7){
                                    echo 'date planned =>'.$other_date.' On regarde les dispos à j+1 <br>';
                                    
                                    foreach($mandrel_ids as $mandrel_id){
                                        //regarder dans planning task si ce jour (n+1) le mandrel avec cet id est occupé ou non
                                        $dispo=count_task_at_date_with_mandrel_id($tomorrow_string, $mandrel_id['mn_id']);
                                        if($dispo != 0){
                                            echo 'non dispo'.$tomorrow_string.' <br>';
                                        } else {
                                            echo 'dispo'.$tomorrow_string.' <br>';
                                        }
                                    }

                                } else if ($day_number==2 || $day_number == 3 || $day_number == 4){
                                    echo 'date planned =>'.$other_date.' On regarde les dispos à j+1 et j-1 <br>';
                                    foreach($mandrel_ids as $mandrel_id){
                                        //regarder dans planning task si ce jour (n+-1) le mandrel avec cet id est occupé ou non
                                        $dispo_tomorrow=count_task_at_date_with_mandrel_id($tomorrow_string, $mandrel_id['mn_id']);
                                        if($dispo_tomorrow != 0){
                                            echo 'non dispo le '.$tomorrow_string.' <br>';
                                        } else {
                                            echo 'dispo le '.$tomorrow_string.' <br>';
                                        }

                                        $dispo_yesterday=count_task_at_date_with_mandrel_id($yesterday_string, $mandrel_id['mn_id']);
                                        if($dispo_yesterday != 0){
                                            echo 'non dispo le '.$yesterday_string.' <br>';
                                        } else {
                                            echo 'dispo le '.$yesterday_string.' <br>';
                                        }
                                    }
                                } else if ($day_number=5 || $day_number == 6){
                                    echo 'date planned =>'.$other_date.' On regarde les dispos à j-1 <br>';

                                    foreach($mandrel_ids as $mandrel_id){
                                        //regarder dans planning task si ce jour (n-1) le mandrel avec cet id est occupé ou non
                                        
                                        $dispo_yesterday=count_task_at_date_with_mandrel_id($yesterday_string, $mandrel_id['mn_id']);
                                        if($dispo_yesterday != 0){
                                            echo 'non dispo le '.$yesterday_string.' <br>';
                                        } else {
                                            echo 'dispo le '.$yesterday_string.' <br>';
                                        }
                                    }
                                }
                            }



                            
                        } else {
                            // The step does not need a mandrel
                            echo ' does not need a mandrel';
                        }
                    }
                    echo '<br>';

                    $minimum_time=$minimum_time-$steps[$i]['stp_minimum_time'];
                    echo ' minimum time' .$minimum_time .'<br>';
                }
            }
            echo '<br>';
        } else{
            // The minimum time required is not respected, we return the fact that the time is impossible
            return 'impossible';
        }


        
    }
}

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