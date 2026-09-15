<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../../config/conexao.php";
require_once "../../classes/mural_fotos.php";

$conexao = new Conexao();
$pdo = $conexao->conectar();

$mural = new MuralFotos($pdo);

$dados = $mural->listar();

include "../../includes/header.php";
include "../../includes/head.php";

?>

<link rel="stylesheet" href="/assets/css/mural.css">

<div class="mural-container">

    <div class="mural-conteudo">

        <!-- CABEÇALHO -->
        <div class="mural-topo">

            <div>
                <h1 class="mural-titulo">Mural</h1>

                <p class="mural-subtitulo">
                    Compartilhe seus momentos em Pindamonhangaba.
                </p>
            </div>

            <button
                type="button"
                class="mural-botao-novo"
                id="abrirModalUpload"
            >
                📸 Publicar foto
            </button>

        </div>


        <!-- MENSAGEM DE SUCESSO / ERRO -->
        <div
            id="mensagemCoin"
            class="mensagem-coin"
            aria-live="polite"
        ></div>


        <!-- MURAL -->
        <?php if (!empty($dados)): ?>

            <div class="mural-grid">

                <?php foreach ($dados as $foto): ?>

                    <article class="polaroid-card">

                        <div class="polaroid-imagem">

                            <img
                                src="/assets/uploads/mural/<?= htmlspecialchars($foto['caminho_foto']) ?>"
                                alt="Foto publicada no Mural"
                                loading="lazy"
                            >

                        </div>

                        <div class="polaroid-info">

                            <?php if (!empty($foto['descricao'])): ?>

                                <p class="polaroid-descricao">
                                    <?= nl2br(htmlspecialchars($foto['descricao'])) ?>
                                </p>

                            <?php endif; ?>


                            <div class="polaroid-rodape">

                                <div class="polaroid-usuario">

                                    <?php if (!empty($foto['usuario_foto'])): ?>

                                        <img
                                            src="/assets/uploads/perfil/<?= htmlspecialchars($foto['usuario_foto']) ?>"
                                            alt="Foto do usuário"
                                        >

                                    <?php else: ?>

                                        <div class="polaroid-avatar">
                                            <?= strtoupper(
                                                mb_substr(
                                                    $foto['usuario_nome'] ?? 'U',
                                                    0,
                                                    1
                                                )
                                            ) ?>
                                        </div>

                                    <?php endif; ?>


                                    <span>
                                        <?= htmlspecialchars($foto['usuario_nome'] ?? 'Usuário') ?>
                                    </span>

                                </div>

                            </div>


                            <?php if (!empty($foto['data_criacao'])): ?>

                                <div class="polaroid-data">

                                    <?= date(
                                        'd/m/Y',
                                        strtotime($foto['data_criacao'])
                                    ) ?>

                                </div>

                            <?php endif; ?>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="mural-vazio">

                <div class="mural-vazio-icone">
                    📸
                </div>

                <h2>Nenhuma foto publicada ainda</h2>

                <p>
                    Seja o primeiro a compartilhar um momento
                    de Pindamonhangaba!
                </p>

            </div>

        <?php endif; ?>

    </div>

</div>


<!-- =========================================================
     MODAL DE UPLOAD
========================================================= -->

<div
    class="upload-modal"
    id="uploadModal"
>

    <div class="upload-modal-conteudo">

        <button
            type="button"
            class="upload-modal-fechar"
            id="fecharModalUpload"
        >
            ×
        </button>


        <h2>Publicar foto</h2>

        <p class="upload-subtitulo">
            Compartilhe um momento especial no Mural.
        </p>


        <form
            id="formUpload"
            enctype="multipart/form-data"
        >

            <!-- ÁREA DE UPLOAD -->

            <div
                class="upload-area"
                id="uploadArea"
            >

                <div class="upload-icone">
                    📸
                </div>

                <h3>
                    Escolha uma foto
                </h3>

                <p>
                    Clique aqui ou arraste sua imagem
                </p>

                <small>
                    JPG, PNG, WEBP ou GIF — máximo 5MB
                </small>

                <input
                    type="file"
                    name="foto"
                    id="fotoInput"
                    accept="image/jpeg,image/png,image/webp,image/gif"
                    hidden
                >

            </div>


            <!-- PREVIEW -->

            <div
                class="preview-container"
                id="previewContainer"
            >

                <img
                    id="fotoPreview"
                    src=""
                    alt="Pré-visualização"
                >

            </div>


            <!-- DESCRIÇÃO -->

            <div class="campo-descricao">

                <label for="descricao">
                    Descrição
                </label>

                <textarea
                    name="descricao"
                    id="descricao"
                    maxlength="500"
                    placeholder="Conte um pouco sobre essa foto..."
                ></textarea>

                <div class="contador">
                    <span id="charCount">0</span>/500
                </div>

            </div>


            <!-- BOTÃO -->

            <button
                type="submit"
                class="botao-publicar"
                id="botaoPublicar"
            >
                📸 Publicar foto
            </button>

        </form>

    </div>

</div>


<script src="/assets/js/mural.js"></script>