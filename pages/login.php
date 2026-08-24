<?php

session_start();

if (!empty($_SESSION['usuario_id'])) {
    header('Location: profile.php');
    exit;
}

$erro = $_SESSION['login_erro'] ?? '';
unset($_SESSION['login_erro']);

$email_anterior = $_SESSION['login_email'] ?? '';
unset($_SESSION['login_email']);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Entrar</title>

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

        <h1 class="logo-fallback">LOGIN</h1>

        <?php if ($erro): ?>

            <div class="erro">
                <?= htmlspecialchars($erro) ?>
            </div>

        <?php endif; ?>

        <form action="../src/auth.php" method="POST" novalidate>

            <div class="field">

                <input 
                    type="email" 
                    name="email" 
                    placeholder="Email"
                    value="<?= htmlspecialchars($email_anterior) ?>"
                    required
                    autofocus
                >

            </div>

            <div class="field">

                <input 
                    type="password" 
                    name="senha" 
                    placeholder="Senha"
                    required
                >

            </div>

            <button type="submit" class="btn-login">
                Entrar
            </button>

        </form>

        <a href="../pages/cadastro.php" class="btn-cadastro">
            Cadastrar novo usuário
        </a>

    </div>

</body>

</html>