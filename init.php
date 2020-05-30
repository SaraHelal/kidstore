<?php 
    include 'connect.php';

    $sessionUser='';
    if(isset($_SESSION['user'])){
        $sessionUser = $_SESSION['user'];
    }

    //Routes
    $tpl='include/templates/'; // Template Directory
    $css='layout/css/'; //css Directory
    $func='include/functions/';
    $js='layout/js/'; //js Directory
    $lang='include/languages/';

    include $func . 'functions.php';
    include $lang . 'english.php';
    include $tpl . 'header.php';
    include $tpl . 'mainNav.php';


