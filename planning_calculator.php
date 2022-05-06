<?php
include('sql/connect.php');
include('sql/get.php');
include('sql/set.php');
include('sql/engine.php');
include('infos.php');

//received data

//for test
$millnet_order_id='00111111';
$millnet_order_part_id='001-000';
$millnet_id= $millnet_order_id.'-'.$millnet_order_part_id;

$customer_number='11122';
$customer_name='customerTest';
$csr='Olivier Pouzeau';
$piece_number=2;
$deadline=date('2022-06-07');
// $deadline=date('2022-05-07'); //Wrong date for test
$status='awaiting validation';
$saving_date= date('Y-m-d H:i:s');

$form='parallel';
$product_type='engraving sleeves';
$rubber= '166014K';
$sleeve_length='1100';
$table_length='1000';
$sleeve_offset='0';
$mandrel_diameter='78';
$notch= '20';
$notch_position= 'Left';
$developement= '254';
$fiber= 'standard';
$fiber_thickness= '1.3';
$chip= '0';
$cutback='0';
$cutback_diameter='0';


$plan_result=first_planningSimulation($millnet_id,$customer_number,$customer_name,$csr,$piece_number,$deadline,$status,$saving_date,$product_type,$rubber,$sleeve_length,$table_length,$sleeve_offset,$mandrel_diameter,$notch,$notch_position,$developement,$fiber,$fiber_thickness,$chip,$cutback,$cutback_diameter,$form);


if($plan_result['status'] == false){
    echo $plan_result['reasons'];
}
else {

    if($piece_number<10){
        $id_last_piece=$millnet_id.'_00'.$piece_number;
        
    } else if($piece_number<100){
        $id_last_piece=$millnet_id.'_0'.$piece_number;
    } else {
        $id_last_piece=$millnet_id.'_'.$piece_number;
    }

    echo 'The fabrication is possible.<br> Deadline => '.$plan_result[$id_last_piece]['rectification']['date'].'<br>';


    


    // $planning['product_type_id']=$product_type_id;
    // $planning['flow_id']=$flow_id;
    // $planning['rubber_id']=$flow_id;
    // $planning['notch_id']=$notch_id;
    // $planning['fiber_id']=$fiber_id;
    // $planning['sleeve_length']=$sleeve_length;
    // $planning['mandrel_diameter']=$mandrel_diameter;
    // $planning['developement']=$developement;
    // $planning['fiber_thickness']=$fiber_thickness;
    // $planning['cutback_diameter']=$cutback_diameter;

    for($i=1;$i<=$piece_number;$i++){
        if($i<10){
            $piece_id=$millnet_id.'_00'.$i;
            
        } else if($i<100){
            $piece_id=$millnet_id.'_0'.$i;
        } else {
            $piece_id=$millnet_id.'_'.$i;
        }
        echo'<br>------- '. $piece_id.' -------<br>';
        
        $steps=get_steps_by_flow_id($plan_result['flow_id']);

        foreach($steps as $step){ //$plan_result[$piece_id] as $result
            // var_dump($step);
            echo '<br>'.strtoupper($step['stp_label']).'<br>';
            if(isset($plan_result[$piece_id][$step['stp_label']]['date']) && $plan_result[$piece_id][$step['stp_label']]['date'] != null){
                echo 'Date => '.$plan_result[$piece_id][$step['stp_label']]['date'].'<br>';
            }
            if(isset($plan_result[$piece_id][$step['stp_label']]['mandrel_id'])){
                echo 'Mandrel => '.$plan_result[$piece_id][$step['stp_label']]['mandrel_id'].'<br>';
            }
            if(isset($plan_result[$piece_id][$step['stp_label']]['operator'])){
                $operator=select_operators_by_id($plan_result[$piece_id][$step['stp_label']]['operator']);
                echo 'Operator => '.$operator['us_firstname'].' '.$operator['us_name'].'<br>';
            }
            if(isset($plan_result[$piece_id][$step['stp_label']]['machine'])){
                $machine=select_machines_by_id($plan_result[$piece_id][$step['stp_label']]['machine']);
                echo 'Machine => '.$machine['mc_label'].'<br>';
            }
        }
        
        

    }

}