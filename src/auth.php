<?php

session_start();
require_once(__DIR__ . "/../src/conexao.php");

$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

$sql = "SELECT * FROM usuarios WHERE email = ?";
$stmt = mysqli_prepare($conexao, $sql);

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($resultado)) {

    if (password_verify($senha, $row['senha'])) {

        $_SESSION['usuario_id']    = $row['id'];
        $_SESSION['usuario_nome']  = $row['nome'];
        $_SESSION['usuario_email'] = $row['email'];
        $_SESSION['usuario_foto']  = $row['foto'] ?? '';
        $_SESSION['usuario_tipo']  = $row['tipo_usuario'] ?? 'usuario';

        header("Location: /ecopinda/pages/profile.php");
        exit;

    } else {

        $_SESSION['login_erro']  = "Senha incorreta";
        $_SESSION['login_email'] = $email;

        header("Location: /ecopinda/pages/login.php");
        exit;
    }

} else {

    $_SESSION['login_erro']  = "Usuário não encontrado";
    $_SESSION['login_email'] = $email;

    header("Location: /pages/login.php");
    exit;
}

//AGORA VAI 