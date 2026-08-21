<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . "/conexao.php");

if (!isset($_GET['id'])) {
    die("ID do restaurante não encontrado.");
}

$id = (int) $_GET['id'];

$sql = "DELETE FROM restaurante WHERE id = $id";

if (mysqli_query($conexao, $sql)) {
    header("Location: ../pages/restaurante.php");
    exit();
} else {
    echo "Erro ao deletar restaurante: " . mysqli_error($conexao);
}

?>