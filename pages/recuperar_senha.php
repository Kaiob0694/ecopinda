<?php

session_start();

if (!empty($_SESSION['usuario_id'])) {
    header('Location: profile.php');
    exit;
}

$erro = $_SESSION['recuperar_erro'] ?? '';
unset($_SESSION['recuperar_erro']);

$sucesso = $_SESSION['recuperar_sucesso'] ?? '';
unset($_SESSION['recuperar_sucesso']);

// Link mostrado na tela só porque o servidor local não tem e-mail configurado.
// Isso é apenas para facilitar o teste - ver src/solicitar_recuperacao.php
$link_dev = $_SESSION['recuperar_link_dev'] ?? '';
unset($_SESSION['recuperar_link_dev']);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Recuperar senha</title>

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

        <h1 class="logo-fallback">RECUPERAR SENHA</h1>

        <?php if ($erro): ?>

            <div class="erro">
                <?= htmlspecialchars($erro) ?>
            </div>

        <?php endif; ?>

        <?php if ($sucesso): ?>

            <div class="sucesso">
                <?= htmlspecialchars($sucesso) ?>
            </div>

            <?php if ($link_dev): ?>

                <div class="dev-link">
                    <strong>Modo de teste local</strong><br>
                    Nenhum servidor de e-mail está configurado neste ambiente, então
                    o link também aparece aqui para você conseguir testar:<br>
                    <a href="<?= htmlspecialchars($link_dev) ?>">
                        <?= htmlspecialchars($link_dev) ?>
                    </a>
                </div>

            <?php endif; ?>

        <?php else: ?>

            <p class="texto-ajuda">
                Informe o e-mail cadastrado. Vamos enviar um link para você criar uma nova senha.
            </p>

            <form action="../src/solicitar_recuperacao.php" method="POST" novalidate>

                <div class="field">

                    <input 
                        type="email" 
                        name="email" 
                        placeholder="Email"
                        required
                        autofocus
                    >

                </div>

                <button type="submit" class="btn-login">
                    Enviar link de recuperação
                </button>

            </form>

        <?php endif; ?>

        <a href="../pages/login.php" class="btn-cadastro">
            Voltar para o login
        </a>

    </div>

</body>

</html>
