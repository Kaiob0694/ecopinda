<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../classes/guiasTuristicos.php';

$baseUrl = 'https://pindaeco.rf.gd';

// Cria a conexão
$db = new Conexao();
$pdo = $db->conectar();

// Passa o PDO para a classe
$guiasTuristicos = new GuiasTuristicos($pdo);

$guias = $guiasTuristicos->listar();

$pageTitle = 'Guia Turístico';

require_once __DIR__ . '/../../includes/header.php';

?>

<link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/hotel.css">

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
                    ? '/assets/uploads/guias/' . htmlspecialchars($guia['foto_perfil'])
                    : '/assets/img2/sem-foto.png';

            ?>

                <div class="guia-card">

                    <div class="guia-foto">
                        <img
                            src="<?= $fotoPerfil ?>"
                            alt="Foto de <?= htmlspecialchars($guia['nome']) ?>"
                        >
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
                                        src="/assets/uploads/guias/<?= htmlspecialchars($foto['foto']) ?>"
                                        alt="<?= htmlspecialchars($foto['descricao'] ?? 'Foto do guia') ?>"
                                    >

                                <?php endforeach; ?>

                            </div>
                        <?php endif; ?>

                        <div class="guia-contato">

                            <?php if (!empty($guia['telefone'])): ?>
                                <a
                                    href="https://wa.me/55<?= preg_replace('/\D/', '', $guia['telefone']) ?>"
                                    target="_blank"
                                    class="guia-btn whatsapp"
                                >
                                    WhatsApp
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($guia['instagram'])): ?>
                                <a
                                    href="https://instagram.com/<?= ltrim(htmlspecialchars($guia['instagram']), '@') ?>"
                                    target="_blank"
                                    class="guia-btn instagram"
                                >
                                    Instagram
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($guia['email'])): ?>
                                <a
                                    href="mailto:<?= htmlspecialchars($guia['email']) ?>"
                                    class="guia-btn email"
                                >
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

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>