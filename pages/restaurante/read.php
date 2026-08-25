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

<div class="restaurantes-container">

    <div class="restaurantes-conteudo">

        <!-- =====================================================
             CABEÇALHO
        ====================================================== -->

        <div class="restaurantes-topo">

            <div>

                <h1 class="restaurantes-titulo">
                    Restaurantes
                </h1>

                <p class="restaurantes-subtitulo">
                    Encontre os melhores restaurantes de
                    Pindamonhangaba e região.
                </p>

            </div>

        </div>


        <!-- =====================================================
             QUANTIDADE DE RESTAURANTES
        ====================================================== -->

        <div class="restaurantes-quantidade">

            <?php if (!empty($dados)): ?>

                <?= count($dados) ?>

                <?= count($dados) === 1
                    ? 'restaurante encontrado'
                    : 'restaurantes encontrados'
                ?>

            <?php else: ?>

                Nenhum restaurante encontrado

            <?php endif; ?>

        </div>


        <!-- =====================================================
             GRID DE RESTAURANTES
        ====================================================== -->

        <?php if (!empty($dados)): ?>

            <div class="restaurantes-grid">

                <?php foreach ($dados as $linha): ?>

                    <?php

                    /*
                     * Busca as fotos do restaurante.
                     * Será utilizada a primeira foto cadastrada.
                     */

                    $fotosRestaurante =
                        $restauranteFoto->listarPorRestaurante($linha['id']);

                    $primeiraFoto =
                        $fotosRestaurante[0]['caminho'] ?? null;

                    ?>


                    <!-- =================================================
                         CARD DO RESTAURANTE
                    ================================================== -->

                    <article class="restaurante-card">


                        <!-- =================================================
                             FOTO
                        ================================================== -->

                        <div class="restaurante-imagem-container">

                            <?php if (!empty($fotosRestaurante)): ?>

                                <div class="restaurante-galeria">

                                    <?php foreach ($fotosRestaurante as $indice => $foto): ?>

                                        <img
                                            class="restaurante-imagem <?= $indice === 0 ? 'ativa' : '' ?>"
                                            src="/ecopinda/uploads/restaurantes/<?= htmlspecialchars($foto['caminho']) ?>"
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
                                            <?= count($fotosRestaurante) ?>

                                        </span>

                                    <?php endif; ?>

                                </div>

                            <?php else: ?>

                                <div class="restaurante-imagem-placeholder">

                                    <span>
                                        Foto não disponível
                                    </span>

                                </div>

                            <?php endif; ?>

                        </div>


                        <!-- =================================================
                             INFORMAÇÕES DO RESTAURANTE
                        ================================================== -->

                        <div class="restaurante-info">


                            <!-- =================================================
                                 NOME
                            ================================================== -->

                            <h2 class="restaurante-nome">

                                <?= htmlspecialchars($linha['nome']) ?>

                            </h2>


                            <!-- =================================================
                                 CIDADE
                            ================================================== -->

                            <span class="restaurante-cidade">

                                📍
                                <?= htmlspecialchars($linha['cidade']) ?>

                            </span>


                            <!-- =================================================
                                 ENDEREÇO
                            ================================================== -->

                            <?php if (!empty($linha['logradouro'])): ?>

                                <p class="restaurante-localizacao">

                                    <?= htmlspecialchars($linha['logradouro']) ?>

                                    <?php if (!empty($linha['numero'])): ?>
                                        , <?= htmlspecialchars($linha['numero']) ?>
                                    <?php endif; ?>

                                </p>

                            <?php endif; ?>


                            <!-- =================================================
                                 TELEFONE
                            ================================================== -->

                            <?php if (!empty($linha['telefone'])): ?>

                                <p class="restaurante-telefone">

                                    📞
                                    <?= htmlspecialchars($linha['telefone']) ?>

                                </p>

                            <?php endif; ?>


                            <!-- =================================================
                                 CARACTERÍSTICAS
                            ================================================== -->

                            <div class="restaurante-caracteristicas">


                                <!-- WI-FI -->

                                <?php if ($linha['possui_wifi']): ?>

                                    <span class="restaurante-caracteristica">

                                        ✓ Wi-Fi

                                    </span>

                                <?php endif; ?>


                                <!-- DELIVERY -->

                                <?php if ($linha['possui_delivery']): ?>

                                    <span class="restaurante-caracteristica">

                                        ✓ Delivery

                                    </span>

                                <?php endif; ?>


                                <!-- CATEGORIA -->

                                <?php if (!empty($linha['categoria'])): ?>

                                    <span class="restaurante-caracteristica">

                                        🍽️

                                        <?= htmlspecialchars($linha['categoria']) ?>

                                    </span>

                                <?php endif; ?>


                                <!-- HORÁRIO DE FUNCIONAMENTO -->

                                <?php if (!empty($linha['horario_funcionamento'])): ?>

                                    <span class="restaurante-caracteristica">

                                        🕒

                                        <?= htmlspecialchars($linha['horario_funcionamento']) ?>

                                    </span>

                                <?php endif; ?>


                            </div>


                            <!-- =================================================
                                 RODAPÉ
                            ================================================== -->

                            <div class="restaurante-footer">


                                <!-- =================================================
                                     CONTATO
                                ================================================== -->

                                <?php if (!empty($linha['telefone'])): ?>

                                    <a
                                        class="restaurante-botao"
                                        href="tel:<?= htmlspecialchars($linha['telefone']) ?>">

                                        Entrar em contato

                                    </a>

                                <?php endif; ?>


                                <!-- =================================================
                                     AÇÕES DO MASTER
                                ================================================== -->

                                <?php if ($usuarioMaster): ?>

                                    <div class="restaurante-acoes">

                                        <!-- EDITAR -->

                                        <a
                                            href="update.php?id=<?= (int) $linha['id'] ?>"
                                            class="restaurante-editar">

                                            Editar

                                        </a>


                                        <!-- EXCLUIR -->

                                        <a
                                            href="delete.php?id=<?= (int) $linha['id'] ?>"
                                            class="restaurante-excluir"
                                            onclick="return confirm('Deseja realmente excluir este restaurante?')">

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
                 NENHUM RESTAURANTE CADASTRADO
            ====================================================== -->

            <div class="restaurantes-vazio">

                <h3>

                    Nenhum restaurante cadastrado

                </h3>

                <p>

                    No momento não existem restaurantes disponíveis
                    para consulta.

                </p>

            </div>

        <?php endif; ?>


    </div>

</div>


<script src="/ecopinda/assets/js/restaurante.js"></script>

<?php

include "../../includes/footer.php";

?>
