<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../classes/guiasTuristicos.php';

$baseUrl = 'https://pindaeco.rf.gd';

$guiasTuristicos = new GuiasTuristicos();

$guias = $guiasTuristicos->listar();

$usuarioMaster = ($_SESSION['usuario_tipo'] ?? '') === 'master';

$sucesso = $_GET['sucesso'] ?? '';
$erro = $_GET['erro'] ?? '';

$mensagensSucesso = [
    'cadastrado' => 'Guia cadastrado com sucesso!',
    'atualizado' => 'Guia atualizado com sucesso!',
    'excluido'   => 'Guia excluído com sucesso!',
];

$mensagensErro = [
    'nao_encontrado' => 'Guia não encontrado.',
    'sem_permissao'  => 'Você não tem permissão para acessar essa área.',
];

$pageTitle = 'Guia Turístico';

include __DIR__ . '/../../includes/head.php';
include __DIR__ . '/../../includes/header.php';

?>

<link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/guia-turistico.css">

<main class="guia-container">

    <div class="guia-topo">
        <h1>Guia Turístico</h1>
        <p>Conheça quem pode te guiar pela cidade</p>

        <?php if ($usuarioMaster): ?>
            <a href="create.php" class="guia-btn salvar">+ Novo Guia</a>
        <?php endif; ?>
    </div>

    <?php if ($sucesso && isset($mensagensSucesso[$sucesso])): ?>
        <div class="guia-alerta sucesso">
            <?= htmlspecialchars($mensagensSucesso[$sucesso]) ?>
        </div>
    <?php endif; ?>

    <?php if ($erro && isset($mensagensErro[$erro])): ?>
        <div class="guia-alerta erro">
            <?= htmlspecialchars($mensagensErro[$erro]) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($guias)): ?>

        <div class="guia-vazio">
            <p>Nenhum guia cadastrado no momento.</p>
        </div>

    <?php else: ?>

        <div class="guia-grid">

            <?php foreach ($guias as $guia): ?>

                <?php

                $fotos = $guiasTuristicos->listarFotos($guia['id']);
                $categorias = $guiasTuristicos->listarCategorias($guia['id']);

                if (!empty($guia['foto_perfil'])) {
                    $fotoPerfil = $baseUrl . '/assets/uploads/guias/' . rawurlencode($guia['foto_perfil']);
                } else {
                    $fotoPerfil = $baseUrl . '/assets/img2/sem-foto.png';
                }

                ?>

                <div class="guia-card">

                    <div class="guia-foto">
                        <img
                            src="<?= htmlspecialchars($fotoPerfil) ?>"
                            alt="Foto de <?= htmlspecialchars($guia['nome']) ?>">
                    </div>

                    <div class="guia-info">

                        <h2><?= htmlspecialchars($guia['nome']) ?></h2>

                        <?php if (!empty($guia['cidade'])): ?>
                            <span class="guia-cidade">
                                📍 <?= htmlspecialchars($guia['cidade']) ?>
                            </span>
                        <?php endif; ?>

                        <?php if (!empty($guia['experiencia'])): ?>
                            <span class="guia-experiencia">
                                <?= htmlspecialchars($guia['experiencia']) ?> de experiência
                            </span>
                        <?php endif; ?>

                        <?php if (!empty($guia['descricao'])): ?>
                            <p class="guia-descricao">
                                <?= nl2br(htmlspecialchars($guia['descricao'])) ?>
                            </p>
                        <?php endif; ?>

                        <?php if (!empty($categorias)): ?>
                            <div class="guia-categorias">
                                <?php foreach ($categorias as $categoria): ?>
                                    <span class="guia-tag">
                                        <?= htmlspecialchars($categoria['nome']) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($fotos)): ?>
                            <div class="guia-galeria">
                                <?php foreach (array_slice($fotos, 0, 4) as $foto): ?>
                                    <img
                                        src="<?= $baseUrl ?>/assets/uploads/guias/<?= rawurlencode($foto['foto']) ?>"
                                        alt="<?= htmlspecialchars($foto['descricao'] ?? 'Foto do guia') ?>">
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="guia-contato">

                            <?php if (!empty($guia['telefone'])): ?>
                                <?php $telefone = preg_replace('/\D/', '', $guia['telefone']); ?>
                                <a href="https://wa.me/55<?= $telefone ?>" target="_blank" rel="noopener noreferrer" class="guia-btn whatsapp">
                                    WhatsApp
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($guia['instagram'])): ?>
                                <?php $instagram = ltrim($guia['instagram'], '@'); ?>
                                <a href="https://instagram.com/<?= htmlspecialchars($instagram) ?>" target="_blank" rel="noopener noreferrer" class="guia-btn instagram">
                                    Instagram
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($guia['email'])): ?>
                                <a href="mailto:<?= htmlspecialchars($guia['email']) ?>" class="guia-btn email">
                                    E-mail
                                </a>
                            <?php endif; ?>

                        </div>

                        <?php if ($usuarioMaster): ?>
                            <div class="guia-admin-acoes">
                                <a href="update.php?id=<?= (int) $guia['id'] ?>" class="guia-btn editar">
                                    Editar
                                </a>

                                href="delete.php?id=<?= (int) $guia['id'] ?>"
                                class="guia-btn excluir"
                                onclick="return confirm('Excluir o guia <?= htmlspecialchars($guia['nome'], ENT_QUOTES) ?>?');"
                                >
                                Excluir
                                </a>
                            </div>
                        <?php endif; ?>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>