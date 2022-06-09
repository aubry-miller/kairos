<?php
include('sql/connect.php');
include('sql/get.php');
include('sql/engine.php');
session_start();
$title='lining day plan';
$pi=pi();
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
        <title><?php echo trad('lining_day_plan',$_SESSION["language"]);?></title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="dist/css/app.css" />
        <link rel="stylesheet" href="style/custom.css" />
        <!-- END: CSS Assets-->
<!--[if lt IE 9]><script type="text/javascript" src="js/excanvas.compiled.js"></script><![endif]-->
        </script>
        </head>
<!-- END: Head -->
<body class="main">
        <?php include('contents/header.php'); 
        $date=new DateTime();
        $date = $date->format('Y-m-d');
        echo '<h4 class="fs-xl fw-medium lh-1 mt-3">'.$date.'</h4>';
        ?>
        
        <div class="row gap-y-6 mt-5">
        <div class="box d-sm-flex mt-8 px-8 py-12">
                    <div class="p-5 border-bottom border-gray-200 dark-border-dark-5">
                        <h4 class="fs-xl fw-medium lh-1 mt-3">
                            <?php echo trad('manual_lining',$_SESSION["language"]);?>
                        </h4>
                        <div class="overflow-x-auto">
                            <table class="table mt-40">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-bottom-0 text-gray-700 text-nowrap">
                                            <?php echo trad('number',$_SESSION["language"]);?>
                                        </th>
                                        <th class="border-bottom-0 text-gray-700 text-nowrap">
                                            <?php echo trad('mandrel_ø',$_SESSION["language"]);?>
                                        </th>
                                        <th class="border-bottom-0 text-gray-700 text-nowrap">
                                            <?php echo trad('grinding_ø',$_SESSION["language"]);?>
                                        </th>
                                        <th class="border-bottom-0 text-gray-700 text-nowrap">
                                            <?php echo trad('overall',$_SESSION["language"]);?>
                                        </th>
                                        <th class="border-bottom-0 text-gray-700 text-nowrap">
                                            <?php echo trad('rubber',$_SESSION["language"]);?>
                                        </th>
                                        <th class="border-bottom-0 text-gray-700 text-nowrap">
                                            <?php echo trad('status',$_SESSION["language"]);?>
                                        </th>
                                        <th class="border-bottom-0 text-gray-700 text-nowrap">
                                            Attribuée à
                                        </th>
                                        <th class="border-bottom-0 text-gray-700 text-nowrap">
                                            <?php echo trad('estimated_time',$_SESSION["language"]);?>
                                        </th>
                                        <th class="border-bottom-0 text-gray-700 text-nowrap">
                                            <?php echo trad('real_time',$_SESSION["language"]);?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $jobs=select_jobs_by_day_and_step_id($date, '2');
                                    ?>
                                    <?php
                                    foreach($jobs as $job){
                                        if($job['pt_status'] != "Finished"){?>
                                        
                                            <tr onclick="document.location='do_job.php?plan_id=<?php echo $job['pt_id'];?>&piece=<?php echo $job['pc_id'].'&step=2';?>'" class="plan_table">
                                                <td>
                                                    <?php echo $job['pc_id'];?>
                                                </td>
                                                <td>
                                                    <?php echo $job['pc_mandrel_diameter'];?>
                                                </td>
                                                <td>
                                                    <?php echo ceil($job['pc_developement']/$pi); ?>
                                                </td>
                                                <td>
                                                    <?php echo $job['pc_sleeve_length'];?>
                                                </td>
                                                <td>
                                                    <?php echo $job['rb_label'];?>
                                                </td>
                                                <td>
                                                    <?php echo $job['pt_status'];?>
                                                </td>
                                                <td>
                                                    <?php echo $job['us_firstname'].' '.$job['us_name'];?>
                                                </td>
                                                <td>
                                                    <?php echo $job['pt_expected_material_consumption'];?>
                                                </td>
                                                <td>
                                                    <?php echo $job['pt_real_material_consumption'];?>
                                                </td>
                                            </tr>
                                        <?php }
                                    } ?>
                                </tbody>


                            </table>
                        </div>                        
                    </div>
                </div>
            </div>
        
            <div class="box d-sm-flex mt-8 px-8 py-12">
                    <div class="p-5 border-bottom border-gray-200 dark-border-dark-5">
                    <h4 class="fs-xl fw-medium lh-1 mt-3">
                            <?php echo trad('mechanical_lining',$_SESSION["language"]);?>
                        </h4>
                        <div class="overflow-x-auto">
                            <table class="table mt-40">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-bottom-0 text-gray-700 text-nowrap">
                                            <?php echo trad('number',$_SESSION["language"]);?>
                                        </th>
                                        <th class="border-bottom-0 text-gray-700 text-nowrap">
                                            <?php echo trad('mandrel_ø',$_SESSION["language"]);?>
                                        </th>
                                        <th class="border-bottom-0 text-gray-700 text-nowrap">
                                            <?php echo trad('grinding_ø',$_SESSION["language"]);?>
                                        </th>
                                        <th class="border-bottom-0 text-gray-700 text-nowrap">
                                            <?php echo trad('overall',$_SESSION["language"]);?>
                                        </th>
                                        <th class="border-bottom-0 text-gray-700 text-nowrap">
                                            <?php echo trad('rubber',$_SESSION["language"]);?>
                                        </th>
                                        <th class="border-bottom-0 text-gray-700 text-nowrap">
                                            <?php echo trad('status',$_SESSION["language"]);?>
                                        </th>
                                        <th class="border-bottom-0 text-gray-700 text-nowrap">
                                            Attribuée à
                                        </th>
                                        <th class="border-bottom-0 text-gray-700 text-nowrap">
                                            <?php echo trad('estimated_time',$_SESSION["language"]);?>
                                        </th>
                                        <th class="border-bottom-0 text-gray-700 text-nowrap">
                                            <?php echo trad('real_time',$_SESSION["language"]);?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $jobs=select_jobs_by_day_and_step_id($date, '6');
                                    ?>
                                    <?php
                                    foreach($jobs as $job){?>
                                        <tr onclick="document.location='do_job.php?piece=<?php echo $job['pc_id'].'&step=6';?>'" class="plan_table">
                                            <td>
                                                <?php echo $job['pc_id'];?>
                                            </td>
                                            <td>
                                                <?php echo $job['pc_mandrel_diameter'];?>
                                            </td>
                                            <td>
                                                <?php echo ceil($job['pc_developement']/$pi); ?>
                                            </td>
                                            <td>
                                                <?php echo $job['pc_sleeve_length'];?>
                                            </td>
                                            <td>
                                                <?php echo $job['rb_label'];?>
                                            </td>
                                            <td>
                                                <?php echo $job['pt_status'];?>
                                            </td>
                                            <td>
                                                <?php echo $job['us_firstname'].' '.$job['us_name'];?>
                                            </td>
                                            <td>
                                                <?php echo $job['pt_expected_material_consumption'];?>
                                            </td>
                                            <td>
                                                <?php echo $job['pt_real_material_consumption'];?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>


                            </table>
                        </div>          
                        
                    </div>
                </div>
            </div>
        </div>



<?php include('contents/footer.php'); ?>

</body>
</html>