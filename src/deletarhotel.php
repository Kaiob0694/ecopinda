<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../src/conexao.php");


if (!isset($_GET['id'])) {
    die("ID do hotel não encontrado.");
}


$id = (int) $_GET['id'];


$sql = "DELETE FROM hoteis WHERE id = $id";


if (mysqli_query($conexao, $sql)) {

    header("Location: ../pages/hoteis.php");
    exit();

} else {

    echo "Erro ao deletar hotel: " . mysqli_error($conexao);

}


?>
