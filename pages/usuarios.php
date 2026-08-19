<?php
session_start();
require __DIR__ . '/../includes/exigir_master.php';
require __DIR__ . '/../src/conexao.php';

$sucesso = $_SESSION['usuarios_sucesso'] ?? '';
unset($_SESSION['usuarios_sucesso']);

$erro = $_SESSION['usuarios_erro'] ?? '';
unset($_SESSION['usuarios_erro']);

$sql = "SELECT id, nome, email, tipo_usuario FROM usuarios ORDER BY nome ASC";
$resultado = mysqli_query($conexao, $sql);

$rotulos = [
    'usuario' => 'Usuário',
    'admin'   => 'Admin',
    'master'  => 'Master',
];
?>

<?php include '../includes/head.php'; ?>

<link rel="stylesheet" href="../assets/css/profile.css">
<link rel="stylesheet" href="../assets/css/usuarios.css">

<?php include '../includes/header.php'; ?>

<div class="usuarios-page">

<img src="../assets/img2/pindamonhangaba_cidade.jpeg" class="bg-photo" alt="fundo">
<div class="bg-overlay"></div>

<main class="usuarios-container">
    <div class="usuarios-card">

        <div class="usuarios-header">
            <h1><i class="fa-solid fa-user-shield"></i> Gerenciar Usuários</h1>
            <p>Como administrador master, você pode promover contas a administrador ou devolvê-las a usuário comum.</p>
        </div>

        <?php if (!empty($sucesso)): ?>
            <div class="form-alerta form-alerta-sucesso">
                <i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($sucesso) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($erro)): ?>
            <div class="form-alerta form-alerta-erro">
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>

        <div class="usuarios-tabela-wrap">
            <table class="usuarios-tabela">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Nível</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($usuario = mysqli_fetch_assoc($resultado)): ?>
                        <?php
                            $tipo   = $usuario['tipo_usuario'];
                            $ehVoce = (int) $usuario['id'] === (int) $_SESSION['usuario_id'];
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($usuario['nome']) ?></td>
                            <td><?= htmlspecialchars($usuario['email']) ?></td>
                            <td>
                                <span class="badge badge-<?= htmlspecialchars($tipo) ?>">
                                    <?= htmlspecialchars($rotulos[$tipo] ?? $tipo) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($tipo === 'master'): ?>
                                    <?php if ($ehVoce): ?>
                                        <span class="voce-tag">Sua conta</span>
                                    <?php else: ?>
                                        <span class="voce-tag">Não gerenciável por aqui</span>
                                    <?php endif; ?>
                                <?php elseif ($tipo === 'admin'): ?>
                                    <form class="acao-form" action="../src/alterar_nivel_usuario.php" method="POST" onsubmit="return confirm('Remover o acesso de administrador desta conta?');">
                                        <input type="hidden" name="usuario_id" value="<?= (int) $usuario['id'] ?>">
                                        <input type="hidden" name="novo_tipo" value="usuario">
                                        <button type="submit" class="btn-acao btn-acao-rebaixar">
                                            <i class="fa-solid fa-arrow-down"></i> Rebaixar para usuário
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <form class="acao-form" action="../src/alterar_nivel_usuario.php" method="POST" onsubmit="return confirm('Promover esta conta a administrador?');">
                                        <input type="hidden" name="usuario_id" value="<?= (int) $usuario['id'] ?>">
                                        <input type="hidden" name="novo_tipo" value="admin">
                                        <button type="submit" class="btn-acao btn-acao-promover">
                                            <i class="fa-solid fa-arrow-up"></i> Promover a admin
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </div>
</main>

</div>

<?php include '../includes/footer.php'; ?>
