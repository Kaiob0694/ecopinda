<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../classes/rotas.php";
include "../../includes/header.php";
include "../../includes/head.php";
$baseUrl = 'https://pindaeco.rf.gd';

$rotas = new Rotas();

$lista = $rotas->listar();

?>



<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Rotas - PindaEco</title>

    <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/rotas.css?v=<?= time(); ?>">

</head>

<body>


    <div class="pagina-rotas">


        <header class="hero-rotas">

            <div>

                <span class="tag">
                    PINDAECO
                </span>

                <h1>
                    Descubra novas rotas
                </h1>

                <p>
                    Explore experiências, lugares e histórias
                    de Pindamonhangaba.
                </p>

            </div>

        </header>


        <div class="conteudo-rotas">


            <aside class="filtros">

                <h3>
                    Filtrar rotas
                </h3>


                <input type="text" id="buscarRota" placeholder="🔎 Buscar rota...">


                <h4>
                    Categoria
                </h4>

                <button class="filtro">
                    Todas
                </button>

                <button class="filtro">
                    🌳 Natureza
                </button>

                <button class="filtro">
                    🏛️ História
                </button>

                <button class="filtro">
                    🍽️ Gastronomia
                </button>

                <button class="filtro">
                    👨‍👩‍👧 Família
                </button>

            </aside>


            <main class="lista-rotas">


                <div class="titulo-lista">

                    <h2>
                        Rotas em Pindamonhangaba
                    </h2>

                    <a href="create.php" class="btn-nova-rota">
                        + Nova rota
                    </a>

                </div>


                <div class="grid-rotas">


                    <?php foreach ($lista as $rota): ?>


                        <article class="card-rota">


                            <div class="card-imagem">

                                <?php if (!empty($rota["imagem"])): ?>

                                    <img src="../../<?= htmlspecialchars($rota["imagem"]) ?>"
                                        alt="<?= htmlspecialchars($rota["nome"]) ?>">

                                <?php else: ?>

                                    <div class="sem-imagem">
                                        🗺️
                                    </div>

                                <?php endif; ?>

                            </div>


                            <div class="card-conteudo">


                                <span class="categoria">

                                    <?= htmlspecialchars($rota["categoria"]) ?>

                                </span>


                                <h3>

                                    <?= htmlspecialchars($rota["nome"]) ?>

                                </h3>


                                <p>

                                    <?= htmlspecialchars(
                                        mb_strimwidth(
                                            $rota["descricao"],
                                            0,
                                            120,
                                            "..."
                                        )
                                    ) ?>

                                </p>


                                <div class="informacoes">

                                    <span>
                                        ⏱️ <?= htmlspecialchars($rota["duracao"]) ?>
                                    </span>

                                    <span>
                                        🚶 <?= htmlspecialchars($rota["dificuldade"]) ?>
                                    </span>

                                </div>


                                <a href="detalhes.php?id=<?= $rota["id"] ?>" class="btn-ver-rota">

                                    Ver rota

                                </a>


                            </div>


                        </article>


                    <?php endforeach; ?>


                </div>


            </main>


        </div>


    </div>


    <script>

        const campo = document.getElementById("buscarRota");

        campo.addEventListener("input", function () {

            const busca = this.value.toLowerCase();

            document
                .querySelectorAll(".card-rota")
                .forEach(function (card) {

                    const texto =
                        card.innerText.toLowerCase();

                    card.style.display =
                        texto.includes(busca)
                            ? ""
                            : "none";

                });

        });

    </script>


</body>

</html>