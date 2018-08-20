<?php

$do  = '';
if (isset($_GET['do'])) {
    $do =  $_GET['do'];
} else {
    $do = 'Manage';
}


if ($do == 'Manage') {
    echo "You are In manage page <br>";
    echo "<a href='page.php?do=Add'>Add new Client </a>";
} elseif ($do == 'Add') {
    echo "You are in Add page";
}
elseif ($do == 'Insert') {
    echo "You are in Insert page";
}
 else {
    echo "Error, There's no page with this name";
}