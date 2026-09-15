<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);

header('Content-Type: application/json; charset=utf-8');

require_once "../../config/conexao.php";
require_once "../../classes/mural_fotos.php";

// Verificar se usuário está logado
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Usuário não autenticado'
    ]);
    exit;
}

$usuarioId = $_SESSION['usuario_id'];

// Validar método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Método não permitido'
    ]);
    exit;
}

// Validar arquivo
if (
    empty($_FILES['foto']) ||
    $_FILES['foto']['error'] !== UPLOAD_ERR_OK
) {
    http_response_code(400);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Nenhum arquivo enviado ou erro no upload'
    ]);
    exit;
}

$arquivo = $_FILES['foto'];
$descricao = $_POST['descricao'] ?? '';

// Validar tipo de arquivo
$tiposPermitidos = [
    'image/jpeg',
    'image/png',
    'image/webp',
    'image/gif'
];

if (!in_array($arquivo['type'], $tiposPermitidos)) {
    http_response_code(400);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Tipo de arquivo não permitido. Use JPG, PNG, WEBP ou GIF'
    ]);
    exit;
}

// Validar tamanho
$tamanhoMaximo = 5 * 1024 * 1024;

if ($arquivo['size'] > $tamanhoMaximo) {
    http_response_code(400);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Arquivo muito grande. Máximo 5MB'
    ]);
    exit;
}

// Criar diretório
$diretorio = __DIR__ . '/../../assets/uploads/mural/';

if (!is_dir($diretorio)) {
    mkdir($diretorio, 0755, true);
}

// Gerar nome único
$extensao = pathinfo(
    $arquivo['name'],
    PATHINFO_EXTENSION
);

$nomeArquivo =
    uniqid('foto_') .
    '_' .
    time() .
    '.' .
    strtolower($extensao);

$caminhoCompleto = $diretorio . $nomeArquivo;

// Mover arquivo
if (!move_uploaded_file(
    $arquivo['tmp_name'],
    $caminhoCompleto
)) {

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao salvar arquivo'
    ]);

    exit;
}

// Otimizar imagem
try {

    if (
        in_array(
            $arquivo['type'],
            ['image/jpeg', 'image/png']
        )
    ) {

        if (extension_loaded('gd')) {

            $img = null;

            if ($arquivo['type'] === 'image/jpeg') {
                $img = imagecreatefromjpeg(
                    $caminhoCompleto
                );
            }

            if ($arquivo['type'] === 'image/png') {
                $img = imagecreatefrompng(
                    $caminhoCompleto
                );
            }

            if ($img) {

                $maxWidth = 1200;
                $maxHeight = 1200;

                $width = imagesx($img);
                $height = imagesy($img);

                if (
                    $width > $maxWidth ||
                    $height > $maxHeight
                ) {

                    $ratio = min(
                        $maxWidth / $width,
                        $maxHeight / $height
                    );

                    $novaLargura =
                        (int)($width * $ratio);

                    $novaAltura =
                        (int)($height * $ratio);

                    $novaImg =
                        imagecreatetruecolor(
                            $novaLargura,
                            $novaAltura
                        );

                    if ($arquivo['type'] === 'image/png') {

                        imagealphablending(
                            $novaImg,
                            false
                        );

                        imagesavealpha(
                            $novaImg,
                            true
                        );
                    }

                    imagecopyresampled(
                        $novaImg,
                        $img,
                        0,
                        0,
                        0,
                        0,
                        $novaLargura,
                        $novaAltura,
                        $width,
                        $height
                    );

                    if ($arquivo['type'] === 'image/jpeg') {

                        imagejpeg(
                            $novaImg,
                            $caminhoCompleto,
                            85
                        );

                    } elseif (
                        $arquivo['type'] === 'image/png'
                    ) {

                        imagepng(
                            $novaImg,
                            $caminhoCompleto,
                            9
                        );
                    }

                    imagedestroy($novaImg);
                }

                imagedestroy($img);
            }
        }
    }

} catch (Exception $e) {

    error_log(
        "Erro ao otimizar imagem: " .
        $e->getMessage()
    );
}


// ============================================================
// SALVAR FOTO + DAR PINDACOINS
// ============================================================

try {

    $conexao = new Conexao();
    $pdo = $conexao->conectar();

    // Iniciar transação
    $pdo->beginTransaction();

    // Classe do mural
    $mural = new MuralFotos($pdo);

    // Criar foto
    $id = $mural->criar(
        $usuarioId,
        $nomeArquivo,
        $descricao
    );


    // ========================================================
    // PINDACOINS
    // ========================================================

    $coinsGanhos = 10;


    // Adicionar moedas ao usuário
    $sqlCoins = "
        UPDATE usuarios
        SET pindacoins = pindacoins + :quantidade
        WHERE id = :usuario_id
    ";

    $stmtCoins = $pdo->prepare($sqlCoins);

    $stmtCoins->execute([
        ':quantidade' => $coinsGanhos,
        ':usuario_id' => $usuarioId
    ]);


    // ========================================================
    // REGISTRAR MOVIMENTAÇÃO
    // ========================================================

    $sqlMovimento = "
        INSERT INTO pindacoins_movimentos
        (
            id_usuario,
            quantidade,
            tipo,
            descricao
        )
        VALUES
        (
            :usuario_id,
            :quantidade,
            :tipo,
            :descricao
        )
    ";

    $stmtMovimento = $pdo->prepare(
        $sqlMovimento
    );

    $stmtMovimento->execute([
        ':usuario_id' => $usuarioId,
        ':quantidade' => $coinsGanhos,
        ':tipo' => 'foto_mural',
        ':descricao' => 'Foto publicada no Mural'
    ]);


    // Confirmar tudo
    $pdo->commit();


    // Resposta
    http_response_code(200);

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Foto enviada com sucesso! Você ganhou ' . $coinsGanhos . ' PindaCoins!',
        'id' => $id,
        'pindacoins_ganhos' => $coinsGanhos
    ]);


} catch (Exception $e) {

    // Desfazer transação
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    // Apagar imagem se algo deu errado
    if (file_exists($caminhoCompleto)) {
        unlink($caminhoCompleto);
    }

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' =>
            'Erro ao salvar foto ou PindaCoins: ' .
            $e->getMessage()
    ]);
}