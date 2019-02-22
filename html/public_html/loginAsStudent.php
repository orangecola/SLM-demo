<?php
include_once('components/config.php');
$_SESSION['username'] = 'Student';
$_SESSION['role'] = 'student';
$_SESSION['user_ID'] = '2';
header("Location: question.php?id=4");
 ?>
