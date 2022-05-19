<?php
session_start();
include('../sql/connect.php');
include('../sql/update.php');

update_mode_affichage($_SESSION['user_id'], $_GET['choix_mode']);
$_SESSION['mode'] = $_GET['choix_mode'];
header("location:../profil.php");