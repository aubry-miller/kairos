<?php
function connect(){
    try{
        $pdo=new PDO("mysql:host=localhost; charset=utf8; dbname=kairos","phpmyadmin","87Lim@TB");
        return $pdo;
     }
     catch(PDOException $e){
        echo $e->getMessage();
     }
}