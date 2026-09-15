<?php
$prefixo = '../../';

require_once $prefixo . 'includes/verifica_master.php';
require_once $prefixo . 'includes/conexao.php';
require_once $prefixo . 'classes/GuiasTuristicos.php';

$guiasTuristicos = new GuiasTuristicos($pdo);

$id = (int) ($_GET['id'] ?? 0);
$guia = $guiasTuristicos->buscarPorId($id);

if (!$guia) {
    header('Location: read.php?erro=nao_encontrado');
    exit;
}

// remove fotos da galeria do disco e do banco
foreach ($guiasTuristicos->listarFotos($id) as $foto) {
    $caminho = $prefixo . 'assets/uploads/guias/' . $foto['foto'];
    if (is_file($caminho)) {
        unlink($caminho);
    }
    $guiasTuristicos->excluirFoto($foto['id']);
}

// remove foto de perfil
if (!empty($guia['foto_perfil'])) {
    $caminho = $prefixo . 'assets/uploads/guias/' . $guia['foto_perfil'];
    if (is_file($caminho)) {
        unlink($caminho);
    }
}

// remove vínculos de categoria
foreach ($guiasTuristicos->listarCategorias($id) as $categoria) {
    $guiasTuristicos->removerCategoria($id, $categoria['id']);
}

$guiasTuristicos->excluir($id);

header('Location: read.php?sucesso=excluido');
exit;