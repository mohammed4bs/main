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

$stmtC = $db->prepare('SELECT contracts.contract_id , contracts.total_space, maint.balance, maint.start_date, maint.end_date, maint.maint_fee 
 FROM contracts 
INNER JOIN maint on contracts.contract_id = maint.contract_id');

$stmtC->execute();

$rows = $stmtC->fetchAll();
$curDate = date('Y-m-d');
//$curDate = date('2018-9-26');
//echo '<h3>Current Date is ' . $curDate . '</h3><br>';
foreach ($rows as $row) {
    //$expDate = date('2018-09-30');
    echo $row['start_date'];
    $expDate = $row['start_date'];
    echo '<h3>Expiry Date is ' . $expDate . '</h3><br>';
    if ($expDate < $curDate) {
         $d = (int)abs((strtotime($curDate) - strtotime($expDate))/(60*60*24*30));; //Month
        //$d = (int)abs((strtotime($curDate) - strtotime($expDate))/(60*60*24));; // days
        echo '<h3> diffrence is ' . $d . '</h3><br>';
        if ($d > 0) {
            $m = $d * $row['total_space'] * $row['maint_fee']; 
            //echo '<h3> result is ' . $m . '</h3><br>';
            $blnc = $row['balance'];
            //$m = $m + $blnc;
            
            $start_date_ = $curDate;   //date("Y-m-d", strtotime(date("Y-m-d", strtotime($curDate)). "next day"));   //date('Y-m-01');
            $end_date_ = date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_date_)). "next month"));
            //echo '<h3> Next Start Date is ' . $start_date_ . '</h3><br>';
            echo '<h3> Next End Date is ' . $end_date_ . '</h3><br>';
            $updateBalance = $db->prepare('UPDATE maint SET balance = :zblnce, start_date = :sdt, end_date = :edt WHERE contract_id = :zid');
            $updateBalance->execute(array(
                ':zblnce' => $m + $blnc,
                ':zid'  => $row['contract_id'],
                ':sdt'  => $start_date_,
                ':edt'  => $end_date_
            ));
        }
        
        
    } else {
        //echo 'No' . '<br>';
    }
}

echo '<pre>';
//print_r($rows);
echo '</pre>';


