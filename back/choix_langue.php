<?php
session_start();
include('../sql/connect.php');
include('../sql/update.php');

update_language($_SESSION['user_id'], $_GET['language']);
$_SESSION['language'] = $_GET['language'];
header("location:../profil.php");