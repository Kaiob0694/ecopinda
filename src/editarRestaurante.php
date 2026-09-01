<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/conexao.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID do restaurante não encontrado.");
}

$id = (int) $_GET['id'];

if ($id <= 0) {
    die("ID do restaurante inválido.");
}

$stmt = mysqli_prepare(
    $conexao,
    "SELECT * FROM restaurante WHERE id = ?"
);

if (!$stmt) {
    die("Erro ao buscar restaurante.");
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);

if (!$resultado || mysqli_num_rows($resultado) === 0) {
    die("Restaurante não encontrado.");
}

$restaurante = mysqli_fetch_assoc($resultado);

$categorias = [
    'Restaurante',
    'Lanchonete',
    'Pizzaria',
    'Cafeteria',
    'Padaria',
    'Outro'
];

$categoriaBanco = $restaurante['categoria'] ?? '';

if (in_array($categoriaBanco, $categorias, true)) {
    $categoriaSelecionada = $categoriaBanco;
    $categoriaOutro = '';
} else {
    $categoriaSelecionada = 'Outro';
    $categoriaOutro = $categoriaBanco;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome'] ?? '');

    if ($nome === '') {
        die("O nome do restaurante é obrigatório.");
    }

    if (mb_strlen($nome) < 2 || mb_strlen($nome) > 100) {
        die("O nome deve possuir entre 2 e 100 caracteres.");
    }

    $logradouro = trim($_POST['logradouro'] ?? '');

    if ($logradouro === '') {
        die("O logradouro é obrigatório.");
    }

    if (mb_strlen($logradouro) < 3 || mb_strlen($logradouro) > 150) {
        die("O logradouro deve possuir entre 3 e 150 caracteres.");
    }

    $numero = trim($_POST['numero'] ?? '');

    if ($numero === '' || !ctype_digit($numero)) {
        die("Digite um número de endereço válido.");
    }

    $numero = (int) $numero;

    if ($numero < 1 || $numero > 999999) {
        die("O número deve estar entre 1 e 999999.");
    }

    $cidade = trim($_POST['cidade'] ?? '');

    if ($cidade === '') {
        die("A cidade é obrigatória.");
    }

    if (mb_strlen($cidade) < 2 || mb_strlen($cidade) > 100) {
        die("A cidade deve possuir entre 2 e 100 caracteres.");
    }

    $cep = trim($_POST['cep'] ?? '');

    if (!preg_match('/^[0-9]{5}-?[0-9]{3}$/', $cep)) {
        die("Digite um CEP válido. Exemplo: 12345-678.");
    }

    $cep = preg_replace('/\D/', '', $cep);

    $telefone = trim($_POST['telefone'] ?? '');

    if (!preg_match('/^\([0-9]{2}\) [0-9]{5}-[0-9]{4}$/', $telefone)) {
        die("Digite um telefone válido. Exemplo: (11) 12345-6789.");
    }

    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Digite um e-mail válido.");
    }

    if (mb_strlen($email) > 150) {
        die("O e-mail deve possuir no máximo 150 caracteres.");
    }

    $categoria = trim($_POST['categoria'] ?? '');

    if (!in_array($categoria, $categorias, true)) {
        die("Categoria inválida.");
    }

    $categoriaOutro = trim($_POST['categoria_outro'] ?? '');

    if ($categoria === 'Outro') {

        if ($categoriaOutro === '') {
            die("Digite a categoria.");
        }

        if (mb_strlen($categoriaOutro) < 2 || mb_strlen($categoriaOutro) > 50) {
            die("A categoria deve possuir entre 2 e 50 caracteres.");
        }

        $categoria = $categoriaOutro;
    }

    $possui_delivery = $_POST['possui_delivery'] ?? '';

    if (!in_array($possui_delivery, ['0', '1'], true)) {
        die("Informe se o restaurante possui delivery.");
    }

    $possui_delivery = (int) $possui_delivery;

    $possui_wifi = $_POST['possui_wifi'] ?? '';

    if (!in_array($possui_wifi, ['0', '1'], true)) {
        die("Informe se o restaurante possui Wi-Fi.");
    }

    $possui_wifi = (int) $possui_wifi;

    $horario = trim($_POST['horario_funcionamento'] ?? '');

    if ($horario === '') {
        die("O horário de funcionamento é obrigatório.");
    }

    if (mb_strlen($horario) < 3 || mb_strlen($horario) > 100) {
        die("O horário deve possuir entre 3 e 100 caracteres.");
    }

    $imagem = $restaurante['imagem'] ?? '';

    if (
        isset($_FILES['foto']) &&
        $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            die("Erro ao enviar a imagem.");
        }

        if ($_FILES['foto']['size'] > 5 * 1024 * 1024) {
            die("A imagem deve ter no máximo 5 MB.");
        }

        $tiposPermitidos = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp'
        ];

        if (class_exists('finfo')) {

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $tipoImagem = $finfo->file($_FILES['foto']['tmp_name']);

        } else {

            $tipoImagem = $_FILES['foto']['type'] ?? '';
        }

        if (!isset($tiposPermitidos[$tipoImagem])) {
            die("A imagem deve ser JPG, PNG ou WEBP.");
        }

        $extensao = $tiposPermitidos[$tipoImagem];

        $nomeImagem = uniqid('restaurante_', true) . '.' . $extensao;

        $pasta = __DIR__ . '/../uploads/restaurantes/';

        if (!is_dir($pasta)) {
            if (!mkdir($pasta, 0755, true)) {
                die("Não foi possível criar a pasta de imagens.");
            }
        }

        if (!move_uploaded_file(
            $_FILES['foto']['tmp_name'],
            $pasta . $nomeImagem
        )) {
            die("Não foi possível salvar a imagem.");
        }

        $imagem = $nomeImagem;
    }

    $sql = "UPDATE restaurante SET
        nome = ?,
        logradouro = ?,
        numero = ?,
        cidade = ?,
        cep = ?,
        telefone = ?,
        email = ?,
        categoria = ?,
        possui_delivery = ?,
        possui_wifi = ?,
        imagem = ?,
        horario_funcionamento = ?
        WHERE id = ?";

    $stmt = mysqli_prepare($conexao, $sql);

    if (!$stmt) {
        die("Erro ao preparar atualização: " . mysqli_error($conexao));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "ssisssssiissi",
        $nome,
        $logradouro,
        $numero,
        $cidade,
        $cep,
        $telefone,
        $email,
        $categoria,
        $possui_delivery,
        $possui_wifi,
        $imagem,
        $horario,
        $id
    );

    if (!mysqli_stmt_execute($stmt)) {
        die("Erro ao editar restaurante: " . mysqli_stmt_error($stmt));
    }

    header("Location: ../pages/restaurante.php");
    exit();
}

$horarioFuncionamento = $restaurante['horario_funcionamento'] ?? '';

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Editar Restaurante</title>

    <link
        rel="stylesheet"
        href="../assets/css/formulario.css"
    >

</head>

<body>

<div class="container">

    <h1>Editar Restaurante</h1>

    <form
        method="POST"
        enctype="multipart/form-data"
    >

        <label for="foto">
            Foto do Restaurante:
        </label>

        <?php if (!empty($restaurante['imagem'])): ?>

            <img
                src="../uploads/restaurantes/<?= htmlspecialchars($restaurante['imagem']) ?>"
                alt="Foto atual do restaurante"
                width="250"
            >

            <small>
                Escolha uma nova foto para substituir a atual.
            </small>

        <?php endif; ?>

        <input
            type="file"
            id="foto"
            name="foto"
            accept=".jpg,.jpeg,.png,.webp"
        >

        <label for="nome">
            Nome:
        </label>

        <input
            type="text"
            id="nome"
            name="nome"
            value="<?= htmlspecialchars($restaurante['nome'] ?? '') ?>"
            required
            minlength="2"
            maxlength="100"
            placeholder="Nome do restaurante"
        >

        <label for="logradouro">
            Logradouro:
        </label>

        <input
            type="text"
            id="logradouro"
            name="logradouro"
            value="<?= htmlspecialchars($restaurante['logradouro'] ?? '') ?>"
            required
            minlength="3"
            maxlength="150"
            placeholder="Rua, avenida..."
        >

        <label for="numero">
            Número:
        </label>

        <input
            type="number"
            id="numero"
            name="numero"
            value="<?= htmlspecialchars($restaurante['numero'] ?? '') ?>"
            required
            min="1"
            max="999999"
            placeholder="Número"
        >

        <label for="cidade">
            Cidade:
        </label>

        <input
            type="text"
            id="cidade"
            name="cidade"
            value="<?= htmlspecialchars($restaurante['cidade'] ?? '') ?>"
            required
            minlength="2"
            maxlength="100"
            placeholder="Cidade"
        >

        <label for="cep">
            CEP:
        </label>

        <input
            type="text"
            id="cep"
            name="cep"
            value="<?= htmlspecialchars($restaurante['cep'] ?? '') ?>"
            required
            maxlength="9"
            pattern="[0-9]{5}-?[0-9]{3}"
            placeholder="12345-678"
        >

        <label for="telefone">
            Telefone:
        </label>

        <input
            type="text"
            id="telefone"
            name="telefone"
            value="<?= htmlspecialchars($restaurante['telefone'] ?? '') ?>"
            required
            pattern="\([0-9]{2}\) [0-9]{5}-[0-9]{4}"
            placeholder="(11) 12345-6789"
        >

        <label for="email">
            E-mail:
        </label>

        <input
            type="email"
            id="email"
            name="email"
            value="<?= htmlspecialchars($restaurante['email'] ?? '') ?>"
            required
            maxlength="150"
            placeholder="exemplo@email.com"
        >

        <label for="categoria">
            Categoria:
        </label>

        <select
            id="categoria"
            name="categoria"
            required
            onchange="mostrarOutraCategoria()"
        >

            <option value="">
                Selecione uma categoria
            </option>

            <?php foreach ($categorias as $categoria): ?>

                <option
                    value="<?= htmlspecialchars($categoria) ?>"
                    <?= $categoriaSelecionada === $categoria ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($categoria) ?>
                </option>

            <?php endforeach; ?>

        </select>

        <div
            id="outra_categoria"
            style="<?= $categoriaSelecionada === 'Outro' ? 'display:block;' : 'display:none;' ?>"
        >

            <label for="categoria_outro">
                Digite a categoria:
            </label>

            <input
                type="text"
                id="categoria_outro"
                name="categoria_outro"
                value="<?= htmlspecialchars($categoriaOutro) ?>"
                minlength="2"
                maxlength="50"
                placeholder="Digite a categoria"
                <?= $categoriaSelecionada === 'Outro' ? 'required' : '' ?>
            >

        </div>

        <label for="possui_delivery">
            Possui Delivery?
        </label>

        <select
            id="possui_delivery"
            name="possui_delivery"
            required
        >

            <option value="">
                Selecione
            </option>

            <option
                value="1"
                <?= (string) $restaurante['possui_delivery'] === '1' ? 'selected' : '' ?>
            >
                Sim
            </option>

            <option
                value="0"
                <?= (string) $restaurante['possui_delivery'] === '0' ? 'selected' : '' ?>
            >
                Não
            </option>

        </select>

        <label for="possui_wifi">
            Possui Wi-Fi?
        </label>

        <select
            id="possui_wifi"
            name="possui_wifi"
            required
        >

            <option value="">
                Selecione
            </option>

            <option
                value="1"
                <?= (string) $restaurante['possui_wifi'] === '1' ? 'selected' : '' ?>
            >
                Sim
            </option>

            <option
                value="0"
                <?= (string) $restaurante['possui_wifi'] === '0' ? 'selected' : '' ?>
            >
                Não
            </option>

        </select>

        <label for="horario_funcionamento">
            Horário de Funcionamento:
        </label>

        <input
            type="text"
            id="horario_funcionamento"
            name="horario_funcionamento"
            value="<?= htmlspecialchars($horarioFuncionamento) ?>"
            required
            minlength="3"
            maxlength="100"
            placeholder="Ex: Segunda a sábado, das 08:00 às 18:00"
        >

        <div class="botoes">

            <button type="submit">
                Salvar Alterações
            </button>

            <a
                href="../pages/restaurante.php"
                class="botao-voltar"
            >
                Voltar
            </a>

        </div>

    </form>

</div>

<script>

function mostrarOutraCategoria() {

    const categoria = document.getElementById('categoria');
    const outra = document.getElementById('outra_categoria');
    const campo = document.getElementById('categoria_outro');

    if (categoria.value === 'Outro') {
        outra.style.display = 'block';
        campo.required = true;
    } else {
        outra.style.display = 'none';
        campo.required = false;
        campo.value = '';
    }
}

document.getElementById('cep').addEventListener('blur', function () {

    const cep = this.value.replace(/\D/g, '');

    if (cep.length !== 8) {
        return;
    }

    fetch('https://viacep.com.br/ws/' + cep + '/json/')
        .then(response => response.json())
        .then(data => {

            if (data.erro) {
                alert('CEP não encontrado.');
                return;
            }

            document.getElementById('logradouro').value =
                data.logradouro || '';

            document.getElementById('cidade').value =
                data.localidade || '';

        })
        .catch(() => {
            alert('Erro ao consultar o CEP.');
        });
});

mostrarOutraCategoria();

</script>

</body>
</html>