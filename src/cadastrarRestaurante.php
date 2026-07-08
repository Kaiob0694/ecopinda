<?php
session_start();
include_once("src/conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = mysqli_real_escape_string($conexao, $_POST['nome']);
    $logradouro = mysqli_real_escape_string($conexao, $_POST['logradouro']);
    $numero = mysqli_real_escape_string($conexao, $_POST['numero']);
    $cidade = mysqli_real_escape_string($conexao, $_POST['cidade']);
    $cep = mysqli_real_escape_string($conexao, $_POST['cep']);
    $telefone = mysqli_real_escape_string($conexao, $_POST['telefone']);
    $email = mysqli_real_escape_string($conexao, $_POST['email']);
    $categoria = mysqli_real_escape_string($conexao, $_POST['categoria']);
    $possui_delivery = mysqli_real_escape_string($conexao, $_POST['possui_delivery']);
    $possui_wifi = mysqli_real_escape_string($conexao, $_POST['possui_wifi']);
    $horario_funcionamento = mysqli_real_escape_string($conexao, $_POST['horario_funcionamento']);


     $imagem = "";

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {

        $nomeImagem = time() . "_" . basename($_FILES['foto']['name']);

        // caminho físico real
        $pasta = __DIR__ . "/assets/img/imgGastronomia/";

        // cria a pasta caso não exista
        if (!is_dir($pasta)) {
            mkdir($pasta, 0777, true);
        }

        $destino = $pasta . $nomeImagem;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {

            // caminho salvo no banco
            $imagem = "/assets/img/imgGastronomia/" . $nomeImagem;

        } else {

            echo "Falha no upload";
            echo "<pre>";
            print_r($_FILES);
            echo "</pre>";
            exit;
        }
    }
      $sql = "INSERT INTO restaurante
    (
        nome,
        logradouro,
        numero,
        cidade,
        cep,
        telefone,
        email,
        categoria,
        possui_delivery,
        possui_wifi,
        horario_funcionamento
    )
    VALUES
    (
        '$nome',
        '$logradouro',
        '$numero',
        '$cidade',
        '$cep',
        '$telefone',
        '$email',
        '$categoria',
        '$possui_delivery',
        '$possui_wifi',
        '$horario_funcionamento'
    )";

    if (mysqli_query($conexao, $sql)) {

        echo "<script>
                alert('Restaurante cadastrado com sucesso!');
                window.location='restaurante.php';
              </script>";

    } else {

        echo "Erro ao cadastrar: " . mysqli_error($conexao);

    }

    mysqli_close($conexao);
}
?>