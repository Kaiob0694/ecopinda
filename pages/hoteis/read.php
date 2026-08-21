<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../classes/hoteis.php";

$hotel = new Hotel();
$dados = $hotel->listar();

include "../../includes/header.php";
include "../../includes/head.php";
?>

<link rel="stylesheet" href="/ecopinda/assets/css/hotel.css">

<div class="hoteis-container">

    <div class="hoteis-painel">

        <div class="hoteis-topo">

            <h2 class="hoteis-titulo">
                Lista de Hotéis
            </h2>

            <a href="create.php" class="botao-novo">
                Cadastrar Novo Hotel
            </a>

        </div>

        <div class="tabela-wrapper">

            <table class="tabela-hoteis">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Endereço</th>
                        <th>Cidade</th>
                        <th>CEP</th>
                        <th>Telefone</th>
                        <th>Email</th>
                        <th>Quantidade de Quartos</th>
                        <th>Possui Wi-Fi</th>
                        <th>Possui Estacionamento</th>
                        <th>Data de Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($dados as $linha): ?>

                        <tr>

                            <td>
                                <?= $linha['id_hoteis'] ?>
                            </td>

                            <td>
                                <?= $linha['nome'] ?>
                            </td>

                            <td>
                                <?= $linha['endereco'] ?>
                            </td>

                            <td>
                                <?= $linha['cidade'] ?>
                            </td>

                            <td>
                                <?= $linha['cep'] ?>
                            </td>

                            <td>
                                <?= $linha['telefone'] ?>
                            </td>

                            <td>
                                <?= $linha['email'] ?>
                            </td>

                            <td>
                                <?= $linha['quantidade_quartos'] ?>
                            </td>

                            <td>

                                <?php if ($linha['possui_wifi']): ?>

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
                                        href="editar.php?id=<?= $linha['id_hoteis'] ?>"
                                    >
                                        Editar
                                    </a>

                                    <a
                                        class="excluir"
                                        href="excluir.php?id=<?= $linha['id_hoteis'] ?>"
                                        onclick="return confirm('Deseja realmente excluir este hotel?')"
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