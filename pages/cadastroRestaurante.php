<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start();

require_once __DIR__ . "/../src/conexao.php";

global $conexao;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitização básica
    $nome = trim($_POST['nome']);
    $logradouro = trim($_POST['logradouro']);
    $numero = trim($_POST['numero']);
    $cidade = trim($_POST['cidade']);
    $cep = trim($_POST['cep']);
    $telefone = trim($_POST['telefone']);
    $email = trim($_POST['email']);
    $categoria = trim($_POST['categoria']);
    $possui_delivery = trim($_POST['possui_delivery']);
    $possui_wifi = trim($_POST['possui_wifi']);
    $horario_funcionamento = trim($_POST['horario_funcionamento']);
    $imagem = '';

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {

    $pasta = __DIR__ . "/../assets/img/img/Gastronomia/";

    if (!is_dir($pasta)) {
        mkdir($pasta, 0777, true);
    }

    $extensao = strtolower(
        pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION)
    );

    $nomeArquivo = pathinfo(
        $_FILES['foto']['name'],
        PATHINFO_FILENAME
    );

    $nomeArquivo = preg_replace(
        '/[^a-zA-Z0-9_-]/',
        '',
        $nomeArquivo
    );

    $nomeArquivo = $nomeArquivo . "." . $extensao;

    $destino = $pasta . $nomeArquivo;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {

        $imagem = "/assets/img/img/Gastronomia/" . $nomeArquivo;

    } else {

        die("Erro ao salvar a imagem.");
    }
}

    // SQL com prepared statement
    $sql = "INSERT INTO restaurante 
    (nome, logradouro, numero, cidade, cep, telefone, email, categoria, possui_delivery, possui_wifi, horario_funcionamento, imagem)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conexao, $sql);

    if ($stmt) {

        mysqli_stmt_bind_param(
            $stmt,
            "ssssssssssss",
            $nome,
            $logradouro,
            $numero,
            $cidade,
            $cep,
            $telefone,
            $email,
            $categoria,
            $possui_delivery,
            $possui_wifi,
            $horario_funcionamento,
            $imagem
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: restaurante.php");
            exit;
        } else {
            echo "Erro ao cadastrar restaurante: " . mysqli_error($conexao);
        }

        mysqli_stmt_close($stmt);

    } else {
        echo "Erro na preparação da query: " . mysqli_error($conexao);
    }

} else {
    echo "Acesso inválido.";
}

mysqli_close($conexao);
?>