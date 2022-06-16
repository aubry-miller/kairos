<?php
include('sql/connect.php');
include('sql/get.php');
include('sql/set.php');
include('sql/delete.php');
include('sql/engine.php');
include('infos.php');
session_start();

//received data

//for test
$millnet_order_id=$_GET['millnet_order_id'];
$millnet_order_part_id=$_GET['millnet_order_part_id'];
$millnet_id= $millnet_order_id.'-'.$millnet_order_part_id;

$customer_number=$_GET['customer_number'];
$customer_name=$_GET['customer_name'];
$csr=$_GET['csr'];
$piece_number=$_GET['piece_number'];
$deadline=date($_GET['deadline']);
$status='awaiting validation';
$saving_date=$_GET['saving_date'];
$form='parallel';
$product_type=$_GET['product_type'];
$rubber= $_GET['rubber'];
$sleeve_length=$_GET['sleeve_length'];
$table_length=$_GET['table_length'];
$sleeve_offset=$_GET['sleeve_offset'];
$mandrel_diameter=$_GET['mandrel_diameter'];
$notch= $_GET['notch'];
$notch_position= $_GET['notch_position'];
$developement= $_GET['developement'];
$fiber= $_GET['fiber'];
$fiber_thickness= $_GET['fiber_thickness'];
$chip= $_GET['chip'];
$cutback=$_GET['cutback'];
$cutback_diameter=$_GET['cutback_diameter'];


$plan_result=first_planningSimulation($millnet_id,$customer_number,$customer_name,$csr,$piece_number,$deadline,$status,$saving_date,$product_type,$rubber,$sleeve_length,$table_length,$sleeve_offset,$mandrel_diameter,$notch,$notch_position,$developement,$fiber,$fiber_thickness,$chip,$cutback,$cutback_diameter,$form);

if($plan_result['status'] == false){

    echo $plan_result['reasons'];
    delete_order($millnet_id);

    ?>
    <br>
    <br>
    <div>
        <?php echo trad('Want_to_know_the_best_delay?',$_SESSION["language"]);?>
        <form action="back/best_deadline.php" method="get">
            <?php
            if(isset($_GET['temp_id'])){
                ?>
                <input type="hidden" name="temp_id" value="<?php echo $_GET['temp_id'];?>">
                <?php
            }
            ?>
            <input type="hidden" name="millnet_id" value="<?php echo $millnet_id;?>">
            <input type="submit" name="submit" value="Yes">
            <?php
            ?>
        </form>
    </div>
    <br>
    <div>
        <?php echo trad('Want_to_chek_orphan_sleeve_For_best_delay?',$_SESSION["language"]);?>
        <form action="orphan_sleeve.php" method="get">
            <?php
            if(isset($_GET['temp_id'])){
                ?>
                <input type="hidden" name="temp_id" value="<?php echo $_GET['temp_id'];?>">
                <?php
            }
            ?>
            <input type="hidden" name="millnet_id" value="<?php echo $millnet_id;?>">
            <input type="submit" name="submit" value="Yes">
            <?php
            ?>
        </form>
    </div>
    <br>
    <div>
        <?php echo trad('Whant_to_delete_temp_order?',$_SESSION["language"]);?>
        <form action="back/best_deadline.php" method="get">
            <?php
            if(isset($_GET['temp_id'])){
                ?>
                <input type="hidden" name="temp_id" value="<?php echo $_GET['temp_id'];?>">
                <?php
            }
            ?>
            <input type="hidden" name="millnet_id" value="<?php echo $millnet_id;?>">
            <input type="submit" name="submit" value="Delete">
            <?php
            ?>
        </form>
    </div>
    
    <?php
}
else {
    if(isset($_GET['temp_id'])){
        delete_temp_order($_GET['temp_id']);
    }

    if($piece_number<10){
        $id_last_piece=$millnet_id.'_00'.$piece_number;
        
    } else if($piece_number<100){
        $id_last_piece=$millnet_id.'_0'.$piece_number;
    } else {
        $id_last_piece=$millnet_id.'_'.$piece_number;
    }

    echo 'The fabrication is possible.<br> Deadline => '.$plan_result[$id_last_piece]['rectification']['date'].'<br>';
    echo '<hr>';


    for($i=1;$i<=$piece_number;$i++){
        if($i<10){
            $piece_id=$millnet_id.'_00'.$i;
            
        } else if($i<100){
            $piece_id=$millnet_id.'_0'.$i;
        } else {
            $piece_id=$millnet_id.'_'.$i;
        }
        echo'------- '. $piece_id.' -------<br>';
        
        $steps=get_steps_by_flow_id($plan_result['flow_id']);

        foreach($steps as $step){ 
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
            if(isset($plan_result[$piece_id][$step['stp_label']]['during'])){
                echo 'Durée => '.$plan_result[$piece_id][$step['stp_label']]['during'].'<br>';
            }
            
        }
        
        echo '<hr>';

    }

    ?>
    <br>
    Confirm order ?
    <form action="back/confirm.php" method="get">
        <input type="hidden" name="piece_number" value="<?php echo $piece_number;?>">
        <input type="hidden" name="millnet_id" value="<?php echo $millnet_id;?>">
        <input type="submit" name="submit" value="Yes">
        <input type="submit" name="submit" value="&#x260E; Hold the date">
        <input type="submit" name="submit" value="No">
        <?php
        ?>
    </form>
    <?php
}