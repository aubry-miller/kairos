<?php
 
@$nom = $_POST["nom"];
@$prenom = $_POST["prenom"];
@$pseudo = $_POST["pseudo"];
@$fonction = $_POST["fonction"];
@$password = $_POST["password"];
@$passwordConf = $_POST["passconf"];
@$pass_crypt = md5($_POST["password"]);
@$tracking_time = "7"; //temps que sont conservé les lignes de trackage

?>