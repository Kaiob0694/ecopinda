<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Cadastro de Restaurante</title>
    <link rel="stylesheet" href="assets/css/formulario.css">
</head>

<body>

    <div class="container">

        <h1>Cadastro de Restaurante</h1>

        <form action="cadastrorestaurante.php" method="POST">

            <label for="nome">Nome:</label><br>
            <input type="text" id="nome" name="nome" required><br><br>

            <label for="logradouro">Logradouro:</label><br>
            <input type="text" id="logradouro" name="logradouro" required><br><br>

            <label for="numero">Número:</label><br>
            <input type="number" id="numero" name="numero" required><br><br>

            <label for="cidade">Cidade:</label><br>
            <input type="text" id="cidade" name="cidade" required><br><br>

            <label for="cep">CEP:</label><br>
            <input type="text" id="cep" name="cep" required><br><br>

            <label for="telefone">Telefone:</label><br>
            <input type="text" id="telefone" name="telefone" required><br><br>

            <label for="email">E-mail:</label><br>
            <input type="email" id="email" name="email" required><br><br>

            <label for="categoria">Categoria:</label><br>
            <input type="text" id="categoria" name="categoria" required><br><br>

            <label for="possui_delivery">Possui Delivery?</label><br>
            <select id="possui_delivery" name="possui_delivery" required>
                <option value="1">Sim</option>
                <option value="0">Não</option>
            </select><br><br>

            <label for="possui_wifi">Possui Wi-Fi?</label><br>
            <select id="possui_wifi" name="possui_wifi" required>
                <option value="1">Sim</option>
                <option value="0">Não</option>
            </select><br><br>

            <label for="horario_funcionamento">Horário de Funcionamento:</label><br>
            <input type="text" id="horario_funcionamento" name="horario_funcionamento" required><br><br>

            <div class="botoes">
                <button type="submit">Cadastrar Restaurante</button>

                <a href="restaurante.php">
                    <button type="button">Voltar</button>
                </a>
            </div>

        </form>

    </div>

</body>

</html>