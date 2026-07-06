<?php
session_start();
require "conexao.php";

$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

$sql = "SELECT * FROM usuarios WHERE email = '$email'";
$resultado = mysqli_query($conexao, $sql);

if ($row = mysqli_fetch_assoc($resultado)) {

    if ($row['senha'] === $senha) {

        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_nome'] = $row['nome'];
        $_SESSION['user_email'] = $row['email'];

        header("Location: /dashboard.php");
        exit;

    } else {

        $_SESSION['erro_login'] = "Senha incorreta";
        header("Location: /index.php");
        exit;

    }

} else {

    $_SESSION['erro_login'] = "Usuário não encontrado";
    header("Location: /index.php");
    exit;

}