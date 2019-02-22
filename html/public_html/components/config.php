<?php

session_start();

$DB_HOST = getenv('HOSTNAME');
$DB_USER = getenv('USERNAME');
$DB_PASS = getenv('PASSWORD');
$DB_NAME = getenv('DB_NAME');
$charset = 'utf8' ;

try
{
     $DB_con = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME",$DB_USER,$DB_PASS);
     $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
     echo $e->getMessage();
}


include_once 'components/user.php';
$user = new USER($DB_con);
?>
