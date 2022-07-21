<?php
include('sql/connect.php');
include('sql/get.php');
include('sql/engine.php');
session_start();
$title=trad('roughing_day_plan',$_SESSION["language"]);
$pi=pi();

$furthest_date=select_furthest_roughing_plan_date();
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
    $day_without_year=substr($day_string,5,9);
    $dispo=0;

    $js_days[$day_string]=$day_string;
    
    // Extraction of the day, month, year of the date
    list($year, $month, $day) = explode('-', $day_string);// a voir si les variables ne sont pas dans un mauvais ordre
    // Timestamp calculation
    $timestamp = mktime (0, 0, 0, $month, $day, $year);
    // Day of the week
    $day_number = DAYSWEEK[date("w",$timestamp)];
    if($day_number==1){
        $day_name=trad('sunday',$_SESSION["language"]);
    } else if($day_number==2){
        $day_name=trad('monday',$_SESSION["language"]);
    } else if($day_number==3){
        $day_name=trad('tuesday',$_SESSION["language"]);
    } else if($day_number==4){
        $day_name=trad('wednesday',$_SESSION["language"]);
    } else if($day_number==5){
        $day_name=trad('thursday',$_SESSION["language"]);
    } else if($day_number==6){
        $day_name=trad('friday',$_SESSION["language"]);
    } else if($day_number==7){
        $day_name=trad('saturday',$_SESSION["language"]);
    }

    if($month=='01'){
        $month_name=trad('january',$_SESSION["language"]);
    } else if($month=='02'){
        $month_name=trad('february',$_SESSION["language"]);
    } else if($month=='03'){
        $month_name=trad('march',$_SESSION["language"]);
    } else if($month=='04'){
        $month_name=trad('april',$_SESSION["language"]);
    } else if($month=='05'){
        $month_name=trad('may',$_SESSION["language"]);
    } else if($month=='06'){
        $month_name=trad('june',$_SESSION["language"]);
    } else if($month=='07'){
        $month_name=trad('july',$_SESSION["language"]);
    } else if($month=='08'){
        $month_name=trad('august',$_SESSION["language"]);
    } else if($month=='09'){
        $month_name=trad('september',$_SESSION["language"]);
    } else if($month=='10'){
        $month_name=trad('october',$_SESSION["language"]);
    } else if($month=='11'){
        $month_name=trad('november',$_SESSION["language"]);
    } else if($month=='12'){
        $month_name=trad('december',$_SESSION["language"]);
    }

    
    $over_time=select_over_time_at_date($day_string);
    


    if($over_time==[] && ($day_number==1 || $day_number == 7 || $day_without_year=='01-01' || $day_without_year=='05-01' || $day_without_year=='05-08' || $day_without_year=='07-14' || $day_without_year=='08-15' || $day_without_year=='11-01' || $day_without_year=='11-11' || $day_without_year=='12-25')){
        $dispo=0;
        $verif_occupation=1;
    } else if($over_time!=[] && ($day_number==1 || $day_number == 7 || $day_without_year=='01-01' || $day_without_year=='05-01' || $day_without_year=='05-08' || $day_without_year=='07-14' || $day_without_year=='08-15' || $day_without_year=='11-01' || $day_without_year=='11-11' || $day_without_year=='12-25')){
        $verif_occupation=0;
        // Overtime at weekends and public holidays
        $dispo=0;
        foreach($over_time as $op_over_time){
            if($op_over_time['los_sector']==2){
                $dispo=$dispo+$op_over_time['oo_during'];
            }
        }
    } else {
        $verif_occupation=0;
        // jour travaillé
        $default_times=select_operator_default_time_by_sector('3');
        $dispo=0;
        
        foreach($default_times as $default_time){
            $dispo=$dispo+$default_time['otd_production_time'];
        }

        if($over_time!=[]){
            foreach($over_time as $op_over_time){
                if($op_over_time['los_sector']==2){
                    $dispo=$dispo+$op_over_time['oo_during'];
                }
            }
        }
        
        $absences=select_operator_absence_at_date_and_by_sector($day_string,'3');

        if($absences!=[]){
            foreach($absences as $absence){
                // echo $day_string;
                //  $date_start=new DateTime($absence['oa_start_hour_date']);
                //  $date_end=new DateTime($absence['oa_end_hour_date']);
                $start_date_time= new DateTime($absence['oa_start_hour_date']);
                $start_date=$start_date_time->format('Y-m-d');
                $end_date_time= new DateTime($absence['oa_end_hour_date']);
                $end_date=$end_date_time->format('Y-m-d');

                $operator_overtime=verification_operator_overtime_at_date($absence['oa_operator_id'], $day_string);
                if($operator_overtime==[]){
                    $hs=0;
                } else {
                    $hs=$operator_overtime[0]['oo_during'];
                }

                $operator_default_production_time = select_operator_default_time_by_id($absence['oa_operator_id']);
                $dt=$operator_default_production_time[0]['otd_production_time'];
                
                // si le meme jour, calculer l'écart en heure minute et le comparer au temps de presence
                if($end_date==$start_date){

                    $hm=$absence['oa_during_hours_first_day'].'.'.$absence['oa_during_minutes_first_day'];

                    if($hm>=($dt+$hs)){
                        // son absence couvre son temps normal plus les heures sup prévues
                        $dispo=$dispo-$dt-$hs;
                    } else {
                        $dispo=$dispo-$hm;
                    }

                } else {
                    if($start_date != $day_string && $end_date != $day_string){
                        $dispo=$dispo-$dt-$hs;
                    } else if($start_date == $day_string){
                        $hm=$absence['oa_during_hours_first_day'].'.'.$absence['oa_during_minutes_first_day'];
                        $dispo=$dispo-$hm;
                    }else if($end_date == $day_string){
                        $hm=$absence['oa_during_hours_last_day'].'.'.$absence['oa_during_minutes_last_day'];
                        $dispo=$dispo-$hm;
                    }
                }

                // Execution de code
                $debut = new DateTime($absence['oa_start_hour_date']);
                $fin = new DateTime($absence['oa_end_hour_date']);
                $interval = $debut->diff($fin);

            }
        }
       
    }


    $times=select_estimated_workload_at_date_by_step($day_string,'3');

    
    $occupation=null;
    foreach($times as $time){
        $occupation=$occupation+$time['nb_minute'];
    }

    $occupation_heures=floor($occupation/60);
    $occupation_minutes=$occupation-($occupation_heures*60);
    $occupation_minutes_decimal=ceil($occupation_minutes/60*100);
    $occupation_decimal=$occupation_heures+($occupation_minutes_decimal/100);

    // if($now==$day_string){
        
    //     $day_dispo=$dispo;
    //     $day_occupation_decimal=$occupation_decimal;

    // }

    $t_day_name[$n]=$day_name;
    $t_day[$n]=$day;
    $t_month[$n]=$month;
    $t_month_name[$n]=$month_name;
    $t_year[$n]=$year;
    $t_verif_occupation[$n]=$verif_occupation;
    $t_occupation_heures[$n]=$occupation_heures;
    $t_occupation_minutes[$n]=$occupation_minutes;
    $t_dispo[$n]=$dispo;
    $t_occupation_decimal[$n]=$occupation_decimal;


            ////////////////////////////////////////////////////////////
            ///////////////////// Daily gauges END /////////////////////
            ////////////////////////////////////////////////////////////

            /////////////////////////////////////////////////////////////
            //////////////// Circular chart values START ////////////////
            /////////////////////////////////////////////////////////////


    if($t_dispo[$n]!=0){
        $t_busy[$n]=ceil($t_occupation_decimal[$n]/$t_dispo[$n]*100);
    } else{
        $t_busy[$n]=100;
    }
    $t_not_busy[$n]=100-$t_busy[$n];


    if($t_occupation_decimal[$n] !=0){
        ${'dataPoints'.$n} = array( 
            array("label"=>"Occupé", "y"=>$t_busy[$n],"color"=>'#FFAE42'),
            array("label"=>"Dispo", "y"=>$t_not_busy[$n],"color"=>'#eee')
        );
    } else if($t_occupation_decimal[$n]==0){
        ${'dataPoints'.$n} = array( 
            array("label"=>"Occupé", "y"=>'0.2',"color"=>'red'),
            array("label"=>"Dispo", "y"=>'100',"color"=>'#eee')
        );
    }
    $n++;
    
}


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
        <title><?php echo trad('roughing_day_plan',$_SESSION["language"]);?></title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="dist/css/app.css" />
        <link rel="stylesheet" href="style/custom.css" />
        <!-- END: CSS Assets-->

        <script>

            window.onload = function() {
                var days = <?php echo json_encode($js_days);?>;
                var table_length= Object.keys(days).length;
                //il y a table_length itération a faire
                for(var i=0; i<table_length;i++){
                    document.cookie="i="+i;
                    <?php if(isset($dataPoints0)){?>
                        if(i==0){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints0, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints1)){?>
                        if(i==1){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php } 
                    if(isset($dataPoints2)){?>
                        if(i==2){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints3)){?>
                        if(i==3){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints3, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints4)){?>
                        if(i==4){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints4, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints5)){?>
                        if(i==5){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints5, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints6)){?>
                        if(i==6){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints6, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints7)){?>
                        if(i==7){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints7, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints8)){?>
                        if(i==8){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints8, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints9)){?>
                        if(i==9){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints9, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints10)){?>
                        if(i==10){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints10, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints11)){?>
                        if(i==11){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints11, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php } 
                    if(isset($dataPoints12)){?>
                        if(i==12){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints12, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints13)){?>
                        if(i==13){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints13, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints14)){?>
                        if(i==14){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints14, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints15)){?>
                        if(i==15){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints15, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints16)){?>
                        if(i==16){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints16, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints17)){?>
                        if(i==17){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints17, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints18)){?>
                        if(i==18){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints18, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints19)){?>
                        if(i==19){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints19, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints20)){?>
                        if(i==20){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints20, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints21)){?>
                        if(i==21){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints21, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php } 
                    if(isset($dataPoints22)){?>
                        if(i==22){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints22, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints23)){?>
                        if(i==23){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints23, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints24)){?>
                        if(i==24){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints24, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints25)){?>
                        if(i==25){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints25, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints26)){?>
                        if(i==26){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints26, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints27)){?>
                        if(i==27){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints27, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints28)){?>
                        if(i==28){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints28, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints29)){?>
                        if(i==29){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints29, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints30)){?>
                        if(i==30){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints30, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints31)){?>
                        if(i==31){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints31, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php } 
                    if(isset($dataPoints32)){?>
                        if(i==32){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints32, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints33)){?>
                        if(i==33){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints33, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints34)){?>
                        if(i==34){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints34, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints35)){?>
                        if(i==35){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints35, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints36)){?>
                        if(i==36){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints36, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints37)){?>
                        if(i==37){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints37, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints38)){?>
                        if(i==38){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints38, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints39)){?>
                        if(i==39){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints39, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints40)){?>
                        if(i==40){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints40, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints41)){?>
                        if(i==41){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints41, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php } 
                    if(isset($dataPoints42)){?>
                        if(i==42){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints42, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints43)){?>
                        if(i==43){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints43, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints44)){?>
                        if(i==44){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints44, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints45)){?>
                        if(i==45){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints45, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints46)){?>
                        if(i==46){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints46, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints47)){?>
                        if(i==47){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints47, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints48)){?>
                        if(i==48){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints48, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints49)){?>
                        if(i==49){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints49, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints50)){?>
                        if(i==50){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints50, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints51)){?>
                        if(i==51){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints51, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php } 
                    if(isset($dataPoints52)){?>
                        if(i==52){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints52, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints53)){?>
                        if(i==53){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints53, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints54)){?>
                        if(i==54){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints54, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints55)){?>
                        if(i==55){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints55, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints56)){?>
                        if(i==56){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints56, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints57)){?>
                        if(i==57){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints57, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints58)){?>
                        if(i==58){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints58, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints59)){?>
                        if(i==59){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints59, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints60)){?>
                        if(i==60){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints60, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints61)){?>
                        if(i==61){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints61, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php } 
                    if(isset($dataPoints62)){?>
                        if(i==62){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints62, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints63)){?>
                        if(i==63){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints63, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints64)){?>
                        if(i==64){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints64, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints65)){?>
                        if(i==65){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints65, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints66)){?>
                        if(i==66){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints66, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints67)){?>
                        if(i==67){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints67, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints68)){?>
                        if(i==68){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints68, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints69)){?>
                        if(i==69){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints69, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }

                    if(isset($dataPoints70)){?>
                        if(i==70){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints70, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints71)){?>
                        if(i==71){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints71, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php } 
                    if(isset($dataPoints72)){?>
                        if(i==72){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints72, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints73)){?>
                        if(i==73){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints73, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints74)){?>
                        if(i==74){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints74, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints75)){?>
                        if(i==75){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints75, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints76)){?>
                        if(i==76){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints76, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints77)){?>
                        if(i==77){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints77, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints78)){?>
                        if(i==78){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints78, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints79)){?>
                        if(i==79){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints79, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints70)){?>
                        if(i==70){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints70, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints71)){?>
                        if(i==71){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints71, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php } 
                    if(isset($dataPoints72)){?>
                        if(i==72){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints72, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints73)){?>
                        if(i==73){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints73, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints74)){?>
                        if(i==74){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints74, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints75)){?>
                        if(i==75){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints75, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints76)){?>
                        if(i==76){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints76, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints77)){?>
                        if(i==77){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints77, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints78)){?>
                        if(i==78){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints78, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints79)){?>
                        if(i==79){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints79, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }

                        if(isset($dataPoints80)){?>
                        if(i==80){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints80, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints81)){?>
                        if(i==81){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints81, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php } 
                    if(isset($dataPoints82)){?>
                        if(i==82){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints82, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints83)){?>
                        if(i==83){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints83, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints84)){?>
                        if(i==84){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints84, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints85)){?>
                        if(i==85){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints85, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints86)){?>
                        if(i==86){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints86, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints87)){?>
                        if(i==87){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints87, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints88)){?>
                        if(i==88){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints88, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints89)){?>
                        if(i==89){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints89, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }

                    if(isset($dataPoints80)){?>
                        if(i==80){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints80, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints81)){?>
                        if(i==81){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints81, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php } 
                    if(isset($dataPoints82)){?>
                        if(i==82){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints82, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints83)){?>
                        if(i==83){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints83, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints84)){?>
                        if(i==84){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints84, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints85)){?>
                        if(i==85){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints85, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints86)){?>
                        if(i==86){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints86, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints87)){?>
                        if(i==87){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints87, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints88)){?>
                        if(i==88){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints88, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints89)){?>
                        if(i==89){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints89, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints80)){?>
                        if(i==80){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints80, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints81)){?>
                        if(i==81){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints81, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php } 
                    if(isset($dataPoints82)){?>
                        if(i==82){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints82, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints83)){?>
                        if(i==83){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints83, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints84)){?>
                        if(i==84){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints84, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints85)){?>
                        if(i==85){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints85, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints86)){?>
                        if(i==86){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints86, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints87)){?>
                        if(i==87){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints87, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints88)){?>
                        if(i==88){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints88, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints89)){?>
                        if(i==89){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints89, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints90)){?>
                        if(i==90){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints90, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints91)){?>
                        if(i==91){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints91, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php } 
                    if(isset($dataPoints92)){?>
                        if(i==92){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints92, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints93)){?>
                        if(i==93){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints93, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints94)){?>
                        if(i==94){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints94, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints95)){?>
                        if(i==95){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints95, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints96)){?>
                        if(i==96){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints96, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints97)){?>
                        if(i==97){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints97, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints98)){?>
                        if(i==98){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints98, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    if(isset($dataPoints99)){?>
                        if(i==99){
                            window['chartLining'+i] = new CanvasJS.Chart("chartContainerLining"+i, {
                                theme: "light2",
                                // animationEnabled: true,
                                // title: {
                                //     text: "World Energy Consumption by Sector - 2012"
                                // },
                                data: [{
                                    type: "pie",
                                    indexLabel: "{x}",
                                    yValueFormatString: "#,##0.00\"%\"",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "#36454F",
                                    indexLabelFontSize: 18,
                                    indexLabelFontWeight: "bolder",
                                    // showInLegend: true,
                                    // legendText: "{label}",
                                    dataPoints: <?php echo json_encode($dataPoints99, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                        }
                    <?php }
                    

                    ?>
                    console.log(window['chartLining'+i]);
                    window['chartLining'+i].render();
                }

            }
        </script>




        </head>
<!-- END: Head -->
<body class="main">
    <?php include('contents/header.php'); 
    ?>  
    <h4 class="fs-xl fw-medium lh-1 mt-3 mb-6"><?php echo trad('roughing_workshop',$_SESSION["language"]);?></h4>
    <div class="content">
        <?php
        $v=0;
        foreach($days as $day){
                $day=$day->format('Y-m-d');
                ?>
                <div class="row gap-y-6 mt-5" <?php if($t_dispo[$v] == 0){ echo 'style="display:none;"';}?>>
                    
                    <!-- BEGIN: Boxed Accordion -->
                    <div class="intro-y col-12 col-lg-6">
                        <div class="intro-y box">
                            <div class="d-flex flex-column flex-sm-row align-items-center p-5 border-bottom border-gray-200 dark-border-dark-5">
                                <h2 class="fw-medium fs-base me-auto">
                                    <?php 
                                    if($day==$now_header){
                                        echo trad('today',$_SESSION["language"]);
                                    } else {
                                        echo $day;
                                    }
                                    ?>
                                </h2>
                            </div>
                            <div id="boxed-accordion" class="p-5">
                                <div class="preview">
                                    <div id="faq-accordion-2" class="accordion accordion-boxed">


                                    <?php
                                        //selection des commandes du jour pour l'ébauche
                                        $jobs=select_jobs_by_day_and_step_id($day, '3');
                                        foreach($jobs as $job){
                                            
                                            ?>
                                            <!-- BEGIN: Slide Over Toggle -->
                                            <div class="accordion-item">
                                                <div id="faq-accordion-content-9" class="accordion-header">
                                                    <a data-bs-toggle="offcanvas" href="#basic-slide-over-preview-<?php echo $job['pt_piece_id'];?>" role="button" aria-controls="basicSlideOver" class="accordion-button collapsed">
                                                        <div style="display:inline-block; width:70%;">
                                                            <?php echo $job['od_customer_name'].' - '.$job['pt_piece_id'];?>
                                                        </div>
                                                        <div style="display:inline-block; text-align:right;width:29%;">
                                                            <?php echo $job['pt_expected_duration'];?>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <!-- END: Slide Over Toggle -->
                                            
                                        
                                    
                                            <!-- BEGIN: Slide Over Content -->
                                            <?php include('back/day_plan_order_informations.php');?> 
                                            <!-- END: Slide Over Content -->
                                        <?php } ?>


                                    </div>
                                </div>
                                <div class="source-code d-none">
                                    <button data-target="#copy-boxed-accordion" class="copy-code btn py-1 px-2 btn-outline-secondary"> <i data-feather="file" class="w-4 h-4 me-2"></i> Copy example code </button>
                                    <div class="overflow-y-auto mt-3 rounded-2">
                                        <pre id="copy-boxed-accordion" class="source-preview"> <code class="fs-xs p-0 rounded-2 html ps-5 pt-8 pb-4 mb-n10 mt-n10"> HTMLOpenTagdiv id=&quot;faq-accordion-2&quot; class=&quot;accordion accordion-boxed&quot;HTMLCloseTag HTMLOpenTagdiv class=&quot;accordion-item&quot;HTMLCloseTag HTMLOpenTagdiv id=&quot;faq-accordion-content-1&quot; class=&quot;accordion-header&quot;HTMLCloseTag HTMLOpenTagbutton class=&quot;accordion-button&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#faq-accordion-collapse-5&quot; aria-expanded=&quot;true&quot; aria-controls=&quot;faq-accordion-collapse-5&quot;HTMLCloseTag OpenSSL Essentials: Working with SSL Certificates, Private Keys HTMLOpenTag/buttonHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv id=&quot;faq-accordion-collapse-5&quot; class=&quot;accordion-collapse collapse show&quot; aria-labelledby=&quot;faq-accordion-content-1&quot; data-bs-parent=&quot;#faq-accordion-2&quot;HTMLCloseTag HTMLOpenTagdiv class=&quot;accordion-body lh-lg&quot;HTMLCloseTag Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;accordion-item&quot;HTMLCloseTag HTMLOpenTagdiv id=&quot;faq-accordion-content-2&quot; class=&quot;accordion-header&quot;HTMLCloseTag HTMLOpenTagbutton class=&quot;accordion-button collapsed&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#faq-accordion-collapse-6&quot; aria-expanded=&quot;false&quot; aria-controls=&quot;faq-accordion-collapse-6&quot;HTMLCloseTag Understanding IP Addresses, Subnets, and CIDR Notation HTMLOpenTag/buttonHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv id=&quot;faq-accordion-collapse-6&quot; class=&quot;accordion-collapse collapse&quot; aria-labelledby=&quot;faq-accordion-content-2&quot; data-bs-parent=&quot;#faq-accordion-2&quot;HTMLCloseTag HTMLOpenTagdiv class=&quot;accordion-body lh-lg&quot;HTMLCloseTag Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;accordion-item&quot;HTMLCloseTag HTMLOpenTagdiv id=&quot;faq-accordion-content-3&quot; class=&quot;accordion-header&quot;HTMLCloseTag HTMLOpenTagbutton class=&quot;accordion-button collapsed&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#faq-accordion-collapse-7&quot; aria-expanded=&quot;false&quot; aria-controls=&quot;faq-accordion-collapse-7&quot;HTMLCloseTag How To Troubleshoot Common HTTP Error Codes HTMLOpenTag/buttonHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv id=&quot;faq-accordion-collapse-7&quot; class=&quot;accordion-collapse collapse&quot; aria-labelledby=&quot;faq-accordion-content-3&quot; data-bs-parent=&quot;#faq-accordion-2&quot;HTMLCloseTag HTMLOpenTagdiv class=&quot;accordion-body lh-lg&quot;HTMLCloseTag Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv class=&quot;accordion-item&quot;HTMLCloseTag HTMLOpenTagdiv id=&quot;faq-accordion-content-4&quot; class=&quot;accordion-header&quot;HTMLCloseTag HTMLOpenTagbutton class=&quot;accordion-button collapsed&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#faq-accordion-collapse-8&quot; aria-expanded=&quot;false&quot; aria-controls=&quot;faq-accordion-collapse-8&quot;HTMLCloseTag An Introduction to Securing your Linux VPS HTMLOpenTag/buttonHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTagdiv id=&quot;faq-accordion-collapse-8&quot; class=&quot;accordion-collapse collapse&quot; aria-labelledby=&quot;faq-accordion-content-4&quot; data-bs-parent=&quot;#faq-accordion-2&quot;HTMLCloseTag HTMLOpenTagdiv class=&quot;accordion-body lh-lg&quot;HTMLCloseTag Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag HTMLOpenTag/divHTMLCloseTag </code> </pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Boxed Accordion -->

                    <!-- BEGIN: charge -->
                    <div class="intro-y col-12 col-lg-6">
                        <div class="intro-y box">
                            <div class="intro-y box p-5 mt-1">
                                <em><u><?php echo trad('available_time',$_SESSION["language"]);?> : <?php echo $t_dispo[$v];?> h</u></em>

                                <div id="<?php echo 'chartContainerLining'.$v;?>" style="height: 200px; width: 100%;"></div>
                                <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
                                <div class="mt-8">
                                    <div class="d-flex align-items-center">
                                        <div class="w-2 h-2 bg-theme-17 rounded-circle me-3" style="background-color:#FFAE42 !important"></div>
                                        <span class="truncate"><?php echo trad('workload',$_SESSION["language"]);?></span> 
                                        <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                        <span class="fw-medium ms-xl-auto"><?php echo $t_occupation_decimal[$v];?> h</span> 
                                    </div>
                                    <div class="d-flex align-items-center mt-4">
                                        <div class="w-2 h-2 bg-theme-35 rounded-circle me-3" style="background-color:#eee !important;"></div>
                                        <span class="truncate"><?php echo trad('available',$_SESSION["language"]);?></span> 
                                        <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                        <span class="fw-medium ms-xl-auto"><?php echo $t_dispo[$v]-$t_occupation_decimal[$v];?> h</span> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END: charge -->



                    </div>
                </div>
                <?php
                if($t_dispo[$v] != 0){ 
                    echo '
                        <br>
                        <hr>
                        ';
                }
                
            $v++;
        }?>
        
        
        
    </div>


<?php include('contents/footer.php'); ?>

</body>
</html>