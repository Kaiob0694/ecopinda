<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastro de Hotel</title>
    <link rel="stylesheet" href="assets/css/formulario.css">
</head>

<body>

<div class="container">

    <h2>Cadastro de Hotel</h2>

       <form action="cadastro.php" method="POST">

        <label>Nome do Hotel:</label><br>
        <input type="text" name="Nome" required><br><br>

        <label>Endereço:</label><br>
        <input type="text" name="Endereço" required><br><br>

        <label>Cidade:</label><br>
        <input type="text" name="Cidade" required><br><br>

        <label>Estado:</label><br>
        <input type="text" name="Estado" required><br><br>

        <label>CEP:</label><br>
        <input type="text" name="CEP" required><br><br>

        <label>Telefone:</label><br>
        <input type="tel" name="Telefone"><br><br>

        <label>Email:</label><br>
        <input type="email" name="Email"><br><br>

        <label>Quantidade de quartos:</label><br>
        <input type="number" name="Quantidade_quartos"><br><br>

        <label>Possui Wi-Fi?</label><br>
        <select name="possui_wifi" required>
            <option value="Sim">Sim</option>
            <option value="Não">Não</option>
        </select><br><br>

        <label>Possui estacionamento?</label><br>
        <select name="possui_estacionamento" required>
            <option value="Sim">Sim</option>
            <option value="Não">Não</option>
        </select><br><br>

        <label>Data de cadastro:</label><br>
        <input type="date" name="data_cadastro" required><br><br>

        <button type="submit">Cadastrar</button>

    </form>
</div>

</body>
</html>

       




    

