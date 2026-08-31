<?php

require_once "../../classes/turismo.php";
require_once "../../includes/upload_fotos_turismo.php";

$ponto = new PontoTuristico();
$errosFotos = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_ponto = $ponto->cadastrar(
        $_POST['nome'],
        $_POST['descricao'],
        $_POST['endereco'],
        $_POST['cidade'],
        $_POST['estado'],
        $_POST['cep'],
        $_POST['telefone'],
        $_POST['email'],
        $_POST['categoria'],
        $_POST['horario_funcionamento'],
        $_POST['entrada_gratuita'],
        $_POST['possui_estacionamento'],
        $_POST['data_cadastro']
    );

    if ($id_ponto) {
        $errosFotos = salvarFotosTurismo($id_ponto);
    }

    if (empty($errosFotos)) {
        header("Location: read.php");
        exit;
    }
    // Se houve erro no upload de alguma foto, o ponto turistico ja foi
    // cadastrado; a mensagem e mostrada abaixo e o usuario pode voltar
    // para a lista.
}

include "../../includes/header.php";
include "../../includes/head.php";

?>

<h2>Cadastrar Ponto Turístico</h2>

<?php if (!empty($errosFotos)): ?>
    <div class="erros-upload">
        <p>O ponto turístico foi cadastrado, mas houve problema com algumas fotos:</p>
        <ul>
            <?php foreach ($errosFotos as $erro): ?>
                <li><?= htmlspecialchars($erro) ?></li>
            <?php endforeach; ?>
        </ul>
        <p><a href="read.php">Ir para a lista de pontos turísticos</a></p>
    </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">

    <p>
        Nome:<br>
        <input type="text" name="nome" required>
    </p>

    <p>
        Descrição:<br>
        <textarea name="descricao" rows="4"></textarea>
    </p>

    <p>
        Endereço:<br>
        <input type="text" name="endereco" required>
    </p>

    <p>
        Cidade:<br>
        <input type="text" name="cidade" required>
    </p>

    <p>
        Estado:<br>
        <input type="text" name="estado" maxlength="50" required>
    </p>

    <p>
        CEP:<br>
        <input type="text" name="cep">
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
        Categoria:<br>
        <select name="categoria" required>
            <option value="Trilha">Trilha</option>
            <option value="Cachoeira">Cachoeira</option>
            <option value="Mirante">Mirante</option>
            <option value="Museu">Museu</option>
            <option value="Praça">Praça</option>
            <option value="Histórico">Histórico</option>
            <option value="Religioso">Religioso</option>
            <option value="Outro">Outro</option>
        </select>
    </p>

    <p>
        Horário de Funcionamento:<br>
        <input type="text" name="horario_funcionamento" placeholder="Ex: Todos os dias, das 08:00 às 18:00">
    </p>

    <p>
        Entrada Gratuita:<br>
        <select name="entrada_gratuita" required>
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

    <p>
        Fotos do Ponto Turístico:<br>
        <input
            type="file"
            name="fotos[]"
            accept=".jpg,.jpeg,.png,.webp"
            multiple>
        <br>
        <small>Você pode selecionar várias fotos de uma vez (JPG, PNG ou WEBP, até 5 MB cada).</small>
    </p>

    <button type="submit">
        Salvar
    </button>

</form>

<?php
include "../../includes/footer.php";
?>
