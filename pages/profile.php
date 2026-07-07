<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

include("../src/conexao.php");

$id = $_SESSION['usuario_id'];

$sql = "SELECT nome, cpf, email, telefone
        FROM usuarios
        WHERE id = ?";

$stmt = mysqli_prepare($conexao, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($resultado);

mysqli_stmt_close($stmt);
?>

<?php include '../includes/head.php'; ?>

<link rel="stylesheet" href="../assets/css/profile.css">

<?php include '../includes/header.php'; ?>

<img src="../assets/img2/pindamonhangaba_cidade.jpeg" class="bg-photo" alt="fundo">
<div class="bg-overlay"></div>

<div class="cloud c1"></div>
<div class="cloud c2"></div>
<div class="cloud c3"></div>

<main class="profile-container">

    <div class="profile-card">

        <div class="profile-header">

            <div class="profile-avatar">👤</div>

            <div>
                <h1><?= htmlspecialchars($usuario['nome']) ?></h1>
                <p>Meu Perfil</p>
            </div>

        </div>

        <div class="profile-info">

            <div class="profile-item">
                <label>Nome</label>
                <span><?= htmlspecialchars($usuario['nome']) ?></span>
            </div>

            <div class="profile-item">
                <label>CPF</label>
                <span><?= htmlspecialchars($usuario['cpf']) ?></span>
            </div>

            <div class="profile-item">
                <label>E-mail</label>
                <span><?= htmlspecialchars($usuario['email']) ?></span>
            </div>

            <div class="profile-item">
                <label>Telefone</label>
                <span><?= htmlspecialchars($usuario['telefone']) ?></span>
            </div>

        </div>

        <div class="profile-buttons">

            <a href="editar_perfil.php" class="profile-btn profile-btn-edit">
                Editar Perfil
            </a>

            <a href="../logout.php" class="profile-btn profile-btn-exit">
                Sair
            </a>

        </div>

    </div>

</main>

<?php include '../includes/footer.php'; ?>