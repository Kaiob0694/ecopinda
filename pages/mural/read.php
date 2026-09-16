<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../config/conexao.php";
require_once "../../classes/mural_fotos.php";
require_once "../../classes/mural_stickers.php";

$baseUrl = 'https://pindaeco.rf.gd';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ' . $baseUrl . '/pages/login.php');
    exit;
}

$usuarioId = $_SESSION['usuario_id'];

$conexao = new Conexao();
$pdo = $conexao->conectar();

$mural = new MuralFotos($pdo);
$muralStickers = new MuralStickers($pdo);

$itensPorPagina = 12;

$paginaAtual = max(
    1,
    isset($_GET['page']) ? (int) $_GET['page'] : 1
);

$offset = ($paginaAtual - 1) * $itensPorPagina;

$fotos = $mural->listar(
    $itensPorPagina,
    $offset
);

$totalFotos = $mural->contar();

$totalPaginas = $totalFotos > 0
    ? ceil($totalFotos / $itensPorPagina)
    : 1;

// Busca todos os adesivos das fotos desta página de uma só vez
$idsDasFotos = array_column($fotos, 'id');
$stickersPorFoto = $muralStickers->listarPorFotos($idsDasFotos);

$mensagem = '';

if (isset($_GET['sucesso'])) {

    $mensagem = $_GET['sucesso'] === '1'
        ? 'Foto enviada com sucesso!'
        : 'Foto deletada com sucesso!';
}

include "../../includes/header.php";
include "../../includes/head.php";

?>

<link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/mural.css">


<div class="mural-container">

    <div class="mural-conteudo">

        <!-- TOPO -->

        <div class="mural-topo">

            <div>

                <h1 class="mural-titulo">
                    Mural de Fotos
                </h1>

                <p class="mural-subtitulo">
                    Compartilhe seus melhores momentos em Pindamonhangaba
                </p>

            </div>


            <button
                type="button"
                class="mural-botao-novo"
                onclick="abrirModal()"
            >
                + Adicionar Foto
            </button>

        </div>


        <!-- MENSAGEM -->

        <?php if (!empty($mensagem)): ?>

            <div class="mural-mensagem sucesso">
                <?= htmlspecialchars($mensagem) ?>
            </div>

        <?php endif; ?>


        <!-- QUANTIDADE -->

        <div class="mural-quantidade">

            <?php if ($totalFotos > 0): ?>

                <?= $totalFotos ?>

                <?= $totalFotos === 1
                    ? 'foto'
                    : 'fotos'
                ?>

            <?php else: ?>

                Nenhuma foto no mural ainda

            <?php endif; ?>

        </div>


        <!-- FOTOS -->

        <?php if (!empty($fotos)): ?>

            <div class="mural-grid">

                <?php foreach ($fotos as $foto): ?>

                    <article
                        class="polaroid-card"
                        data-foto-id="<?= (int) $foto['id'] ?>"
                    >

                        <!-- ADESIVOS COLADOS NESTA FOTO -->

                        <div class="polaroid-stickers">

                            <?php foreach (($stickersPorFoto[$foto['id']] ?? []) as $sticker): ?>

                                <div
                                    class="mural-sticker<?= $usuarioId == $sticker['usuario_id'] ? ' sticker-proprio' : '' ?>"
                                    style="top: <?= (float) $sticker['posicao_top'] ?>%; left: <?= (float) $sticker['posicao_left'] ?>%; transform: rotate(<?= (int) $sticker['rotacao'] ?>deg);"
                                    data-sticker-id="<?= (int) $sticker['id'] ?>"
                                    <?php if ($usuarioId == $sticker['usuario_id']): ?>
                                    title="Clique para remover seu adesivo"
                                    <?php endif; ?>
                                ><?= htmlspecialchars($sticker['emoji']) ?></div>

                            <?php endforeach; ?>

                        </div>


                        <div class="polaroid-imagem">

                            <img
                                src="<?= $baseUrl ?>/assets/uploads/mural/<?= htmlspecialchars($foto['caminho_foto']) ?>"
                                alt="Foto do mural"
                            >

                        </div>


                        <div class="polaroid-conteudo">


                            <?php if (!empty($foto['descricao'])): ?>

                                <p class="polaroid-descricao">

                                    <?= htmlspecialchars(
                                        $foto['descricao']
                                    ) ?>

                                </p>

                            <?php endif; ?>


                            <div class="polaroid-footer">


                                <div class="polaroid-usuario">


                                    <?php if (!empty($foto['usuario_foto'])): ?>

                                        <img
                                            src="<?= $baseUrl ?>/assets/uploads/perfil/<?= htmlspecialchars($foto['usuario_foto']) ?>"
                                            alt="Avatar"
                                            class="polaroid-avatar"
                                        >

                                    <?php else: ?>

                                        <div class="polaroid-avatar-placeholder">

                                            <?= htmlspecialchars(
                                                mb_substr(
                                                    $foto['usuario_nome'],
                                                    0,
                                                    1
                                                )
                                            ) ?>

                                        </div>

                                    <?php endif; ?>


                                    <span class="polaroid-nome">

                                        <?= htmlspecialchars(
                                            $foto['usuario_nome']
                                        ) ?>

                                    </span>


                                </div>


                                <?php if ($usuarioId == $foto['usuario_id']): ?>

                                    <div class="polaroid-acoes">

                                        <a
                                            href="delete.php?id=<?= (int) $foto['id'] ?>"
                                            class="polaroid-deletar"
                                            onclick="return confirm('Excluir esta foto?')"
                                        >
                                            🗑️
                                        </a>

                                    </div>

                                <?php endif; ?>


                            </div>


                            <!-- COLAR ADESIVO -->

                            <div class="polaroid-sticker-acao">

                                <button
                                    type="button"
                                    class="botao-colar-adesivo"
                                    onclick="abrirSeletorAdesivo(this, <?= (int) $foto['id'] ?>)"
                                >
                                    🏷️ Colar adesivo
                                </button>

                            </div>


                            <div class="polaroid-data">

                                <?= date(
                                    'd/m/Y',
                                    strtotime($foto['data_criacao'])
                                ) ?>

                            </div>


                        </div>

                    </article>

                <?php endforeach; ?>

            </div>


            <!-- PAGINAÇÃO -->

            <?php if ($totalPaginas > 1): ?>

                <div class="mural-paginacao">


                    <?php if ($paginaAtual > 1): ?>

                        <a
                            href="?page=1"
                            class="paginacao-link"
                        >
                            « Primeira
                        </a>

                        <a
                            href="?page=<?= $paginaAtual - 1 ?>"
                            class="paginacao-link"
                        >
                            ‹ Anterior
                        </a>

                    <?php endif; ?>


                    <span class="paginacao-info">

                        Página
                        <?= $paginaAtual ?>
                        de
                        <?= $totalPaginas ?>

                    </span>


                    <?php if ($paginaAtual < $totalPaginas): ?>

                        <a
                            href="?page=<?= $paginaAtual + 1 ?>"
                            class="paginacao-link"
                        >
                            Próxima ›
                        </a>

                        <a
                            href="?page=<?= $totalPaginas ?>"
                            class="paginacao-link"
                        >
                            Última »
                        </a>

                    <?php endif; ?>


                </div>

            <?php endif; ?>


        <?php else: ?>


            <!-- MURAL VAZIO -->

            <div class="mural-vazio">

                <h3>
                    📸 Mural vazio
                </h3>

                <p>
                    Seja o primeiro a compartilhar uma foto!
                </p>


                <button
                    type="button"
                    class="mural-botao-novo"
                    onclick="abrirModal()"
                >
                    + Adicionar Foto
                </button>

            </div>


        <?php endif; ?>


    </div>

</div>



<!-- =========================================================
     MODAL DE UPLOAD
     ========================================================= -->

<div
    id="uploadModal"
    class="modal"
>


    <div class="modal-conteudo">


        <span
            class="modal-fechar"
            onclick="fecharModal()"
        >
            &times;
        </span>


        <h2>
            Adicionar Foto ao Mural
        </h2>


        <form
            id="formUpload"
            class="upload-form"
        >


            <!-- FOTO -->

            <div class="upload-secao">

                <label class="upload-label">
                    Foto
                </label>


                <div
                    class="upload-area"
                    id="uploadArea"
                >

                    <input
                        type="file"
                        id="fotoInput"
                        accept="image/*"
                        capture="environment"
                        required
                    >


                    <p>
                        Toque para capturar ou selecionar foto
                    </p>

                </div>


                <!-- PREVIEW -->

                <div
                    id="previewContainer"
                    class="preview-container"
                    style="display: none;"
                >

                    <img
                        id="fotoPreview"
                        alt="Preview"
                    >


                    <button
                        type="button"
                        onclick="limparFoto()"
                    >
                        Alterar foto
                    </button>

                </div>

            </div>



            <!-- DESCRIÇÃO -->

            <div class="upload-secao">

                <label for="descricao">
                    Legenda (opcional)
                </label>


                <textarea
                    id="descricao"
                    name="descricao"
                    placeholder="Conte uma história sobre esta foto..."
                    maxlength="500"
                    rows="4"
                ></textarea>


                <small id="charCount">
                    0/500
                </small>

            </div>



            <!-- BOTÕES -->

            <div class="upload-botoes">


                <button
                    type="button"
                    class="botao-cancelar"
                    onclick="fecharModal()"
                >
                    Cancelar
                </button>


                <button
                    type="submit"
                    class="botao-enviar"
                >
                    Enviar Foto
                </button>


            </div>


        </form>

    </div>

</div>



<script src="<?= $baseUrl ?>/assets/js/mural.js"></script>


<?php

include "../../includes/footer.php";

?>