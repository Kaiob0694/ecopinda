<?php

require_once "../../classes/restaurante.php";
require_once "../../classes/restaurante_fotos.php";
require_once "../../includes/upload_fotos_restaurante.php";

$restaurante = new Restaurante();
$restauranteFoto = new RestauranteFoto();
$errosFotos = [];

$id = $_GET['id'];

$dados = $restaurante->buscarPorId($id);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $restaurante->editar(
        $id,
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

    $errosFotos = salvarFotosRestaurante($id);

    if (empty($errosFotos)) {
        header("Location: read.php");
        exit;
    }

    // Recarrega os dados atualizados para exibir o formulário de novo.
    $dados = $restaurante->buscarPorId($id);
}

$fotos = $restauranteFoto->listarPorRestaurante($id);

include "../../includes/head.php";
include "../../includes/header.php";

?>

<link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/cadastrar-restaurante.css">
<link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/update-restaurante.css">

<div class="cadastro-restaurante-container">

    <div class="cadastro-restaurante-painel">

        <!-- =====================================================
             TOPO
        ====================================================== -->

        <div class="cadastro-restaurante-topo">

            <h1 class="cadastro-restaurante-titulo">
                Editar Restaurante
            </h1>

        </div>


        <!-- =====================================================
             ERROS DE UPLOAD
        ====================================================== -->

        <?php if (!empty($errosFotos)): ?>

            <div class="alerta-restaurante alerta-restaurante-erro">

                <p class="alerta-restaurante-titulo">
                    O restaurante foi atualizado, mas houve problema
                    com algumas fotos:
                </p>

                <ul class="alerta-restaurante-lista">

                    <?php foreach ($errosFotos as $erro): ?>

                        <li><?= htmlspecialchars($erro) ?></li>

                    <?php endforeach; ?>

                </ul>

            </div>

        <?php endif; ?>


        <!-- =====================================================
             FOTOS ATUAIS
        ====================================================== -->

        <?php if (!empty($fotos)): ?>

            <div class="fotos-atuais-restaurante">

                <h3 class="fotos-atuais-restaurante-titulo">
                    Fotos atuais
                </h3>

                <div class="galeria-fotos-restaurante">

                    <?php foreach ($fotos as $foto): ?>

                        <div class="foto-restaurante-item">

                            <img
                                class="foto-restaurante-imagem"
                                src="../../uploads/restaurantes/<?= htmlspecialchars($foto['caminho']) ?>"
                                alt="Foto do restaurante">


                            <a href="delete_foto.php?id=<?= $foto['id'] ?>&id_restaurante=<?= $id ?>"
                            class="foto-restaurante-excluir"
                            onclick="return confirm('Excluir esta foto?')">
                            Excluir foto
                            </a>

                        </div>

                    <?php endforeach; ?>

                </div>

            </div>

        <?php endif; ?>


        <!-- =====================================================
             FORMULÁRIO
        ====================================================== -->

        <form
            method="POST"
            enctype="multipart/form-data"
            class="formulario-restaurante">

            <div class="formulario-restaurante-grid">

                <div class="campo-restaurante">
                    <label>Nome <span class="obrigatorio">*</span></label>
                    <input
                        type="text"
                        name="nome"
                        value="<?= htmlspecialchars($dados['nome']); ?>"
                        required>
                </div>

                <div class="campo-restaurante">
                    <label>Cidade <span class="obrigatorio">*</span></label>
                    <input
                        type="text"
                        name="cidade"
                        value="<?= htmlspecialchars($dados['cidade']); ?>"
                        required>
                </div>

                <div class="campo-restaurante largo">
                    <label>Logradouro <span class="obrigatorio">*</span></label>
                    <input
                        type="text"
                        name="logradouro"
                        value="<?= htmlspecialchars($dados['logradouro']); ?>"
                        required>
                </div>

                <div class="campo-restaurante">
                    <label>Número</label>
                    <input
                        type="number"
                        name="numero"
                        value="<?= htmlspecialchars($dados['numero']); ?>">
                </div>

                <div class="campo-restaurante">
                    <label>CEP</label>
                    <input
                        type="text"
                        name="cep"
                        value="<?= htmlspecialchars($dados['cep']); ?>">
                </div>

                <div class="campo-restaurante">
                    <label>Telefone</label>
                    <input
                        type="text"
                        name="telefone"
                        value="<?= htmlspecialchars($dados['telefone']); ?>">
                </div>

                <div class="campo-restaurante">
                    <label>Email</label>
                    <input
                        type="email"
                        name="email"
                        value="<?= htmlspecialchars($dados['email']); ?>">
                </div>

                <div class="campo-restaurante">
                    <label>Categoria</label>
                    <input
                        type="text"
                        name="categoria"
                        value="<?= htmlspecialchars($dados['categoria']); ?>">
                </div>

                <div class="campo-restaurante">
                    <label>Horário de Funcionamento</label>
                    <input
                        type="text"
                        name="horario_funcionamento"
                        value="<?= htmlspecialchars($dados['horario_funcionamento']); ?>">
                </div>

                <div class="campo-restaurante">
                    <label>Possui Delivery <span class="obrigatorio">*</span></label>
                    <select name="possui_delivery" required>
                        <option value="Sim" <?= $dados['possui_delivery'] ? 'selected' : '' ?>>Sim</option>
                        <option value="Não" <?= !$dados['possui_delivery'] ? 'selected' : '' ?>>Não</option>
                    </select>
                </div>

                <div class="campo-restaurante">
                    <label>Possui Wi-Fi <span class="obrigatorio">*</span></label>
                    <select name="possui_wifi" required>
                        <option value="Sim" <?= $dados['possui_wifi'] ? 'selected' : '' ?>>Sim</option>
                        <option value="Não" <?= !$dados['possui_wifi'] ? 'selected' : '' ?>>Não</option>
                    </select>
                </div>

                <div class="campo-restaurante">
                    <label>Data de Cadastro <span class="obrigatorio">*</span></label>
                    <input
                        type="date"
                        name="data_cadastro"
                        value="<?= htmlspecialchars(substr($dados['data_cadastro'], 0, 10)); ?>"
                        required>
                </div>

                <div class="campo-restaurante largo">
                    <label>Adicionar novas fotos</label>
                    <input
                        type="file"
                        name="fotos[]"
                        accept=".jpg,.jpeg,.png,.webp"
                        multiple>
                    <small class="campo-restaurante-dica">
                        Você pode selecionar várias fotos de uma vez
                        (JPG, PNG ou WEBP, até 5 MB cada).
                    </small>
                </div>

            </div>


            <!-- =====================================================
                 AÇÕES
            ====================================================== -->

            <div class="formulario-restaurante-acoes">

                <a href="read.php" class="botao-voltar-restaurante">
                    Cancelar
                </a>

                <button type="submit" class="botao-salvar-restaurante">
                    Atualizar
                </button>

            </div>

        </form>

    </div>

</div>

<?php
include "../../includes/footer.php";
?>
