<?php
$dev=660;
$table=1900;

include('sql/connect.php');

$pdo=connect();

$verify_dev = $pdo->prepare("select * from abacus_grinding_dev where agd_dev=?");
$verify_dev->execute(array($dev));
$dev_results = $verify_dev->fetchAll();



$verify_lz = $pdo->prepare("select * from abacus_table where ab_diam=?");
$verify_lz->execute(array($table));
$lz_results = $verify_lz->fetchAll();

$ref=$dev_results[0]['agd_time'];
$multiplicateur=$lz_results[0]['ab_multipl'];
$constante=$dev_results[0]['agd_const'];


$temps= round(($ref+($multiplicateur*$constante)),2);
echo $temps;