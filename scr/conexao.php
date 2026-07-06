<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// $host = "sql206.infinityfree.com";
// $usuario = "if0_42297225";
// $senha = "zgbGSOdQNMrhcud";
// $banco = "if0_42297225_pindaeco";

$host = "localhost";
$usuario = "root";
$senha = "1234";
$banco = "pindaeco";

$conexao = mysqli_connect($host, $usuario,$senha, $banco);

if (!$conexao) {
    die("Erro na Conexão: " . mysqli_connect_error());
}

mysqli_set_charset($conexao, "utf8mb4");