<?php

header("Content-Type: application/json");

require_once("../config/conexao.php");
require_once("../classes/restaurante.php");

$restaurante = new Restaurante();

$dados = json_decode(file_get_contents("php://input"), true);

$resultado = $restaurante->editar(
    $dados["id"],
    $dados["nome"],
    $dados["imagem"],
    $dados["logradouro"],
    $dados["numero"],
    $dados["cidade"],
    $dados["cep"],
    $dados["telefone"],
    $dados["email"],
    $dados["categoria"],
    $dados["possui_delivery"],
    $dados["possui_wifi"],
    $dados["horario_funcionamento"]
);

echo json_encode($resultado);