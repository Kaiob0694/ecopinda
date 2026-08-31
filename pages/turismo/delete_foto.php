<?php

require_once "../../classes/turismo_fotos.php";

$pontoFoto = new PontoTuristicoFoto();

$id = $_GET['id'];
$id_ponto = $_GET['id_ponto'];

$foto = $pontoFoto->buscarPorId($id);

if ($foto) {

    $caminhoArquivo = __DIR__ . "/../../uploads/turismo/" . $foto['caminho'];

    if (file_exists($caminhoArquivo)) {
        unlink($caminhoArquivo);
    }

    $pontoFoto->excluir($id);
}

header("Location: update.php?id=" . $id_ponto);
exit;
