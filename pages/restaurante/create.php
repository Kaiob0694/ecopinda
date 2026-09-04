<?php

require_once "../../classes/restaurante.php";
require_once "../../includes/upload_fotos_restaurante.php";

$baseUrl = 'https://pindaeco.rf.gd';

$restaurante = new Restaurante();
$errosFotos = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_restaurante = $restaurante->cadastrar(
        $_POST['nome'],
        $_POST['logradouro'],
        $_POST['numero'],
        $_POST['cidade'],
        $_POST['cep'],
        $_POST['telefone'],
        $_POST['email'],
        $_POST['categoria'],
        $_POST['possui_delivery'],
        $_POST['possui_wifi'],
        $_POST['horario_funcionamento'],
        $_POST['data_cadastro']
    );

    if ($id_restaurante) {
        $errosFotos = salvarFotosRestaurante($id_restaurante);
    }

    if (empty($errosFotos)) {
        header("Location: read.php");
        exit;
    }
}

include "../../includes/head.php";
include "../../includes/header.php";
?>

<!-- Tag do CSS com parâmetro para ignorar o cache do navegador -->
<link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/cadastrar-restaurante.css?v=<?= time(); ?>">

<main class="cadastro-restaurante-container">
    <div class="cadastro-restaurante-painel">
        
        <div class="cadastro-restaurante-topo">
            <h2 class="cadastro-restaurante-titulo">Cadastrar Restaurante</h2>
        </div>

        <?php if (!empty($errosFotos)): ?>
            <div class="erros-upload">
                <p>O restaurante foi cadastrado, mas houve problema com algumas fotos:</p>
                <ul>
                    <?php foreach ($errosFotos as $erro): ?>
                        <li><?= htmlspecialchars($erro) ?></li>
                    <?php endforeach; ?>
                </ul>
                <p>
                    <a href="read.php">Ir para a lista de restaurantes</a>
                </p>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="formulario-restaurante">
            
            <div class="formulario-restaurante-grid">

                <div class="campo-restaurante largo">
                    <label>Nome <span class="obrigatorio">*</span></label>
                    <input type="text" name="nome" placeholder="Digite o nome do restaurante" required>
                </div>

                <div class="campo-restaurante largo">
                    <label>Logradouro <span class="obrigatorio">*</span></label>
                    <input type="text" name="logradouro" placeholder="Ex: Av. Principal" required>
                </div>

                <div class="campo-restaurante">
                    <label>Número</label>
                    <input type="number" name="numero" placeholder="123">
                </div>

                <div class="campo-restaurante">
                    <label>Cidade <span class="obrigatorio">*</span></label>
                    <input type="text" name="cidade" required>
                </div>

                <div class="campo-restaurante">
                    <label>CEP</label>
                    <input type="text" name="cep" placeholder="00000-000">
                </div>

                <div class="campo-restaurante">
                    <label>Telefone</label>
                    <input type="text" name="telefone" placeholder="(00) 00000-0000">
                </div>

                <div class="campo-restaurante">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="contato@restaurante.com">
                </div>

                <div class="campo-restaurante">
                    <label>Categoria</label>
                    <input type="text" name="categoria" placeholder="Ex: Italiana, Brasileira, Japonesa">
                </div>

                <div class="campo-restaurante">
                    <label>Horário de Funcionamento</label>
                    <input type="text" name="horario_funcionamento" placeholder="Ex: 11h às 23h">
                </div>

                <div class="campo-restaurante">
                    <label>Possui Delivery <span class="obrigatorio">*</span></label>
                    <select name="possui_delivery" required>
                        <option value="Sim">Sim</option>
                        <option value="Não">Não</option>
                    </select>
                </div>

                <div class="campo-restaurante">
                    <label>Possui Wi-Fi <span class="obrigatorio">*</span></label>
                    <select name="possui_wifi" required>
                        <option value="Sim">Sim</option>
                        <option value="Não">Não</option>
                    </select>
                </div>

                <div class="campo-restaurante">
                    <label>Data de Cadastro <span class="obrigatorio">*</span></label>
                    <input type="date" name="data_cadastro" required>
                </div>

                <div class="campo-restaurante largo">
                    <label>Fotos do Restaurante</label>
                    <input type="file" name="fotos[]" accept=".jpg,.jpeg,.png,.webp" multiple>
                    <small>
                        Você pode selecionar várias fotos de uma vez (JPG, PNG ou WEBP, até 5 MB cada).
                    </small>
                </div>

            </div>

            <div class="formulario-restaurante-acoes">
                <a href="read.php" class="botao-voltar-restaurante">Voltar</a>
                <button type="submit" class="botao-salvar-restaurante">Salvar</button>
            </div>

        </form>

    </div>
</main>

<?php
include "../../includes/footer.php";
?>
