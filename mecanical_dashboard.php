<?php
include('sql/connect.php');
include('sql/get.php');
session_start();
define('DAYSWEEK',array('1', '2', '3', '4', '5', '6', '7'));
$title = 'Mecanical Dashboard';


////////////////////////////////////////////////////////////
//////////////////// Daily gauges START ////////////////////
////////////////////////////////////////////////////////////

//Selection of the furthest date in the schedule for the rectification step
$furthest_date=select_furthest_granding_plan_date();
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
        $dispo_ebauche=0;
        $dispo_lining=0;
        $dispo_fibre=0;
        foreach($over_time as $op_over_time){
            
            if($op_over_time['los_sector']==4){
                $dispo=$dispo+$op_over_time['oo_during'];
            }

            if($op_over_time['los_sector']==3){
                $dispo_ebauche=$dispo_ebauche+$op_over_time['oo_during'];
            }

            if($op_over_time['los_sector']==2){
                $dispo_lining=$dispo_lining+$op_over_time['oo_during'];
            }

            if($op_over_time['los_sector']==1){
                $dispo_fibre=$dispo_fibre+$op_over_time['oo_during'];
            }
        }
    } else {
        $verif_occupation=0;
        // jour travaillé
        $default_times=select_operator_default_time_by_sector('4');
        $default_times_ebauche=select_operator_default_time_by_sector('3');
        $default_times_lining=select_operator_default_time_by_sector('2');
        $default_times_fibre=select_operator_default_time_by_sector('1');
        $dispo=0;
        $dispo_ebauche=0;
        $dispo_lining=0;
        $dispo_fibre=0;
        foreach($default_times as $default_time){
            $dispo=$dispo+$default_time['otd_production_time'];
        }

        foreach($default_times_ebauche as $default_time_ebauche){
            $dispo_ebauche=$dispo_ebauche+$default_time_ebauche['otd_production_time'];
        }

        foreach($default_times_lining as $default_time_lining){
            $dispo_lining=$dispo_lining+$default_time_lining['otd_production_time'];
        }

        foreach($default_times_fibre as $default_time_fibre){
            $dispo_fibre=$dispo_fibre+$default_time_fibre['otd_production_time'];
        }
        if($over_time!=[]){
            foreach($over_time as $op_over_time){
                if($op_over_time['los_sector']==4){
                    $dispo=$dispo+$op_over_time['oo_during'];
                }

                if($op_over_time['los_sector']==3){
                    $dispo_ebauche=$dispo_ebauche+$op_over_time['oo_during'];
                }

                if($op_over_time['los_sector']==2){
                    $dispo_lining=$dispo_lining+$op_over_time['oo_during'];
                }

                if($op_over_time['los_sector']==1){
                    $dispo_fibre=$dispo_fibre+$op_over_time['oo_during'];
                }
            }
        }
        $absences=select_operator_absence_at_date_and_by_sector($day_string,'4');
        $absences_ebauche=select_operator_absence_at_date_and_by_sector($day_string,'3');
        $absences_lining=select_operator_absence_at_date_and_by_sector($day_string,'2');
        $absences_fibre=select_operator_absence_at_date_and_by_sector($day_string,'1');
        if($absences!=[]){
            foreach($absences as $absence){
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

        if($absences_ebauche!=[]){
            foreach($absences_ebauche as $absence_ebauche){
                // echo $day_string;
                //  $date_start=new DateTime($absence['oa_start_hour_date']);
                //  $date_end=new DateTime($absence['oa_end_hour_date']);
                $start_date_time_ebauche= new DateTime($absence_ebauche['oa_start_hour_date']);
                $start_date_ebauche=$start_date_time_ebauche->format('Y-m-d');
                $end_date_time_ebauche= new DateTime($absence_ebauche['oa_end_hour_date']);
                $end_date_ebauche=$end_date_time_ebauche->format('Y-m-d');

                $operator_overtime_ebauche=verification_operator_overtime_at_date($absence_ebauche['oa_operator_id'], $day_string);
                if($operator_overtime_ebauche==[]){
                    $hs_ebauche=0;
                } else {
                    $hs_ebauche=$operator_overtime_ebauche[0]['oo_during'];
                }

                $operator_default_production_time_ebauche = select_operator_default_time_by_id($absence_ebauche['oa_operator_id']);
                $dt_ebauche=$operator_default_production_time_ebauche[0]['otd_production_time'];
                
                // si le meme jour, calculer l'écart en heure minute et le comparer au temps de presence
                if($end_date_ebauche==$start_date_ebauche){

                    $hm_ebauche=$absence_ebauche['oa_during_hours_first_day'].'.'.$absence_ebauche['oa_during_minutes_first_day'];

                    if($hm_ebauche>=($dt_ebauche+$hs_ebauche)){
                        // son absence couvre son temps normal plus les heures sup prévues
                        $dispo_ebauche=$dispo_ebauche-$dt_ebauche-$hs_ebauche;
                    } else {
                        $dispo_ebauche=$dispo_ebauche-$hm_ebauche;
                    }

                } else {
                    if($start_date_ebauche != $day_string && $end_date_ebauche != $day_string){
                        $dispo_ebauche=$dispo_ebauche-$dt_ebauche-$hs_ebauche;
                    } else if($start_date_ebauche == $day_string){
                        $hm_ebauche=$absence_ebauche['oa_during_hours_first_day'].'.'.$absence_ebauche['oa_during_minutes_first_day'];
                        $dispo_ebauche=$dispo_ebauche-$hm_ebauche;
                    }else if($end_date_ebauche == $day_string){
                        $hm_ebauche=$absence_ebauche['oa_during_hours_last_day'].'.'.$absence_ebauche['oa_during_minutes_last_day'];
                        $dispo_ebauche=$dispo_ebauche-$hm_ebauche;
                    }
                }

                // Execution de code
                $debut_ebauche = new DateTime($absence_ebauche['oa_start_hour_date']);
                $fin_ebauche = new DateTime($absence_ebauche['oa_end_hour_date']);
                $interval_ebauche = $debut_ebauche->diff($fin_ebauche);

            }
        }

        if($absences_lining!=[]){
            foreach($absences_lining as $absence_lining){
                // echo $day_string;
                //  $date_start=new DateTime($absence['oa_start_hour_date']);
                //  $date_end=new DateTime($absence['oa_end_hour_date']);
                $start_date_time_lining= new DateTime($absence_lining['oa_start_hour_date']);
                $start_date_lining=$start_date_time_lining->format('Y-m-d');
                $end_date_time_lining= new DateTime($absence_lining['oa_end_hour_date']);
                $end_date_lining=$end_date_time_lining->format('Y-m-d');

                $operator_overtime_lining=verification_operator_overtime_at_date($absence_lining['oa_operator_id'], $day_string);
                if($operator_overtime_lining==[]){
                    $hs_lining=0;
                } else {
                    $hs_lining=$operator_overtime_lining[0]['oo_during'];
                }

                $operator_default_production_time_lining = select_operator_default_time_by_id($absence_lining['oa_operator_id']);
                $dt_lining=$operator_default_production_time_lining[0]['otd_production_time'];
                
                // si le meme jour, calculer l'écart en heure minute et le comparer au temps de presence
                if($end_date_lining==$start_date_lining){

                    $hm_lining=$absence_lining['oa_during_hours_first_day'].'.'.$absence_lining['oa_during_minutes_first_day'];

                    if($hm_lining>=($dt_lining+$hs_lining)){
                        // son absence couvre son temps normal plus les heures sup prévues
                        $dispo_lining=$dispo_lining-$dt_lining-$hs_lining;
                    } else {
                        $dispo_lining=$dispo_lining-$hm_lining;
                    }

                } else {
                    if($start_date_lining != $day_string && $end_date_lining != $day_string){
                        $dispo_lining=$dispo_lining-$dt_lining-$hs_lining;
                    } else if($start_date_lining == $day_string){
                        $hm_lining=$absence_lining['oa_during_hours_first_day'].'.'.$absence_lining['oa_during_minutes_first_day'];
                        $dispo_lining=$dispo_lining-$hm_lining;
                    }else if($end_date_lining == $day_string){
                        $hm_lining=$absence_lining['oa_during_hours_last_day'].'.'.$absence_lining['oa_during_minutes_last_day'];
                        $dispo_lining=$dispo_lining-$hm_lining;
                    }
                }

                // Execution de code
                $debut_lining = new DateTime($absence_lining['oa_start_hour_date']);
                $fin_lining = new DateTime($absence_lining['oa_end_hour_date']);
                $interval_lining = $debut_lining->diff($fin_lining);

            }
        }

        if($absences_fibre!=[]){
            foreach($absences_fibre as $absence_fibre){
                // echo $day_string;
                //  $date_start=new DateTime($absence['oa_start_hour_date']);
                //  $date_end=new DateTime($absence['oa_end_hour_date']);
                $start_date_time_fibre= new DateTime($absence_fibre['oa_start_hour_date']);
                $start_date_fibre=$start_date_time_fibre->format('Y-m-d');
                $end_date_time_fibre= new DateTime($absence_fibre['oa_end_hour_date']);
                $end_date_fibre=$end_date_time_fibre->format('Y-m-d');

                $operator_overtime_fibre=verification_operator_overtime_at_date($absence_fibre['oa_operator_id'], $day_string);
                if($operator_overtime_fibre==[]){
                    $hs_fibre=0;
                } else {
                    $hs_fibre=$operator_overtime_fibre[0]['oo_during'];
                }

                $operator_default_production_time_fibre = select_operator_default_time_by_id($absence_fibre['oa_operator_id']);
                $dt_fibre=$operator_default_production_time_fibre[0]['otd_production_time'];
                
                // si le meme jour, calculer l'écart en heure minute et le comparer au temps de presence
                if($end_date_fibre==$start_date_fibre){

                    $hm_fibre=$absence_fibre['oa_during_hours_first_day'].'.'.$absence_fibre['oa_during_minutes_first_day'];

                    if($hm_fibre>=($dt_fibre+$hs_fibre)){
                        // son absence couvre son temps normal plus les heures sup prévues
                        $dispo_fibre=$dispo_fibre-$dt_fibre-$hs_fibre;
                    } else {
                        $dispo_fibre=$dispo_fibre-$hm_fibre;
                    }

                } else {
                    if($start_date_fibre != $day_string && $end_date_fibre != $day_string){
                        $dispo_fibre=$dispo_fibre-$dt_fibre-$hs_fibre;
                    } else if($start_date_fibre == $day_string){
                        $hm_fibre=$absence_fibre['oa_during_hours_first_day'].'.'.$absence_fibre['oa_during_minutes_first_day'];
                        $dispo_fibre=$dispo_fibre-$hm_fibre;
                    }else if($end_date_fibre == $day_string){
                        $hm_fibre=$absence_fibre['oa_during_hours_last_day'].'.'.$absence_fibre['oa_during_minutes_last_day'];
                        $dispo_fibre=$dispo_fibre-$hm_fibre;
                    }
                }

                // Execution de code
                $debut_fibre = new DateTime($absence_fibre['oa_start_hour_date']);
                $fin_fibre = new DateTime($absence_fibre['oa_end_hour_date']);
                $interval_fibre = $debut_fibre->diff($fin_fibre);

            }
        }
       
    }



    $times=select_estimated_workload_at_date_by_step($day_string,'4');
    $times_ebauche=select_estimated_workload_at_date_by_step($day_string,'3');
    $times_manuel_lining=select_estimated_workload_at_date_by_step($day_string,'2');
    $times_mechanical_lining=select_estimated_workload_at_date_by_step($day_string,'6');
    $times_fibre=select_estimated_workload_at_date_by_step($day_string,'1');

    $occupation=null;
    foreach($times as $time){
        $occupation=$occupation+$time['nb_minute'];
    }

    $occupation_ebauche=null;
    foreach($times_ebauche as $time_ebauche){
        $occupation_ebauche=$occupation_ebauche+$time_ebauche['nb_minute'];
    }

    $occupation_manuel_lining=null;
    foreach($times_manuel_lining as $time_manuel_lining){
        $occupation_manuel_lining=$occupation_manuel_lining+$time_manuel_lining['nb_minute'];
    }

    $occupation_mechanical_lining=null;
    foreach($times_mechanical_lining as $time_mechanical_lining){
        $occupation_mechanical_lining=$occupation_mechanical_lining+$time_mechanical_lining['nb_minute'];
    }

    $occupation_lining=$occupation_mechanical_lining+$occupation_manuel_lining;

    $occupation_fibre=null;
    foreach($times_fibre as $time_fibre){
        $occupation_fibre=$occupation_fibre+$time_fibre['nb_minute'];
    }

    $occupation_heures=floor($occupation/60);
    $occupation_minutes=$occupation-($occupation_heures*60);
    $occupation_minutes_decimal=ceil($occupation_minutes/60*100);
    $occupation_decimal=$occupation_heures+($occupation_minutes_decimal/100);

    $occupation_heures_ebauche=floor($occupation_ebauche/60);
    $occupation_minutes_ebauche=$occupation_ebauche-($occupation_heures_ebauche*60);
    $occupation_minutes_decimal_ebauche=ceil($occupation_minutes_ebauche/60*100);
    $occupation_decimal_ebauche=$occupation_heures_ebauche+($occupation_minutes_decimal_ebauche/100);

    $occupation_heures_lining=floor($occupation_lining/60);
    $occupation_minutes_lining=$occupation_lining-($occupation_heures_lining*60);
    $occupation_minutes_decimal_lining=ceil($occupation_minutes_lining/60*100);
    $occupation_decimal_lining=$occupation_heures_lining+($occupation_minutes_decimal_lining/100);

    $occupation_heures_fibre=floor($occupation_fibre/60);
    $occupation_minutes_fibre=$occupation_fibre-($occupation_heures_fibre*60);
    $occupation_minutes_decimal_fibre=ceil($occupation_minutes_fibre/60*100);
    $occupation_decimal_fibre=$occupation_heures_fibre+($occupation_minutes_decimal_fibre/100);

    if($now==$day_string){
        $day_grinding_dispo=$dispo;
        $day_grinding_occupation_decimal=$occupation_decimal;

        $day_grinding_dispo_ebauche=$dispo_ebauche;
        $day_grinding_occupation_decimal_ebauche=$occupation_decimal_ebauche;

        $day_grinding_dispo_lining=$dispo_lining;
        $day_grinding_occupation_decimal_lining=$occupation_decimal_lining;

        $day_grinding_dispo_fibre=$dispo_fibre;
        $day_grinding_occupation_decimal_fibre=$occupation_decimal_fibre;

    } else if($tomorrow==$day_string){
        $tom_grinding_dispo=$dispo;
        $tom_grinding_occupation_decimal=$occupation_decimal;

        $tom_grinding_dispo_ebauche=$dispo_ebauche;
        $tom_grinding_occupation_decimal_ebauche=$occupation_decimal_ebauche;

        $tom_grinding_dispo_lining=$dispo_lining;
        $tom_grinding_occupation_decimal_lining=$occupation_decimal_lining;

        $tom_grinding_dispo_fibre=$dispo_fibre;
        $tom_grinding_occupation_decimal_fibre=$occupation_decimal_fibre;
    }
    

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

    $n++;
}   

////////////////////////////////////////////////////////////
///////////////////// Daily gauges END /////////////////////
////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////
//////////////// Circular chart values START ////////////////
/////////////////////////////////////////////////////////////
// $day_grinding_dispo;
// $day_grinding_occupation_decimal;

if($day_grinding_dispo!=0){
    $day_grinding_busy=ceil($day_grinding_occupation_decimal/$day_grinding_dispo*100);
} else{
    $day_grinding_busy=100;
}
$day_grinding_not_busy=100-$day_grinding_busy;


if($tom_grinding_dispo_ebauche!=0){
    $tom_grinding_busy=ceil($tom_grinding_occupation_decimal/$tom_grinding_dispo*100);
} else{
    $tom_grinding_busy=100;
}
$tom_grinding_not_busy=100-$tom_grinding_busy;

/////

if($day_grinding_dispo_ebauche!=0){
    $day_grinding_busy_ebauche=ceil($day_grinding_occupation_decimal_ebauche/$day_grinding_dispo_ebauche*100);
} else{
    $day_grinding_busy_ebauche=100;
}
$day_grinding_not_busy_ebauche=100-$day_grinding_busy_ebauche;

if($tom_grinding_dispo_ebauche!=0){
    $tom_grinding_busy_ebauche=ceil($tom_grinding_occupation_decimal_ebauche/$tom_grinding_dispo_ebauche*100);
} else{
    $tom_grinding_busy_ebauche=100;
}
$tom_grinding_not_busy_ebauche=100-$tom_grinding_busy_ebauche;

/////

if($day_grinding_dispo_lining!=0){
    $day_grinding_busy_lining=ceil($day_grinding_occupation_decimal_lining/$day_grinding_dispo_lining*100);
} else{
    $day_grinding_busy_lining=100;
}
$day_grinding_not_busy_lining=100-$day_grinding_busy_lining;

if($tom_grinding_dispo_lining!=0){
    $tom_grinding_busy_lining=ceil($tom_grinding_occupation_decimal_lining/$tom_grinding_dispo_lining*100);
} else{
    $tom_grinding_busy_lining=100;
}
$tom_grinding_not_busy_lining=100-$tom_grinding_busy_lining;

/////

if($day_grinding_dispo_fibre!=0){
    $day_grinding_busy_fibre=ceil($day_grinding_occupation_decimal_fibre/$day_grinding_dispo_fibre*100);
} else{
    $day_grinding_busy_fibre=100;
}
$day_grinding_not_busy_fibre=100-$day_grinding_busy_fibre;

if($tom_grinding_dispo_fibre!=0){
    $tom_grinding_busy_fibre=ceil($tom_grinding_occupation_decimal_fibre/$tom_grinding_dispo_fibre*100);
} else{
    $tom_grinding_busy_fibre=100;
}
$tom_grinding_not_busy_fibre=100-$tom_grinding_busy_fibre;


$dataPointsGrindingN = array( 
	array("label"=>"Occupé", "y"=>$day_grinding_busy,"color"=>'#FFAE42'),
	array("label"=>"Dispo", "y"=>$day_grinding_not_busy,"color"=>'#eee')
);

$dataPointsEbaucheN = array( 
	array("label"=>"Occupé", "y"=>$day_grinding_busy_ebauche,"color"=>'#FF0000'),
	array("label"=>"Dispo", "y"=>$day_grinding_not_busy_ebauche,"color"=>'#eee')
);

$dataPointsLiningN = array( 
	array("label"=>"Occupé", "y"=>$day_grinding_busy_lining,"color"=>'#008000'),
	array("label"=>"Dispo", "y"=>$day_grinding_not_busy_lining,"color"=>'#eee')
);

$dataPointsFibreN = array( 
	array("label"=>"Occupé", "y"=>$day_grinding_busy_fibre,"color"=>'#203f90'),
	array("label"=>"Dispo", "y"=>$day_grinding_not_busy_fibre,"color"=>'#eee')
);


$dataPointsGrindingN1 = array( 
	array("label"=>"Occupé", "y"=>$tom_grinding_busy,"color"=>'#FFAE42'),
	array("label"=>"Dispo", "y"=>$tom_grinding_not_busy,"color"=>'#eee')
);

$dataPointsEbaucheN1 = array( 
	array("label"=>"Occupé", "y"=>$tom_grinding_busy_ebauche,"color"=>'#FF0000'),
	array("label"=>"Dispo", "y"=>$tom_grinding_not_busy_ebauche,"color"=>'#eee')
);

$dataPointsLiningN1 = array( 
	array("label"=>"Occupé", "y"=>$tom_grinding_busy_lining,"color"=>'#008000'),
	array("label"=>"Dispo", "y"=>$tom_grinding_not_busy_lining,"color"=>'#eee')
);

$dataPointsFibreN1 = array( 
	array("label"=>"Occupé", "y"=>$tom_grinding_busy_fibre,"color"=>'#203f90'),
	array("label"=>"Dispo", "y"=>$tom_grinding_not_busy_fibre,"color"=>'#eee')
);

/////////////////////////////////////////////////////////////
///////////////// Circular chart values END /////////////////
/////////////////////////////////////////////////////////////
?>

<!DOCTYPE html>
<html lang="fr" class="<?php echo $_SESSION['mode'];?>">
    <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <!--<link href="images/logo.svg" rel="shortcut icon">-->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo trad('files_awaiting_planning',$_SESSION["language"]);?></title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="dist/css/app.css" />
        <link rel="stylesheet" href="style/custom.css" />
        <!-- END: CSS Assets-->
        
        <script>
            window.onload = function() {
                
            
            
                var chartGrindingN = new CanvasJS.Chart("chartContainerGrindingN", {
                    theme: "light",
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
                        dataPoints: <?php echo json_encode($dataPointsGrindingN, JSON_NUMERIC_CHECK); ?>
                    }]
                });
                chartGrindingN.render();

                var chartEbaucheN = new CanvasJS.Chart("chartContainerEbaucheN", {
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
                        dataPoints: <?php echo json_encode($dataPointsEbaucheN, JSON_NUMERIC_CHECK); ?>
                    }]
                });
                chartEbaucheN.render();

                var chartLiningN = new CanvasJS.Chart("chartContainerLiningN", {
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
                        dataPoints: <?php echo json_encode($dataPointsLiningN, JSON_NUMERIC_CHECK); ?>
                    }]
                });
                chartLiningN.render();

                var chartFibreN = new CanvasJS.Chart("chartContainerFibreN", {
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
                        colorSet: "greenShades",
                        // showInLegend: true,
                        // legendText: "{label}",
                        dataPoints: <?php echo json_encode($dataPointsFibreN, JSON_NUMERIC_CHECK); ?>
                    }]
                });
                chartFibreN.render();



                var chartGrindingN1 = new CanvasJS.Chart("chartContainerGrindingN1", {
                    theme: "light",
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
                        dataPoints: <?php echo json_encode($dataPointsGrindingN1, JSON_NUMERIC_CHECK); ?>
                    }]
                });
                chartGrindingN1.render();

                var chartEbaucheN1 = new CanvasJS.Chart("chartContainerEbaucheN1", {
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
                        dataPoints: <?php echo json_encode($dataPointsEbaucheN1, JSON_NUMERIC_CHECK); ?>
                    }]
                });
                chartEbaucheN1.render();

                var chartLiningN1 = new CanvasJS.Chart("chartContainerLiningN1", {
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
                        dataPoints: <?php echo json_encode($dataPointsLiningN1, JSON_NUMERIC_CHECK); ?>
                    }]
                });
                chartLiningN1.render();

                var chartFibreN1 = new CanvasJS.Chart("chartContainerFibreN1", {
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
                        colorSet: "greenShades",
                        // showInLegend: true,
                        // legendText: "{label}",
                        dataPoints: <?php echo json_encode($dataPointsFibreN1, JSON_NUMERIC_CHECK); ?>
                    }]
                });
                chartFibreN1.render();
            
            }
        </script>
    </head>
<!-- END: Head -->
<body class="main">
    <?php 
    include('contents/header.php');
    ?>
    <div class="content">
        <div class="intro-y box mt-6">
            <div class="d-flex flex-column flex-sm-row align-items-center p-5 border-bottom border-gray-200 dark-border-dark-5">
                <h2 class="fs-lg fw-medium truncate me-5">
                    <?php echo trad('overall_grinding_planning',$_SESSION['language']);?>
                </h2>
            </div>
            
            <div id="progressbar-height" class="p-5">
                <div class="preview">
                    <div id="progressbar-height" class="p-5">
                        <div class="preview">
                        <?php
                        for($j=0; $j<$n; $j++ ){
                            if($t_verif_occupation[$j]==0){
                                ?> <a href="grinding_day_plan.php?date=<?php echo $t_year[$j].'-'.$t_month[$j].'-'.$t_day[$j];?>"> <?php
                            } ?>
                                <div class="mb-6">
                                    <?php echo $t_day_name[$j].' '.$t_day[$j].' '.$t_month_name[$j].' '.$t_year[$j]; if($t_verif_occupation[$j]==0){?> <span style="float:right;">(<?php echo $t_occupation_heures[$j].'h'; if($t_occupation_minutes[$j] != 0){ echo $t_occupation_minutes[$j].'m';} echo '/'.$t_dispo[$j];?> h)</span><?php } ?>
                                    <br>
                                    <meter low="0" high="<?php echo $t_dispo[$j];?>" max="<?php echo $t_dispo[$j];?>" value="<?php echo $t_occupation_decimal[$j];?>" class="meter_kairos" <?php if($t_verif_occupation[$j]==1){echo 'style="opacity: 0.1; !important;"';}?> ><?php echo $t_dispo[$j];?></meter>
                                </div>
                            <?php
                            if($t_verif_occupation[$j]==0){
                            ?>
                                </a>
                            <?php } 
                        }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid columns-12 gap-6">
            <div class="g-col-12 g-col-xxl-9">
                <div class="grid columns-12 gap-6">

                    <!-- BEGIN: fiber charge -->
                    <div class="g-col-12 g-col-sm-6 g-col-lg-3 mt-8">
                        <div class="intro-y d-flex align-items-center h-10" style="background:white; border-radius: .375rem .375rem 0 0 ; min-height:50px; padding:5px;">
                            <h2 class="fs-lg fw-medium truncate me-5">
                                <?php echo trad('days_workload',$_SESSION["language"]);?><br><?php echo trad('fiber',$_SESSION["language"]);?>
                            </h2>
                            <a href="fiber_day_plan.php?date=<?php echo $now;?>" class="ms-auto text-theme-26 dark-text-theme-33 truncate"><?php echo trad('see_planning',$_SESSION["language"]);?></a> 
                        </div>
                        <div class="intro-y box p-5 mt-1" style="border-radius: 0 0 .375rem .375rem;">
                            <em><u><?php echo trad('available_time',$_SESSION["language"]);?> : <?php echo $day_grinding_dispo_fibre;?> h</u></em>
                            <div id="chartContainerFibreN" style="height: 200px; width: 100%;"></div>
                            <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
                            <div class="mt-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-2 h-2 bg-theme-17 rounded-circle me-3" style="background-color:#FFAE42 !important"></div>
                                    <span class="truncate"><?php echo trad('workload',$_SESSION["language"]);?></span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto"><?php echo $day_grinding_occupation_decimal_fibre;?> h</span> 
                                </div>
                                <div class="d-flex align-items-center mt-4">
                                    <div class="w-2 h-2 bg-theme-35 rounded-circle me-3" style="background-color:#eee !important;"></div>
                                    <span class="truncate"><?php echo trad('available',$_SESSION["language"]);?></span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto"><?php echo $day_grinding_dispo_fibre-$day_grinding_occupation_decimal_fibre;?> h</span> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: fiber charge -->

                    <!-- BEGIN: lining charge -->
                    <div class="g-col-12 g-col-sm-6 g-col-lg-3 mt-8">
                    <div class="intro-y d-flex align-items-center h-10" style="background:white; border-radius: .375rem .375rem 0 0 ; min-height:50px; padding:5px;">
                            <h2 class="fs-lg fw-medium truncate me-5">
                                <?php echo trad('days_workload',$_SESSION["language"]);?><br><?php echo trad('lining',$_SESSION["language"]);?>
                            </h2>
                            <a href="lining_day_plan.php?date=<?php echo $now;?>" class="ms-auto text-theme-26 dark-text-theme-33 truncate"><?php echo trad('see_planning',$_SESSION["language"]);?></a> 
                        </div>
                        <div class="intro-y box p-5 mt-1">
                            <em><u><?php echo trad('available_time',$_SESSION["language"]);?> : <?php echo $day_grinding_dispo_lining;?> h</u></em>

                            <div id="chartContainerLiningN" style="height: 200px; width: 100%;"></div>
                            <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
                            <div class="mt-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-2 h-2 bg-theme-17 rounded-circle me-3" style="background-color:#FFAE42 !important"></div>
                                    <span class="truncate"><?php echo trad('workload',$_SESSION["language"]);?></span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto"><?php echo $day_grinding_occupation_decimal_lining;?> h</span> 
                                </div>
                                <div class="d-flex align-items-center mt-4">
                                    <div class="w-2 h-2 bg-theme-35 rounded-circle me-3" style="background-color:#eee !important;"></div>
                                    <span class="truncate"><?php echo trad('available',$_SESSION["language"]);?></span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto"><?php echo $day_grinding_dispo_lining-$day_grinding_occupation_decimal_lining;?> h</span> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: lining charge -->

                    <!-- BEGIN: roughing charge -->
                    <div class="g-col-12 g-col-sm-6 g-col-lg-3 mt-8">
                    <div class="intro-y d-flex align-items-center h-10" style="background:white; border-radius: .375rem .375rem 0 0 ; min-height:50px; padding:5px;">
                            <h2 class="fs-lg fw-medium truncate me-5">
                                <?php echo trad('days_workload',$_SESSION["language"]);?><br><?php echo trad('roughing',$_SESSION["language"]);?>
                            </h2>
                            <a href="roughing_day_plan.php?date=<?php echo $now;?>" class="ms-auto text-theme-26 dark-text-theme-33 truncate"><?php echo trad('see_planning',$_SESSION["language"]);?></a> 
                        </div>
                        <div class="intro-y box p-5 mt-1">
                            <em><u><?php echo trad('available_time',$_SESSION["language"]);?> : <?php echo $day_grinding_dispo_ebauche;?> h</u></em>
                            <div id="chartContainerEbaucheN" style="height: 200px; width: 100%;"></div>
                            <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
                            <div class="mt-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-2 h-2 bg-theme-17 rounded-circle me-3" style="background-color:#FFAE42 !important"></div>
                                    <span class="truncate"><?php echo trad('workload',$_SESSION["language"]);?></span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto"><?php echo $day_grinding_occupation_decimal_ebauche;?> h</span> 
                                </div>
                                <div class="d-flex align-items-center mt-4">
                                    <div class="w-2 h-2 bg-theme-35 rounded-circle me-3" style="background-color:#eee !important;"></div>
                                    <span class="truncate"><?php echo trad('available',$_SESSION["language"]);?></span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto"><?php echo $day_grinding_dispo_ebauche-$day_grinding_occupation_decimal_ebauche;?> h</span> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: roughing charge -->

                    <!-- BEGIN: grinding charge -->
                    <div class="g-col-12 g-col-sm-6 g-col-lg-3 mt-8">
                    <div class="intro-y d-flex align-items-center h-10" style="background:white; border-radius: .375rem .375rem 0 0 ; min-height:50px; padding:5px;">
                            <h2 class="fs-lg fw-medium truncate me-5">
                                <?php echo trad('days_workload',$_SESSION["language"]);?><br><?php echo trad('grinding',$_SESSION["language"]);?>
                            </h2>
                            <a href="grinding_day_plan.php?date=<?php echo $now;?>" class="ms-auto text-theme-26 dark-text-theme-33 truncate"><?php echo trad('see_planning',$_SESSION["language"]);?></a> 
                        </div>
                        <div class="intro-y box p-5 mt-1">
                            <em><u><?php echo trad('available_time',$_SESSION["language"]);?> : <?php echo $day_grinding_dispo;?> h</u></em>
                            <div id="chartContainerGrindingN" style="height: 200px; width: 100%;"></div>
                            <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

                            <div class="mt-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-2 h-2 bg-theme-17 rounded-circle me-3" style="background-color:#FFAE42 !important"></div>
                                    <span class="truncate"><?php echo trad('workload',$_SESSION["language"]);?></span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto"><?php echo $day_grinding_occupation_decimal;?> h</span> 
                                </div>
                                <div class="d-flex align-items-center mt-4">
                                    <div class="w-2 h-2 bg-theme-35 rounded-circle me-3" style="background-color:#eee !important;"></div>
                                    <span class="truncate"><?php echo trad('available',$_SESSION["language"]);?></span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto"><?php echo $day_grinding_dispo-$day_grinding_occupation_decimal;?> h</span> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: grinding charge -->

                </div>
            </div>
        </div>

        <div class="row gap-y-6 mt-5">
            <div class="intro-y col-12 col-lg-6">
                <div class="intro-y box mt-5">
                    <div class="d-flex flex-column flex-sm-row align-items-center p-5 border-bottom border-gray-200 dark-border-dark-5">
                        <h2 class="fw-medium fs-base me-auto">
                            <?php echo trad('informations_of_day',$_SESSION['language']);?>
                        </h2>
                    </div>
                    <div id="icon-alert" class="p-5">
                        <div class="preview">
                            <div class="alert alert-primary d-flex align-items-center mb-2" role="alert"> <i data-feather="alert-triangle" class="w-6 h-6 me-2"></i>
                            <?php

                            ////////////////////////////////////////////////////////////
                            ////////////////// Operator absence START //////////////////
                            ////////////////////////////////////////////////////////////

                            $absence_operators=select_operators_absence_at_date($now);
                            if($absence_operators!=[]){
                                foreach($absence_operators as $absence_operator){
                                    $start_date_time= new DateTime($absence_operator['oa_start_hour_date']);
                                    $start_date=$start_date_time->format('Y-m-d');
                                    $end_date_time= new DateTime($absence_operator['oa_end_hour_date']);
                                    $end_date=$end_date_time->format('Y-m-d');

                                    if($end_date==$start_date){
                                        $operator_default_production_time = select_operator_default_time_by_id($absence_operator['oa_operator_id']);
                                        $dt=$operator_default_production_time[0]['otd_production_time'];
                                        $hm=$absence_operator['oa_during_hours_first_day'].'.'.$absence_operator['oa_during_minutes_first_day'];
                                        
                                        if($hm>=$dt){
                                            echo '• '.$absence_operator['us_firstname'].' '.$absence_operator['us_name'].' '.trad('is_absent',$_SESSION["language"]).'.<br>';
                                        } else {
                                            $absence_time=$dt-$hm;
                                            echo '• '.$absence_operator['us_firstname'].' '.$absence_operator['us_name'].' '.trad('is_absent',$_SESSION["language"]).' '.$hm.' h.<br>';
                                        }
                                    } else {
                                        if($start_date != $now && $end_date != $now){
                                            echo '• '.$absence_operator['us_firstname'].' '.$absence_operator['us_name'].' '.trad('is_absent',$_SESSION["language"]).'.<br>';
                                        } else if($start_date == $now){
                                            $operator_default_production_time = select_operator_default_time_by_id($absence_operator['oa_operator_id']);
                                            $dt=$operator_default_production_time[0]['otd_production_time'];
                                            $hm=$absence_operator['oa_during_hours_first_day'].'.'.$absence_operator['oa_during_minutes_first_day'];
                                            if($hm>=$dt){
                                                echo '• '.$absence_operator['us_firstname'].' '.$absence_operator['us_name'].' '.trad('is_absent',$_SESSION["language"]).'.<br>';
                                            } else {
                                                $absence_time=$dt-$hm;
                                                echo '• '.$absence_operator['us_firstname'].' '.$absence_operator['us_name'].' '.trad('is_absent',$_SESSION["language"]).' '.$hm.' h.<br>';
                                            }
                                           

                                        }else if($end_date == $now){
                                            $operator_default_production_time = select_operator_default_time_by_id($absence_operator['oa_operator_id']);
                                            $dt=$operator_default_production_time[0]['otd_production_time'];
                                            $hm=$absence_operator['oa_during_hours_last_day'].'.'.$absence_operator['oa_during_minutes_last_day'];
                                            if($hm>=$dt){
                                                echo '• '.$absence_operator['us_firstname'].' '.$absence_operator['us_name'].' '.trad('is_absent',$_SESSION["language"]).'.<br>';
                                            } else {
                                                $absence_time=$dt-$hm;
                                                echo '• '.$absence_operator['us_firstname'].' '.$absence_operator['us_name'].' '.trad('is_absent',$_SESSION["language"]).' '.$hm.' h.<br>';
                                            }
                                        }
                                    }
                                    
                                }
                            }

                            ////////////////////////////////////////////////////////////
                            /////////////////// Operator absence END ///////////////////
                            ////////////////////////////////////////////////////////////

                            /////////////////////////////////////////////////////////////
                            ///////////////// Machine maintenance START /////////////////
                            /////////////////////////////////////////////////////////////

                            $maintenances=select_maintenance_at_date($now);
                            
                            
                            if($maintenances!=[]){
                                foreach($maintenances as $maintenance){
                                    $start_date_time= new DateTime($maintenance['ms_start_hour_date']);
                                    $start_date=$start_date_time->format('Y-m-d');
                                    $end_date_time= new DateTime($maintenance['ms_end_hour_date']);
                                    $end_date=$end_date_time->format('Y-m-d');

                                    if($end_date==$start_date){
                                        echo '• '.$maintenance['mc_label'].' '.trad('is_under_maintenance_for',$_SESSION["language"]).' '.$maintenance['ms_during_hours_first_day'].'.'.$maintenance['ms_during_minutes_first_day'].' h<br>';
                                        
                                    } else {
                                        if($start_date != $now && $end_date != $now){
                                            echo '• Machine '.$maintenance['mc_label'].' '.trad('is_in_maintenance_all_day',$_SESSION["language"]).'.<br>';
                                        } else if($start_date == $now){
                                            echo '• Machine '.$maintenance['mc_label'].' '.trad('is_under_maintenance_for',$_SESSION["language"]).' '.$maintenance['ms_during_hours_first_day'].'.'.$maintenance['ms_during_minutes_first_day'].' h<br>';
                                        }else if($end_date == $now){
                                            echo '• Machine '.$maintenance['mc_label'].' '.trad('is_under_maintenance_for',$_SESSION["language"]).' '.$maintenance['ms_during_hours_last_day'].'.'.$maintenance['ms_during_minutes_last_day'].' h<br>';
                                        }
                                    }
                                    
                                }
                            }
                            
                            if($absence_operators==[] && $maintenances==[]){
                                echo trad('nothing_to_report',$_SESSION["language"]);
                            }

                            /////////////////////////////////////////////////////////////
                            ////////////////// Machine maintenance END //////////////////
                            /////////////////////////////////////////////////////////////

                            /////////////////////////////////////////////////////////////
                            ////////////////////// Over time START //////////////////////
                            /////////////////////////////////////////////////////////////

                            $alert_overtimes=select_over_time_at_date($now);
                            if($alert_overtimes!=[]){
                                foreach($alert_overtimes as $alert_overtime){                    
                                 echo '• '.$alert_overtime['oo_during'].' heure(s) supplémentaire(s) prévue(s) pour '.$alert_overtime ["us_firstname"].' '.$alert_overtime ["us_name"].'.<br>';
                                }
                            }

                            /////////////////////////////////////////////////////////////
                            //////////////////////// Over time END //////////////////////
                            /////////////////////////////////////////////////////////////

                            ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="intro-y col-12 col-lg-6">
                <div class="intro-y box mt-5">
                    <div class="d-flex flex-column flex-sm-row align-items-center p-5 border-bottom border-gray-200 dark-border-dark-5">
                        <h2 class="fw-medium fs-base me-auto">
                            <?php echo trad('important',$_SESSION["language"]);?>
                        </h2>
                    </div>
                    <div id="icon-alert" class="p-5">
                        <div class="preview">
                            <div class="alert alert-danger d-flex align-items-center mb-2" role="alert"> <i data-feather="alert-triangle" class="w-6 h-6 me-2"></i>
                                <?php
                                /////////////////////////////////////////////////////////////
                                ///////////////////// late orders START /////////////////////
                                /////////////////////////////////////////////////////////////

                                $late_orders=select_late_orders($now);
                                foreach($late_orders as $late_order){
                                    echo '• '.trad('the_step',$_SESSION['language']).' "'.trad($late_order['stp_label'],$_SESSION['language']).'" '.trad('of_the_piece',$_SESSION['language']).' '.$late_order['pt_piece_id'].' '.trad('is_behind_schedule',$_SESSION['language']).'.<br>';
                                }

                                /////////////////////////////////////////////////////////////
                                ////////////////////// late orders END //////////////////////
                                /////////////////////////////////////////////////////////////
                                
                                //TODO Alert when stock is too low
                                ?>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="grid columns-12 gap-6">
            <div class="g-col-12 g-col-xxl-9">
                <div class="grid columns-12 gap-6">

                    <!-- BEGIN: fiber charge -->
                    <div class="g-col-12 g-col-sm-6 g-col-lg-3 mt-8">
                        <div class="intro-y d-flex align-items-center h-10" style="background:white; border-radius: .375rem .375rem 0 0 ; min-height:50px; padding:5px;">
                            <h2 class="fs-lg fw-medium truncate me-5">
                                <?php echo trad('workload_d+1',$_SESSION["language"]);?><br><?php echo trad('fiber',$_SESSION["language"]);?>
                            </h2>
                            <a href="fiber_day_plan.php?date=<?php echo $tomorrow;?>" class="ms-auto text-theme-26 dark-text-theme-33 truncate"><?php echo trad('see_planning',$_SESSION["language"]);?></a> 
                        </div>
                        <div class="intro-y box p-5 mt-1">
                            <em><u><?php echo trad('available_time',$_SESSION["language"]);?> : <?php echo $tom_grinding_dispo_fibre;?> h</u></em>
                            <div id="chartContainerFibreN1" style="height: 200px; width: 100%;"></div>
                            <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
                            <div class="mt-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-2 h-2 bg-theme-17 rounded-circle me-3" style="background-color:#FFAE42 !important"></div>
                                    <span class="truncate"><?php echo trad('workload',$_SESSION["language"]);?></span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto"><?php echo $tom_grinding_occupation_decimal_fibre;?> h</span> 
                                </div>
                                <div class="d-flex align-items-center mt-4">
                                    <div class="w-2 h-2 bg-theme-35 rounded-circle me-3" style="background-color:#eee !important;"></div>
                                    <span class="truncate"><?php echo trad('available',$_SESSION["language"]);?></span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto"><?php echo $tom_grinding_dispo_fibre-$tom_grinding_occupation_decimal_fibre;?> h</span> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: fiber charge -->

                    <!-- BEGIN: lining charge -->
                    <div class="g-col-12 g-col-sm-6 g-col-lg-3 mt-8">
                    <div class="intro-y d-flex align-items-center h-10" style="background:white; border-radius: .375rem .375rem 0 0 ; min-height:50px; padding:5px;">
                            <h2 class="fs-lg fw-medium truncate me-5">
                                <?php echo trad('workload_d+1',$_SESSION["language"]);?><br><?php echo trad('lining',$_SESSION["language"]);?>
                            </h2>
                            <a href="lining_day_plan.php?date=<?php echo $tomorrow;?>" class="ms-auto text-theme-26 dark-text-theme-33 truncate"><?php echo trad('see_planning',$_SESSION["language"]);?></a> 
                        </div>
                        <div class="intro-y box p-5 mt-1">
                            <em><u><?php echo trad('available_time',$_SESSION["language"]);?> : <?php echo $tom_grinding_dispo_lining;?> h</u></em>
                            <div id="chartContainerLiningN1" style="height: 200px; width: 100%;"></div>
                            <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
                            <div class="mt-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-2 h-2 bg-theme-17 rounded-circle me-3" style="background-color:#FFAE42 !important"></div>
                                    <span class="truncate"><?php echo trad('workload',$_SESSION["language"]);?></span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto"><?php echo $tom_grinding_occupation_decimal_lining;?> h</span> 
                                </div>
                                <div class="d-flex align-items-center mt-4">
                                    <div class="w-2 h-2 bg-theme-35 rounded-circle me-3" style="background-color:#eee !important;"></div>
                                    <span class="truncate"><?php echo trad('available',$_SESSION["language"]);?></span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto"><?php echo $tom_grinding_dispo_lining-$tom_grinding_occupation_decimal_lining;?> h</span> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: lining charge -->

                    <!-- BEGIN: roughing charge -->
                    <div class="g-col-12 g-col-sm-6 g-col-lg-3 mt-8">
                    <div class="intro-y d-flex align-items-center h-10" style="background:white; border-radius: .375rem .375rem 0 0 ; min-height:50px; padding:5px;">
                            <h2 class="fs-lg fw-medium truncate me-5">
                                <?php echo trad('workload_d+1',$_SESSION["language"]);?><br><?php echo trad('roughing',$_SESSION["language"]);?>
                            </h2>
                            <a href="roughing_day_plan.php?date=<?php echo $tomorrow;?>" class="ms-auto text-theme-26 dark-text-theme-33 truncate"><?php echo trad('see_planning',$_SESSION["language"]);?></a> 
                        </div>
                        <div class="intro-y box p-5 mt-1">
                            <em><u><?php echo trad('available_time',$_SESSION["language"]);?> : <?php echo $tom_grinding_dispo_ebauche;?> h</u></em>
                            <div id="chartContainerEbaucheN1" style="height: 200px; width: 100%;"></div>
                            <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
                            <div class="mt-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-2 h-2 bg-theme-17 rounded-circle me-3" style="background-color:#FFAE42 !important"></div>
                                    <span class="truncate"><?php echo trad('workload',$_SESSION["language"]);?></span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto"><?php echo $tom_grinding_occupation_decimal_ebauche;?> h</span> 
                                </div>
                                <div class="d-flex align-items-center mt-4">
                                    <div class="w-2 h-2 bg-theme-35 rounded-circle me-3" style="background-color:#eee !important;"></div>
                                    <span class="truncate"><?php echo trad('available',$_SESSION["language"]);?></span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto"><?php echo $tom_grinding_dispo_ebauche-$tom_grinding_occupation_decimal_ebauche;?> h</span> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: roughing charge -->

                    <!-- BEGIN: grinding charge -->
                    <div class="g-col-12 g-col-sm-6 g-col-lg-3 mt-8">
                    <div class="intro-y d-flex align-items-center h-10" style="background:white; border-radius: .375rem .375rem 0 0 ; min-height:50px; padding:5px;">
                            <h2 class="fs-lg fw-medium truncate me-5">
                                <?php echo trad('workload_d+1',$_SESSION["language"]);?><br><?php echo trad('grinding',$_SESSION["language"]);?>
                            </h2>
                            <a href="grinding_day_plan.php?date=<?php echo $tomorrow;?>" class="ms-auto text-theme-26 dark-text-theme-33 truncate"><?php echo trad('see_planning',$_SESSION["language"]);?></a> 
                        </div>
                        <div class="intro-y box p-5 mt-1">
                            <em><u><?php echo trad('available_time',$_SESSION["language"]);?> : <?php echo $tom_grinding_dispo;?> h</u></em>
                            <div id="chartContainerGrindingN1" style="height: 200px; width: 100%;"></div>
                            <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
                            <div class="mt-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-2 h-2 bg-theme-17 rounded-circle me-3" style="background-color:#FFAE42 !important"></div>
                                    <span class="truncate"><?php echo trad('workload',$_SESSION["language"]);?></span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto"><?php echo $tom_grinding_occupation_decimal;?> h</span> 
                                </div>
                                <div class="d-flex align-items-center mt-4">
                                    <div class="w-2 h-2 bg-theme-35 rounded-circle me-3" style="background-color:#eee !important;"></div>
                                    <span class="truncate"><?php echo trad('available',$_SESSION["language"]);?></span> 
                                    <div class="h-px flex-1 border border-start border-dashed border-gray-300 mx-3 d-xl-none"></div>
                                    <span class="fw-medium ms-xl-auto"><?php echo $tom_grinding_dispo-$tom_grinding_occupation_decimal;?> h</span> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: grinding charge -->

                </div>
            </div>
        </div>


        <div class="row gap-y-6 mt-5">
            <div class="intro-y box mt-5">
                <div class="d-flex flex-column flex-sm-row align-items-center p-5 border-bottom border-gray-200 dark-border-dark-5">
                    <h2 class="fw-medium fs-base me-auto">
                        <?php echo trad('next_day_informations',$_SESSION["language"]);?>
                    </h2>
                </div>
                <div id="icon-alert" class="p-5">
                    <div class="preview">
                        <div class="alert alert-warning d-flex align-items-center mb-2" role="alert"> <i data-feather="alert-circle" class="w-6 h-6 me-2"></i>
                        <?php
                        ////////////////////////////////////////////////////////////
                            ////////////////// Operator absence START //////////////////
                            ////////////////////////////////////////////////////////////

                            $absence_operators=select_operators_absence_at_date($tomorrow);
                            if($absence_operators!=[]){
                                foreach($absence_operators as $absence_operator){
                                    $start_date_time= new DateTime($absence_operator['oa_start_hour_date']);
                                    $start_date=$start_date_time->format('Y-m-d');
                                    $end_date_time= new DateTime($absence_operator['oa_end_hour_date']);
                                    $end_date=$end_date_time->format('Y-m-d');

                                    if($end_date==$start_date){
                                        $operator_default_production_time = select_operator_default_time_by_id($absence_operator['oa_operator_id']);
                                        $dt=$operator_default_production_time[0]['otd_production_time'];
                                        $hm=$absence_operator['oa_during_hours_first_day'].'.'.$absence_operator['oa_during_minutes_first_day'];
                                        
                                        if($hm>=$dt){
                                            echo '• '.$absence_operator['us_firstname'].' '.$absence_operator['us_name'].' '.trad('is_absent',$_SESSION["language"]).'.<br>';
                                        } else {
                                            $absence_time=$dt-$hm;
                                            echo '• '.$absence_operator['us_firstname'].' '.$absence_operator['us_name'].' '.trad('is_absent',$_SESSION["language"]).' '.$hm.' h.<br>';
                                        }
                                    } else {
                                        if($start_date != $tomorrow && $end_date != $tomorrow){
                                            echo '• '.$absence_operator['us_firstname'].' '.$absence_operator['us_name'].' '.trad('is_absent',$_SESSION["language"]).'.<br>';
                                        } else if($start_date == $tomorrow){
                                            $operator_default_production_time = select_operator_default_time_by_id($absence_operator['oa_operator_id']);
                                            $dt=$operator_default_production_time[0]['otd_production_time'];
                                            $hm=$absence_operator['oa_during_hours_first_day'].'.'.$absence_operator['oa_during_minutes_first_day'];
                                            if($hm>=$dt){
                                                echo '• '.$absence_operator['us_firstname'].' '.$absence_operator['us_name'].' '.trad('is_absent',$_SESSION["language"]).'.<br>';
                                            } else {
                                                $absence_time=$dt-$hm;
                                                echo '• '.$absence_operator['us_firstname'].' '.$absence_operator['us_name'].' '.trad('is_absent',$_SESSION["language"]).' '.$hm.' h.<br>';
                                            }
                                           

                                        }else if($end_date == $tomorrow){
                                            $operator_default_production_time = select_operator_default_time_by_id($absence_operator['oa_operator_id']);
                                            $dt=$operator_default_production_time[0]['otd_production_time'];
                                            $hm=$absence_operator['oa_during_hours_last_day'].'.'.$absence_operator['oa_during_minutes_last_day'];
                                            if($hm>=$dt){
                                                echo '• '.$absence_operator['us_firstname'].' '.$absence_operator['us_name'].' '.trad('is_absent',$_SESSION["language"]).'.<br>';
                                            } else {
                                                $absence_time=$dt-$hm;
                                                echo '• '.$absence_operator['us_firstname'].' '.$absence_operator['us_name'].' '.trad('is_absent',$_SESSION["language"]).' '.$hm.' h.<br>';
                                            }
                                        }
                                    }
                                    
                                }
                            }

                            ////////////////////////////////////////////////////////////
                            /////////////////// Operator absence END ///////////////////
                            ////////////////////////////////////////////////////////////

                            /////////////////////////////////////////////////////////////
                            ///////////////// Machine maintenance START /////////////////
                            /////////////////////////////////////////////////////////////

                            $maintenances=select_maintenance_at_date($tomorrow);                   
                            if($maintenances!=[]){
                                foreach($maintenances as $maintenance){
                                    $start_date_time= new DateTime($maintenance['ms_start_hour_date']);
                                    $start_date=$start_date_time->format('Y-m-d');
                                    $end_date_time= new DateTime($maintenance['ms_end_hour_date']);
                                    $end_date=$end_date_time->format('Y-m-d');

                                    if($end_date==$start_date){
                                        echo '• '.$maintenance['mc_label'].' '.trad('is_under_maintenance_for',$_SESSION["language"]).' '.$maintenance['ms_during_hours_first_day'].'.'.$maintenance['ms_during_minutes_first_day'].' h<br>';
                                        
                                    } else {
                                        if($start_date != $tomorrow && $end_date != $tomorrow){
                                            echo '• Machine '.$maintenance['mc_label'].' '.trad('is_in_maintenance_all_day',$_SESSION["language"]).'.<br>';
                                        } else if($start_date == $tomorrow){
                                            echo '• Machine '.$maintenance['mc_label'].' '.trad('is_under_maintenance_for',$_SESSION["language"]).' '.$maintenance['ms_during_hours_first_day'].'.'.$maintenance['ms_during_minutes_first_day'].' h<br>';
                                        }else if($end_date == $tomorrow){
                                            echo '• Machine '.$maintenance['mc_label'].' '.trad('is_under_maintenance_for',$_SESSION["language"]).' '.$maintenance['ms_during_hours_last_day'].'.'.$maintenance['ms_during_minutes_last_day'].' h<br>';
                                        }
                                    }
                                    
                                }
                            }

                            /////////////////////////////////////////////////////////////
                            ////////////////// Machine maintenance END //////////////////
                            /////////////////////////////////////////////////////////////

                            /////////////////////////////////////////////////////////////
                            ////////////////////// Over time START //////////////////////
                            /////////////////////////////////////////////////////////////

                            $alert_overtimes=select_over_time_at_date($tomorrow);
                            if($alert_overtimes!=[]){
                                foreach($alert_overtimes as $alert_overtime){                    
                                 echo '• '.$alert_overtime['oo_during'].' heure(s) supplémentaire(s) prévue(s) pour '.$alert_overtime ["us_firstname"].' '.$alert_overtime ["us_name"].'.<br>';
                                }
                            }

                            /////////////////////////////////////////////////////////////
                            //////////////////////// Over time END //////////////////////
                            /////////////////////////////////////////////////////////////
                            
                            if($absence_operators==[] && $maintenances==[] && $alert_overtimes==[]){
                                echo trad('nothing_to_report',$_SESSION["language"]);
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include('contents/footer.php'); ?>
</body>
</html>
