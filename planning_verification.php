<?php
include('sql/connect.php');
include('sql/get.php');
session_start();

$title = trad('orders_awaiting_planning',$_SESSION["language"]);
?>

<!DOCTYPE html>
<html lang="fr" class="<?php echo $_SESSION['mode'];?>">
    <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <!--<link href="images/logo.svg" rel="shortcut icon">-->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Icewall admin is super flexible, powerful, clean & modern responsive bootstrap admin template with unlimited possibilities.">
        <meta name="keywords" content="admin template, Icewall Admin Template, dashboard template, flat admin template, responsive admin template, web app">
        <meta name="author" content="LEFT4CODE">
        <title><?php echo trad('files_awaiting_planning',$_SESSION["language"]);?></title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="dist/css/app.css" />
        <!-- END: CSS Assets-->
        </script>
        </head>
<!-- END: Head -->
<body class="main">
        <?php include('contents/header.php');?>




<ul>
    <li>Numéro Millnet : <?php echo $_GET['millnet_order_id'].'-'.$_GET['millnet_order_part_id'];?></li>
    <li>Client : <?php echo $_GET['customer_name'];?></li>
    <li>CSR : <?php echo $_GET['csr'];?></li>
    <li>Nombre de pièces : <?php echo $_GET['piece_number'];?></li>
    <li>Deadline : <?php echo $_GET['deadline'];?></li>
    <li>Type de produit : <?php echo $_GET['product_type'];?></li>
    <li>Type de gomme : <?php echo $_GET['rubber'];?></li>
    <li>Laize : <?php echo $_GET['sleeve_length'];?></li>
    <li>Table : <?php echo $_GET['table_length'];?></li>
    <li>Décalage table :<?php echo $_GET['sleeve_offset'];?></li>
    <li>ø porteur : <?php echo $_GET['mandrel_diameter'];?></li>
    <li>Encoche : <?php echo $_GET['notch'];?></li>
    <li>Position des encoches : <?php echo $_GET['notch_position'];?></li>
    <li>Développement : <?php echo $_GET['developement'];?></li>
    <li>Fibre : <?php echo $_GET['fiber'];?></li>
    <li>Épaisseur fibre : <?php echo $_GET['fiber_thickness'];?></li>
    <li>Puce : <?php echo $_GET['chip'];?></li>
    <li>Épaulement : <?php echo $_GET['cutback'];?></li>
    <li>Diamètre de l'épaulement : <?php echo $_GET['cutback_diameter'];?></li>
</ul>

<form action="planning_calculator.php" method="get">
    <input type="hidden" name="temp_id" value="<?php echo $_GET['temp_id'];?>">
    <input type="hidden" name="millnet_order_id" value="<?php echo $_GET['millnet_order_id'];?>">
    <input type="hidden" name="millnet_order_part_id" value="<?php echo $_GET['millnet_order_part_id'];?>">
    <input type="hidden" name="customer_number" value="<?php echo $_GET['customer_number'];?>">
    <input type="hidden" name="customer_name" value="<?php echo $_GET['customer_name'];?>">
    <input type="hidden" name="csr" value="<?php echo $_GET['csr'];?>">
    <input type="hidden" name="piece_number" value="<?php echo $_GET['piece_number'];?>">
    <input type="hidden" name="deadline" value="<?php echo $_GET['deadline'];?>">
    <input type="hidden" name="saving_date" value="<?php echo $_GET['saving_date'];?>">
    <input type="hidden" name="product_type" value="<?php echo $_GET['product_type'];?>">
    <input type="hidden" name="rubber" value="<?php echo $_GET['rubber'];?>">
    <input type="hidden" name="sleeve_length" value="<?php echo $_GET['sleeve_length'];?>">
    <input type="hidden" name="table_length" value="<?php echo $_GET['table_length'];?>">
    <input type="hidden" name="sleeve_offset" value="<?php echo $_GET['sleeve_offset'];?>">
    <input type="hidden" name="mandrel_diameter" value="<?php echo $_GET['mandrel_diameter'];?>">
    <input type="hidden" name="notch" value="<?php echo $_GET['notch'];?>">
    <input type="hidden" name="notch_position" value="<?php echo $_GET['notch_position'];?>">
    <input type="hidden" name="developement" value="<?php echo $_GET['developement'];?>">
    <input type="hidden" name="fiber" value="<?php echo $_GET['fiber'];?>">
    <input type="hidden" name="fiber_thickness" value="<?php echo $_GET['fiber_thickness'];?>">
    <input type="hidden" name="chip" value="<?php echo $_GET['chip'];?>">
    <input type="hidden" name="cutback" value="<?php echo $_GET['cutback'];?>">
    <input type="hidden" name="cutback_diameter" value="<?php echo $_GET['cutback_diameter'];?>">
    <div class="form-inline mt-2">
        <label for="horizontal-form-1" class="form-label w-sm-40">
            Nombre de pièces fournies par le client (pour rechappage ou dégarnissage) :
        </label>
        <input type="number" name="supplied_piece" placeholder="0" max="<?php echo $_GET['piece_number'];?>" min="0">
    </div>
    <div class="form-inline mt-6">
        <div class="form-label w-sm-40">
            <input class="btn btn-primary mt-5" type="submit" value="Calculer le planning" name="calcul" id="calcul">
        </div>
    </div>
</form>




<?php


include('contents/footer.php'); ?>

</body>
</html>
