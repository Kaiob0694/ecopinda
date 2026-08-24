<?php

require_once "../../classes/hoteis.php";
require_once "../../classes/hotel_fotos.php";

$hotel = new Hotel();
$hotelFoto = new HotelFoto();

$id = $_GET['id'];

// Remove as fotos (arquivos + registros) antes de remover o hotel.
$fotos = $hotelFoto->listarPorHotel($id);

foreach ($fotos as $foto) {
    $caminhoArquivo = __DIR__ . "/../../uploads/hoteis/" . $foto['caminho'];
    if (file_exists($caminhoArquivo)) {
        unlink($caminhoArquivo);
    }
}

$hotelFoto->excluirPorHotel($id);
$hotel->excluir($id);

header("Location: read.php");
exit;
