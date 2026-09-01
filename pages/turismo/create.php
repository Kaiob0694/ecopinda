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

include "../../includes/head.php";
include "../../includes/header.php";

$categorias = [
    'Trilha',
    'Cachoeira',
    'Mirante',
    'Museu',
    'Praça',
    'Histórico',
    'Religioso',
    'Outro'
];

?>

<!-- Tag do CSS com parâmetro para ignorar o cache do navegador -->
<link rel="stylesheet" href="/ecopinda/assets/css/cadastrar-turismo.css?v=<?= time(); ?>">

<main class="cadastro-turismo-container">
    <div class="cadastro-turismo-painel">

        <div class="cadastro-turismo-topo">
            <h2 class="cadastro-turismo-titulo">Cadastrar Ponto Turístico</h2>
        </div>

        <?php if (!empty($errosFotos)): ?>
            <div class="erros-upload">
                <p>O ponto turístico foi cadastrado, mas houve problema com algumas fotos:</p>
                <ul>
                    <?php foreach ($errosFotos as $erro): ?>
                        <li><?= htmlspecialchars($erro) ?></li>
                    <?php endforeach; ?>
                </ul>
                <p>
                    <a href="read.php">Ir para a lista de pontos turísticos</a>
                </p>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="formulario-turismo">

            <div class="formulario-turismo-grid">

                <div class="campo-turismo largo">
                    <label>Nome <span class="obrigatorio">*</span></label>
                    <input type="text" name="nome" placeholder="Digite o nome do ponto turístico" required>
                </div>

                <div class="campo-turismo largo">
                    <label>Descrição</label>
                    <textarea name="descricao" placeholder="Descreva o ponto turístico"></textarea>
                </div>

                <div class="campo-turismo largo">
                    <label>Endereço <span class="obrigatorio">*</span></label>
                    <input type="text" name="endereco" placeholder="Ex: Av. Principal, 123" required>
                </div>

                <div class="campo-turismo">
                    <label>Cidade <span class="obrigatorio">*</span></label>
                    <input type="text" name="cidade" required>
                </div>

                <div class="campo-turismo">
                    <label>Estado <span class="obrigatorio">*</span></label>
                    <input type="text" name="estado" maxlength="50" required>
                </div>

                <div class="campo-turismo">
                    <label>CEP</label>
                    <input type="text" name="cep" placeholder="00000-000">
                </div>

                <div class="campo-turismo">
                    <label>Telefone</label>
                    <input type="text" name="telefone" placeholder="(00) 00000-0000">
                </div>

                <div class="campo-turismo">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="contato@pontoturistico.com">
                </div>

                <div class="campo-turismo">
                    <label>Categoria <span class="obrigatorio">*</span></label>
                    <select name="categoria" required>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?= htmlspecialchars($categoria) ?>">
                                <?= htmlspecialchars($categoria) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="campo-turismo largo">
                    <label>Horário de Funcionamento</label>
                    <input type="text" name="horario_funcionamento" placeholder="Ex: Todos os dias, das 08:00 às 18:00">
                </div>

                <div class="campo-turismo">
                    <label>Entrada Gratuita <span class="obrigatorio">*</span></label>
                    <select name="entrada_gratuita" required>
                        <option value="Sim">Sim</option>
                        <option value="Não">Não</option>
                    </select>
                </div>

                <div class="campo-turismo">
                    <label>Possui Estacionamento <span class="obrigatorio">*</span></label>
                    <select name="possui_estacionamento" required>
                        <option value="Sim">Sim</option>
                        <option value="Não">Não</option>
                    </select>
                </div>

                <div class="campo-turismo">
                    <label>Data de Cadastro <span class="obrigatorio">*</span></label>
                    <input type="date" name="data_cadastro" required>
                </div>

                <div class="campo-turismo largo">
                    <label>Fotos do Ponto Turístico</label>
                    <input type="file" name="fotos[]" accept=".jpg,.jpeg,.png,.webp" multiple>
                    <small>
                        Você pode selecionar várias fotos de uma vez (JPG, PNG ou WEBP, até 5 MB cada).
                    </small>
                </div>

            </div>

            <div class="formulario-turismo-acoes">
                <a href="read.php" class="botao-voltar-turismo">Voltar</a>
                <button type="submit" class="botao-salvar-turismo">Salvar</button>
            </div>

        </form>

    </div>
</main>

<?php
include "../../includes/footer.php";
?>
