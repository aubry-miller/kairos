<?php
session_start();
include('../sql/connect.php');
include('../sql/update.php');
add_real_material_consumption_to_planning_task_by_id($_GET['quantity_material'],$_GET['plan_id']);

header("location:../lining_day_plan.php");