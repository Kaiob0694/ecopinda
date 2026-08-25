<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


require_once "../../classes/hoteis.php";

$hotel = new Hotel();

$id = $_GET['id'];

$dados = $hotel->buscarPorId($id);
if ($_SERVER["REQUEST_METHOD"] == "POST") {   
    $hotel->editar(
        $id,
        $_POST['nome'],
        $_POST['endereco'],
        $_POST['cidade'],
        $_POST['estado'],
        $_POST['cep'],
        $_POST['telefone'],
        $_POST['email'],
        $_POST['quantidade_quartos'],
        $_POST['possui_wifi'],
        $_POST['possui_estacionamento'],
        $_POST['data_cadastro']

    );
     

    header("Location: read.php");
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
            name="endereco"
            value="<?= $dados['endereco']; ?>"
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
        Estado:<br>
        <input
            type="text"
            name="estado"
            value="<?= $dados['estado']; ?>"
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
         <option value="1" <?= $dados['possui_wifi'] == 1 ? 'selected' : '' ?>>
            Sim
        </option>
        <option value="0" <?= $dados['possui_wifi'] == 0 ? 'selected' : '' ?>>
            Não
        </option>
    </select>
</p>

    <p>
        Possui Estacionamento:<br>
        <select name="possui_estacionamento" required>
         <option value="1" <?= $dados['possui_estacionamento'] == 1 ? 'selected' : '' ?>>
            Sim
        </option>
        <option value="0" <?= $dados['possui_estacionamento'] == 0 ? 'selected' : '' ?>>
            Não
        </option>
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

