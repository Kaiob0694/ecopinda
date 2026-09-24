<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../classes/rotas.php";
require_once "../../classes/ponto_turistico.php";

$rota = new Rotas();
$ponto = new PontoTuristico();

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST["nome"]);
    $descricao = trim($_POST["descricao"]);
    $categoria = $_POST["categoria"];
    $dificuldade = $_POST["dificuldade"];
    $duracao = trim($_POST["duracao"]);
    $transporte = $_POST["transporte"];

    $pontos = $_POST["pontos"] ?? [];

    if (empty($nome)) {

        $mensagem = "Informe o nome da rota.";

    } elseif (empty($pontos)) {

        $mensagem = "Selecione pelo menos um ponto turístico.";

    } else {

        $rotaId = $rota->criar(
            $nome,
            $descricao,
            $categoria,
            $dificuldade,
            $duracao,
            $transporte
        );

        foreach ($pontos as $ordem => $pontoId) {

            $rota->adicionarPonto(
                $rotaId,
                $pontoId,
                $ordem + 1
            );
        }

        header("Location: detalhes.php?id=" . $rotaId);
        exit;
    }
}

$pontosTuristicos = $ponto->listar();

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Criar rota - PindaEco</title>

    <link
        rel="stylesheet"
        href="../../assets/css/rotas.css"
    >

</head>

<body>

<div class="rotas-container">

    <div class="rotas-header">

        <h1>Nova rota</h1>

        <a
            href="rotas.php"
            class="btn-voltar"
        >
            ← Voltar
        </a>

    </div>

    <?php if (!empty($mensagem)): ?>

        <div class="mensagem">
            <?= htmlspecialchars($mensagem) ?>
        </div>

    <?php endif; ?>


    <form method="POST" class="form-rota">

        <div class="campo">

            <label>Nome da rota</label>

            <input
                type="text"
                name="nome"
                placeholder="Ex: Rota Histórica de Pindamonhangaba"
                required
            >

        </div>


        <div class="campo">

            <label>Descrição</label>

            <textarea
                name="descricao"
                rows="5"
                placeholder="Descreva a experiência..."
            ></textarea>

        </div>


        <div class="form-grid">

            <div class="campo">

                <label>Categoria</label>

                <select name="categoria">

                    <option value="Natureza">
                        Natureza
                    </option>

                    <option value="História">
                        História
                    </option>

                    <option value="Gastronomia">
                        Gastronomia
                    </option>

                    <option value="Família">
                        Família
                    </option>

                    <option value="Cultura">
                        Cultura
                    </option>

                </select>

            </div>


            <div class="campo">

                <label>Dificuldade</label>

                <select name="dificuldade">

                    <option value="Fácil">
                        Fácil
                    </option>

                    <option value="Moderada">
                        Moderada
                    </option>

                    <option value="Difícil">
                        Difícil
                    </option>

                </select>

            </div>


            <div class="campo">

                <label>Duração</label>

                <input
                    type="text"
                    name="duracao"
                    placeholder="Ex: 2h 30min"
                >

            </div>


            <div class="campo">

                <label>Transporte</label>

                <select name="transporte">

                    <option value="Caminhada">
                        🚶 Caminhada
                    </option>

                    <option value="Bicicleta">
                        🚲 Bicicleta
                    </option>

                    <option value="Carro">
                        🚗 Carro
                    </option>

                </select>

            </div>

        </div>


        <h2>Pontos da rota</h2>

        <p class="ajuda">
            Selecione os pontos na ordem em que o visitante deverá passar.
        </p>


        <div class="lista-pontos">

            <?php foreach ($pontosTuristicos as $p): ?>

                <label class="ponto-item">

                    <input
                        type="checkbox"
                        name="pontos[]"
                        value="<?= $p["id"] ?>"
                    >

                    <span>

                        <?= htmlspecialchars($p["nome"]) ?>

                    </span>

                </label>

            <?php endforeach; ?>

        </div>


        <button
            type="submit"
            class="btn-criar"
        >

            Criar rota

        </button>

    </form>

</div>

</body>

</html>