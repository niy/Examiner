<?php

header("Content-Type: text/html; charset=utf-8");

session_start();

if (isset($_SESSION['examiner_user']))
    {
    session_destroy();
    $_SESSION=array();
    }
header('Location: index');
include('main.php');
?>