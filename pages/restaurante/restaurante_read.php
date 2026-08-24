<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../classes/restaurante.php";
require_once "../../classes/restaurante_fotos.php";

$restaurante = new Restaurante();
$restauranteFoto = new RestauranteFoto();

$dados = $restaurante->listar();

include "../../includes/header.php";
include "../../includes/head.php";

?>

<link rel="stylesheet" href="/ecopinda/assets/css/restaurante.css">

<div class="hoteis-container">

    <div class="hoteis-conteudo">

        <!-- =====================================================
             CABEÇALHO
        ====================================================== -->

        <div class="hoteis-topo">

            <div>

                <h1 class="hoteis-titulo">
                    Restaurantes
                </h1>

                <p class="hoteis-subtitulo">
                    Encontre os melhores Restaurantes de
                    Pindamonhangaba e região.
                </p>

            </div>

        </div>


        <!-- =====================================================
             QUANTIDADE DE HOTÉIS
        ====================================================== -->

        <div class="hoteis-quantidade">

            <?php if (!empty($dados)): ?>

                <?= count($dados) ?>

                <?= count($dados) === 1
                    ? 'hotel encontrado'
                    : 'hotéis encontrados'
                ?>

            <?php else: ?>

                Nenhum Restaurante encontrado

            <?php endif; ?>

        </div>


        <!-- =====================================================
             GRID DE HOTÉIS
        ====================================================== -->

        <?php if (!empty($dados)): ?>

            <div class="hoteis-grid">

                <?php foreach ($dados as $linha): ?>

                    <?php

                    /*
                     * Busca as fotos do hotel.
                     * Será utilizada a primeira foto cadastrada.
                     */

                    $fotosRestaurante =
                        $hotelFoto->listarPorRestaurante($linha['id']);

                    $primeiraFoto =
                        $fotosRestaurante[0]['caminho'] ?? null;

                    ?>


                    <!-- =================================================
                         CARD DO HOTEL
                    ================================================== -->

                    <article class="hotel-card">


                        <!-- =================================================
                             FOTO
                        ================================================== -->

                        <div class="hotel-imagem-container">

                            <?php if (!empty($fotosRestaurante)): ?>

                                <div class="hotel-galeria">

                                    <?php foreach ($fotosRestaurante as $indice => $foto): ?>

                                        <img
                                            class="hotel-imagem <?= $indice === 0 ? 'ativa' : '' ?>"
                                            src="/ecopinda/uploads/restaurante/<?= htmlspecialchars($foto['caminho']) ?>"
                                            alt="<?= htmlspecialchars($linha['nome']) ?>">

                                    <?php endforeach; ?>


                                    <?php if (count($fotosRestaurante) > 1): ?>

                                        <button
                                            type="button"
                                            class="galeria-seta galeria-anterior"
                                            aria-label="Foto anterior">
                                            &#10094;
                                        </button>

                                        <button
                                            type="button"
                                            class="galeria-seta galeria-proxima"
                                            aria-label="Próxima foto">
                                            &#10095;
                                        </button>

                                        <span class="galeria-contador">

                                            <span class="galeria-atual">1</span>

                                            /
                                            <?= count($fotosrestaurante) ?>

                                        </span>

                                    <?php endif; ?>

                                </div>

                            <?php else: ?>

                                <div class="hotel-imagem-placeholder">

                                    <span>
                                        Foto não disponível
                                    </span>

                                </div>

                            <?php endif; ?>

                        </div>


                        <!-- =================================================
                             INFORMAÇÕES DO HOTEL
                        ================================================== -->

                        <div class="hotel-info">


                            <!-- =================================================
                                 NOME
                            ================================================== -->

                            <h2 class="hotel-nome">

                                <?= htmlspecialchars($linha['nome']) ?>

                            </h2>


                            <!-- =================================================
                                 CIDADE
                            ================================================== -->

                            <span class="hotel-cidade">

                                📍
                                <?= htmlspecialchars($linha['cidade']) ?>

                            </span>


                            <!-- =================================================
                                 ENDEREÇO
                            ================================================== -->

                            <?php if (!empty($linha['endereco'])): ?>

                                <p class="hotel-localizacao">

                                    <?= htmlspecialchars($linha['endereco']) ?>

                                </p>

                            <?php endif; ?>


                            <!-- =================================================
                                 TELEFONE
                            ================================================== -->

                            <?php if (!empty($linha['telefone'])): ?>

                                <p class="hotel-telefone">

                                    📞
                                    <?= htmlspecialchars($linha['telefone']) ?>

                                </p>

                            <?php endif; ?>


                            <!-- =================================================
                                 CARACTERÍSTICAS
                            ================================================== -->

                            <div class="hotel-caracteristicas">


                                <!-- WI-FI -->

                                <?php if ($linha['possui_wifi']): ?>

                                    <span class="hotel-caracteristica">

                                        ✓ Wi-Fi

                                    </span>

                                <?php endif; ?>


                                <!-- ESTACIONAMENTO -->

                                <?php if ($linha['possui_estacionamento']): ?>

                                    <span class="hotel-caracteristica">

                                        ✓ Estacionamento

                                    </span>

                                <?php endif; ?>


                                <!-- QUARTOS -->

                                <?php if (!empty($linha['quantidade_quartos'])): ?>

                                    <span class="hotel-caracteristica">

                                        🛏️

                                        <?= htmlspecialchars(
                                            $linha['quantidade_quartos']
                                        ) ?>

                                        quartos

                                    </span>

                                <?php endif; ?>


                            </div>


                            <!-- =================================================
                                 RODAPÉ
                            ================================================== -->

                            <div class="hotel-footer">


                                <!-- =================================================
                                     CONTATO
                                ================================================== -->

                                <?php if (!empty($linha['telefone'])): ?>

                                    <a
                                        class="hotel-botao"
                                        href="tel:<?= htmlspecialchars($linha['telefone']) ?>">

                                        Entrar em contato

                                    </a>

                                <?php endif; ?>


                                <!-- =================================================
                                     AÇÕES DO MASTER
                                ================================================== -->

                                <?php if ($usuarioMaster): ?>

                                    <div class="hotel-acoes">

                                        <!-- EDITAR -->

                                        <a
                                            href="update.php?id=<?= (int) $linha['id'] ?>"
                                            class="hotel-editar">

                                            Editar

                                        </a>


                                        <!-- EXCLUIR -->

                                        <a
                                            href="delete.php?id=<?= (int) $linha['id'] ?>"
                                            class="hotel-excluir"
                                            onclick="return confirm('Deseja realmente excluir este hotel?')">

                                            Excluir

                                        </a>

                                    </div>

                                <?php endif; ?>


                            </div>


                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php else: ?>


            <!-- =====================================================
                 NENHUM HOTEL CADASTRADO
            ====================================================== -->

            <div class="hoteis-vazio">

                <h3>

                    Nenhum restaurante cadastrado

                </h3>

                <p>

                    No momento não existem restaurante disponíveis
                    para consulta.

                </p>

            </div>

        <?php endif; ?>


    </div>

</div>


<script src="/ecopinda/assets/js/hoteis.js"></script>

<?php

include "../../includes/footer.php";

?>