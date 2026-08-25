<?php

require_once "../../classes/restaurante.php";
require_once "../../classes/restaurante_fotos.php";

$restaurante = new Restaurante();
$restauranteFoto = new RestauranteFoto();

$id = $_GET['id'];

// Remove as fotos (arquivos + registros) antes de remover o restaurante.
$fotos = $restauranteFoto->listarPorRestaurante($id);

foreach ($fotos as $foto) {
    $caminhoArquivo = __DIR__ . "/../../uploads/restaurantes/" . $foto['caminho'];
    if (file_exists($caminhoArquivo)) {
        unlink($caminhoArquivo);
    }
}

$restauranteFoto->excluirPorRestaurante($id);
$restaurante->excluir($id);

header("Location: read.php");
exit;
