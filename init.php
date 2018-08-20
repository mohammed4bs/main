<?php 
include 'db.php';
$tbl = "includes/";
$set = "settings/";
$main_fee = 600;
include $tbl . 'header.php';
include $tbl . 'functions.php';

if (!isset($noNavbar)) {
    include $tbl . 'nav.php';
}


