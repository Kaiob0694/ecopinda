<!DOCTYPE html>
<html lang="pt_br">
<head>
    <title>Cadastro</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/css/style_cadastro.css">
</head>
<body>

<div class="container">
    <h1>Cadastro de Usuário</h1>

    <form action="../src/cadastrar.php" method="POST">
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Salvar</button>
    </form>

    <a href="index.php">Voltar para login</a>

</div>

</body>
</html>