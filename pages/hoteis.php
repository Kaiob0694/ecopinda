<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../classe/hoteis.php";

$hotel = new Hotel();
$dados = $hotel->listar();

include "../includes/head.php";
include "../includes/header.php";

?>

<link rel="stylesheet" href="../assets/css/style_hoteis.css">

<div class="container">

    <div class="hoteis-container">

        <h2 class="titulo">Lista de Hotéis</h2>

        <div class="hoteis-topo">

            <h2 class="hoteis-titulo">
                Lista de Hotéis
            </h2>

        </div>

        <div class="tabela-wrapper">

            <table class="tabela-hoteis">

                <thead>
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
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($dados as $linha): ?>

                        <tr>

                            <td>
                                <?= htmlspecialchars($linha['id']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($linha['nome']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($linha['endereco']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($linha['cidade']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($linha['estado']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($linha['cep']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($linha['telefone']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($linha['email']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($linha['quantidade_quartos']) ?>
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

                                <div class="acoes">

                                    <a
                                        class="editar"
                                        href="atualizarhotel.php?id=<?= $linha['id'] ?>"
                                    >
                                        Editar
                                    </a>

                                    <a
                                        class="excluir"
                                        href="hoteis/delete.php?id=<?= $linha['id'] ?>"
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

include "../includes/footer.php";

?>
