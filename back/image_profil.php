<?php
include("../sql/connect.php");
include("../sql/update.php");
session_start();
change_image_profil($_SESSION['user_id'], $_GET['img']);

header("location:../profil.php");