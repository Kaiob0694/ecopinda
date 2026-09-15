<?php
$prefixo = '../../';

require_once $prefixo . 'includes/verifica_master.php';
require_once $prefixo . 'includes/conexao.php';
require_once $prefixo . 'classes/GuiasTuristicos.php';

$guiasTuristicos = new GuiasTuristicos($pdo);
$categorias = $guiasTuristicos->listarTodasCategorias();

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome'] ?? '');

    if ($nome === '') {
        $erro = 'O nome do guia é obrigatório.';
    } else {

        $nomeFoto = null;

        // UPLOAD DA FOTO DE PERFIL
        if (!empty($_FILES['foto_perfil']['name']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {

            $permitidos = ['image/jpeg', 'image/png', 'image/webp'];
            $tipo = mime_content_type($_FILES['foto_perfil']['tmp_name']);

            if (!in_array($tipo, $permitidos, true)) {
                $erro = 'Formato de imagem inválido. Use JPG, PNG ou WEBP.';
            } elseif ($_FILES['foto_perfil']['size'] > 3 * 1024 * 1024) {
                $erro = 'A imagem deve ter no máximo 3MB.';
            } else {
                $ext = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
                $nomeFoto = 'guia_' . uniqid('', true) . '.' . strtolower($ext);

                $destino = $prefixo . 'assets/uploads/guias/' . $nomeFoto;

                if (!move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $destino)) {
                    $erro = 'Falha ao enviar a imagem.';
                    $nomeFoto = null;
                }
            }
        }

        if ($erro === '') {

            $dados = [
                'usuario_id'  => !empty($_POST['usuario_id']) ? (int) $_POST['usuario_id'] : null,
                'nome'        => $nome,
                'foto_perfil' => $nomeFoto,
                'descricao'   => trim($_POST['descricao'] ?? ''),
                'experiencia' => trim($_POST['experiencia'] ?? ''),
                'cidade'      => trim($_POST['cidade'] ?? ''),
                'telefone'    => trim($_POST['telefone'] ?? ''),
                'email'       => trim($_POST['email'] ?? ''),
                'instagram'   => trim($_POST['instagram'] ?? ''),
                'status'      => isset($_POST['status']) ? 1 : 0,
            ];

            if ($guiasTuristicos->cadastrar($dados)) {

                $guiaId = $pdo->lastInsertId();

                // CATEGORIAS SELECIONADAS
                foreach (($_POST['categorias'] ?? []) as $categoriaId) {
                    $guiasTuristicos->adicionarCategoria($guiaId, (int) $categoriaId);
                }

                header('Location: read.php?sucesso=cadastrado');
                exit;
            }

            $erro = 'Erro ao cadastrar o guia.';
        }
    }
}

require_once $prefixo . 'includes/header.php';
?>

<link rel="stylesheet" href="<?= $prefixo ?>assets/css/guia-turistico.css">

<main class="guia-container">

    <div class="guia-topo">
        <h1>Cadastrar Guia</h1>
    </div>

    <?php if ($erro): ?>
        <div class="guia-alerta erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form class="guia-form" method="POST" enctype="multipart/form-data">

        <label>
            Nome *
            <input type="text" name="nome" required maxlength="150">
        </label>

        <label>
            Foto de perfil
            <input type="file" name="foto_perfil" accept="image/*">
        </label>

        <label>
            Cidade
            <input type="text" name="cidade" maxlength="100" value="Pindamonhangaba">
        </label>

        <label>
            Descrição
            <textarea name="descricao" rows="4"></textarea>
        </label>

        <label>
            Experiência
            <textarea name="experiencia" rows="2"></textarea>
        </label>

        <label>
            Telefone
            <input type="text" name="telefone" maxlength="30">
        </label>

        <label>
            E-mail
            <input type="email" name="email" maxlength="150">
        </label>

        <label>
            Instagram
            <input type="text" name="instagram" maxlength="150" placeholder="@usuario">
        </label>

        <fieldset class="guia-form-categorias">
            <legend>Categorias</legend>
            <?php foreach ($categorias as $categoria): ?>
                <label class="guia-check">
                    <input type="checkbox" name="categorias[]" value="<?= (int) $categoria['id'] ?>">
                    <?= htmlspecialchars($categoria['nome']) ?>
                </label>
            <?php endforeach; ?>
        </fieldset>

        <label class="guia-check">
            <input type="checkbox" name="status" checked>
            Guia ativo (visível no site)
        </label>

        <div class="guia-form-acoes">
            <button type="submit" class="guia-btn salvar">Cadastrar</button>
            <a href="read.php" class="guia-btn cancelar">Cancelar</a>
        </div>

    </form>

</main>

<?php require_once $prefixo . 'includes/footer.php'; ?>