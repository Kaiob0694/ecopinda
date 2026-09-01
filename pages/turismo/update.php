<?php

require_once "../../classes/turismo.php";
require_once "../../classes/turismo_fotos.php";
require_once "../../includes/upload_fotos_turismo.php";

$ponto = new PontoTuristico();
$pontoFoto = new PontoTuristicoFoto();
$errosFotos = [];

$id = $_GET['id'];

$dados = $ponto->buscarPorId($id);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ponto->editar(
        $id,
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

    $errosFotos = salvarFotosTurismo($id);

    if (empty($errosFotos)) {
        header("Location: read.php");
        exit;
    }

    // Recarrega os dados atualizados para exibir o formulário de novo.
    $dados = $ponto->buscarPorId($id);
}

$fotos = $pontoFoto->listarPorPonto($id);

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

include "../../includes/head.php";
include "../../includes/header.php";

?>

<link rel="stylesheet" href="/ecopinda/assets/css/cadastrar-turismo.css">
<link rel="stylesheet" href="/ecopinda/assets/css/update-turismo.css">

<div class="cadastro-turismo-container">

    <div class="cadastro-turismo-painel">

        <!-- =====================================================
             TOPO
        ====================================================== -->

        <div class="cadastro-turismo-topo">

            <h1 class="cadastro-turismo-titulo">
                Editar Ponto Turístico
            </h1>

        </div>


        <!-- =====================================================
             ERROS DE UPLOAD
        ====================================================== -->

        <?php if (!empty($errosFotos)): ?>

            <div class="alerta-turismo alerta-turismo-erro">

                <p class="alerta-turismo-titulo">
                    O ponto turístico foi atualizado, mas houve problema
                    com algumas fotos:
                </p>

                <ul class="alerta-turismo-lista">

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

            <div class="fotos-atuais-turismo">

                <h3 class="fotos-atuais-turismo-titulo">
                    Fotos atuais
                </h3>

                <div class="galeria-fotos-turismo">

                    <?php foreach ($fotos as $foto): ?>

                        <div class="foto-turismo-item">

                            <img
                                class="foto-turismo-imagem"
                                src="../../uploads/turismo/<?= htmlspecialchars($foto['caminho']) ?>"
                                alt="Foto do ponto turístico">


                            <a href="delete_foto.php?id=<?= $foto['id'] ?>&id_ponto=<?= $id ?>"
                            class="foto-turismo-excluir"
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
            class="formulario-turismo">

            <div class="formulario-turismo-grid">

                <div class="campo-turismo largo">
                    <label>Nome <span class="obrigatorio">*</span></label>
                    <input
                        type="text"
                        name="nome"
                        value="<?= htmlspecialchars($dados['nome']); ?>"
                        required>
                </div>

                <div class="campo-turismo largo">
                    <label>Descrição</label>
                    <textarea name="descricao"><?= htmlspecialchars($dados['descricao'] ?? ''); ?></textarea>
                </div>

                <div class="campo-turismo largo">
                    <label>Endereço <span class="obrigatorio">*</span></label>
                    <input
                        type="text"
                        name="endereco"
                        value="<?= htmlspecialchars($dados['endereco']); ?>"
                        required>
                </div>

                <div class="campo-turismo">
                    <label>Cidade <span class="obrigatorio">*</span></label>
                    <input
                        type="text"
                        name="cidade"
                        value="<?= htmlspecialchars($dados['cidade']); ?>"
                        required>
                </div>

                <div class="campo-turismo">
                    <label>Estado <span class="obrigatorio">*</span></label>
                    <input
                        type="text"
                        name="estado"
                        maxlength="50"
                        value="<?= htmlspecialchars($dados['estado']); ?>"
                        required>
                </div>

                <div class="campo-turismo">
                    <label>CEP</label>
                    <input
                        type="text"
                        name="cep"
                        value="<?= htmlspecialchars($dados['cep']); ?>">
                </div>

                <div class="campo-turismo">
                    <label>Telefone</label>
                    <input
                        type="text"
                        name="telefone"
                        value="<?= htmlspecialchars($dados['telefone']); ?>">
                </div>

                <div class="campo-turismo">
                    <label>Email</label>
                    <input
                        type="email"
                        name="email"
                        value="<?= htmlspecialchars($dados['email']); ?>">
                </div>

                <div class="campo-turismo">
                    <label>Categoria <span class="obrigatorio">*</span></label>
                    <select name="categoria" required>
                        <?php foreach ($categorias as $categoria): ?>
                            <option
                                value="<?= htmlspecialchars($categoria) ?>"
                                <?= $dados['categoria'] === $categoria ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($categoria) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="campo-turismo largo">
                    <label>Horário de Funcionamento</label>
                    <input
                        type="text"
                        name="horario_funcionamento"
                        value="<?= htmlspecialchars($dados['horario_funcionamento']); ?>">
                </div>

                <div class="campo-turismo">
                    <label>Entrada Gratuita <span class="obrigatorio">*</span></label>
                    <select name="entrada_gratuita" required>
                        <option value="Sim" <?= $dados['entrada_gratuita'] ? 'selected' : '' ?>>Sim</option>
                        <option value="Não" <?= !$dados['entrada_gratuita'] ? 'selected' : '' ?>>Não</option>
                    </select>
                </div>

                <div class="campo-turismo">
                    <label>Possui Estacionamento <span class="obrigatorio">*</span></label>
                    <select name="possui_estacionamento" required>
                        <option value="Sim" <?= $dados['possui_estacionamento'] ? 'selected' : '' ?>>Sim</option>
                        <option value="Não" <?= !$dados['possui_estacionamento'] ? 'selected' : '' ?>>Não</option>
                    </select>
                </div>

                <div class="campo-turismo">
                    <label>Data de Cadastro <span class="obrigatorio">*</span></label>
                    <input
                        type="date"
                        name="data_cadastro"
                        value="<?= htmlspecialchars(substr($dados['data_cadastro'], 0, 10)); ?>"
                        required>
                </div>

                <div class="campo-turismo largo">
                    <label>Adicionar novas fotos</label>
                    <input
                        type="file"
                        name="fotos[]"
                        accept=".jpg,.jpeg,.png,.webp"
                        multiple>
                    <small class="campo-turismo-dica">
                        Você pode selecionar várias fotos de uma vez
                        (JPG, PNG ou WEBP, até 5 MB cada).
                    </small>
                </div>

            </div>


            <!-- =====================================================
                 AÇÕES
            ====================================================== -->

            <div class="formulario-turismo-acoes">

                <a href="read.php" class="botao-voltar-turismo">
                    Cancelar
                </a>

                <button type="submit" class="botao-salvar-turismo">
                    Atualizar
                </button>

            </div>

        </form>

    </div>

</div>

<?php
include "../../includes/footer.php";
?>
