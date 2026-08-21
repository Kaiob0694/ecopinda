<?php
// Inclua este arquivo no topo de qualquer página/ação que só o
// administrador master pode acessar (ex.: gerenciar nível de acesso
// de outras contas).
//
// Uso:
//   session_start(); // se ainda não tiver sido chamado
//   require __DIR__ . '/../includes/exigir_master.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuarioLogado = isset($_SESSION['usuario_id']);
$usuarioTipo   = $_SESSION['usuario_tipo'] ?? 'usuario';

if (!$usuarioLogado || $usuarioTipo !== 'master') {
    http_response_code(403);
    echo "Acesso restrito ao administrador master.";
    exit;
}
