<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once __DIR__ . '/../../includes/verifica_master.php';
require_once __DIR__ . '/../../classes/guiasTuristicos.php';

$baseUrl = 'https://pindaeco.rf.gd';

$guiasTuristicos = new GuiasTuristicos();

$id = (int) ($_GET['id'] ?? 0);
$guia = $guiasTuristicos->buscarPorId($id);

if (!$guia) {
    header('Location: read.php?erro=nao_encontrado');
    exit;
}

$categorias = $guiasTuristicos->listarTodasCategorias();
$categoriasDoGuia = array_column($guiasTuristicos->listarCategorias($id), 'id');

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome'] ?? '');

    if ($nome === '') {
        $erro = 'O nome do guia é obrigatório.';
    } else {

        $nomeFoto = $guia['foto_perfil'];

        if (!empty($_FILES['foto_perfil']['name']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {

            $permitidos = ['image/jpeg', 'image/png', 'image/webp'];
            $tipo = mime_content_type($_FILES['foto_perfil']['tmp_name']);

            if (!in_array($tipo, $permitidos, true)) {
                $erro = 'Formato de imagem inválido. Use JPG, PNG ou WEBP.';
            } elseif ($_FILES['foto_perfil']['size'] > 3 * 1024 * 1024) {
                $erro = 'A imagem deve ter no máximo 3MB.';
            } else {
                $ext = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
                $novoNome = 'guia_' . uniqid('', true) . '.' . strtolower($ext);

                if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], __DIR__ . '/../../assets/uploads/guias/' . $novoNome)) {

                    if (!empty($guia['foto_perfil'])) {
                        $antiga = __DIR__ . '/../../assets/uploads/guias/' . $guia['foto_perfil'];
                        if (is_file($antiga)) {
                            unlink($antiga);
                        }
                    }

                    $nomeFoto = $novoNome;
                } else {
                    $erro = 'Falha ao enviar a imagem.';
                }
            }
        }

        if ($erro === '') {

            $dados = [
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

            if ($guiasTuristicos->atualizar($id, $dados)) {

                $novasCategorias = array_map('intval', $_POST['categorias'] ?? []);

                foreach (array_diff($novasCategorias, $categoriasDoGuia) as $add) {
                    $guiasTuristicos->adicionarCategoria($id, $add);
                }

                foreach (array_diff($categoriasDoGuia, $novasCategorias) as $remove) {
                    $guiasTuristicos->removerCategoria($id, $remove);
                }

                header('Location: read.php?sucesso=atualizado');
                exit;
            }

            $erro = 'Erro ao atualizar o guia.';
        }
    }
}

$pageTitle = 'Editar Guia';

include __DIR__ . '/../../includes/head.php';
include __DIR__ . '/../../includes/header.php';

?>

<link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/guia-turistico.css">

<main class="guia-container">

    <div class="guia-topo">
        <h1>Editar Guia</h1>
    </div>

    <?php if ($erro): ?>
        <div class="guia-alerta erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form class="guia-form" method="POST" enctype="multipart/form-data">

        <?php if (!empty($guia['foto_perfil'])): ?>
            <div class="guia-form-preview">
                <img src="<?= $baseUrl ?>/assets/uploads/guias/<?= rawurlencode($guia['foto_perfil']) ?>" alt="Foto atual">
            </div>
        <?php endif; ?>

        <label>
            Nome *
            <input type="text" name="nome" required maxlength="150"
                   value="<?= htmlspecialchars($guia['nome']) ?>">
        </label>

        <label>
            Trocar foto de perfil
            <input type="file" name="foto_perfil" accept="image/*">
        </label>

        <label>
            Cidade
            <input type="text" name="cidade" maxlength="100"
                   value="<?= htmlspecialchars($guia['cidade'] ?? '') ?>">
        </label>

        <label>
            Descrição
            <textarea name="descricao" rows="4"><?= htmlspecialchars($guia['descricao'] ?? '') ?></textarea>
        </label>

        <label>
            Experiência
            <textarea name="experiencia" rows="2"><?= htmlspecialchars($guia['experiencia'] ?? '') ?></textarea>
        </label>

        <label>
            Telefone
            <input type="text" name="telefone" maxlength="30"
                   value="<?= htmlspecialchars($guia['telefone'] ?? '') ?>">
        </label>

        <label>
            E-mail
            <input type="email" name="email" maxlength="150"
                   value="<?= htmlspecialchars($guia['email'] ?? '') ?>">
        </label>

        <label>
            Instagram
            <input type="text" name="instagram" maxlength="150"
                   value="<?= htmlspecialchars($guia['instagram'] ?? '') ?>">
        </label>

        <fieldset class="guia-form-categorias">
            <legend>Categorias</legend>
            <?php foreach ($categorias as $categoria): ?>
                <label class="guia-check">
                    <input type="checkbox" name="categorias[]"
                           value="<?= (int) $categoria['id'] ?>"
                           <?= in_array((int) $categoria['id'], $categoriasDoGuia, true) ? 'checked' : '' ?>>
                    <?= htmlspecialchars($categoria['nome']) ?>
                </label>
            <?php endforeach; ?>
        </fieldset>

        <label class="guia-check">
            <input type="checkbox" name="status" <?= $guia['status'] ? 'checked' : '' ?>>
            Guia ativo (visível no site)
        </label>

        <div class="guia-form-acoes">
            <button type="submit" class="guia-btn salvar">Salvar alterações</button>
            <a href="read.php" class="guia-btn cancelar">Cancelar</a>
        </div>

    </form>

</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>