<?php


require_once "../../classes/hoteis.php";

$hotel = new Hotel();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hotel->cadastrar(
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

    header("Location: read.php");
    exit;
}

include "../../includes/header.php";
include "../../includes/head.php";

?>

<h2>Cadastrar Hotel</h2>

<form method="POST">

  <p>
      Nome:<br>
      <input type="text" name="nome" required>
    </p>

  <p>
      Endereço:<br>
      <input type="text" name="endereço" required>
    </p>

    <p>
        Cidade:<br>
        <input type="text" name="cidade" required>
    </p>

    <p>
        CEP:<br>
        <input type="text" name="cep" required>
    </p>

    <p>
        Telefone:<br>
        <input type="text" name="telefone">
    </p>

    <p>
        Email:<br>
        <input type="email" name="email">
    </p>

    <p>
        Quantidade de Quartos:<br>
        <input type="number" name="quantidade_quartos">
    </p>
    
    <p>
        Possui Wi-Fi:<br>
        <select name="possui_wifi" required>
            <option value="Sim">Sim</option>
            <option value="Não">Não</option>
        </select>
    </p>

    <p>
        Possui Estacionamento:<br>
        <select name="possui_estacionamento" required>
            <option value="Sim">Sim</option>
            <option value="Não">Não</option>
        </select>
    </p>

    <p>
        Data de Cadastro:<br>
        <input type="date" name="data_cadastro" required>
    </p>

    <button type="submit">
        Salvar 
    </button>

</form>


<?php
include "../../includes/footer.php";
?>

