<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../classes/restaurante.php";
require_once "../../classes/restaurante_fotos.php";

$baseUrl = 'https://pindaeco.rf.gd';

$restaurante = new Restaurante();
$restauranteFoto = new RestauranteFoto();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$dados = $id > 0 ? $restaurante->buscarPorId($id) : false;

include "../../includes/header.php";
include "../../includes/head.php";

?>

<link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/restaurante.css">
<link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/restaurante-detalhes.css">

<?php if (!$dados): ?>

    <!-- =====================================================
         RESTAURANTE NÃO ENCONTRADO
    ====================================================== -->

    <div class="restaurantes-container">

        <div class="restaurantes-conteudo">

            <div class="restaurantes-vazio">

                <h3>Restaurante não encontrado</h3>

                <p>O restaurante que você está procurando não existe ou foi removido.</p>

                <a href="read.php" class="restaurante-detalhes-voltar">
                    &#8592; Voltar para restaurantes
                </a>

            </div>

        </div>

    </div>

<?php else: ?>

    <?php

    $fotosRestaurante = $restauranteFoto->listarPorRestaurante($dados['id']);

    $totalFotos = count($fotosRestaurante);

    $enderecoCompleto = trim(
        $dados['logradouro']
        . (!empty($dados['numero']) ? ', ' . $dados['numero'] : '')
        . ', ' . $dados['cidade']
        . (!empty($dados['cep']) ? ', ' . $dados['cep'] : '')
    );

    ?>

    <div class="restaurante-detalhes-container">

        <div class="restaurante-detalhes-conteudo">

            <!-- =====================================================
                 VOLTAR
            ====================================================== -->

            <a href="read.php" class="restaurante-detalhes-voltar">
                &#8592; Voltar para restaurantes
            </a>


            <!-- =====================================================
                 TÍTULO
            ====================================================== -->

            <div class="restaurante-detalhes-topo">

                <h1 class="restaurante-detalhes-titulo">
                    <?= htmlspecialchars($dados['nome']) ?>
                </h1>

                <span class="restaurante-detalhes-local">
                    📍 <?= htmlspecialchars($dados['cidade']) ?>
                </span>

            </div>


            <!-- =====================================================
                 GALERIA ESTILO AIRBNB
            ====================================================== -->

            <div class="restaurante-detalhes-galeria <?= $totalFotos === 0 ? 'sem-fotos' : ('total-' . min($totalFotos, 5)) ?>">

                <?php if ($totalFotos === 0): ?>

                    <div class="restaurante-detalhes-foto restaurante-detalhes-foto-principal restaurante-imagem-placeholder">
                        <span>Foto não disponível</span>
                    </div>

                <?php else: ?>

                    <?php foreach ($fotosRestaurante as $indice => $foto): ?>

                        <?php if ($indice >= 5) break; ?>

                        <button type="button"
                            class="restaurante-detalhes-foto <?= $indice === 0 ? 'restaurante-detalhes-foto-principal' : '' ?>"
                            data-indice="<?= $indice ?>">

                            <img src="<?= $baseUrl ?>/uploads/restaurantes/<?= htmlspecialchars($foto['caminho']) ?>"
                                alt="<?= htmlspecialchars($dados['nome']) ?>">

                            <?php if ($indice === 4 && $totalFotos > 5): ?>

                                <span class="restaurante-detalhes-mais-fotos">
                                    + <?= $totalFotos - 5 ?> fotos
                                </span>

                            <?php endif; ?>

                        </button>

                    <?php endforeach; ?>

                    <?php if ($totalFotos > 1): ?>

                        <button type="button" class="restaurante-detalhes-ver-todas">
                            &#128247; Ver todas as fotos
                        </button>

                    <?php endif; ?>

                <?php endif; ?>

            </div>


            <!-- =====================================================
                 CORPO (INFORMAÇÕES + CARD LATERAL)
            ====================================================== -->

            <div class="restaurante-detalhes-corpo">

                <!-- ================================
                     COLUNA PRINCIPAL
                ================================= -->

                <div class="restaurante-detalhes-principal">

                    <div class="restaurante-detalhes-bloco">

                        <h2>Sobre o restaurante</h2>

                        <p class="restaurante-detalhes-endereco">
                            <?= htmlspecialchars($dados['logradouro']) ?><?php if (!empty($dados['numero'])): ?>, <?= htmlspecialchars($dados['numero']) ?><?php endif; ?>
                            — <?= htmlspecialchars($dados['cidade']) ?>
                        </p>

                        <?php if (!empty($dados['cep'])): ?>
                            <p class="restaurante-detalhes-cep">
                                CEP: <?= htmlspecialchars($dados['cep']) ?>
                            </p>
                        <?php endif; ?>

                    </div>

                    <hr class="restaurante-detalhes-separador">

                    <div class="restaurante-detalhes-bloco">

                        <h2>O que esse lugar oferece</h2>

                        <ul class="restaurante-detalhes-comodidades">

                            <?php if (!empty($dados['categoria'])): ?>
                                <li>🍽️ <?= htmlspecialchars($dados['categoria']) ?></li>
                            <?php endif; ?>

                            <?php if (!empty($dados['horario_funcionamento'])): ?>
                                <li>🕒 <?= htmlspecialchars($dados['horario_funcionamento']) ?></li>
                            <?php endif; ?>

                            <li class="<?= $dados['possui_wifi'] ? '' : 'indisponivel' ?>">
                                <?= $dados['possui_wifi'] ? '✓' : '✕' ?> Wi-Fi
                            </li>

                            <li class="<?= $dados['possui_delivery'] ? '' : 'indisponivel' ?>">
                                <?= $dados['possui_delivery'] ? '✓' : '✕' ?> Delivery
                            </li>

                        </ul>

                    </div>

                    <hr class="restaurante-detalhes-separador">

                    <div class="restaurante-detalhes-bloco">

                        <h2>Localização</h2>

                        <div class="restaurante-detalhes-mapa">

                            <iframe
                                src="https://www.google.com/maps?q=<?= urlencode($enderecoCompleto) ?>&output=embed"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                allowfullscreen>
                            </iframe>

                        </div>

                        <a class="restaurante-detalhes-mapa-link"
                            href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($enderecoCompleto) ?>"
                            target="_blank" rel="noopener noreferrer">
                            📍 Ver no Google Maps
                        </a>

                    </div>

                </div>


                <!-- ================================
                     CARD LATERAL (CONTATO)
                ================================= -->

                <aside class="restaurante-detalhes-lateral">

                    <div class="restaurante-detalhes-card-contato">

                        <h3>Entre em contato</h3>

                        <?php if (!empty($dados['telefone'])): ?>

                            <p class="restaurante-detalhes-contato-item">
                                📞 <?= htmlspecialchars($dados['telefone']) ?>
                            </p>

                        <?php endif; ?>

                        <?php if (!empty($dados['email'])): ?>

                            <p class="restaurante-detalhes-contato-item">
                                ✉️ <?= htmlspecialchars($dados['email']) ?>
                            </p>

                        <?php endif; ?>

                        <?php if (!empty($dados['telefone'])): ?>

                            <a class="restaurante-botao restaurante-detalhes-botao-contato"
                                href="tel:<?= htmlspecialchars($dados['telefone']) ?>">
                                Ligar agora
                            </a>

                        <?php endif; ?>

                        <?php if ($usuarioMaster): ?>

                            <div class="restaurante-acoes restaurante-detalhes-acoes">

                                <a href="update.php?id=<?= (int) $dados['id'] ?>" class="restaurante-editar">
                                    Editar
                                </a>

                                <a href="delete.php?id=<?= (int) $dados['id'] ?>" class="restaurante-excluir"
                                    onclick="return confirm('Deseja realmente excluir este restaurante?')">
                                    Excluir
                                </a>

                            </div>

                        <?php endif; ?>

                    </div>

                </aside>

            </div>

        </div>

    </div>


    <!-- =====================================================
         LIGHTBOX DE FOTOS
    ====================================================== -->

    <div class="restaurante-lightbox" id="restauranteLightbox">

        <button type="button" class="restaurante-lightbox-fechar" aria-label="Fechar">&times;</button>

        <button type="button" class="restaurante-lightbox-seta restaurante-lightbox-anterior" aria-label="Foto anterior">&#10094;</button>

        <img class="restaurante-lightbox-imagem" src="" alt="">

        <button type="button" class="restaurante-lightbox-seta restaurante-lightbox-proxima" aria-label="Próxima foto">&#10095;</button>

        <span class="restaurante-lightbox-contador"></span>

    </div>

    <script>
        window.restauranteFotos = <?= json_encode(array_map(function ($f) {
            return "/ecopinda/uploads/restaurantes/" . $f['caminho'];
        }, $fotosRestaurante)) ?>;
    </script>

    <script src="<?= $baseUrl ?>/assets/js/restaurante-detalhes.js"></script>

<?php endif; ?>

<?php

include "../../includes/footer.php";

?>
