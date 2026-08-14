<?php

function temPermissao($permissao)
{
    if (!isset($_SESSION['usuario_id'])) {
        return false;
    }

    $nivel = $_SESSION['nivel'] ?? '';

    if ($permissao === 'gerenciar_restaurante') {
        return $nivel === 'proprietario';
    }

    return false;
}