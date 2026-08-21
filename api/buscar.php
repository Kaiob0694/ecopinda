<?php 

header("Content-Type: application/json; charset=UTF-8");

require_once("../config/conexao.php");
require_once("../classes/restaurante.php");

$restaurante = new Restaurante();

$id = $_GET["id"];

$resultado = $restaurante->buscarPorId($id);

echo json_encode($resultado);