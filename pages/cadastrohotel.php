<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start();

require_once __DIR__ . "/../src/conexao.php";

global $conexao;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitização básica
    $nome = trim($_POST['Nome']);
    $endereco = trim($_POST['Endereço']);
    $id = trim($_POST['id']);
    $cidade = trim($_POST['Cidade']);
    $estado = trim($_POST['Estado']);
    $cep = trim($_POST['CEP']);
    $telefone = trim($_POST['Telefone']);
    $email = trim($_POST['Email']);
    $quantidade_quartos = trim($_POST['Quantidade_quartos']);
    $possui_wifi= trim($_POST['possui_wifi']);
    $possui_estacionamento = trim($_POST['possui_estacionamento']);
    $data_cadastro = trim($_POST['data_cadastro']);

    // SQL com prepared statement
    $sql = "INSERT INTO hotel 
    (nome, endereco, id, cidade, estado, cep, telefone, email, quantidade_quartos, possui_wifi, possui_estacionamento, data_cadastro)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conexao, $sql);

    if ($stmt) {

        mysqli_stmt_bind_param(
            $stmt,
            "sssssssssss",
            $nome,
            $endereco,
            $id,
            $cidade,
            $estado,
            $cep,
            $telefone,
            $email,
            $quantidade_quartos,
            $possui_wifi,
            $possui_estacionamento,
            $data_cadastro
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: hoteis.php");
            exit;
        } else {
            echo "Erro ao cadastrar hoteis: " . mysqli_error($conexao);
        }

        mysqli_stmt_close($stmt);

    } else {
        echo "Erro na preparação da query: " . mysqli_error($conexao);
    }

} else {
    echo "Acesso inválido.";
}

mysqli_close($conexao);
?>


