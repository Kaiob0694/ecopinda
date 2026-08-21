<?php

require_once "../../classes/hoteis.php";

$hotel = new Hotel();

$id = $_GET['id'];

$dados = $hotel->buscarPorId($id);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hotel->editar(
        $id,
        $_POST['nome'],
        $_POST['endereço'],
        $_POST['cidade'],
        $_POST['cep'],
        $_POST['telefone'],
        $_POST['email'],
        $_POST['quantidade_quartos'],
        $_POST['possui_wifi'],
        $_POST['possui_estacionamento'],
        $_POST['data_cadastro']
    );
     

    header("Location: listar.php");
    exit;
}

include "../../includes/header.php";
include "../../includes/head.php";

?>

<h2>Editar Hotel</h2>

<form method="POST">

    <p>
        Nome:<br>
        <input
            type="text"
            name="nome"
            value="<?= $dados['nome']; ?>"
            required>
    </p>

    <p>
        Endereço:<br>
        <input
            type="text"
            name="endereço"
            value="<?= $dados['endereço']; ?>"
            required>
    </p>

    <p>
        Cidade:<br>
        <input
            type="text"
            name="cidade"
            value="<?= $dados['cidade']; ?>"
            required>
    </p>

    <p>
        CEP:<br>
        <input
            type="text"
            name="cep"
            value="<?= $dados['cep']; ?>"
            required>
    </p>

    <p>
        Telefone:<br>
        <input
            type="text"
            name="telefone"
            value="<?= $dados['telefone']; ?>">
    </p>

    <p>
        Email:<br>
        <input
            type="email"
            name="email"
            value="<?= $dados['email']; ?>">
    </p>

    <p>
        Quantidade de Quartos:<br>
        <input
            type="number"
            name="quantidade_quartos"
            value="<?= $dados['quantidade_quartos']; ?>">
    </p>

    <p>
        Possui Wi-Fi:<br>
        <select name="possui_wifi" required>
            <option value="Sim" <?= $dados['possui_wifi'] == 'Sim' ? 'selected' : '' ?>>Sim</option>
            <option value="Não" <?= $dados['possui_wifi'] == 'Não' ? 'selected' : '' ?>>Não</option>
        </select>
    </p>

    <p>
        Possui Estacionamento:<br>
        <select name="possui_estacionamento" required>
            <option value="Sim" <?= $dados['possui_estacionamento'] == 'Sim' ? 'selected' : '' ?>>Sim</option>
            <option value="Não" <?= $dados['possui_estacionamento'] == 'Não' ? 'selected' : '' ?>>Não</option>
        </select>
    </p>

    <p>
        Data de Cadastro:<br>
        <input
            type="date"
            name="data_cadastro"
            value="<?= $dados['data_cadastro']; ?>"
            required>
    </p>

    <button type="submit">
        Atualizar
    </button>

</form>

<?php
include "../../includes/footer.php";
?>
        Nome
        <input
            type="text"
            name="nome"
            value="<?= $dados['nome']; ?>"
            required>
    </p>

    <p>
        Endereço:<br>
        <input
            type="text"
            name="endereço"
            value="<?= $dados['endereço']; ?>"
            required>
    </p>

    <p>
        Cidade:<br>
        <input
            type="text"
            name="cidade"
            value="<?= $dados['cidade']; ?>"
            required>
    </p>

    <p>
        CEP:<br>
        <input
            type="text"
            name="cep"
            value="<?= $dados['cep']; ?>"
            required>
    </p>

    <p>
        Telefone:<br>
        <input
            type="text"
            name="telefone"
            value="<?= $dados['telefone']; ?>">
    </p>

    <p>
        Email:<br>
        <input
            type="email"
            name="email"
            value="<?= $dados['email']; ?>">
    </p>

    <p>
        Quantidade de Quartos:<br>
        <input
            type="number"
            name="quantidade_quartos"
            value="<?= $dados['quantidade_quartos']; ?>">
    </p>

    <p>
        Possui Wi-Fi:<br>
        <select name="possui_wifi" required>
            <option value="Sim" <?= $dados['possui_wifi'] == 'Sim' ? 'selected' : '' ?>>Sim</option>
            <option value="Não" <?= $dados['possui_wifi'] == 'Não' ? 'selected' : '' ?>>Não</option>
        </select>
    </p>

    <p>
        Possui Estacionamento:<br>
        <select name="possui_estacionamento" required>
            <option value="Sim" <?= $dados['possui_estacionamento'] == 'Sim' ? 'selected' : '' ?>>Sim</option>
            <option value="Não" <?= $dados['possui_estacionamento'] == 'Não' ? 'selected' : '' ?>>Não</option>
        </select>
    </p>

    <br>

    <button type="submit">
        Atualizar
    </button>

</form>

<?php
include "../../includes/footer.php";
?>