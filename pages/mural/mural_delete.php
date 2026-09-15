<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../config/conexao.php";
require_once "../../classes/mural_fotos.php";

$baseUrl = 'https://pindaeco.rf.gd';

// Verificar se usuário está logado
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ' . $baseUrl . '/pages/login.php');
    exit;
}

$usuarioId = $_SESSION['usuario_id'];

// Validar ID
if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: read.php');
    exit;
}

$fotoId = (int)$_GET['id'];

try {
    $conexao = new Conexao();
    $pdo = $conexao->conectar();
    $mural = new MuralFotos($pdo);
    
    // Obter foto
    $foto = $mural->obter($fotoId);
    
    if (!$foto) {
        header('Location: read.php');
        exit;
    }
    
    // Verificar se é o dono
    if ($foto['usuario_id'] != $usuarioId) {
        header('Location: read.php?erro=1');
        exit;
    }
    
    // Caminho do arquivo
    $caminhoArquivo = __DIR__ . '/../../assets/uploads/mural/' . $foto['caminho_foto'];
    
    // Deletar
    $mural->deletar($fotoId, $caminhoArquivo);
    
    // Redirecionar com sucesso
    header('Location: read.php?sucesso=2');
    exit;
    
} catch (Exception $e) {
    header('Location: read.php?erro=1');
    exit;
}