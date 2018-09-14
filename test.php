<?php
$fee = 500;
$start_date = date('i');
$end_date = date('i') + 1;
if (date('i') == $end_date) {
    $fee += 500;
    echo $fee;
}
echo '<br> ' . $start_date . '   <br>          ' .$end_date . '<br>';

?>