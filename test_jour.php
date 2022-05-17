<?php

define('DAYSWEEK',array('0', '1', '2', '3', '4', '5', '6')); 
$date=new DateTime();
$date_string = $date->format('Y-m-d H:i:s');
list($year, $month, $day) = explode('-', $date_string);// a voir si les variables ne sont pas dans un mauvais ordre
$day=substr($day,0,2);
$timestamp = mktime (0, 0, 0, $month, $day, $year);
// Day of the week
$day_number = DAYSWEEK[date("w",$timestamp)];

echo $day_number;





// define('DAYSWEEK',array('1', '2', '3', '4', '5', '6', '7')); //7=>Sunday, 1=>Monday , 2=>Tuesday , 3=>Wednesday, 4=>Thursday , 5=>Friday, 6=>Saturday


