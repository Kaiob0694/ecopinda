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

include "../../includes/header.php";
include "../../includes/head.php";

?>

<h2>Editar Ponto Turístico</h2>

<?php if (!empty($errosFotos)): ?>
    <div class="erros-upload">
        <p>O ponto turístico foi atualizado, mas houve problema com algumas fotos:</p>
        <ul>
            <?php foreach ($errosFotos as $erro): ?>
                <li><?= htmlspecialchars($erro) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if (!empty($fotos)): ?>

    <h3>Fotos atuais</h3>

    <div class="galeria-fotos-hotel">

        <?php foreach ($fotos as $foto): ?>

            <div class="foto-hotel-item">

                <img
                    src="../../uploads/turismo/<?= htmlspecialchars($foto['caminho']) ?>"
                    alt="Foto do ponto turístico"
                    width="180">

                <br>

                <a
                    href="delete_foto.php?id=<?= $foto['id'] ?>&id_ponto=<?= $id ?>"
                    onclick="return confirm('Excluir esta foto?')"
                >
                    Excluir foto
                </a>

            </div>

        <?php endforeach; ?>

    </div>

<?php endif; ?>

<form method="POST" enctype="multipart/form-data">

    <p>
        Nome:<br>
        <input
            type="text"
            name="nome"
            value="<?= htmlspecialchars($dados['nome']); ?>"
            required>
    </p>

    <p>
        Descrição:<br>
        <textarea name="descricao" rows="4"><?= htmlspecialchars($dados['descricao'] ?? ''); ?></textarea>
    </p>

    <p>
        Endereço:<br>
        <input
            type="text"
            name="endereco"
            value="<?= htmlspecialchars($dados['endereco']); ?>"
            required>
    </p>

    <p>
        Cidade:<br>
        <input
            type="text"
            name="cidade"
            value="<?= htmlspecialchars($dados['cidade']); ?>"
            required>
    </p>

    <p>
        Estado:<br>
        <input
            type="text"
            name="estado"
            maxlength="50"
            value="<?= htmlspecialchars($dados['estado']); ?>"
            required>
    </p>

    <p>
        CEP:<br>
        <input
            type="text"
            name="cep"
            value="<?= htmlspecialchars($dados['cep']); ?>">
    </p>

    <p>
        Telefone:<br>
        <input
            type="text"
            name="telefone"
            value="<?= htmlspecialchars($dados['telefone']); ?>">
    </p>

    <p>
        Email:<br>
        <input
            type="email"
            name="email"
            value="<?= htmlspecialchars($dados['email']); ?>">
    </p>

    <p>
        Categoria:<br>
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
    </p>

    <p>
        Horário de Funcionamento:<br>
        <input
            type="text"
            name="horario_funcionamento"
            value="<?= htmlspecialchars($dados['horario_funcionamento']); ?>">
    </p>

    <p>
        Entrada Gratuita:<br>
        <select name="entrada_gratuita" required>
            <option value="Sim" <?= $dados['entrada_gratuita'] ? 'selected' : '' ?>>Sim</option>
            <option value="Não" <?= !$dados['entrada_gratuita'] ? 'selected' : '' ?>>Não</option>
        </select>
    </p>

    <p>
        Possui Estacionamento:<br>
        <select name="possui_estacionamento" required>
            <option value="Sim" <?= $dados['possui_estacionamento'] ? 'selected' : '' ?>>Sim</option>
            <option value="Não" <?= !$dados['possui_estacionamento'] ? 'selected' : '' ?>>Não</option>
        </select>
    </p>

    <p>
        Data de Cadastro:<br>
        <input
            type="date"
            name="data_cadastro"
            value="<?= htmlspecialchars($dados['data_cadastro']); ?>"
            required>
    </p>

    <p>
        Adicionar novas fotos:<br>
        <input
            type="file"
            name="fotos[]"
            accept=".jpg,.jpeg,.png,.webp"
            multiple>
        <br>
        <small>Você pode selecionar várias fotos de uma vez (JPG, PNG ou WEBP, até 5 MB cada).</small>
    </p>

    <button type="submit">
        Atualizar
    </button>

</form>

<?php
include "../../includes/footer.php";
?>
