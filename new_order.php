<?php
include('sql/connect.php');
include('sql/get.php');
include('sql/engine.php');
session_start();
?>

<!DOCTYPE html>
<html lang="fr" class="<?php echo $_SESSION['mode'];?>">
    <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <link href="images/logo.svg" rel="shortcut icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Icewall admin is super flexible, powerful, clean & modern responsive bootstrap admin template with unlimited possibilities.">
        <meta name="keywords" content="admin template, Icewall Admin Template, dashboard template, flat admin template, responsive admin template, web app">
        <meta name="author" content="LEFT4CODE">
        <title><?php echo trad('new_order',$_SESSION["language"]);?></title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="dist/css/app.css" />
        <!-- END: CSS Assets-->
        </script>
        </head>
<!-- END: Head -->
<body class="main">
        <?php include('contents/header.php');
        $orders=select_all_temp_orders();
        $awaiting_orders=select_all_orders_awainting();
        ?>
        <h4 class="fs-xl fw-medium lh-1 mt-3 mb-6"><?php echo trad('orders_awaiting_planning',$_SESSION["language"]);?></h4>

        <div class="overflow-x-auto">
                <table class="table mt-40">
                        <thead class="table-light">
                                <tr>
                                <th class="border-bottom-0 text-gray-700 text-nowrap"><?php echo trad('order_number',$_SESSION["language"]);?></th>
                                <th class="border-bottom-0 text-gray-700 text-nowrap"><?php echo trad('customer',$_SESSION["language"]);?></th>
                                <th class="border-bottom-0 text-gray-700 text-nowrap"><?php echo trad('csr',$_SESSION["language"]);?></th>
                                <th class="border-bottom-0 text-gray-700 text-nowrap"><?php echo trad('pieces_number',$_SESSION["language"]);?></th>
                                <th class="border-bottom-0 text-gray-700 text-nowrap"><?php echo trad('deadline',$_SESSION["language"]);?></th>
                                <th class="border-bottom-0 text-gray-700 text-nowrap"><?php echo trad('status',$_SESSION["language"]);?></th>
                                <th class="border-bottom-0 text-gray-700 text-nowrap"></th>
                                <tr>
                        </thead>
                        <tbody>
                                <?php
                                foreach ($orders as $order){
                                        ?>
                                        <tr>
                                                <td>
                                                        <?php echo $order['temp_millnet_id'].'-'.$order['temp_millnet_part_id'];?>
                                                </td>
                                                <td>
                                                        <?php echo $order['temp_customer_name'];?>
                                                </td>
                                                <td>
                                                        <?php echo $order['temp_csr_name'];?>
                                                </td>
                                                <td>
                                                        <?php echo $order['temp_pieces_number'];?>
                                                </td>
                                                <td>
                                                        <?php echo $order['temp_deadline'];?>
                                                </td>
                                                <td>
                                                        waiting
                                                </td>
                                                <td>
                                                        <form action="planning_calculator.php" method="get">
                                                                <input type="hidden" name="temp_id" value="<?php echo $order['temp_id'];?>">
                                                                <input type="hidden" name="millnet_order_id" value="<?php echo $order['temp_millnet_id'];?>">
                                                                <input type="hidden" name="millnet_order_part_id" value="<?php echo $order['temp_millnet_part_id'];?>">
                                                                <input type="hidden" name="customer_number" value="<?php echo $order['temp_customer_number'];?>">
                                                                <input type="hidden" name="customer_name" value="<?php echo $order['temp_customer_name'];?>">
                                                                <input type="hidden" name="csr" value="<?php echo $order['temp_csr_name'];?>">
                                                                <input type="hidden" name="piece_number" value="<?php echo $order['temp_pieces_number'];?>">
                                                                <input type="hidden" name="deadline" value="<?php echo $order['temp_deadline'];?>">
                                                                <input type="hidden" name="saving_date" value="<?php echo $order['temp_saving_date'];?>">
                                                                <input type="hidden" name="product_type" value="<?php echo $order['temp_product_type'];?>">
                                                                <input type="hidden" name="rubber" value="<?php echo $order['temp_rubber'];?>">
                                                                <input type="hidden" name="sleeve_length" value="<?php echo $order['temp_sleeve_length'];?>">
                                                                <input type="hidden" name="table_length" value="<?php echo $order['temp_table_length'];?>">
                                                                <input type="hidden" name="sleeve_offset" value="<?php echo $order['temp_sleeve_offset'];?>">
                                                                <input type="hidden" name="mandrel_diameter" value="<?php echo $order['temp_mandrel_diameter'];?>">
                                                                <input type="hidden" name="notch" value="<?php echo $order['temp_notch'];?>">
                                                                <input type="hidden" name="notch_position" value="<?php echo $order['temp_notch_position'];?>">
                                                                <input type="hidden" name="developement" value="<?php echo $order['temp_developement'];?>">
                                                                <input type="hidden" name="fiber" value="<?php echo $order['temp_fiber'];?>">
                                                                <input type="hidden" name="fiber_thickness" value="<?php echo $order['temp_fiber_thickness'];?>">
                                                                <input type="hidden" name="chip" value="<?php echo $order['temp_chip'];?>">
                                                                <input type="hidden" name="cutback" value="<?php echo $order['temp_cutback'];?>">
                                                                <input type="hidden" name="cutback_diameter" value="<?php echo $order['temp_cutback_diameter'];?>">
                                                                
                                                                <input class="btn btn-primary mt-5" type="submit" value="Calculer le planning" name="calcul" id="calcul">
                                                        </form>
                                                </td>
                                        </tr>
                                        <?php
                                }
                                foreach ($awaiting_orders as $awaiting_order){
                                        // var_dump($awaiting_order);die();
                                        ?>
                                        
                                        <tr>
                                                <td>
                                                        <?php echo $awaiting_order['od_millnet_id'];?>
                                                </td>
                                                <td>
                                                        <?php echo $awaiting_order['od_customer_name'];?>
                                                </td>
                                                <td>
                                                        <?php echo $awaiting_order['od_csr_name'];?>
                                                </td>
                                                <td>
                                                        <?php echo $awaiting_order['od_pieces_number'];?>
                                                </td>
                                                <td>
                                                        <?php echo $awaiting_order['od_deadline'];?>
                                                </td>
                                                <td>
                                                        <?php echo $awaiting_order['od_status'];?>
                                                </td>
                                                <td>
                                                        <form action="planning_decide.php" method="get">
                                                                <input type="hidden" name="millnet_id" value="<?php echo $awaiting_order['od_millnet_id'];?>">
                                                                
                                                                <input class="btn btn-primary mt-5" type="submit" value="Statuer sur le planning" name="decide" id="decide">
                                                        </form>
                                                </td>
                                        </tr>
                                        <?php
                                }
                                ?>
                        </tbody>
                </table>
        </div>
        <?php


        include('contents/footer.php'); ?>

</body>
</html>
