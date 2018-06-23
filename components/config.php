<?php

session_start();

$DB_HOST = $_SERVER['RDS_HOSTNAME'];
$DB_USER = $_SERVER['RDS_USERNAME'];
$DB_PASS = $_SERVER['PASSWORD'];
$DB_NAME = $_SERVER['RDS_DB_NAME'];
$charset = 'utf8' ;

try
{
     $DB_con = new PDO("mysql:host={$DB_HOST};dbname={$DB_NAME}",$DB_USER,$DB_PASS);
     $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
     echo $e->getMessage();
}


include_once 'components/user.php';
$user = new USER($DB_con);
?>
