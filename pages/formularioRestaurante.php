
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastro de Restaurante</title>
    <link rel="stylesheet" href="assets/css/formulario.css">
</head>

<body>

<div class="container">

    <h1>Cadastro de Restaurante</h1>

    <form action="cadastrorestaurante.php" method="POST">

        <label>Nome:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>Logradouro:</label><br>
        <input type="text" name="logradouro" required><br><br>

        <label>Número:</label><br>
        <input type="number" name="numero" required><br><br>

        <label>Cidade:</label><br>
        <input type="text" name="cidade" required><br><br>

        <label>CEP:</label><br>
        <input type="text" name="cep" required><br><br>

        <label>Telefone:</label><br>
        <input type="text" name="telefone" required><br><br>

        <label>E-mail:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Categoria:</label><br>
        <input type="text" name="categoria" required><br><br>

        <label>Possui Delivery?</label><br>
        <select name="possui_delivery" required>
            <option value="Sim">Sim</option>
            <option value="Não">Não</option>
        </select><br><br>

        <label>Possui Wi-Fi?</label><br>
        <select name="possui_wifi" required>
            <option value="Sim">Sim</option>
            <option value="Não">Não</option>
        </select><br><br>

        <label>Horário de Funcionamento:</label><br>
        <input type="text" name="horario_funcionamento" required><br><br>

        <button type="submit">Cadastrar</button>

    </form>

</div>

</body>
</html>