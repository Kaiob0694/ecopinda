<?php

require_once "../../classes/hoteis.php";
require_once "../../classes/hotel_fotos.php";
require_once "../../includes/upload_fotos_hotel.php";

$hotel = new Hotel();
$hotelFoto = new HotelFoto();
$errosFotos = [];

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

    $errosFotos = salvarFotosHotel($id);

    if (empty($errosFotos)) {
        header("Location: read.php");
        exit;
    }

    // Recarrega os dados atualizados para exibir o formulário de novo.
    $dados = $hotel->buscarPorId($id);
}

$fotos = $hotelFoto->listarPorHotel($id);

include "../../includes/header.php";
include "../../includes/head.php";

?>

<h2>Editar Hotel</h2>

<?php if (!empty($errosFotos)): ?>
    <div class="erros-upload">
        <p>O hotel foi atualizado, mas houve problema com algumas fotos:</p>
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
                    src="../../uploads/hoteis/<?= htmlspecialchars($foto['caminho']) ?>"
                    alt="Foto do hotel"
                    width="180">

                <br>

                <a
                    href="delete_foto.php?id=<?= $foto['id'] ?>&id_hotel=<?= $id ?>"
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
            value="<?= htmlspecialchars($dados['cep']); ?>"
            required>
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
        Quantidade de Quartos:<br>
        <input
            type="number"
            name="quantidade_quartos"
            value="<?= htmlspecialchars($dados['quantidade_quartos']); ?>">
    </p>

    <p>
        Possui Wi-Fi:<br>
        <select name="possui_wifi" required>
            <option value="Sim" <?= $dados['possui_wifi'] ? 'selected' : '' ?>>Sim</option>
            <option value="Não" <?= !$dados['possui_wifi'] ? 'selected' : '' ?>>Não</option>
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
