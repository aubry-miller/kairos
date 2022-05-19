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
        <title>Workflow</title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="dist/css/app.css" />
        <!-- END: CSS Assets-->
        </script>
        </head>
<!-- END: Head -->
<body class="main">
        <?php include('contents/header.php');
        ?>
        <div class="box d-sm-flex mt-8 px-8 py-12">
                <?php
                        $products_type=select_all_product_type();
                
                if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
                        $url = "https"; 
                else
                        $url = "http"; 
                        
                $url .= "://";        
                $url .= $_SERVER['HTTP_HOST']; 

                if(strpos($_SERVER['REQUEST_URI'],'?product=' ) != false){
                        $_SERVER['REQUEST_URI']=substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'],'?product=' ));
                }
                $url .= $_SERVER['REQUEST_URI']; 
                ?><ul><?php
                foreach($products_type as $product_type){
                        ?><li>
                                <a href='<?php echo $url.'?product='.$product_type['pt_id'];?>'><?php echo $product_type['pt_label'];?></a>

                        </li><?php
                }
                ?></ul><?php
                ?>
        </div>
        <?php
        if(isset($_GET['product'])){?>
                <div class="box d-sm-flex mt-8 px-8 py-12">
                        <div class="p-5 border-bottom border-gray-200 dark-border-dark-5">
                                <?php
                                $label= get_product_type_label_by_id($_GET['product']);
                                ?>
                                <h4 class="fs-xl fw-medium lh-1 mt-3 mb-12"><?php echo trad('product_worflow:',$_SESSION["language"]).' '. $label;?></h4>
                                <?php
                                $steps=get_steps_by_flow_id(get_flow_id_by_product_id($_GET['product']));
                                foreach($steps as $key => $step){
                                        echo '<b>'.$step['stp_label'].'<b>';
                                        if ($key !== array_key_last($steps)) {
                                                echo ' &#x279C; ';
                                        }
                                }
                                ?>
                        </div>
                </div>
        <?php
        }

        include('contents/footer.php'); ?>


</body>
</html>
