<?php
include("../sql/connect.php");
include("../sql/update.php");
session_start();
change_image_profil($_SESSION['ID'], $_GET['img']);

header("location:../profil.php");