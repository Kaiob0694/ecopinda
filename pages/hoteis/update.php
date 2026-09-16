<?php

require_once "../../classes/hoteis.php";
require_once "../../classes/hotel_fotos.php";
require_once "../../includes/upload_fotos_hotel.php";

$baseUrl = 'https://pindaeco.rf.gd';

$hotel = new Hotel();
$hotelFoto = new HotelFoto();

$errosFotos = [];

// =========================================================
// VERIFICA ID
// =========================================================

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: read.php");
    exit;
}

// =========================================================
// BUSCA HOTEL
// =========================================================

$dados = $hotel->buscarPorId($id);

if (!$dados) {
    header("Location: read.php");
    exit;
}

// =========================================================
// ATUALIZA HOTEL
// =========================================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST['nome'] ?? '');
    $endereco = trim($_POST['endereco'] ?? '');
    $cidade = trim($_POST['cidade'] ?? '');
    $estado = trim($_POST['estado'] ?? '');
    $cep = trim($_POST['cep'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $quantidade_quartos = $_POST['quantidade_quartos'] ?? 0;
    $possui_wifi = $_POST['possui_wifi'] ?? 'Não';
    $possui_estacionamento = $_POST['possui_estacionamento'] ?? 'Não';
    $data_cadastro = $_POST['data_cadastro'] ?? '';

    // Atualiza os dados do hotel
    $hotel->editar(
        $nome,
        $endereco,
        $cidade,
        $estado,
        $cep,
        $telefone,
        $email,
        $quantidade_quartos,
        $possui_wifi,
        $possui_estacionamento,
        $data_cadastro,
        $id
    );

    // =====================================================
    // SALVA NOVAS FOTOS
    // =====================================================

    $errosFotos = salvarFotosHotel($id);

    // =====================================================
    // REDIRECIONA SE DEU TUDO CERTO
    // =====================================================

    if (empty($errosFotos)) {
        header("Location: read.php");
        exit;
    }

    // Recarrega os dados atualizados
    $dados = $hotel->buscarPorId($id);
}

// =========================================================
// BUSCA FOTOS
// =========================================================

$fotos = $hotelFoto->listarPorHotel($id);

// =========================================================
// HTML
// =========================================================

include "../../includes/head.php";
include "../../includes/header.php";

?>

<link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/cadastrar-hotel.css">
<link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/update-hotel.css">


<div class="cadastro-hotel-container">

    <div class="cadastro-hotel-painel">

        <!-- =====================================================
             TOPO
        ====================================================== -->

        <div class="cadastro-hotel-topo">

            <h1 class="cadastro-hotel-titulo">
                Editar Hotel
            </h1>

        </div>


        <!-- =====================================================
             ERROS DE UPLOAD
        ====================================================== -->

        <?php if (!empty($errosFotos)): ?>

            <div class="alerta-hotel alerta-hotel-erro">

                <p class="alerta-hotel-titulo">
                    O hotel foi atualizado, mas houve problema
                    com algumas fotos:
                </p>

                <ul class="alerta-hotel-lista">

                    <?php foreach ($errosFotos as $erro): ?>

                        <li>
                            <?= htmlspecialchars($erro) ?>
                        </li>

                    <?php endforeach; ?>

                </ul>

            </div>

        <?php endif; ?>


        <!-- =====================================================
             FOTOS ATUAIS
        ====================================================== -->

        <?php if (!empty($fotos)): ?>

            <div class="fotos-atuais-hotel">

                <h3 class="fotos-atuais-hotel-titulo">
                    Fotos atuais
                </h3>

                <div class="galeria-fotos-hotel">

                    <?php foreach ($fotos as $foto): ?>

                        <div class="foto-hotel-item">

                            <img
                                class="foto-hotel-imagem"
                                src="<?= $baseUrl ?>/assets/uploads/hoteis/<?= htmlspecialchars($foto['caminho']) ?>"
                                alt="Foto do hotel"
                            >

                            <a
                                href="delete_foto.php?id=<?= (int)$foto['id'] ?>&id_hotel=<?= (int)$id ?>"
                                class="foto-hotel-excluir"
                                onclick="return confirm('Excluir esta foto?')"
                            >
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
            class="formulario-hotel"
        >

            <div class="formulario-hotel-grid">

                <!-- NOME -->

                <div class="campo-hotel">

                    <label>
                        Nome <span class="obrigatorio">*</span>
                    </label>

                    <input
                        type="text"
                        name="nome"
                        value="<?= htmlspecialchars($dados['nome'] ?? '') ?>"
                        required
                    >

                </div>


                <!-- CIDADE -->

                <div class="campo-hotel">

                    <label>
                        Cidade <span class="obrigatorio">*</span>
                    </label>

                    <input
                        type="text"
                        name="cidade"
                        value="<?= htmlspecialchars($dados['cidade'] ?? '') ?>"
                        required
                    >

                </div>


                <!-- ENDEREÇO -->

                <div class="campo-hotel largo">

                    <label>
                        Endereço <span class="obrigatorio">*</span>
                    </label>

                    <input
                        type="text"
                        name="endereco"
                        value="<?= htmlspecialchars($dados['endereco'] ?? '') ?>"
                        required
                    >

                </div>


                <!-- ESTADO -->

                <div class="campo-hotel">

                    <label>
                        Estado <span class="obrigatorio">*</span>
                    </label>

                    <input
                        type="text"
                        name="estado"
                        maxlength="50"
                        value="<?= htmlspecialchars($dados['estado'] ?? '') ?>"
                        required
                    >

                </div>


                <!-- CEP -->

                <div class="campo-hotel">

                    <label>
                        CEP <span class="obrigatorio">*</span>
                    </label>

                    <input
                        type="text"
                        name="cep"
                        value="<?= htmlspecialchars($dados['cep'] ?? '') ?>"
                        required
                    >

                </div>


                <!-- TELEFONE -->

                <div class="campo-hotel">

                    <label>
                        Telefone
                    </label>

                    <input
                        type="text"
                        name="telefone"
                        value="<?= htmlspecialchars($dados['telefone'] ?? '') ?>"
                    >

                </div>


                <!-- EMAIL -->

                <div class="campo-hotel">

                    <label>
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="<?= htmlspecialchars($dados['email'] ?? '') ?>"
                    >

                </div>


                <!-- QUANTIDADE DE QUARTOS -->

                <div class="campo-hotel">

                    <label>
                        Quantidade de Quartos
                    </label>

                    <input
                        type="number"
                        name="quantidade_quartos"
                        min="0"
                        value="<?= htmlspecialchars($dados['quantidade_quartos'] ?? '') ?>"
                    >

                </div>


                <!-- WI-FI -->

                <div class="campo-hotel">

                    <label>
                        Possui Wi-Fi <span class="obrigatorio">*</span>
                    </label>

                    <select name="possui_wifi" required>

                        <option
                            value="Sim"
                            <?= ((int)($dados['possui_wifi'] ?? 0) === 1) ? 'selected' : '' ?>
                        >
                            Sim
                        </option>

                        <option
                            value="Não"
                            <?= ((int)($dados['possui_wifi'] ?? 0) === 0) ? 'selected' : '' ?>
                        >
                            Não
                        </option>

                    </select>

                </div>


                <!-- ESTACIONAMENTO -->

                <div class="campo-hotel">

                    <label>
                        Possui Estacionamento <span class="obrigatorio">*</span>
                    </label>

                    <select
                        name="possui_estacionamento"
                        required
                    >

                        <option
                            value="Sim"
                            <?= ((int)($dados['possui_estacionamento'] ?? 0) === 1) ? 'selected' : '' ?>
                        >
                            Sim
                        </option>

                        <option
                            value="Não"
                            <?= ((int)($dados['possui_estacionamento'] ?? 0) === 0) ? 'selected' : '' ?>
                        >
                            Não
                        </option>

                    </select>

                </div>


                <!-- DATA -->

                <div class="campo-hotel">

                    <label>
                        Data de Cadastro <span class="obrigatorio">*</span>
                    </label>

                    <input
                        type="date"
                        name="data_cadastro"
                        value="<?= htmlspecialchars(substr($dados['data_cadastro'] ?? '', 0, 10)) ?>"
                        required
                    >

                </div>


                <!-- FOTOS -->

                <div class="campo-hotel largo">

                    <label>
                        Adicionar novas fotos
                    </label>

                    <input
                        type="file"
                        name="fotos[]"
                        accept=".jpg,.jpeg,.png,.webp"
                        multiple
                    >

                    <small class="campo-hotel-dica">
                        Você pode selecionar várias fotos de uma vez
                        (JPG, PNG ou WEBP, até 5 MB cada).
                    </small>

                </div>

            </div>


            <!-- =====================================================
                 AÇÕES
            ====================================================== -->

            <div class="formulario-hotel-acoes">

                <a
                    href="read.php"
                    class="botao-voltar-hotel"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="botao-salvar-hotel"
                >
                    Atualizar
                </button>

            </div>

        </form>

    </div>

</div>


<?php

include "../../includes/footer.php";

?>