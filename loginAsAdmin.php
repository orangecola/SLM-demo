<?php
include_once('components/config.php');
$_SESSION['username'] = 'Admin';
$_SESSION['role'] = 'admin';
$_SESSION['user_ID'] = '1';
header("Location: adminvideolist.php?id=3");
?>
