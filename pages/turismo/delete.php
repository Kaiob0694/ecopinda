<?php

require_once "../../classes/turismo.php";
require_once "../../classes/turismo_fotos.php";

$ponto = new PontoTuristico();
$pontoFoto = new PontoTuristicoFoto();

$id = $_GET['id'];

// Remove as fotos (arquivos + registros) antes de remover o ponto turistico.
$fotos = $pontoFoto->listarPorPonto($id);

foreach ($fotos as $foto) {
    $caminhoArquivo = __DIR__ . "/../../uploads/turismo/" . $foto['caminho'];
    if (file_exists($caminhoArquivo)) {
        unlink($caminhoArquivo);
    }
}

$pontoFoto->excluirPorPonto($id);
$ponto->excluir($id);

header("Location: read.php");
exit;
