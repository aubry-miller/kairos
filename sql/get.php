<?php
include('connect.php');

function get_user_by_pseudo_and_password($pseudo, $pass_crypt){
    $pdo=connect();

    //On joue la requete
    $verify = $pdo->prepare("select * from user where us_login=? and us_password=? limit 1");
    $verify->execute(array($pseudo, $pass_crypt));
    $user = $verify->fetchAll();
    $pdo=null;
    return $user;
}

function get_all_consumption(){
    $pdo=connect();

    //On joue la requete
    $verify = $pdo->prepare("select * from consumption");
    $verify->execute();
    $results = $verify->fetchAll();
    $pdo=null;
    return $results;
}

function get_all_customer_stock(){
    $pdo=connect();

    //On joue la requete
    $verify = $pdo->prepare("select * from customer_stock");
    $verify->execute();
    $results = $verify->fetchAll();
    $pdo=null;
    return $results;
}

function get_all_default_machine_operator_link(){
    $pdo=connect();

    //On joue la requete
    $verify = $pdo->prepare("select * from default_machine_operator_link");
    $verify->execute();
    $results = $verify->fetchAll();
    $pdo=null;
    return $results;
}

function get_all_exceptional_machine_operator_link (){
    $pdo=connect();

    //On joue la requete
    $verify = $pdo->prepare("select * from exceptional_machine_operator_link ");
    $verify->execute();
    $results = $verify->fetchAll();
    $pdo=null;
    return $results;
}

function get_all_fiber(){
    $pdo=connect();

    //On joue la requete
    $verify = $pdo->prepare("select * from fiber");
    $verify->execute();
    $results = $verify->fetchAll();
    $pdo=null;
    return $results;
}

function get_all_fiber_reference(){
    $pdo=connect();

    //On joue la requete
    $verify = $pdo->prepare("select * from fiber_reference");
    $verify->execute();
    $results = $verify->fetchAll();
    $pdo=null;
    return $results;
}

function get_all_flow(){
    $pdo=connect();

    //On joue la requete
    $verify = $pdo->prepare("select * from flow");
    $verify->execute();
    $results = $verify->fetchAll();
    $pdo=null;
    return $results;
}

function get_all_function(){
    $pdo=connect();

    //On joue la requete
    $verify = $pdo->prepare("select * from function");
    $verify->execute();
    $results = $verify->fetchAll();
    $pdo=null;
    return $results;
}

function get_all_homepage(){
    $pdo=connect();

    //On joue la requete
    $verify = $pdo->prepare("select * from homepage");
    $verify->execute();
    $results = $verify->fetchAll();
    $pdo=null;
    return $results;
}

function get_all_language(){
    $pdo=connect();

    //On joue la requete
    $verify = $pdo->prepare("select * from language");
    $verify->execute();
    $results = $verify->fetchAll();
    $pdo=null;
    return $results;
}

function get_all_machine(){
    $pdo=connect();

    //On joue la requete
    $verify = $pdo->prepare("select * from machine");
    $verify->execute();
    $results = $verify->fetchAll();
    $pdo=null;
    return $results;
}

function get_all_user(){
    $pdo=connect();

    //On joue la requete
    $verify = $pdo->prepare("select * from user");
    $verify->execute();
    $results = $verify->fetchAll();
    $pdo=null;
    return $results;
}