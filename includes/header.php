<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuarioLogado = isset($_SESSION['usuario_id']);
$usuarioNome   = $_SESSION['usuario_nome'] ?? '';
$usuarioFoto   = $_SESSION['usuario_foto'] ?? '';
$usuarioTipo   = $_SESSION['usuario_tipo'] ?? 'usuario';
$usuarioAdmin  = in_array($usuarioTipo, ['admin', 'master'], true);
$usuarioMaster = $usuarioTipo === 'master';

if (!function_exists('iniciaisHeader')) {
    function iniciaisHeader($nome) {
        $partes = preg_split('/\s+/', trim($nome));
        $iniciais = mb_substr($partes[0] ?? '', 0, 1);
        if (count($partes) > 1) {
            $iniciais .= mb_substr(end($partes), 0, 1);
        }
        return $iniciais;
    }
}
?>
<header class="header">
    <div class="logo">
        <a href="../index.php">
            <img src="../assets/img2/logo.png" alt="pinda eco">
        </a>
    </div>

    <nav class="menu">
        <a href="../index.php">Início</a>
        <a href="/pages/cidade.php">Cidade</a>
        <a href="#">Turismo</a>
        <a href="pages/hoteis.php">Hotéis</a>
        <a href="pages/restaurante.php">Restaurantes</a>

        <?php if ($usuarioMaster): ?>
            <a href="pages/usuarios.php">Usuários</a>
        <?php endif; ?>

        <?php if ($usuarioLogado): ?>
            <a href="pages/profile.php" class="menu-usuario">
                <span class="menu-avatar">
                    <?php if (!empty($usuarioFoto)): ?>
                        <img src="../assets/uploads/perfil/<?= htmlspecialchars($usuarioFoto) ?>" alt="Foto de perfil">
                    <?php else: ?>
                        <?= htmlspecialchars(iniciaisHeader($usuarioNome)) ?>
                    <?php endif; ?>
                </span>
                <span class="menu-usuario-nome">
                    <?= htmlspecialchars($usuarioNome) ?>
                    <?php if ($usuarioMaster): ?>
                        <small style="opacity:.7;">(master)</small>
                    <?php elseif ($usuarioAdmin): ?>
                        <small style="opacity:.7;">(admin)</small>
                    <?php endif; ?>
                </span>
            </a>
        <?php else: ?>
            <a href="pages/login.php">Login</a>
        <?php endif; ?>

        <span class="indicator"></span>
    </nav>
</header>