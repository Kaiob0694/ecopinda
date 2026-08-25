<?php

require_once "../../classes/restaurante_fotos.php";

$restauranteFoto = new RestauranteFoto();

$id = $_GET['id'];
$id_restaurante = $_GET['id_restaurante'];

$foto = $restauranteFoto->buscarPorId($id);

if ($foto) {

    $caminhoArquivo = __DIR__ . "/../../uploads/restaurantes/" . $foto['caminho'];

    if (file_exists($caminhoArquivo)) {
        unlink($caminhoArquivo);
    }

    $restauranteFoto->excluir($id);
}

header("Location: update.php?id=" . $id_restaurante);
exit;
