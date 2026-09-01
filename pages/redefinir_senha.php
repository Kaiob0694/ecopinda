<?php

session_start();
require_once __DIR__ . "/../src/conexao.php";

$token = trim($_GET['token'] ?? '');

$erro = $_SESSION['redefinir_erro'] ?? '';
unset($_SESSION['redefinir_erro']);

$tokenValido = false;

if ($token !== '') {

    $sql = "SELECT id, reset_token_expira FROM usuarios WHERE reset_token = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $usuario = mysqli_fetch_assoc($resultado);
    mysqli_stmt_close($stmt);

    if ($usuario && strtotime($usuario['reset_token_expira']) >= time()) {
        $tokenValido = true;
    } elseif (!$erro) {
        $erro = "Este link de recuperação é inválido ou já expirou. Solicite um novo.";
    }

} elseif (!$erro) {
    $erro = "Link de recuperação inválido.";
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Redefinir senha</title>

    <link rel="stylesheet" href="../assets/css/login.css">
</head>

<body>

    <img 
        src="../assets/img/fundo.png" 
        alt="" 
        class="bg-photo"
        onerror="this.style.display='none'"
    >

    <div class="bg-overlay"></div>

    <div class="cloud c1"></div>
    <div class="cloud c2"></div>
    <div class="cloud c3"></div>

    <div class="card">

        <img 
            src="../assets/img/logo_login.png" 
            alt="Logo" 
            class="logo"
            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
        >

        <h1 class="logo-fallback">NOVA SENHA</h1>

        <?php if ($erro): ?>

            <div class="erro">
                <?= htmlspecialchars($erro) ?>
            </div>

        <?php endif; ?>

        <?php if ($tokenValido): ?>

            <form action="../src/redefinir_senha.php" method="POST" novalidate>

                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                <div class="field">
                    <input 
                        type="password" 
                        name="senha" 
                        placeholder="Nova senha (mínimo 6 caracteres)"
                        required
                        minlength="6"
                        autofocus
                    >
                </div>

                <div class="field">
                    <input 
                        type="password" 
                        name="senha_confirma" 
                        placeholder="Confirme a nova senha"
                        required
                        minlength="6"
                    >
                </div>

                <button type="submit" class="btn-login">
                    Salvar nova senha
                </button>

            </form>

        <?php endif; ?>

        <a href="../pages/login.php" class="btn-cadastro">
            Voltar para o login
        </a>

    </div>

</body>

</html>
