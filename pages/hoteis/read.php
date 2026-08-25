<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../classes/hoteis.php";

$hotel = new Hotel();
$dados = $hotel->listar();

include "../../includes/head.php";
include "../../includes/header.php";

?>
<link rel="stylesheet" href="../../assets/css/style_hoteis.css">
<div class="container">

<div class="hoteis-container">

        <h2 class="titulo">Lista de Hotéis</h2>

        <div class="hoteis-topo">

            <h2 class="hoteis-titulo">
                Lista de Hotéis
            </h2>


    <table border="1">

        <tr>

            <th>ID</th>
            <th>Nome</th>
            <th>Endereço</th>
            <th>Cidade</th>
            <th>Estado</th>
            <th>CEP</th>
            <th>Telefone</th>
            <th>Email</th>
            <th>Quantidade de Quartos</th>
            <th>Possui Wi-Fi</th>
            <th>Possui Estacionamento</th>
            <th>Data de Cadastro</th>

            </tr>

    <?php foreach ($dados as $linha): ?>

        <tr>

            <td><?= $linha['id'] ?></td>
            <td><?= $linha['nome'] ?></td>
            <td><?= $linha['endereco'] ?></td>
            <td><?= $linha['cidade'] ?></td>
            <td><?= $linha['estado'] ?></td>
            <td><?= $linha['cep'] ?></td>
            <td><?= $linha['telefone'] ?></td>
            <td><?= $linha['email'] ?></td>
            <td><?= $linha['quantidade_quartos'] ?></td>
            <td><?= $linha['possui_wifi'] ? 'Sim' : 'Não' ?></td>
            <td><?= $linha['possui_estacionamento'] ? 'Sim' : 'Não' ?></td>
            <td><?= $linha['data_cadastro'] ?></td>

           <td>

         <a class="editar"
         href="update.php?id=<?= $linha['id'] ?>">
         Editar
            </a>

         <a class="excluir"
          href="delete.php?id=<?= $linha['id'] ?>"
          onclick="return confirm('Deseja realmente excluir este hotel?')">
         Excluir
            </a>

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