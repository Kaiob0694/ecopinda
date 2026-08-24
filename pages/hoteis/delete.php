<?php

require_once "../../classes/hoteis.php";

$hoteis = new Hotéis();

$id = $_GET['id'];

$hoteis->excluir($id);
header("Location: hoteis.php");
exit;

