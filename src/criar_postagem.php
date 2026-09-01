<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../pages/login.php");
    exit;
}

require_once(__DIR__ . "/conexao.php");

$usuarioId = $_SESSION['usuario_id'];

$texto = trim($_POST['texto'] ?? '');

if ($texto === '') {
    header("Location: ../pages/feed.php");
    exit;
}

if (mb_strlen($texto) > 2000) {
    die("A postagem não pode ter mais de 2000 caracteres.");
}


/*
|--------------------------------------------------------------------------
| PASTA DE UPLOAD
|--------------------------------------------------------------------------
*/

$pasta = __DIR__ . "/../assets/uploads/postagens/";

if (!is_dir($pasta)) {
    mkdir($pasta, 0755, true);
}


$nomeImagem = null;


/*
|--------------------------------------------------------------------------
| UPLOAD DA IMAGEM
|--------------------------------------------------------------------------
*/

if (
    isset($_FILES['imagem']) &&
    $_FILES['imagem']['error'] !== UPLOAD_ERR_NO_FILE
) {

    if ($_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {
        die("Erro ao enviar a imagem.");
    }


    // Limite de 5 MB

    if ($_FILES['imagem']['size'] > 5 * 1024 * 1024) {
        die("A imagem deve ter no máximo 5 MB.");
    }


    // Verifica o tipo real do arquivo

    $tipo = mime_content_type($_FILES['imagem']['tmp_name']);

    $tiposPermitidos = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp'
    ];


    if (!isset($tiposPermitidos[$tipo])) {
        die("Formato de imagem não permitido.");
    }


    $extensao = $tiposPermitidos[$tipo];


    // Nome único

    $nomeImagem =
        'post_' .
        bin2hex(random_bytes(10)) .
        '.' .
        $extensao;


    $destino = $pasta . $nomeImagem;


    if (!move_uploaded_file(
        $_FILES['imagem']['tmp_name'],
        $destino
    )) {

        die("Não foi possível salvar a imagem.");
    }
}


/*
|--------------------------------------------------------------------------
| SALVAR POSTAGEM
|--------------------------------------------------------------------------
*/

$sql = "
    INSERT INTO postagens
    (usuario_id, texto, imagem)
    VALUES (?, ?, ?)
";

$stmt = mysqli_prepare($conexao, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "iss",
    $usuarioId,
    $texto,
    $nomeImagem
);

mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);


/*
|--------------------------------------------------------------------------
| VOLTAR PARA O FEED
|--------------------------------------------------------------------------
*/

header("Location: ../pages/feed.php");

exit;