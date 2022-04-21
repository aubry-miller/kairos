<?php
function first_planningSimulation($millnet_id,$customer_number,$customer_name,$csr,$piece_number,$deadline,$status,$saving_date,$product_type,$rubber,$sleeve_lenght,$table_lenght,$sleeve_offset,$mandrel_diameter,$notch,$notch_position,$developement,$fiber,$fiber_thickness,$chip,$cutback,$cutback_diameter){
    // Create a new order with the status "awaiting validation" in database
    new_order($millnet_id,$customer_number,$customer_name,$csr,$piece_number,$deadline,$status,$saving_date);

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
        new_piece($piece_id, $millnet_id, $product_type_id, $rubber_id, $sleeve_lenght, $table_lenght, $sleeve_offset, $mandrel_diameter, $notch, $notch_position, $developement, $fiber_id, $fiber_thickness, $chip, $cutback, $cutback_diameter, $flow_id);

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

            echo 'piece '.$n.'=> <br>';
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
                            echo ' needs a mandrel';



                            
                        } else {
                            // The step does not need a mandrel
                            echo ' does not need a mandrel';
                        }
                    }
                    echo '<br>';
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