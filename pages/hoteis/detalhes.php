<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../classes/hoteis.php";
require_once "../../classes/hotel_fotos.php";

$hotel = new Hotel();
$hotelFoto = new HotelFoto();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$dados = $id > 0 ? $hotel->buscarPorId($id) : false;

include "../../includes/header.php";
include "../../includes/head.php";

?>

<link rel="stylesheet" href="/ecopinda/assets/css/hotel.css">
<link rel="stylesheet" href="/ecopinda/assets/css/hotel-detalhes.css">

<?php if (!$dados): ?>

    <!-- =====================================================
         HOTEL NÃO ENCONTRADO
    ====================================================== -->

    <div class="hoteis-container">

        <div class="hoteis-conteudo">

            <div class="hoteis-vazio">

                <h3>Hotel não encontrado</h3>

                <p>O hotel que você está procurando não existe ou foi removido.</p>

                <a href="read.php" class="hotel-detalhes-voltar">
                    &#8592; Voltar para hotéis
                </a>

            </div>

        </div>

    </div>

<?php else: ?>

    <?php

    $fotosHotel = $hotelFoto->listarPorHotel($dados['id']);

    $telefoneWhatsApp = preg_replace('/\D/', '', $dados['telefone'] ?? '');

    $totalFotos = count($fotosHotel);

    $enderecoCompleto = trim(
        $dados['endereco']
        . ', ' . $dados['cidade']
        . ' - ' . $dados['estado']
        . (!empty($dados['cep']) ? ', ' . $dados['cep'] : '')
    );

    ?>

    <div class="hotel-detalhes-container">

        <div class="hotel-detalhes-conteudo">

            <!-- =====================================================
                 VOLTAR
            ====================================================== -->

            <a href="read.php" class="hotel-detalhes-voltar">
                &#8592; Voltar para hotéis
            </a>


            <!-- =====================================================
                 TÍTULO
            ====================================================== -->

            <div class="hotel-detalhes-topo">

                <h1 class="hotel-detalhes-titulo">
                    <?= htmlspecialchars($dados['nome']) ?>
                </h1>

                <span class="hotel-detalhes-local">
                    📍 <?= htmlspecialchars($dados['cidade']) ?>, <?= htmlspecialchars($dados['estado']) ?>
                </span>

            </div>


            <!-- =====================================================
                 GALERIA ESTILO AIRBNB
            ====================================================== -->

            <div class="hotel-detalhes-galeria <?= $totalFotos === 0 ? 'sem-fotos' : ('total-' . min($totalFotos, 5)) ?>">

                <?php if ($totalFotos === 0): ?>

                    <div class="hotel-detalhes-foto hotel-detalhes-foto-principal hotel-imagem-placeholder">
                        <span>Foto não disponível</span>
                    </div>

                <?php else: ?>

                    <?php foreach ($fotosHotel as $indice => $foto): ?>

                        <?php if ($indice >= 5) break; ?>

                        <button type="button"
                            class="hotel-detalhes-foto <?= $indice === 0 ? 'hotel-detalhes-foto-principal' : '' ?>"
                            data-indice="<?= $indice ?>">

                            <img src="/ecopinda/assets/uploads/hoteis/<?= htmlspecialchars($foto['caminho']) ?>"
                                alt="<?= htmlspecialchars($dados['nome']) ?>">

                            <?php if ($indice === 4 && $totalFotos > 5): ?>

                                <span class="hotel-detalhes-mais-fotos">
                                    + <?= $totalFotos - 5 ?> fotos
                                </span>

                            <?php endif; ?>

                        </button>

                    <?php endforeach; ?>

                    <?php if ($totalFotos > 1): ?>

                        <button type="button" class="hotel-detalhes-ver-todas">
                            &#128247; Ver todas as fotos
                        </button>

                    <?php endif; ?>

                <?php endif; ?>

            </div>


            <!-- =====================================================
                 CORPO (INFORMAÇÕES + CARD LATERAL)
            ====================================================== -->

            <div class="hotel-detalhes-corpo">

                <!-- ================================
                     COLUNA PRINCIPAL
                ================================= -->

                <div class="hotel-detalhes-principal">

                    <div class="hotel-detalhes-bloco">

                        <h2>Sobre o hotel</h2>

                        <p class="hotel-detalhes-endereco">
                            <?= htmlspecialchars($dados['endereco']) ?>
                        </p>

                        <?php if (!empty($dados['cep'])): ?>
                            <p class="hotel-detalhes-cep">
                                CEP: <?= htmlspecialchars($dados['cep']) ?>
                            </p>
                        <?php endif; ?>

                    </div>

                    <hr class="hotel-detalhes-separador">

                    <div class="hotel-detalhes-bloco">

                        <h2>O que esse lugar oferece</h2>

                        <ul class="hotel-detalhes-comodidades">

                            <?php if (!empty($dados['quantidade_quartos'])): ?>
                                <li>🛏️ <?= htmlspecialchars($dados['quantidade_quartos']) ?> quartos</li>
                            <?php endif; ?>

                            <li class="<?= $dados['possui_wifi'] ? '' : 'indisponivel' ?>">
                                <?= $dados['possui_wifi'] ? '✓' : '✕' ?> Wi-Fi
                            </li>

                            <li class="<?= $dados['possui_estacionamento'] ? '' : 'indisponivel' ?>">
                                <?= $dados['possui_estacionamento'] ? '✓' : '✕' ?> Estacionamento
                            </li>

                        </ul>

                    </div>

                    <hr class="hotel-detalhes-separador">

                    <div class="hotel-detalhes-bloco">

                        <h2>Localização</h2>

                        <div class="hotel-detalhes-mapa">

                            <iframe
                                src="https://www.google.com/maps?q=<?= urlencode($enderecoCompleto) ?>&output=embed"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                allowfullscreen>
                            </iframe>

                        </div>

                        <a class="hotel-detalhes-mapa-link"
                            href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($enderecoCompleto) ?>"
                            target="_blank" rel="noopener noreferrer">
                            📍 Ver no Google Maps
                        </a>

                    </div>

                </div>


                <!-- ================================
                     CARD LATERAL (CONTATO)
                ================================= -->

                <aside class="hotel-detalhes-lateral">

                    <div class="hotel-detalhes-card-contato">

                        <h3>Entre em contato</h3>

                        <?php if (!empty($dados['telefone'])): ?>

                            <p class="hotel-detalhes-contato-item">
                                📞 <?= htmlspecialchars($dados['telefone']) ?>
                            </p>

                        <?php endif; ?>

                        <?php if (!empty($dados['email'])): ?>

                            <p class="hotel-detalhes-contato-item">
                                ✉️ <?= htmlspecialchars($dados['email']) ?>
                            </p>

                        <?php endif; ?>

                        <?php if (!empty($telefoneWhatsApp)): ?>

                            <a class="hotel-botao hotel-detalhes-botao-whatsapp"
                                href="https://wa.me/55<?= htmlspecialchars($telefoneWhatsApp) ?>"
                                target="_blank" rel="noopener noreferrer">
                                Falar no WhatsApp
                            </a>

                        <?php endif; ?>

                        <?php if ($usuarioMaster): ?>

                            <div class="hotel-acoes hotel-detalhes-acoes">

                                <a href="update.php?id=<?= (int) $dados['id'] ?>" class="hotel-editar">
                                    Editar
                                </a>

                                <a href="delete.php?id=<?= (int) $dados['id'] ?>" class="hotel-excluir"
                                    onclick="return confirm('Deseja realmente excluir este hotel?')">
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

    <div class="hotel-lightbox" id="hotelLightbox">

        <button type="button" class="hotel-lightbox-fechar" aria-label="Fechar">&times;</button>

        <button type="button" class="hotel-lightbox-seta hotel-lightbox-anterior" aria-label="Foto anterior">&#10094;</button>

        <img class="hotel-lightbox-imagem" src="" alt="">

        <button type="button" class="hotel-lightbox-seta hotel-lightbox-proxima" aria-label="Próxima foto">&#10095;</button>

        <span class="hotel-lightbox-contador"></span>

    </div>

    <script>
        window.hotelFotos = <?= json_encode(array_map(function ($f) {
            return "/ecopinda/assets/uploads/hoteis/" . $f['caminho'];
        }, $fotosHotel)) ?>;
    </script>

    <script src="/ecopinda/assets/js/hotel-detalhes.js"></script>

<?php endif; ?>

<?php

include "../../includes/footer.php";

?>
