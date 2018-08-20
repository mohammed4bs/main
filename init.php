<?php 
include 'db.php';
$tbl = "includes/";
$set = "settings/";
$main_fee = 600;
include $tbl . 'functions.php';
include $tbl . 'header.php';


if (!isset($noNavbar)) {
    include $tbl . 'nav.php';
}


