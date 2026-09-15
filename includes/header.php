<?php

$baseUrl = 'https://pindaeco.rf.gd';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuarioLogado = isset($_SESSION['usuario_id']);

$usuarioNome = $_SESSION['usuario_nome'] ?? '';
$usuarioFoto = $_SESSION['usuario_foto'] ?? '';
$usuarioTipo = $_SESSION['usuario_tipo'] ?? 'usuario';

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

    <!-- LOGO -->
    <div class="logo">

        <a href="<?= $baseUrl ?>/index.php">

            <img
                src="/../assets/img2/logo.png"
                alt="Pinda Eco"
            >

        </a>

    </div>


    <nav class="menu">

        <a href="/../index.php">
            Início
        </a>

        <a href="/../pages/cidade.php">
            Cidade
        </a>


        <!-- ITEM COM SUBMENU -->
        <div class="menu-dropdown">

            <a href="/../pages/turismo/read.php">
                Turismo
            </a>

            <div class="submenu">

                <a href="/../pages/guia/read.php">
                    Guia Turístico
                </a>

            </div>

        </div>


        <a href="/../pages/hoteis/read.php">
            Hotéis
        </a>

        <a href="/../pages/restaurante/read.php">
            Restaurantes
        </a>

        <a href="/../pages/feed.php">
            +PINDA
        </a>


        <?php if ($usuarioLogado): ?>

            <!-- MURAL -->
            <a href="/../pages/mural/read.php">
                📸 Mural
            </a>


            <!-- USUÁRIO -->
            <a
                href="/../pages/profile.php"
                class="menu-usuario"
            >

                <!-- FOTO / INICIAIS -->
                <span class="menu-avatar">

                    <?php if (!empty($usuarioFoto)): ?>

                        <img
                            src="/../assets/uploads/perfil/<?= htmlspecialchars($usuarioFoto) ?>"
                            alt="Foto de perfil"
                        >

                    <?php else: ?>

                        <?= htmlspecialchars(
                            iniciaisHeader($usuarioNome)
                        ) ?>

                    <?php endif; ?>

                </span>


                <!-- INFORMAÇÕES -->
                <span class="menu-usuario-info">

                    <!-- NOME + PINDACOINS -->
                    <span class="menu-usuario-nome">

                        <span class="nome-usuario">
                            <?= htmlspecialchars($usuarioNome) ?>
                        </span>

                        <span
                            class="menu-pindacoins"
                            id="pindacoins"
                        >
                            PindaCOINS <?= $pindaCoins ?>
                        </span>

                    </span>


                    <!-- TIPO DE USUÁRIO -->
                    <?php if ($usuarioMaster): ?>

                        <span class="menu-usuario-cargo master">
                            Master
                        </span>

                    <?php elseif ($usuarioAdmin): ?>

                        <span class="menu-usuario-cargo admin">
                            Administrador
                        </span>

                    <?php else: ?>

                        <span class="menu-usuario-cargo">
                            Usuário
                        </span>

                    <?php endif; ?>

                </span>

            </a>


        <?php else: ?>

            <!-- USUÁRIO NÃO LOGADO -->
            <a href="/../pages/login.php">
                Login
            </a>

        <?php endif; ?>


        <span class="indicator"></span>

    </nav>

</header>