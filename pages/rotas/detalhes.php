<?php

require_once "../../classes/rotas.php";

$rotas = new Rotas();

$id = filter_input(
    INPUT_GET,
    "id",
    FILTER_VALIDATE_INT
);

if (!$id) {
    die("Rota inválida.");
}

$rota = $rotas->buscar($id);

if (!$rota) {
    die("Rota não encontrada.");
}

$pontos = $rotas->pontosDaRota($id);

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= htmlspecialchars($rota["nome"]) ?> - PindaEco
    </title>


    <link
        rel="stylesheet"
        href="../../assets/css/rotas.css"
    >


    <!-- Leaflet -->

    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    >

</head>

<body>


<div class="detalhes-rota">


    <div class="topo-detalhes">

        <a href="rotas.php">
            ← Voltar para rotas
        </a>

    </div>


    <section class="cabecalho-rota">


        <span class="categoria">

            <?= htmlspecialchars($rota["categoria"]) ?>

        </span>


        <h1>

            <?= htmlspecialchars($rota["nome"]) ?>

        </h1>


        <p>

            <?= nl2br(
                htmlspecialchars($rota["descricao"])
            ) ?>

        </p>


        <div class="dados-rota">

            <div>
                ⏱️
                <strong>
                    <?= htmlspecialchars($rota["duracao"]) ?>
                </strong>
            </div>

            <div>
                🚶
                <strong>
                    <?= htmlspecialchars($rota["dificuldade"]) ?>
                </strong>
            </div>

            <div>
                🚗
                <strong>
                    <?= htmlspecialchars($rota["transporte"]) ?>
                </strong>
            </div>

        </div>


    </section>


    <section class="rota-layout">


        <div class="pontos-rota">


            <h2>
                Seu roteiro
            </h2>


            <?php foreach ($pontos as $index => $ponto): ?>


                <div class="ponto-rota">


                    <div class="numero-ponto">

                        <?= $index + 1 ?>

                    </div>


                    <div>

                        <h3>

                            <?= htmlspecialchars(
                                $ponto["nome"]
                            ) ?>

                        </h3>


                        <span>
                            Ponto <?= $index + 1 ?>
                        </span>

                    </div>


                </div>


            <?php endforeach; ?>


        </div>


        <div
            id="mapa"
            class="mapa"
        ></div>


    </section>


</div>


<script
    src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
></script>


<script>

const pontos = <?= json_encode(
    $pontos,
    JSON_UNESCAPED_UNICODE
) ?>;


if (pontos.length > 0) {


    const primeiro = pontos[0];


    const mapa = L.map("mapa").setView(
        [
            parseFloat(primeiro.latitude),
            parseFloat(primeiro.longitude)
        ],
        14
    );


    L.tileLayer(
        "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
        {
            attribution:
                '&copy; OpenStreetMap contributors'
        }
    ).addTo(mapa);


    const coordenadas = [];


    pontos.forEach(function (ponto, index) {


        const latitude =
            parseFloat(ponto.latitude);


        const longitude =
            parseFloat(ponto.longitude);


        const coordenada = [
            latitude,
            longitude
        ];


        coordenadas.push(coordenada);


        L.marker(coordenada)
            .addTo(mapa)
            .bindPopup(
                "<strong>" +
                (index + 1) +
                ". " +
                ponto.nome +
                "</strong>"
            );

    });


    if (coordenadas.length > 1) {


        L.polyline(
            coordenadas,
            {
                weight: 5
            }
        ).addTo(mapa);


    }


    mapa.fitBounds(coordenadas);

}

</script>


</body>

</html>