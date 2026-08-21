<?php
session_start();
require __DIR__ . '/../includes/exigir_master.php';
require "conexao.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../pages/usuarios.php");
    exit;
}

$usuario_id = (int) ($_POST['usuario_id'] ?? 0);
$novo_tipo  = $_POST['novo_tipo'] ?? '';

// Só permite promover/rebaixar entre usuario e admin por aqui.
// Nunca é possível conceder ou remover "master" por este formulário.
if (!in_array($novo_tipo, ['usuario', 'admin'], true) || $usuario_id <= 0) {
    $_SESSION['usuarios_erro'] = "Requisição inválida.";
    header("Location: ../pages/usuarios.php");
    exit;
}

// Um master não pode alterar o próprio nível por aqui, para evitar
// se trancar fora do painel por engano.
if ($usuario_id === (int) $_SESSION['usuario_id']) {
    $_SESSION['usuarios_erro'] = "Você não pode alterar o nível da sua própria conta por aqui.";
    header("Location: ../pages/usuarios.php");
    exit;
}

// Confere o nível atual do alvo: nunca mexe em outra conta master.
$sqlAlvo = "SELECT nome, tipo_usuario FROM usuarios WHERE id = ?";
$stmtAlvo = mysqli_prepare($conexao, $sqlAlvo);
mysqli_stmt_bind_param($stmtAlvo, "i", $usuario_id);
mysqli_stmt_execute($stmtAlvo);
$alvo = mysqli_stmt_get_result($stmtAlvo)->fetch_assoc();
mysqli_stmt_close($stmtAlvo);

if (!$alvo) {
    $_SESSION['usuarios_erro'] = "Usuário não encontrado.";
    header("Location: ../pages/usuarios.php");
    exit;
}

if ($alvo['tipo_usuario'] === 'master') {
    $_SESSION['usuarios_erro'] = "Não é possível alterar o nível de outra conta master por aqui.";
    header("Location: ../pages/usuarios.php");
    exit;
}

$sqlUpdate = "UPDATE usuarios SET tipo_usuario = ? WHERE id = ?";
$stmtUpdate = mysqli_prepare($conexao, $sqlUpdate);
mysqli_stmt_bind_param($stmtUpdate, "si", $novo_tipo, $usuario_id);

if (mysqli_stmt_execute($stmtUpdate)) {
    $acao = $novo_tipo === 'admin' ? 'promovida a administrador' : 'rebaixada a usuário comum';
    $_SESSION['usuarios_sucesso'] = "A conta de " . $alvo['nome'] . " foi " . $acao . ".";
} else {
    $_SESSION['usuarios_erro'] = "Erro ao atualizar o nível de acesso: " . mysqli_error($conexao);
}

mysqli_stmt_close($stmtUpdate);
header("Location: ../pages/usuarios.php");
exit;
