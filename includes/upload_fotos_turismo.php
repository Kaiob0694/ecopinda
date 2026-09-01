<?php
require_once __DIR__ . "/../classes/turismo_fotos.php";

/**
 * Salva as fotos enviadas em $_FILES['fotos'] (input com "multiple")
 * para o ponto turistico indicado, gravando cada uma na tabela
 * ponto_turistico_foto.
 *
 * Retorna um array com mensagens de erro (vazio se tudo correu bem).
 */
function salvarFotosTurismo($id_ponto_turistico, $campo = 'fotos')
{

    $erros = [];

    if (!isset($_FILES[$campo]) || empty($_FILES[$campo]['name'][0])) {
        return $erros;
    }

    $tiposPermitidos = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    $tamanhoMaximo = 5 * 1024 * 1024; // 5 MB por foto

    $pasta = __DIR__ . '/../uploads/turismo/';

    if (!is_dir($pasta)) {
        if (!mkdir($pasta, 0755, true)) {
            $erros[] = "Não foi possível criar a pasta de imagens.";
            return $erros;
        }
    }

    $pontoFoto = new PontoTuristicoFoto();

    $totalFotos = count($_FILES[$campo]['name']);

    for ($i = 0; $i < $totalFotos; $i++) {

        if ($_FILES[$campo]['error'][$i] === UPLOAD_ERR_NO_FILE) {
            continue;
        }

        if ($_FILES[$campo]['error'][$i] !== UPLOAD_ERR_OK) {
            $erros[] = "Erro ao enviar a foto \"{$_FILES[$campo]['name'][$i]}\".";
            continue;
        }

        if ($_FILES[$campo]['size'][$i] > $tamanhoMaximo) {
            $erros[] = "A foto \"{$_FILES[$campo]['name'][$i]}\" deve ter no máximo 5 MB.";
            continue;
        }

        if (class_exists('finfo')) {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $tipo = $finfo->file($_FILES[$campo]['tmp_name'][$i]);
        } else {
            $tipo = $_FILES[$campo]['type'][$i] ?? '';
        }

        if (!isset($tiposPermitidos[$tipo])) {
            $erros[] = "A foto \"{$_FILES[$campo]['name'][$i]}\" deve ser JPG, PNG ou WEBP.";
            continue;
        }

        $extensao = $tiposPermitidos[$tipo];
        $nomeArquivo = uniqid('turismo_', true) . '.' . $extensao;
        $destino = $pasta . $nomeArquivo;

        if (!move_uploaded_file($_FILES[$campo]['tmp_name'][$i], $destino)) {
            $erros[] = "Não foi possível salvar a foto \"{$_FILES[$campo]['name'][$i]}\".";
            continue;
        }

        $pontoFoto->adicionar($id_ponto_turistico, $nomeArquivo);
    }

    return $erros;
}
