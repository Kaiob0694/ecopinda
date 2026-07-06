<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Conexão com o banco
include("../includes/conexao.php");

$id = $_SESSION['usuario_id'];

// Consulta dos dados do usuário
$sql = "SELECT nome, cpf, email, telefone, endereco
        FROM usuarios
        WHERE id = ?";

$stmt = mysqli_prepare($conexao, $sql);

if (!$stmt) {
    die("Erro ao preparar consulta: " . mysqli_error($conexao));
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);

if (!$usuario = mysqli_fetch_assoc($resultado)) {
    die("Usuário não encontrado.");
}

mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil</title>

    <link rel="stylesheet" href="../assets/css/profile.css">
</head>

<body>

    <!-- Nuvens -->
    <div class="cloud c1"></div>
    <div class="cloud c2"></div>
    <div class="cloud c3"></div>

    <div class="card">

        <div class="header">

            <div class="avatar">
                👤
            </div>

            <div>
                <h1><?= htmlspecialchars($usuario['nome']) ?></h1>
                <p>Meu Perfil</p>
            </div>

        </div>

        <div class="info">

            <div class="item">
                <label>Nome</label>
                <span><?= htmlspecialchars($usuario['nome']) ?></span>
            </div>

            <div class="item">
                <label>CPF</label>
                <span><?= htmlspecialchars($usuario['cpf']) ?></span>
            </div>

            <div class="item">
                <label>E-mail</label>
                <span><?= htmlspecialchars($usuario['email']) ?></span>
            </div>

            <div class="item">
                <label>Telefone</label>
                <span><?= htmlspecialchars($usuario['telefone']) ?></span>
            </div>

            <div class="item endereco">
                <label>Endereço</label>
                <span><?= htmlspecialchars($usuario['endereco']) ?></span>
            </div>

        </div>

        <div class="botoes">

            <a href="editar_perfil.php" class="btn editar">
                Editar Perfil
            </a>

            <a href="logout.php" class="btn sair">
                Sair
            </a>

        </div>

    </div>

</body>

</html>