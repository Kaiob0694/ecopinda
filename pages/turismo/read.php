<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../classes/turismo.php";
require_once "../../classes/turismo_fotos.php";

$ponto = new PontoTuristico();
$pontoFoto = new PontoTuristicoFoto();

$dados = $ponto->listar();

include "../../includes/header.php";
include "../../includes/head.php";

?>

<link rel="stylesheet" href="/ecopinda/assets/css/turismo.css">

<div class="turismo-container">

    <div class="turismo-conteudo">

        <!-- =====================================================
             CABEÇALHO
        ====================================================== -->

        <div class="turismo-topo">

            <div>

                <h1 class="turismo-titulo">
                    Pontos Turísticos
                </h1>

                <p class="turismo-subtitulo">
                    Descubra trilhas, mirantes e atrações de
                    Pindamonhangaba e região.
                </p>

            </div>

            <?php if ($usuarioMaster): ?>

                <a href="create.php" class="turismo-botao-cadastrar">
                    + Cadastrar Ponto Turístico
                </a>

            <?php endif; ?>

        </div>


        <!-- =====================================================
             QUANTIDADE DE PONTOS TURÍSTICOS
        ====================================================== -->

        <div class="turismo-quantidade">

            <?php if (!empty($dados)): ?>

                <?= count($dados) ?>

                <?= count($dados) === 1
                    ? 'ponto turístico encontrado'
                    : 'pontos turísticos encontrados'
                    ?>

            <?php else: ?>

                Nenhum ponto turístico encontrado

            <?php endif; ?>

        </div>


        <!-- =====================================================
             GRID DE PONTOS TURÍSTICOS
        ====================================================== -->

        <?php if (!empty($dados)): ?>

            <div class="turismo-grid">

                <?php foreach ($dados as $linha): ?>

                    <?php

                    /*
                     * Busca as fotos do ponto turistico.
                     * Sera utilizada a primeira foto cadastrada.
                     */

                    $fotosPonto =
                        $pontoFoto->listarPorPonto($linha['id']);

                    $primeiraFoto =
                        $fotosPonto[0]['caminho'] ?? null;

                    /*
                     * Prepara o telefone para o WhatsApp.
                     *
                     * Remove:
                     * - espaços
                     * - parênteses
                     * - hífens
                     * - outros caracteres
                     */

                    $telefoneWhatsApp = preg_replace(
                        '/\D/',
                        '',
                        $linha['telefone'] ?? ''
                    );

                    ?>

                    <!-- =================================================
                         CARD DO PONTO TURÍSTICO
                    ================================================== -->

                    <article class="turismo-card turismo-card-clicavel" data-id="<?= (int) $linha['id'] ?>">


                        <!-- =================================================
                             FOTO
                        ================================================== -->

                        <div class="turismo-imagem-container">

                            <?php if (!empty($fotosPonto)): ?>

                                <div class="turismo-galeria">

                                    <?php foreach ($fotosPonto as $indice => $foto): ?>

                                        <img class="turismo-imagem <?= $indice === 0 ? 'ativa' : '' ?>"
                                            src="/ecopinda/uploads/turismo/<?= htmlspecialchars($foto['caminho']) ?>"
                                            alt="<?= htmlspecialchars($linha['nome']) ?>">

                                    <?php endforeach; ?>


                                    <?php if (count($fotosPonto) > 1): ?>

                                        <button type="button" class="galeria-seta galeria-anterior" aria-label="Foto anterior">

                                            &#10094;

                                        </button>

                                        <button type="button" class="galeria-seta galeria-proxima" aria-label="Próxima foto">

                                            &#10095;

                                        </button>

                                        <span class="galeria-contador">

                                            <span class="galeria-atual">
                                                1
                                            </span>

                                            /
                                            <?= count($fotosPonto) ?>

                                        </span>

                                    <?php endif; ?>

                                </div>

                            <?php else: ?>

                                <div class="turismo-imagem-placeholder">

                                    <span>
                                        Foto não disponível
                                    </span>

                                </div>

                            <?php endif; ?>

                        </div>


                        <!-- =================================================
                             INFORMAÇÕES DO PONTO TURÍSTICO
                        ================================================== -->

                        <div class="turismo-info">


                            <!-- =================================================
                                 NOME
                            ================================================== -->

                            <h2 class="turismo-nome">

                                <?= htmlspecialchars($linha['nome']) ?>

                            </h2>


                            <!-- =================================================
                                 CIDADE
                            ================================================== -->

                            <span class="turismo-cidade">

                                📍
                                <?= htmlspecialchars($linha['cidade']) ?>

                            </span>


                            <!-- =================================================
                                 DESCRIÇÃO
                            ================================================== -->

                            <?php if (!empty($linha['descricao'])): ?>

                                <p class="turismo-descricao">

                                    <?= htmlspecialchars($linha['descricao']) ?>

                                </p>

                            <?php endif; ?>


                            <!-- =================================================
                                 ENDEREÇO
                            ================================================== -->

                            <?php if (!empty($linha['endereco'])): ?>

                                <p class="turismo-localizacao">

                                    <?= htmlspecialchars($linha['endereco']) ?>

                                </p>

                            <?php endif; ?>


                            <!-- =================================================
                                 TELEFONE
                            ================================================== -->

                            <?php if (!empty($linha['telefone'])): ?>

                                <p class="turismo-telefone">

                                    📞
                                    <?= htmlspecialchars($linha['telefone']) ?>

                                </p>

                            <?php endif; ?>


                            <!-- =================================================
                                 CARACTERÍSTICAS
                            ================================================== -->

                            <div class="turismo-caracteristicas">


                                <!-- CATEGORIA -->

                                <?php if (!empty($linha['categoria'])): ?>

                                    <span class="turismo-caracteristica">

                                        🏷️
                                        <?= htmlspecialchars($linha['categoria']) ?>

                                    </span>

                                <?php endif; ?>


                                <!-- HORÁRIO -->

                                <?php if (!empty($linha['horario_funcionamento'])): ?>

                                    <span class="turismo-caracteristica">

                                        🕒
                                        <?= htmlspecialchars($linha['horario_funcionamento']) ?>

                                    </span>

                                <?php endif; ?>


                                <!-- ENTRADA GRATUITA -->

                                <?php if ($linha['entrada_gratuita']): ?>

                                    <span class="turismo-caracteristica">

                                        ✓ Entrada Gratuita

                                    </span>

                                <?php endif; ?>


                                <!-- ESTACIONAMENTO -->

                                <?php if ($linha['possui_estacionamento']): ?>

                                    <span class="turismo-caracteristica">

                                        ✓ Estacionamento

                                    </span>

                                <?php endif; ?>


                            </div>


                            <!-- =================================================
                                 RODAPÉ
                            ================================================== -->

                            <div class="turismo-footer">


                                <!-- =================================================
                                     CONTATO / WHATSAPP
                                ================================================== -->

                                <?php if (!empty($telefoneWhatsApp)): ?>

                                    <a class="turismo-botao" href="https://wa.me/55<?= htmlspecialchars($telefoneWhatsApp) ?>"
                                        target="_blank" rel="noopener noreferrer">

                                        Entrar em contato

                                    </a>

                                <?php endif; ?>


                                <!-- =================================================
                                     AÇÕES DO MASTER
                                ================================================== -->

                                <?php if ($usuarioMaster): ?>

                                    <div class="turismo-acoes">


                                        <!-- EDITAR -->

                                        <a href="update.php?id=<?= (int) $linha['id'] ?>" class="turismo-editar">

                                            Editar

                                        </a>


                                        <!-- EXCLUIR -->

                                        <a href="delete.php?id=<?= (int) $linha['id'] ?>" class="turismo-excluir"
                                            onclick="return confirm('Deseja realmente excluir este ponto turístico?')">

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
                 NENHUM PONTO TURÍSTICO CADASTRADO
            ====================================================== -->

            <div class="turismo-vazio">

                <h3>

                    Nenhum ponto turístico cadastrado

                </h3>

                <p>

                    No momento não existem pontos turísticos disponíveis
                    para consulta.

                </p>

            </div>

        <?php endif; ?>


    </div>

</div>


<!-- =====================================================
     JAVASCRIPT DA GALERIA
====================================================== -->

<script src="/ecopinda/assets/js/turismo.js"></script>


<?php

include "../../includes/footer.php";

?>
