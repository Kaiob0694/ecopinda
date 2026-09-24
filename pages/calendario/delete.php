<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../config/conexao.php";

// Apenas o usuário master pode excluir eventos
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'master') {
    header("Location: index.php");
    exit;
}

$conexao = new Conexao();
$pdo = $conexao->conectar();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

try {

    // Busca o evento antes de excluir, para saber se tem imagem a apagar
    $stmt = $pdo->prepare("SELECT imagem FROM eventos WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $evento = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$evento) {
        // Evento não existe, apenas volta para a lista
        header("Location: index.php");
        exit;
    }

    // Remove o registro do banco
    $stmt = $pdo->prepare("DELETE FROM eventos WHERE id = :id");
    $stmt->execute([':id' => $id]);

    // Se o evento tinha uma imagem própria (upload local), remove o arquivo
    if (!empty($evento['imagem']) && strpos($evento['imagem'], '/assets/uploads/eventos/') === 0) {

        $caminhoArquivo = __DIR__ . '/../..' . $evento['imagem'];

        if (file_exists($caminhoArquivo)) {
            @unlink($caminhoArquivo);
        }

    }

    $_SESSION['login_sucesso'] = 'Evento excluído com sucesso.';

} catch (PDOException $e) {

    $_SESSION['login_erro'] = 'Erro ao excluir o evento: ' . $e->getMessage();

}

header("Location: index.php");
exit;