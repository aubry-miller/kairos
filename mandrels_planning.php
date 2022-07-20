<?php
include('sql/connect.php');
include('sql/get.php');
define('DAYSWEEK',array('1', '2', '3', '4', '5', '6', '7'));
session_start();

$title = 'Mandrel Planning';
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
        <title><?php echo $title;?></title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="dist/css/app.css" />
        <link rel="stylesheet" href="style/custom.css" />
        <!-- END: CSS Assets-->
        </script>
        </head>
<!-- END: Head -->
<body class="main">
        <?php include('contents/header.php');
        ?>
        <div class="mx-6">
            <div class="single-item">
                <?php
                $mandrels=select_all_mandrels();

                $furthest_date=select_furthest_date_of_use_of_mandrel();
                // var_dump($last_date);
                $now=date('Y-m-d');
                $tomorrow=date('Y-m-d', strtotime('+1 day'));
                $from = new \DateTime($now);
                $to = new \DateTime($furthest_date[0]['max(pt_date)']);
                $to->add(new DateInterval('P1D'));

                $interval = new DateInterval( "P1D" );
                $days = new \DatePeriod($from, $interval, $to);
                /** @var \DateTimeInterface $day */
                $n=0;
                foreach ($days as $day) {
                    $day_string=$day->format('Y-m-d');
                    // Extraction of the day, month, year of the date
                    list($year, $month, $day) = explode('-', $day_string);// a voir si les variables ne sont pas dans un mauvais ordre
                    // Timestamp calculation
                    $timestamp = mktime (0, 0, 0, $month, $day, $year);
                    // Day of the week
                    $day_number = DAYSWEEK[date("w",$timestamp)];
                    // echo 'day =>'.$day_number.'<br>';
                    if($day_number==1){
                        $day_name="Dimanche";//TODO traduct
                    } else if($day_number==2){
                        $day_name="Lundi";//TODO traduct
                    } else if($day_number==3){
                        $day_name="Mardi";//TODO traduct
                    } else if($day_number==4){
                        $day_name="Mercredi";//TODO traduct
                    } else if($day_number==5){
                        $day_name="Jeudi";//TODO traduct
                    } else if($day_number==6){
                        $day_name="Vendredi";//TODO traduct
                    } else if($day_number==7){
                        $day_name="Samedi";//TODO traduct
                    }

                    if($month=='01'){
                        $month_name='janvier';//TODO traduct
                    } else if($month=='02'){
                        $month_name='février';//TODO traduct
                    } else if($month=='03'){
                        $month_name='mars';//TODO traduct
                    } else if($month=='04'){
                        $month_name='avril';//TODO traduct
                    } else if($month=='05'){
                        $month_name='mai';//TODO traduct
                    } else if($month=='06'){
                        $month_name='juin';//TODO traduct
                    } else if($month=='07'){
                        $month_name='juillet';//TODO traduct
                    } else if($month=='08'){
                        $month_name='août';//TODO traduct
                    } else if($month=='09'){
                        $month_name='septembre';//TODO traduct
                    } else if($month=='10'){
                        $month_name='octobre';//TODO traduct
                    } else if($month=='11'){
                        $month_name='novembre';//TODO traduct
                    } else if($month=='12'){
                        $month_name='décembre';//TODO traduct
                    }
                    ?>
                    <div class="box mt-8 px-2">
                        <div class="h-full mt-8 pb-8 rounded-2">
                            <h3 class="h-full fw-medium d-flex align-items-center justify-content-center fs-2xl pb-8"><?php echo $day_name.' '.$day.' '.$month_name.' '.$year;?></h3>
                            <div>
                                <table class="table mt-40">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-bottom-0 text-gray-700 text-nowrap">
                                                ID
                                            </th>
                                            <th class="border-bottom-0 text-gray-700 text-nowrap">
                                                Diameter
                                            </th>
                                            <th class="border-bottom-0 text-gray-700 text-nowrap">
                                                Length
                                            </th>
                                            <th class="border-bottom-0 text-gray-700 text-nowrap">
                                                Form
                                            </th>
                                            <th class="border-bottom-0 text-gray-700 text-nowrap">
                                                Sector
                                            </th>
                                            <th class="border-bottom-0 text-gray-700 text-nowrap" style="text-align:center;">
                                                Available
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach($mandrels as $mandrel){
                                            ?>
                                            <tr class="mandrels_list" onclick="document.location='mandrel_planning.php?id=<?php echo $mandrel['mn_id'];?>'">
                                                <td>
                                                    <?php echo $mandrel['mn_id'];?>
                                                </td>
                                                <td>
                                                    <?php echo $mandrel['mn_diameter'];?>
                                                </td>
                                                <td>
                                                    <?php echo $mandrel['mn_length'];?>
                                                </td>
                                                <td>
                                                    <?php echo $mandrel['mn_form'];?>
                                                </td>
                                                <td>
                                                    <?php echo $mandrel['sc_label'];?>
                                                </td>
                                                <td style="text-align:center;">
                                                    <?php
                                                    $mandrel_used=get_mandrel_use_by_id_at_date($mandrel['mn_id'],$day_string);
                                                    if($mandrel_used==[]){
                                                        echo '&#x2714;';
                                                    } else {
                                                        echo '<i data-feather="lock" class="breadcrumb__icon"></i>';
                                                    }
                                                ?>                                                    
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <div class="box mt-8 px-2">
                        <div class="h-full mt-8 pb-8 rounded-2">
                            Pas d'autres jours à afficher
                        </div>
                </div>           
            </div>
        </div>

        <?php


        include('contents/footer.php'); ?>

</body>
</html>
