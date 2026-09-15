<?php

require_once __DIR__ . '/../../includes/verifica_master.php';
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../classes/guiasTuristicos.php';

$db = new Conexao();
$pdo = $db->conectar();

$guiasTuristicos = new GuiasTuristicos($pdo);

$id = (int) ($_GET['id'] ?? 0);
$guia = $guiasTuristicos->buscarPorId($id);

if (!$guia) {
    header('Location: read.php?erro=nao_encontrado');
    exit;
}

foreach ($guiasTuristicos->listarFotos($id) as $foto) {
    $caminho = __DIR__ . '/../../assets/uploads/guias/' . $foto['foto'];
    if (is_file($caminho)) {
        unlink($caminho);
    }
    $guiasTuristicos->excluirFoto($foto['id']);
}

if (!empty($guia['foto_perfil'])) {
    $caminho = __DIR__ . '/../../assets/uploads/guias/' . $guia['foto_perfil'];
    if (is_file($caminho)) {
        unlink($caminho);
    }
}

foreach ($guiasTuristicos->listarCategorias($id) as $categoria) {
    $guiasTuristicos->removerCategoria($id, $categoria['id']);
}

$guiasTuristicos->excluir($id);

header('Location: read.php?sucesso=excluido');
exit;