<?php
function connect(){
    try{
        $pdo=new PDO("mysql:host=localhost;dbname=kairos","phpmyadmin","87Lim@TB");
        return $pdo;
     }
     catch(PDOException $e){
        echo $e->getMessage();
     }
}