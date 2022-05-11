<?php
$dev=660;
$table=1900;

include('sql/connect.php');

$pdo=connect();

$verify_dev = $pdo->prepare("select * from abaque_garnissage_dev where COL1=?");
$verify_dev->execute(array($dev));
$dev_results = $verify_dev->fetchAll();



$verify_lz = $pdo->prepare("select * from abaque_garnissage_table where COL1=?");
$verify_lz->execute(array($table));
$lz_results = $verify_lz->fetchAll();

$ref=$dev_results[0]['COL2'];
$multiplicateur=$lz_results[0]['COL2'];
$constante=$dev_results[0]['COL3'];


$temps= round((($ref/1.1)+($multiplicateur*$constante))*1.1,2);
echo $temps;