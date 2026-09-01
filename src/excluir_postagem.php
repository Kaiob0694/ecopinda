<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../pages/login.php");
    exit;
}

require_once(__DIR__ . "/conexao.php");

$usuarioId = $_SESSION['usuario_id'];

$postagemId = (int)($_POST['id'] ?? 0);

if ($postagemId <= 0) {
    header("Location: ../pages/feed.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| BUSCAR POSTAGEM
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT imagem
    FROM postagens
    WHERE id = ?
    AND usuario_id = ?
";

$stmt = mysqli_prepare($conexao, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ii",
    $postagemId,
    $usuarioId
);

mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);

$postagem = mysqli_fetch_assoc($resultado);

mysqli_stmt_close($stmt);


if (!$postagem) {
    header("Location: ../pages/feed.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| EXCLUIR IMAGEM
|--------------------------------------------------------------------------
*/

if (!empty($postagem['imagem'])) {

    $arquivo = __DIR__ .
        "/../assets/uploads/postagens/" .
        $postagem['imagem'];

    if (file_exists($arquivo)) {
        unlink($arquivo);
    }
}


/*
|--------------------------------------------------------------------------
| EXCLUIR POSTAGEM
|--------------------------------------------------------------------------
*/

$sql = "
    DELETE FROM postagens
    WHERE id = ?
    AND usuario_id = ?
";

$stmt = mysqli_prepare($conexao, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ii",
    $postagemId,
    $usuarioId
);

mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);


header("Location: ../pages/feed.php");

exit;