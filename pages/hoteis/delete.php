<?php

require_once "../../classes/hoteis.php";

$hotel = new Hotel();

$id = $_GET['id'];

$hotel->excluir($id);

header("Location: read.php");
exit;
