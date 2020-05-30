<?php
session_start();

unset($_SESSION['uid']);
unset($_SESSION['userName']);

header('Location:index.php');
exit();