<?php

$baseUrl = 'https://pindaeco.rf.gd';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuarioLogado = isset($_SESSION['usuario_id']);
$usuarioNome   = $_SESSION['usuario_nome'] ?? '';
$usuarioFoto   = $_SESSION['usuario_foto'] ?? '';
$usuarioTipo   = $_SESSION['usuario_tipo'] ?? 'usuario';

$usuarioAdmin  = in_array($usuarioTipo, ['admin', 'master'], true);
$usuarioMaster = $usuarioTipo === 'master';

/*
|--------------------------------------------------------------------------
| PINDACOINS DO USUÁRIO
|--------------------------------------------------------------------------
*/

$pindaCoins = 0;

if ($usuarioLogado) {

    require_once __DIR__ . '/../config/conexao.php';

    try {

        $conexao = new Conexao();
        $pdo = $conexao->conectar();

        $stmt = $pdo->prepare("
            SELECT pindacoins
            FROM usuarios
            WHERE id = :id
        ");

        $stmt->execute([
            ':id' => $_SESSION['usuario_id']
        ]);

        $pindaCoins = (int) $stmt->fetchColumn();

    } catch (Exception $e) {

        $pindaCoins = 0;

    }
}

/*
|--------------------------------------------------------------------------
| INICIAIS DO USUÁRIO
|--------------------------------------------------------------------------
*/

if (!function_exists('iniciaisHeader')) {

    function iniciaisHeader($nome)
    {
        $partes = preg_split('/\s+/', trim($nome));

        $iniciais = mb_substr(
            $partes[0] ?? '',
            0,
            1
        );

        if (count($partes) > 1) {

            $iniciais .= mb_substr(
                end($partes),
                0,
                1
            );

        }

        return $iniciais;
    }

}

?>

<header class="header">

    <div class="header-shell">

        <!-- LOGO -->
        <a href="<?= $baseUrl ?>/index.php" class="logo-pill">

            <img
                src="/../assets/img2/logo.png"
                alt="Pinda Eco">

        </a>


        <!-- MENU CENTRAL (PÍLULA) -->
        <nav class="nav-pill">


            <!-- =====================================================
                 INÍCIO
            ====================================================== -->

            <a href="/../index.php">
                Início
            </a>



            <!-- =====================================================
                 EXPLORAR
            ====================================================== -->

            <div class="menu-dropdown">

                <a
                    href="/../pages/cidade.php"
                    data-indicator-color="verde"
                >
                    Explorar
                </a>

                <div class="submenu">

                    <a href="/../pages/cidade.php">
                        Cidade
                    </a>

                    <a href="/../pages/turismo/read.php">
                        Turismo
                    </a>

                    <a href="/../pages/guia/read.php">
                        Guia Turístico
                    </a>

                    <a href="/../pages/hoteis/read.php">
                        Hotéis
                    </a>

                    <a href="/../pages/restaurante/read.php">
                        Restaurantes
                    </a>

                </div>

            </div>



            <!-- =====================================================
                 COMUNIDADE
            ====================================================== -->

            <div class="menu-dropdown">

                <a
                    href="/../pages/feed.php"
                    data-indicator-color="azul"
                >
                    Comunidade
                </a>

                <div class="submenu">

                    <a href="/../pages/feed.php">
                        +PINDA
                    </a>


                    <?php if ($usuarioLogado): ?>

                        <a href="/../pages/mural/read.php">
                            📸 Mural
                        </a>

                    <?php endif; ?>

                </div>

            </div>



            <!-- =====================================================
                 LOJA DE RECOMPENSAS
            ====================================================== -->

            <a href="/../index.php">
                Loja de Recompensas
            </a>
            <a href="/../pages/calendario/calendario.php">
                Calendario
            </a>


        </nav>



        <!-- =====================================================
             AÇÕES — USUÁRIO / LOGIN
        ====================================================== -->

        <div class="header-actions">

            <?php if ($usuarioLogado): ?>

                <a
                    href="/../pages/profile.php"
                    class="user-chip"
                >

                    <!-- AVATAR -->
                    <span class="user-avatar">

                        <?php if (!empty($usuarioFoto)): ?>

                            <img
                                src="/../assets/uploads/perfil/<?= htmlspecialchars($usuarioFoto) ?>"
                                alt="Foto de perfil">

                        <?php else: ?>

                            <?= htmlspecialchars(
                                iniciaisHeader($usuarioNome)
                            ) ?>

                        <?php endif; ?>

                    </span>


                    <!-- INFORMAÇÕES DO USUÁRIO -->
                    <span class="user-meta">

                        <span class="user-name">

                            <?= htmlspecialchars($usuarioNome) ?>


                            <!-- MASTER -->
                            <?php if ($usuarioMaster): ?>

                                <span class="user-badge badge-master">
                                    Master
                                </span>


                            <!-- ADMIN -->
                            <?php elseif ($usuarioAdmin): ?>

                                <span class="user-badge badge-admin">
                                    Admin
                                </span>

                            <?php endif; ?>

                        </span>


                        <!-- PINDA COINS -->
                        <span
                            class="user-coins"
                            id="pindacoins"
                        >
                            PindaCOINS: <?= $pindaCoins ?>
                        </span>

                    </span>

                </a>


            <?php else: ?>


                <!-- LOGIN -->
                <a
                    href="/../pages/login.php"
                    class="btn-login"
                >
                    Login
                </a>


            <?php endif; ?>

        </div>

    </div>

</header>