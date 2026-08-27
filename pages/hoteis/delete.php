<?php

require_once "../../classe/hoteis.php";

$hotel = new Hotel();

$id = $_GET['id'];

$hotel->excluir($id);

header("Location: ../hoteis.php");
exit;
