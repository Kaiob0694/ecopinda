<?php 


error_reporting(E_ALL);
ini_set('display_errors',1); 


require_once "../../classes/hoteis.php";

$hotel = new Hotel();
$dados = $hotel->listar();

include "../../includes/header.php";
include "../../includes/head.php";

?>

<div class="container">

    <div class="card">

        <h2>Lista de Hotéis</h2>

        <a href="create.php" class="botao-novo">
            Cadastrar Novo Hotel
        </a>

        <br><br>


    <table border="1">

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

            </tr>

    <?php foreach ($dados as $linha): ?>

        <tr>

            <td><?= $linha['id_hoteis'] ?></td>
            <td><?= $linha['nome'] ?></td>
            <td><?= $linha['endereco'] ?></td>
            <td><?= $linha['cidade'] ?></td>
            <td><?= $linha['cep'] ?></td>
            <td><?= $linha['telefone'] ?></td>
            <td><?= $linha['email'] ?></td>
            <td><?= $linha['quantidade_quartos'] ?></td>
            <td><?= $linha['possui_wifi'] ? 'Sim' : 'Não' ?></td>
            <td><?= $linha['possui_estacionamento'] ? 'Sim' : 'Não' ?></td>
            <td><?= $linha['data_cadastro'] ?></td>

           <td>

         <a class="editar"
         href="editar.php?id=<?= $linha['id_hoteis'] ?>">
         Editar
            </a>

         <a class="excluir"
          href="excluir.php?id=<?= $linha['id_hoteis'] ?>"
          onclick="return confirm('Deseja realmente excluir este hotel?')">
         Excluir
            </a>

</td>

                </tr>

            <?php endforeach; ?>

        </table>
    </div>

    <?php

    include "../../includes/footer.php";








         







         

     