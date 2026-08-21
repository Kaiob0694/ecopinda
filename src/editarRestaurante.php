<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/conexao.php";


/* =========================
   VERIFICAR ID
========================= */

if (!isset($_GET['id'])) {
    die("ID do restaurante não encontrado.");
}

$id = (int) $_GET['id'];


/* =========================
   ATUALIZAR RESTAURANTE
========================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = mysqli_real_escape_string(
        $conexao,
        $_POST['nome']
    );

    $logradouro = mysqli_real_escape_string(
        $conexao,
        $_POST['logradouro']
    );

    $numero = !empty($_POST['numero'])
        ? (int) $_POST['numero']
        : "NULL";

    $cidade = mysqli_real_escape_string(
        $conexao,
        $_POST['cidade']
    );

    $cep = mysqli_real_escape_string(
        $conexao,
        $_POST['cep']
    );

    $telefone = mysqli_real_escape_string(
        $conexao,
        $_POST['telefone']
    );

    $email = mysqli_real_escape_string(
        $conexao,
        $_POST['email']
    );

    $categoria = mysqli_real_escape_string(
        $conexao,
        $_POST['categoria']
    );

    $horario = mysqli_real_escape_string(
        $conexao,
        $_POST['horario_funcionamento']
    );

    $possui_delivery = isset($_POST['possui_delivery']) ? 1 : 0;

    $possui_wifi = isset($_POST['possui_wifi']) ? 1 : 0;

    // FOTO
    $imagem = $restaurante['imagem'] ?? '';

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {

        $nomeImagem = basename($_FILES['imagem']['name']);

        $nomeImagem = time() . '_' . $nomeImagem;

        $pasta = __DIR__ . '/../uploads/restaurantes/';

        if (!is_dir($pasta)) {
            mkdir($pasta, 0777, true);
        }

        if (move_uploaded_file(
            $_FILES['imagem']['tmp_name'],
            $pasta . $nomeImagem
        )) {
            $imagem = $nomeImagem;
        }
    }





    $sql = "UPDATE restaurante SET

        nome = '$nome',
        logradouro = '$logradouro',
        numero = $numero,
        cidade = '$cidade',
        cep = '$cep',
        telefone = '$telefone',
        email = '$email',
        categoria = '$categoria',
        possui_delivery = $possui_delivery,
        possui_wifi = $possui_wifi,
        imagem = '$imagem',
        horario_funcionamento = '$horario'

        WHERE id = $id";


    if (mysqli_query($conexao, $sql)) {

        header("Location: ../pages/restaurante.php");
        exit();
    } else {

        echo "Erro ao editar restaurante: "
            . mysqli_error($conexao);
    }
}


/* =========================
   BUSCAR RESTAURANTE
========================= */
$id = (int) $_GET['id'];

$sql = "SELECT * FROM restaurante WHERE id = $id";

$resultado = mysqli_query($conexao, $sql);

if (!$resultado || mysqli_num_rows($resultado) == 0) {
    die("Restaurante não encontrado.");
}

$restaurante = mysqli_fetch_assoc($resultado);

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Restaurante</title>

</head>

<body>

    <h1>Editar Restaurante</h1>

    <form method="POST" enctype="multipart/form-data">


        <!-- NOME -->

        <label for="nome">
            Nome:
        </label>

        <input
            type="text"
            id="nome"
            name="nome"
            value="<?= htmlspecialchars($restaurante['nome'] ?? '') ?>"
            required>


        <br><br>


        <!-- LOGRADOURO -->

        <label for="logradouro">
            Logradouro:
        </label>

        <input
            type="text"
            id="logradouro"
            name="logradouro"
            value="<?= htmlspecialchars($restaurante['logradouro'] ?? '') ?>"
            required>


        <br><br>


        <!-- NÚMERO -->

        <label for="numero">
            Número:
        </label>

        <input
            type="number"
            id="numero"
            name="numero"
            value="<?= htmlspecialchars($restaurante['numero'] ?? '') ?>">


        <br><br>


        <!-- CIDADE -->

        <label for="cidade">
            Cidade:
        </label>

        <input
            type="text"
            id="cidade"
            name="cidade"
            value="<?= htmlspecialchars($restaurante['cidade'] ?? '') ?>"
            required>


        <br><br>


        <!-- CEP -->

        <label for="cep">
            CEP:
        </label>

        <input
            type="text"
            id="cep"
            name="cep"
            value="<?= htmlspecialchars($restaurante['cep'] ?? '') ?>">


        <br><br>


        <!-- TELEFONE -->

        <label for="telefone">
            Telefone:
        </label>

        <input
            type="text"
            id="telefone"
            name="telefone"
            value="<?= htmlspecialchars($restaurante['telefone'] ?? '') ?>">


        <br><br>


        <!-- EMAIL -->

        <label for="email">
            E-mail:
        </label>

        <input
            type="email"
            id="email"
            name="email"
            value="<?= htmlspecialchars($restaurante['email'] ?? '') ?>">


        <br><br>


        <!-- CATEGORIA -->

        <label for="categoria">
            Categoria:
        </label>

        <input
            type="text"
            id="categoria"
            name="categoria"
            value="<?= htmlspecialchars($restaurante['categoria'] ?? '') ?>">


        <br><br>


        <!-- HORÁRIO -->

        <label for="horario_funcionamento">
            Horário de funcionamento:
        </label>

        <input
            type="text"
            id="horario_funcionamento"
            name="horario_funcionamento"
            value="<?= htmlspecialchars($restaurante['horario_funcionamento'] ?? '') ?>">


        <br><br>


        <!-- DELIVERY -->

        <label>

            <input
                type="checkbox"
                name="possui_delivery"
                value="1"

                <?php
                if (!empty($restaurante['possui_delivery'])) {
                    echo 'checked';
                }
                ?>>

            Possui Delivery

        </label>


        <br><br>


        <!-- WI-FI -->

        <label>

            <input
                type="checkbox"
                name="possui_wifi"
                value="1"

                <?php
                if (!empty($restaurante['possui_wifi'])) {
                    echo 'checked';
                }
                ?>>

            Possui Wi-Fi

        </label>

        <br><br>

        <?php if (!empty($restaurante['imagem'])): ?>

            <p>Imagem atual:</p>

            <img
                src="../uploads/restaurantes/<?= htmlspecialchars($restaurante['imagem']) ?>"
                alt="Foto do restaurante"
                width="250">

            <br><br>

        <?php endif; ?>

        <label for="imagem">
            Foto do restaurante:
        </label>

        <br>

        <input
            type="file"
            id="imagem"
            name="imagem"
            accept="image/*">

        <br><br>


        <!-- BOTÕES -->

        <button type="submit">
            Salvar alterações
        </button>


        <a href="../pages/restaurante.php">
            Cancelar
        </a>


    </form>

</body>

</html>