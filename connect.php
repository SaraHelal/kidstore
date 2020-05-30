<?php 
    
    $dsn='mysql:host=localhost;dbname=u806998272_myshop';
    $user='u806998272_sara';
    $pass= 'Sa_12345';

    $option = array (
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
    );


    try{

        $con= new PDO($dsn , $user, $pass , $option);
        $con -> setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
        //echo 'You are conected to DB';
    }

    catch(PDOException $e){
        
        //echo 'Failed to connect ' . $e->getMessage();
        
        
    }