<?php

require_once "../../classes/hotel_fotos.php";

$hotelFoto = new HotelFoto();

$id = $_GET['id'];
$id_hotel = $_GET['id_hotel'];

$foto = $hotelFoto->buscarPorId($id);

if ($foto) {

    $caminhoArquivo = __DIR__ . "/../../uploads/hoteis/" . $foto['caminho'];

    if (file_exists($caminhoArquivo)) {
        unlink($caminhoArquivo);
    }

    $hotelFoto->excluir($id);
}

header("Location: update.php?id=" . $id_hotel);
exit;
