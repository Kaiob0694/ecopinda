<?php
$prefixo = '../../';

require_once $prefixo . 'config/conexao.php';
require_once $prefixo . 'classes/guiasTuristicos.php';

$guiasTuristicos = new GuiasTuristicos($pdo);
$guias = $guiasTuristicos->listar();

$pageTitle = 'Guia Turístico';
require_once $prefixo . 'includes/header.php';
?>

<link rel="stylesheet" href="<?= $prefixo ?>assets/css/guia-turistico.css">

<main class="guia-container">

    <div class="guia-topo">
        <h1>Guia Turístico</h1>
        <p>Conheça quem pode te guiar pela cidade</p>
    </div>

    <?php if (empty($guias)): ?>

        <div class="guia-vazio">
            <p>Nenhum guia cadastrado no momento.</p>
        </div>

    <?php else: ?>

        <div class="guia-grid">

            <?php foreach ($guias as $guia):

                $fotos = $guiasTuristicos->listarFotos($guia['id']);
                $categorias = $guiasTuristicos->listarCategorias($guia['id']);

                $fotoPerfil = !empty($guia['foto_perfil'])
                    ? $prefixo . 'assets/uploads/guias/' . htmlspecialchars($guia['foto_perfil'])
                    : $prefixo . 'assets/img2/sem-foto.png';
            ?>

                <div class="guia-card">

                    <!-- FOTO DE PERFIL -->
                    <div class="guia-foto">
                        <img src="<?= $fotoPerfil ?>" alt="Foto de <?= htmlspecialchars($guia['nome']) ?>">
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

                        <!-- CATEGORIAS -->
                        <?php if (!empty($categorias)): ?>
                            <div class="guia-categorias">
                                <?php foreach ($categorias as $categoria): ?>
                                    <span class="guia-tag">
                                        <?= htmlspecialchars($categoria['nome']) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- GALERIA DE FOTOS -->
                        <?php if (!empty($fotos)): ?>
                            <div class="guia-galeria">
                                <?php foreach (array_slice($fotos, 0, 4) as $foto): ?>
                                    <img
                                        src="<?= $prefixo ?>assets/uploads/guias/<?= htmlspecialchars($foto['foto']) ?>"
                                        alt="<?= htmlspecialchars($foto['descricao'] ?? 'Foto do guia') ?>"
                                    >
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- CONTATO -->
                        <div class="guia-contato">

                            <?php if (!empty($guia['telefone'])): ?>
                                <a href="https://wa.me/55<?= preg_replace('/\D/', '', $guia['telefone']) ?>" target="_blank" class="guia-btn whatsapp">
                                    WhatsApp
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($guia['instagram'])): ?>
                                <a href="https://instagram.com/<?= ltrim(htmlspecialchars($guia['instagram']), '@') ?>" target="_blank" class="guia-btn instagram">
                                    Instagram
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($guia['email'])): ?>
                                <a href="mailto:<?= htmlspecialchars($guia['email']) ?>" class="guia-btn email">
                                    E-mail
                                </a>
                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</main>

<?php require_once $prefixo . 'includes/footer.php'; ?>