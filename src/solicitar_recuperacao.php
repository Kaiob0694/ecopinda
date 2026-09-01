<?php

session_start();
require "conexao.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../pages/recuperar_senha.php");
    exit;
}

$email = trim($_POST['email'] ?? '');

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['recuperar_erro'] = "Informe um e-mail válido.";
    header("Location: ../pages/recuperar_senha.php");
    exit;
}

$sql = "SELECT id, nome FROM usuarios WHERE email = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt);

// A mensagem é sempre a mesma, exista ou não o e-mail no banco.
// Isso evita que alguém use este formulário para descobrir quais
// e-mails estão cadastrados no sistema.
$_SESSION['recuperar_sucesso'] = "Se este e-mail estiver cadastrado, enviamos um link de recuperação para ele.";

if ($usuario) {

    $token  = bin2hex(random_bytes(32));
    $expira = date('Y-m-d H:i:s', time() + 3600); // link válido por 1 hora

    $sqlUpdate = "UPDATE usuarios SET reset_token = ?, reset_token_expira = ? WHERE id = ?";
    $stmtUpdate = mysqli_prepare($conexao, $sqlUpdate);
    mysqli_stmt_bind_param($stmtUpdate, "ssi", $token, $expira, $usuario['id']);
    mysqli_stmt_execute($stmtUpdate);
    mysqli_stmt_close($stmtUpdate);

    // Monta a URL completa até pages/redefinir_senha.php, funcionando
    // tanto em localhost quanto no servidor final, sem precisar hardcodar o domínio.
    $scheme  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $baseDir = rtrim(str_replace('\\', '/', dirname(dirname($_SERVER['SCRIPT_NAME']))), '/');
    $link    = $scheme . '://' . $_SERVER['HTTP_HOST'] . $baseDir . '/pages/redefinir_senha.php?token=' . $token;

    $assunto  = "Recuperação de senha - EcoPinda Tour";
    $mensagem = "Olá, {$usuario['nome']}!\r\n\r\n"
        . "Recebemos uma solicitação para redefinir sua senha no EcoPinda Tour.\r\n"
        . "Clique no link abaixo para criar uma nova senha (válido por 1 hora):\r\n\r\n"
        . $link . "\r\n\r\n"
        . "Se você não solicitou isso, apenas ignore este e-mail.";
    $headers = "From: nao-responda@ecopinda.com.br\r\n";

    // Tenta enviar de verdade. Em ambiente local (XAMPP/WAMP), sem SMTP configurado,
    // isso normalmente não envia nada - por isso o link também é guardado abaixo.
    @mail($email, $assunto, $mensagem, $headers);

    // -----------------------------------------------------------------
    // MODO DE TESTE: como este projeto ainda não tem um serviço de e-mail
    // configurado (ex: PHPMailer + SMTP do Gmail), o link também é mostrado
    // na própria página de recuperação para você conseguir testar o fluxo.
    // Remova as duas linhas abaixo quando configurar o envio de e-mail de verdade.
    $_SESSION['recuperar_link_dev'] = $link;
    // -----------------------------------------------------------------
}

header("Location: ../pages/recuperar_senha.php");
exit;
