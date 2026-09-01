<?php
session_start();
$_SESSION = [];
session_destroy();
header("Location: /ecopinda/index.php");
exit;
