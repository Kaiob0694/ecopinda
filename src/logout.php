<?php
session_start();

$baseUrl = 'https://pindaeco.rf.gd';
$_SESSION = [];
session_destroy();
header("Location:" . $baseUrl . "/index.php");
exit;
