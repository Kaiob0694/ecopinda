<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

include("../src/conexao.php");

$id = $_SESSION['usuario_id'];

$sql = "SELECT nome, cpf, email, telefone, cep, foto
        FROM usuarios
        WHERE id = ?";

$stmt = mysqli_prepare($conexao, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($resultado);

mysqli_stmt_close($stmt);

// Iniciais do nome para o avatar (ex: "Kaio Silva" -> "KS")
function iniciais($nome)
{
    $partes = preg_split('/\s+/', trim($nome));
    $iniciais = mb_substr($partes[0], 0, 1);
    if (count($partes) > 1) {
        $iniciais .= mb_substr(end($partes), 0, 1);
    }
    return $iniciais;
}

// Mostra o valor ou um aviso discreto de "não informado"
function campo($valor)
{
    if (empty($valor)) {
        return '<span class="vazio">Não informado</span>';
    }
    return '<span>' . htmlspecialchars($valor) . '</span>';
}
?>

<?php include '../includes/head.php'; ?>

<link rel="stylesheet" href="../assets/css/profile.css">

<?php include '../includes/header.php'; ?>

<div class="profile-page">

    <img src="../assets/img2/pindamonhangaba_cidade.jpeg" class="bg-photo" alt="fundo">
    <div class="bg-overlay"></div>

    <div class="cloud c1"></div>
    <div class="cloud c2"></div>
    <div class="cloud c3"></div>

    <main class="profile-container">

        <div class="profile-card">

            <div class="profile-header">

                <div class="profile-avatar">
                    <?php if (!empty($usuario['foto'])): ?>
                        <img src="../assets/uploads/perfil/<?= htmlspecialchars($usuario['foto']) ?>" alt="Foto de perfil">
                    <?php else: ?>
                        <?= htmlspecialchars(iniciais($usuario['nome'])) ?>
                    <?php endif; ?>
                </div>

                <div>
                    <h1><?= htmlspecialchars($usuario['nome']) ?></h1>
                    <p><i class="fa-solid fa-leaf"></i> Meu Perfil</p>
                </div>

            </div>

            <?php if (!empty($_SESSION['perfil_sucesso'])): ?>
                <div class="form-alerta form-alerta-sucesso">
                    <i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($_SESSION['perfil_sucesso']) ?>
                </div>
                <?php unset($_SESSION['perfil_sucesso']); ?>
            <?php endif; ?>

            <div class="profile-info">

                <div class="profile-item">
                    <div class="item-icon"><i class="fa-solid fa-user"></i></div>
                    <div class="item-text">
                        <label>Nome</label>
                        <?= campo($usuario['nome']) ?>
                    </div>
                </div>

                <div class="profile-item">
                    <div class="item-icon"><i class="fa-solid fa-id-card"></i></div>
                    <div class="item-text">
                        <label>CPF</label>
                        <?= campo($usuario['cpf']) ?>
                    </div>
                </div>

                <div class="profile-item">
                    <div class="item-icon"><i class="fa-solid fa-envelope"></i></div>
                    <div class="item-text">
                        <label>E-mail</label>
                        <?= campo($usuario['email']) ?>
                    </div>
                </div>

                <div class="profile-item">
                    <div class="item-icon"><i class="fa-solid fa-phone"></i></div>
                    <div class="item-text">
                        <label>Telefone</label>
                        <?= campo($usuario['telefone']) ?>
                    </div>
                </div>

                <div class="profile-item">
                    <div class="item-icon"><i class="fa-solid fa-map-pin"></i></div>
                    <div class="item-text">
                        <label>CEP</label>
                        <?= campo($usuario['cep']) ?>
                    </div>
                </div>

            </div>

            <div class="profile-buttons">

                <a href="../src/editar_perfil.php" class="profile-btn profile-btn-edit">
                    <i class="fa-solid fa-pen"></i> Editar Perfil
                </a>

                <a href="../pages/usuarios.php" class="profile-btn profile-btn-user">
                    <i class="fa-solid fa-user"></i> Gerenciador de Usuário
                </a>

                <a href="../src/logout.php" class="profile-btn profile-btn-exit">
                    <i class="fa-solid fa-right-from-bracket"></i> Sair
                </a>

            </div>

        </div>

    </main>

</div>

<?php include '../includes/footer.php'; ?>