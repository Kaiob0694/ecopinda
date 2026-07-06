<?php
session_start();
require "conexao.php";

$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

// Consulta segura com prepared statement (evita SQL Injection)
$sql = "SELECT * FROM usuarios WHERE email = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($resultado)) {

    if ($row['senha'] === $senha) {

        // Login correto: guarda os dados do usuário na sessão
        $_SESSION['usuario_id']    = $row['id'];
        $_SESSION['usuario_nome']  = $row['nome'];
        $_SESSION['usuario_email'] = $row['email'];

        // Volta para a página principal já logado
        header("Location: /index.php");
        exit;

    } else {

        $_SESSION['login_erro']  = "Senha incorreta";
        $_SESSION['login_email'] = $email;
        header("Location: /pages/login.php");
        exit;

    }

} else {

    $_SESSION['login_erro']  = "Usuário não encontrado";
    $_SESSION['login_email'] = $email;
    header("Location: /pages/login.php");
    exit;

}
