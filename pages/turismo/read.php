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

<div class="hoteis-container">

    <div class="hoteis-painel">

        <div class="hoteis-topo">

            <h2 class="hoteis-titulo">
                Lista de Pontos Turísticos
            </h2>

            <a href="create.php" class="botao-novo">
                Cadastrar Novo Ponto Turístico
            </a>

        </div>

        <div class="tabela-wrapper">

            <table class="tabela-hoteis">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Nome</th>
                        <th>Categoria</th>
                        <th>Endereço</th>
                        <th>Cidade</th>
                        <th>Telefone</th>
                        <th>Horário</th>
                        <th>Entrada Gratuita</th>
                        <th>Estacionamento</th>
                        <th>Data de Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($dados as $linha): ?>

                        <tr>

                            <td>
                                <?= $linha['id'] ?>
                            </td>

                            <td>
                                <?php
                                    $fotosPonto = $pontoFoto->listarPorPonto($linha['id']);
                                    $primeiraFoto = $fotosPonto[0]['caminho'] ?? null;
                                ?>

                                <?php if ($primeiraFoto): ?>
                                    <img
                                        src="/ecopinda/uploads/turismo/<?= htmlspecialchars($primeiraFoto) ?>"
                                        alt="Foto do ponto turístico"
                                        width="70">
                                <?php else: ?>
                                    <span class="status-nao">Sem foto</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?= $linha['nome'] ?>
                            </td>

                            <td>
                                <?= $linha['categoria'] ?>
                            </td>

                            <td>
                                <?= $linha['endereco'] ?>
                            </td>

                            <td>
                                <?= $linha['cidade'] ?>
                            </td>

                            <td>
                                <?= $linha['telefone'] ?>
                            </td>

                            <td>
                                <?= $linha['horario_funcionamento'] ?>
                            </td>

                            <td>

                                <?php if ($linha['entrada_gratuita']): ?>

                                    <span class="status-sim">
                                        Sim
                                    </span>

                                <?php else: ?>

                                    <span class="status-nao">
                                        Não
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>

                                <?php if ($linha['possui_estacionamento']): ?>

                                    <span class="status-sim">
                                        Sim
                                    </span>

                                <?php else: ?>

                                    <span class="status-nao">
                                        Não
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>
                                <?= $linha['data_cadastro'] ?>
                            </td>

                            <td>

                                <div class="acoes">

                                    <a
                                        class="editar"
                                        href="update.php?id=<?= $linha['id'] ?>"
                                    >
                                        Editar
                                    </a>

                                    <a
                                        class="excluir"
                                        href="delete.php?id=<?= $linha['id'] ?>"
                                        onclick="return confirm('Deseja realmente excluir este ponto turístico?')"
                                    >
                                        Excluir
                                    </a>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php

include "../../includes/footer.php";

?>
