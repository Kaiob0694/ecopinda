<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once "../../config/conexao.php";
require_once "../../classes/mural_fotos.php";
require_once "../../classes/mural_stickers.php";

header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode([
        'sucesso'  => false,
        'mensagem' => 'Você precisa estar logado.'
    ]);
    exit;
}

$usuarioId = $_SESSION['usuario_id'];

$dados = json_decode(file_get_contents('php://input'), true);

if (!is_array($dados)) {
    $dados = [];
}

$acao = $dados['acao'] ?? '';

$conexao = new Conexao();
$pdo = $conexao->conectar();

$muralFotos = new MuralFotos($pdo);
$muralStickers = new MuralStickers($pdo);

try {

    if ($acao === 'adicionar') {

        $fotoId = isset($dados['foto_id']) ? (int) $dados['foto_id'] : 0;
        $emoji  = $dados['emoji'] ?? '';

        if ($fotoId <= 0) {
            throw new Exception('Foto inválida.');
        }

        // Confirma que a foto existe de verdade antes de colar o adesivo
        $foto = $muralFotos->obter($fotoId);

        if (!$foto) {
            throw new Exception('Foto não encontrada.');
        }

        $sticker = $muralStickers->adicionar($fotoId, $usuarioId, $emoji);

        echo json_encode([
            'sucesso' => true,
            'sticker' => $sticker
        ]);

    } elseif ($acao === 'remover') {

        $stickerId = isset($dados['sticker_id']) ? (int) $dados['sticker_id'] : 0;

        if ($stickerId <= 0) {
            throw new Exception('Adesivo inválido.');
        }

        $removido = $muralStickers->remover($stickerId, $usuarioId);

        if (!$removido) {
            throw new Exception('Não foi possível remover este adesivo.');
        }

        echo json_encode(['sucesso' => true]);

    } else {

        throw new Exception('Ação desconhecida.');

    }

} catch (Exception $erro) {

    http_response_code(400);

    echo json_encode([
        'sucesso'  => false,
        'mensagem' => $erro->getMessage()
    ]);

}
