<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../classes/turismo.php";
require_once "../../classes/turismo_fotos.php";

$baseUrl = 'https://pindaeco.rf.gd';

$ponto = new PontoTuristico();
$pontoFoto = new PontoTuristicoFoto();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$dados = $id > 0 ? $ponto->buscarPorId($id) : false;

include "../../includes/header.php";
include "../../includes/head.php";

?>

<link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/turismo.css">
<link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/turismo-detalhes.css">

<?php if (!$dados): ?>

    <!-- =====================================================
         PONTO TURÍSTICO NÃO ENCONTRADO
    ====================================================== -->

    <div class="turismo-container">

        <div class="turismo-conteudo">

            <div class="turismo-vazio">

                <h3>Ponto turístico não encontrado</h3>

                <p>O ponto turístico que você está procurando não existe ou foi removido.</p>

                <a href="read.php" class="turismo-detalhes-voltar">
                    &#8592; Voltar para pontos turísticos
                </a>

            </div>

        </div>

    </div>

<?php else: ?>

    <?php

    $fotosPonto = $pontoFoto->listarPorPonto($dados['id']);

    $telefoneWhatsApp = preg_replace('/\D/', '', $dados['telefone'] ?? '');

    $totalFotos = count($fotosPonto);

    $enderecoCompleto = trim(
        ($dados['endereco'] ?? '')
            . ', ' . $dados['cidade']
            . (!empty($dados['estado']) ? ' - ' . $dados['estado'] : '')
            . (!empty($dados['cep']) ? ', ' . $dados['cep'] : '')
    );

    ?>

    <div class="turismo-detalhes-container">

        <div class="turismo-detalhes-conteudo">

            <!-- =====================================================
                 VOLTAR
            ====================================================== -->

            <a href="read.php" class="turismo-detalhes-voltar">
                &#8592; Voltar para pontos turísticos
            </a>


            <!-- =====================================================
                 TÍTULO
            ====================================================== -->

            <div class="turismo-detalhes-topo">

                <h1 class="turismo-detalhes-titulo">
                    <?= htmlspecialchars($dados['nome']) ?>
                </h1>

                <span class="turismo-detalhes-local">
                    📍 <?= htmlspecialchars($dados['cidade']) ?><?php if (!empty($dados['estado'])): ?>, <?= htmlspecialchars($dados['estado']) ?><?php endif; ?>
                </span>

            </div>


            <!-- =====================================================
                 GALERIA ESTILO AIRBNB
            ====================================================== -->

            <div class="turismo-detalhes-galeria <?= $totalFotos === 0 ? 'sem-fotos' : ('total-' . min($totalFotos, 5)) ?>">

                <?php if ($totalFotos === 0): ?>

                    <div class="turismo-detalhes-foto turismo-detalhes-foto-principal turismo-imagem-placeholder">
                        <span>Foto não disponível</span>
                    </div>

                <?php else: ?>

                    <?php foreach ($fotosPonto as $indice => $foto): ?>

                        <?php if ($indice >= 5) break; ?>

                        <button type="button"
                            class="turismo-detalhes-foto <?= $indice === 0 ? 'turismo-detalhes-foto-principal' : '' ?>"
                            data-indice="<?= $indice ?>">

                            <img src="<?= $baseUrl ?>/uploads/turismo/<?= htmlspecialchars($foto['caminho']) ?>"
                                alt="<?= htmlspecialchars($dados['nome']) ?>">

                            <?php if ($indice === 4 && $totalFotos > 5): ?>

                                <span class="turismo-detalhes-mais-fotos">
                                    + <?= $totalFotos - 5 ?> fotos
                                </span>

                            <?php endif; ?>

                        </button>

                    <?php endforeach; ?>

                    <?php if ($totalFotos > 1): ?>

                        <button type="button" class="turismo-detalhes-ver-todas">
                            &#128247; Ver todas as fotos
                        </button>

                    <?php endif; ?>

                <?php endif; ?>

            </div>


            <!-- =====================================================
                 CORPO (INFORMAÇÕES + CARD LATERAL)
            ====================================================== -->

            <div class="turismo-detalhes-corpo">

                <!-- ================================
                     COLUNA PRINCIPAL
                ================================= -->

                <div class="turismo-detalhes-principal">

                    <?php if (!empty($dados['descricao'])): ?>

                        <div class="turismo-detalhes-bloco">

                            <h2>Sobre este lugar</h2>

                            <p class="turismo-detalhes-descricao">
                                <?= nl2br(htmlspecialchars($dados['descricao'])) ?>
                            </p>

                        </div>

                        <hr class="turismo-detalhes-separador">

                    <?php endif; ?>

                    <div class="turismo-detalhes-bloco">

                        <h2>Endereço</h2>

                        <p class="turismo-detalhes-endereco">
                            <?= htmlspecialchars($dados['endereco'] ?? '') ?>
                        </p>

                        <?php if (!empty($dados['cep'])): ?>
                            <p class="turismo-detalhes-cep">
                                CEP: <?= htmlspecialchars($dados['cep']) ?>
                            </p>
                        <?php endif; ?>

                    </div>

                    <hr class="turismo-detalhes-separador">

                    <div class="turismo-detalhes-bloco">

                        <h2>Informações</h2>

                        <ul class="turismo-detalhes-comodidades">

                            <?php if (!empty($dados['categoria'])): ?>
                                <li>🏷️ <?= htmlspecialchars($dados['categoria']) ?></li>
                            <?php endif; ?>

                            <?php if (!empty($dados['horario_funcionamento'])): ?>
                                <li>🕒 <?= htmlspecialchars($dados['horario_funcionamento']) ?></li>
                            <?php endif; ?>

                            <li class="<?= $dados['entrada_gratuita'] ? '' : 'indisponivel' ?>">
                                <?= $dados['entrada_gratuita'] ? '✓' : '✕' ?> Entrada Gratuita
                            </li>

                            <li class="<?= $dados['possui_estacionamento'] ? '' : 'indisponivel' ?>">
                                <?= $dados['possui_estacionamento'] ? '✓' : '✕' ?> Estacionamento
                            </li>

                        </ul>

                    </div>

                    <hr class="turismo-detalhes-separador">

                    <div class="turismo-detalhes-bloco">

                        <h2>Localização</h2>

                        <div class="turismo-detalhes-mapa">

                            <iframe
                                src="https://www.google.com/maps?q=<?= urlencode($enderecoCompleto) ?>&output=embed"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                allowfullscreen>
                            </iframe>

                        </div>

                        <a class="turismo-detalhes-mapa-link"
                            href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($enderecoCompleto) ?>"
                            target="_blank" rel="noopener noreferrer">
                            📍 Ver no Google Maps
                        </a>

                    </div>

                </div>


                <!-- ================================
                     CARD LATERAL (CONTATO)
                ================================= -->

                <aside class="turismo-detalhes-lateral">

                    <div class="turismo-detalhes-card-contato">

                        <h3>Entre em contato</h3>

                        <?php if (!empty($dados['telefone'])): ?>

                            <p class="turismo-detalhes-contato-item">
                                📞 <?= htmlspecialchars($dados['telefone']) ?>
                            </p>

                        <?php endif; ?>

                        <?php if (!empty($dados['email'])): ?>

                            <p class="turismo-detalhes-contato-item">
                                ✉️ <?= htmlspecialchars($dados['email']) ?>
                            </p>

                        <?php endif; ?>

                        <?php if (!empty($telefoneWhatsApp)): ?>

                            <a class="turismo-botao turismo-detalhes-botao-whatsapp"
                                href="https://wa.me/55<?= htmlspecialchars($telefoneWhatsApp) ?>"
                                target="_blank" rel="noopener noreferrer">
                                Falar no WhatsApp
                            </a>

                        <?php endif; ?>

                        <?php if ($usuarioMaster): ?>

                            <div class="turismo-acoes turismo-detalhes-acoes">

                                <a href="update.php?id=<?= (int) $dados['id'] ?>" class="turismo-editar">
                                    Editar
                                </a>

                                <a href="delete.php?id=<?= (int) $dados['id'] ?>" class="turismo-excluir"
                                    onclick="return confirm('Deseja realmente excluir este ponto turístico?')">
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

    <div class="turismo-lightbox" id="turismoLightbox">

        <button type="button" class="turismo-lightbox-fechar" aria-label="Fechar">&times;</button>

        <button type="button" class="turismo-lightbox-seta turismo-lightbox-anterior" aria-label="Foto anterior">&#10094;</button>

        <img class="turismo-lightbox-imagem" src="" alt="">

        <button type="button" class="turismo-lightbox-seta turismo-lightbox-proxima" aria-label="Próxima foto">&#10095;</button>

        <span class="turismo-lightbox-contador"></span>

    </div>

    <script>
        window.turismoFotos = <?= json_encode(array_map(function ($f) use ($baseUrl) {
               return $baseUrl . "/uploads/turismo/" . $f['caminho'];
        }, $fotosPonto)) ?>;
    </script>

    <script src="<?= $baseUrl ?>/assets/js/turismo-detalhes.js"></script>

<?php endif; ?>

<?php

include "../../includes/footer.php";

?>