<?php
include('sql/connect.php');
include('sql/update.php');

session_start();
echo $_SESSION['plan_id'].' '.$_GET['timer'].' '.$_GET['status'];

$now= new DateTime();
$now = $now->format('Y-m-d H:i:s');

if($_GET['status'] == 'In progress'){
    update_task_during($_SESSION['plan_id'],$_GET['timer'],$_GET['status']);
} else if($_GET['status'] == 'Started'){
    update_task_during_and_started_date($_SESSION['plan_id'],$_GET['timer'],$_GET['status'], $now);
} else if($_GET['status'] == 'Finished'){
    update_task_during_and_finished_date($_SESSION['plan_id'],$_GET['timer'],$_GET['status'], $now);
}

update_task_during($_SESSION['plan_id'],$_GET['timer'],$_GET['status']);

?>
<script>window.close();</script>