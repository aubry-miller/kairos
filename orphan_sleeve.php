<?php
include('sql/connect.php');
include('sql/get.php');
include('sql/engine.php');
include('back/orphan_sleeve_engine.php');
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
        <title><?php echo trad('files_awaiting_planning',$_SESSION["language"]); //////// TODO //////// ?></title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="dist/css/app.css" />
        <!-- END: CSS Assets-->
        </script>
        </head>
<!-- END: Head -->
<body class="main">
    <?php include('contents/header.php');
    
    
    $orphan_sleeve=orphan_sleeve($_GET['temp_id']);

    //TODO
    // afficher les manchons orphelins disponibles pour la fabrication avec les commentaires et un lien pour en selectionner un pour la fabrication (partie encore à faire avec recalcule de flux)
    
    //Display a table of orphans that can match the command
    ?>
    <div class="box d-sm-flex mt-8 px-8 py-12">
        <div class="overflow-x-auto">
            <table class="table mt-40">
                <tbody>
                    <?php
                    for($i=0; $i<$orphan_sleeve['number'];$i++){
                    ?>
                        <tr>
                            <td>
                                <?php echo $orphan_sleeve[$i]['pc_id'];?>
                            </td>
                            <td>
                                <!--
                                orphan status :
                                    • 1 => directly usable
                                    • 2 => rectification started but not finished, check where the production stopped physically
                                    • 3 => the next task is not finished
                                    • 4 => it is necessary to rework the piece to adapt it
                                 -->
                                <?php if($orphan_sleeve[$i]['status'] == '1'){
                                    echo trad('directly_usable',$_SESSION["language"]);
                                } else if($orphan_sleeve[$i]['status'] == '2'){
                                    echo trad('rectification_started_but_not_finished',$_SESSION["language"]);
                                } else if($orphan_sleeve[$i]['status'] == '4'){
                                    echo trad('Its_necessary_rework_piece_to_adapt',$_SESSION["language"]);
                                } else {
                                    $task= 'no task';
                                    if(substr($orphan_sleeve[$i]['status'], 2) == '1'){
                                        $task=trad('fiber',$_SESSION["language"]);
                                    } else if(substr($orphan_sleeve[$i]['status'], 2) == '2'){
                                        $task=trad('manual_lining',$_SESSION["language"]);
                                    } else if(substr($orphan_sleeve[$i]['status'], 2) == '3'){
                                        $task=trad('roughing',$_SESSION["language"]);
                                    } else if(substr($orphan_sleeve[$i]['status'], 2) == '4'){
                                        $task=trad('grinding',$_SESSION["language"]);
                                    } else if(substr($orphan_sleeve[$i]['status'], 2) == '6'){
                                        $task=trad('mechanical_lining',$_SESSION["language"]);
                                    }
                                    echo trad('next_task_finished',$_SESSION["language"]).' : '.$task;
                                }
                                ?>
                            </td>
                            <td>
                                <form method="GET" action="back/use_orphan.php">
                                    <input type="hidden" name="millnet_id" value="<?php echo $_GET['millnet_id'];?>">
                                    <input type="hidden" name="temp_id" value="<?php echo $_GET['temp_id'];?>">
                                    <input type="hidden" name="pc_id" value="<?php echo $orphan_sleeve[$i]['pc_id'];?>">
                                    <input type="hidden" name="orphan_status" value="<?php echo $orphan_sleeve[$i]['status'];?>">
                                    <input type="hidden" name="orphan_step" value="<?php echo $orphan_sleeve[$i]['step'];?>">
                                    <button type="submit" name="orphan_utilisation" class="btn btn-primary w-32 me-2 mb-2"><i data-feather="play" class="w-4 h-4 me-2"></i><?php echo trad('use_it',$_SESSION["language"]);?></button>
                                </form>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    include('contents/footer.php'); ?>

</body>
</html>
