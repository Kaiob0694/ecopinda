<?php
// Inclua este arquivo no topo de qualquer página/ação que só o administrador
// pode acessar (ex.: cadastrar/deletar hotel, cadastrar/deletar restaurante).
//
// Uso:
//   session_start(); // se ainda não tiver sido chamado
//   require __DIR__ . '/../includes/exigir_admin.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuarioLogado = isset($_SESSION['usuario_id']);
$usuarioTipo   = $_SESSION['usuario_tipo'] ?? 'usuario';

// O administrador master também tem acesso a tudo que é restrito a admin.
if (!$usuarioLogado || !in_array($usuarioTipo, ['admin', 'master'], true)) {
    http_response_code(403);
    echo "Acesso restrito a administradores.";
    exit;
}