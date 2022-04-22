<?php
include('sql/connect.php');
include('sql/get.php');
include('sql/set.php');
include('sql/engine.php');

//received data

//for test
$millnet_order_id='00569491';
$millnet_order_part_id='001-000';
$millnet_id= $millnet_order_id.'-'.$millnet_order_part_id;

$customer_number='11111';
$customer_name='customerTest';
$csr='Olivier Pouzeau';
$piece_number=2;
$deadline=date('2022-06-21');
$status='awaiting validation';
$saving_date= date('Y-m-d H:i:s');

$form='parallel';
$product_type='engraving sleeves';
$rubber= '166014K';
$sleeve_lenght='1100';
$table_lenght='1000';
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


$plan_result=first_planningSimulation($millnet_id,$customer_number,$customer_name,$csr,$piece_number,$deadline,$status,$saving_date,$product_type,$rubber,$sleeve_lenght,$table_lenght,$sleeve_offset,$mandrel_diameter,$notch,$notch_position,$developement,$fiber,$fiber_thickness,$chip,$cutback,$cutback_diameter,$form);
echo $plan_result;