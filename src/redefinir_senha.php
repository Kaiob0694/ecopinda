<?php

session_start();
require "conexao.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../pages/recuperar_senha.php");
    exit;
}

$token          = trim($_POST['token'] ?? '');
$senha          = trim($_POST['senha'] ?? '');
$senha_confirma = trim($_POST['senha_confirma'] ?? '');

if ($token === '') {
    header("Location: ../pages/recuperar_senha.php");
    exit;
}

// Revalida o token no servidor (nunca confiar só na validação da página anterior)
$sql = "SELECT id, reset_token_expira FROM usuarios WHERE reset_token = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "s", $token);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt);

if (!$usuario || strtotime($usuario['reset_token_expira']) < time()) {
    $_SESSION['recuperar_erro'] = "Este link de recuperação é inválido ou já expirou. Solicite um novo.";
    header("Location: ../pages/recuperar_senha.php");
    exit;
}

$erros = [];

if (strlen($senha) < 6) {
    $erros[] = "A senha deve ter no mínimo 6 caracteres.";
}

if ($senha !== $senha_confirma) {
    $erros[] = "A confirmação de senha não confere.";
}

if (!empty($erros)) {
    $_SESSION['redefinir_erro'] = implode(' ', $erros);
    header("Location: ../pages/redefinir_senha.php?token=" . urlencode($token));
    exit;
}

$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

$sqlUpdate = "UPDATE usuarios SET senha = ?, reset_token = NULL, reset_token_expira = NULL WHERE id = ?";
$stmtUpdate = mysqli_prepare($conexao, $sqlUpdate);
mysqli_stmt_bind_param($stmtUpdate, "si", $senha_hash, $usuario['id']);
mysqli_stmt_execute($stmtUpdate);
mysqli_stmt_close($stmtUpdate);

$_SESSION['login_sucesso'] = "Senha alterada com sucesso! Faça login com a nova senha.";
header("Location: ../pages/login.php");
exit;
