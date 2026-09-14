<?php

require_once "../../classes/hoteis.php";
require_once "../../includes/upload_fotos_hotel.php";

$baseUrl = 'https://pindaeco.rf.gd';

$hotel = new Hotel();
$errosFotos = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_hotel = $hotel->cadastrar(
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

    if ($id_hotel) {
        $errosFotos = salvarFotosHotel($id_hotel);
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
<link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/cadastrar-hotel.css?v=<?= time(); ?>">

<main class="cadastro-hotel-container">
    <div class="cadastro-hotel-painel">
        
        <div class="cadastro-hotel-topo">
            <h2 class="cadastro-hotel-titulo">Cadastrar Hotel</h2>
        </div>

        <?php if (!empty($errosFotos)): ?>
            <div class="erros-upload">
                <p>O hotel foi cadastrado, mas houve problema com algumas fotos:</p>
                <ul>
                    <?php foreach ($errosFotos as $erro): ?>
                        <li><?= htmlspecialchars($erro) ?></li>
                    <?php endforeach; ?>
                </ul>
                <p>
                    <a href="read.php">Ir para a lista de hotéis</a>
                </p>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="formulario-hotel">
            
            <div class="formulario-hotel-grid">

                <div class="campo-hotel largo">
                    <label>Nome <span class="obrigatorio">*</span></label>
                    <input type="text" name="nome" placeholder="Digite o nome do hotel" required>
                </div>

                <div class="campo-hotel largo">
                    <label>Endereço <span class="obrigatorio">*</span></label>
                    <input type="text" name="endereco" placeholder="Ex: Av. Principal, 123" required>
                </div>

                <div class="campo-hotel">
                    <label>Cidade <span class="obrigatorio">*</span></label>
                    <input type="text" name="cidade" required>
                </div>

                <div class="campo-hotel">
                    <label>Estado <span class="obrigatorio">*</span></label>
                    <input type="text" name="estado" maxlength="50" required>
                </div>

                <div class="campo-hotel">
                    <label>CEP <span class="obrigatorio">*</span></label>
                    <input type="text" name="cep" placeholder="00000-000" required>
                </div>

                <div class="campo-hotel">
                    <label>Telefone</label>
                    <input type="text" name="telefone" placeholder="(00) 00000-0000">
                </div>

                <div class="campo-hotel">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="contato@hotel.com">
                </div>

                <div class="campo-hotel">
                    <label>Quantidade de Quartos</label>
                    <input type="number" name="quantidade_quartos" min="1">
                </div>

                <div class="campo-hotel">
                    <label>Possui Wi-Fi <span class="obrigatorio">*</span></label>
                    <select name="possui_wifi" required>
                        <option value="Sim">Sim</option>
                        <option value="Não">Não</option>
                    </select>
                </div>

                <div class="campo-hotel">
                    <label>Possui Estacionamento <span class="obrigatorio">*</span></label>
                    <select name="possui_estacionamento" required>
                        <option value="Sim">Sim</option>
                        <option value="Não">Não</option>
                    </select>
                </div>

                <div class="campo-hotel">
                    <label>Data de Cadastro <span class="obrigatorio">*</span></label>
                    <input type="date" name="data_cadastro" required>
                </div>

                <div class="campo-hotel largo">
                    <label>Fotos do Hotel</label>
                    <input type="file" name="fotos[]" accept=".jpg,.jpeg,.png,.webp" multiple>
                    <small>
                        Você pode selecionar várias fotos de uma vez (JPG, PNG ou WEBP, até 5 MB cada).
                    </small>
                </div>

            </div>

            <div class="formulario-hotel-acoes">
                <a href="read.php" class="botao-voltar-hotel">Voltar</a>
                <button type="submit" class="botao-salvar-hotel">Salvar</button>
            </div>

        </form>

    </div>
</main>

<?php
include "../../includes/footer.php";
?>
