<?php

require_once __DIR__ . "/../classes/restaurante_fotos.php";

/**
 * Salva as fotos enviadas em $_FILES['fotos']
 * para o restaurante indicado.
 *
 * Retorna um array com mensagens de erro.
 * O array fica vazio quando tudo ocorre corretamente.
 */
function salvarFotosRestaurante($id_restaurante, $campo = 'fotos')
{
    $erros = [];

    // Verifica se alguma foto foi enviada
    if (
        !isset($_FILES[$campo]) ||
        empty($_FILES[$campo]['name'][0])
    ) {
        return $erros;
    }

    // Tipos de imagem permitidos
    $tiposPermitidos = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp'
    ];

    // 5 MB por foto
    $tamanhoMaximo = 5 * 1024 * 1024;

    // Pasta onde as fotos serão armazenadas
    $pasta = __DIR__ . '/../uploads/restaurante/';

    // Cria a pasta caso ela não exista
    if (!is_dir($pasta)) {

        if (!mkdir($pasta, 0755, true)) {

            $erros[] = "Não foi possível criar a pasta de imagens.";

            return $erros;
        }
    }

    // Classe responsável pelas fotos do restaurante
    $restauranteFoto = new RestauranteFoto();

    $totalFotos = count($_FILES[$campo]['name']);

    // Percorre todas as fotos enviadas
    for ($i = 0; $i < $totalFotos; $i++) {

        // Nenhum arquivo nesse campo
        if (
            $_FILES[$campo]['error'][$i]
            === UPLOAD_ERR_NO_FILE
        ) {
            continue;
        }

        // Erro no upload
        if (
            $_FILES[$campo]['error'][$i]
            !== UPLOAD_ERR_OK
        ) {

            $erros[] =
                "Erro ao enviar a foto \"" .
                $_FILES[$campo]['name'][$i] .
                "\".";

            continue;
        }

        // Verifica tamanho
        if (
            $_FILES[$campo]['size'][$i]
            > $tamanhoMaximo
        ) {

            $erros[] =
                "A foto \"" .
                $_FILES[$campo]['name'][$i] .
                "\" deve ter no máximo 5 MB.";

            continue;
        }

        // Descobre o MIME real do arquivo
        if (class_exists('finfo')) {

            $finfo = new finfo(FILEINFO_MIME_TYPE);

            $tipo = $finfo->file(
                $_FILES[$campo]['tmp_name'][$i]
            );

        } else {

            $tipo =
                $_FILES[$campo]['type'][$i] ?? '';
        }

        // Verifica se o tipo é permitido
        if (!isset($tiposPermitidos[$tipo])) {

            $erros[] =
                "A foto \"" .
                $_FILES[$campo]['name'][$i] .
                "\" deve ser JPG, PNG ou WEBP.";

            continue;
        }

        // Define extensão
        $extensao = $tiposPermitidos[$tipo];

        // Cria nome único
        $nomeArquivo =
            uniqid('restaurante_', true) .
            '.' .
            $extensao;

        $destino = $pasta . $nomeArquivo;

        // Move a foto para a pasta
        if (
            !move_uploaded_file(
                $_FILES[$campo]['tmp_name'][$i],
                $destino
            )
        ) {

            $erros[] =
                "Não foi possível salvar a foto \"" .
                $_FILES[$campo]['name'][$i] .
                "\".";

            continue;
        }

        // Salva o nome da foto no banco
        $restauranteFoto->adicionar(
            $id_restaurante,
            $nomeArquivo
        );
    }

    return $erros;
}