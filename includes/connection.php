<?php
error_reporting(E_ALL);

$connection = mysqli_connect('localhost', 'manomoic', 'manamela1000');
$selectDB   = mysqli_select_db($connection, 'trial');

if ( !$connection )
    trigger_error('DB Connection Failed.', E_USER_NOTICE);
elseif ( !$selectDB )
    trigger_error('DB Connection Selection Failed.', E_USER_NOTICE);
?>