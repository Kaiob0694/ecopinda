<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../config/conexao.php";
require_once "../../classes/mural_fotos.php";

$baseUrl = 'https://pindaeco.rf.gd';

// Verificar se usuário está logado
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ' . $baseUrl . '/pages/login.php');
    exit;
}

$usuarioId = $_SESSION['usuario_id'];

// Conectar banco
$conexao = new Conexao();
$pdo = $conexao->conectar();
$mural = new MuralFotos($pdo);

// Paginação
$itensPorPagina = 12;
$paginaAtual = max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
$offset = ($paginaAtual - 1) * $itensPorPagina;

// Buscar fotos
$fotos = $mural->listar($itensPorPagina, $offset);
$totalFotos = $mural->contar();
$totalPaginas = ceil($totalFotos / $itensPorPagina);

// Mensagens de sucesso
$mensagem = '';
if (isset($_GET['sucesso'])) {
    $mensagem = $_GET['sucesso'] === '1' ? 'Foto enviada com sucesso!' : 'Foto deletada com sucesso!';
}

include "../../includes/header.php";
include "../../includes/head.php";

?>

<link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/mural.css">

<div class="mural-container">

    <div class="mural-conteudo">

        <!-- =====================================================
             CABEÇALHO
        ====================================================== -->
        <div class="mural-topo">
            <div>
                <h1 class="mural-titulo">Mural de Fotos</h1>
                <p class="mural-subtitulo">
                    Compartilhe seus melhores momentos em Pindamonhangaba
                </p>
            </div>

            <button class="mural-botao-novo" onclick="abrirUpload()">
                + Adicionar Foto
            </button>
        </div>

        <!-- =====================================================
             MENSAGEM DE SUCESSO
        ====================================================== -->
        <?php if (!empty($mensagem)): ?>
            <div class="mural-mensagem sucesso">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <!-- =====================================================
             TOTAL DE FOTOS
        ====================================================== -->
        <div class="mural-quantidade">
            <?php if ($totalFotos > 0): ?>
                <?= $totalFotos ?>
                <?= $totalFotos === 1 ? 'foto' : 'fotos' ?>
            <?php else: ?>
                Nenhuma foto no mural ainda
            <?php endif; ?>
        </div>

        <!-- =====================================================
             GRID POLAROID
        ====================================================== -->
        <?php if (!empty($fotos)): ?>

            <div class="mural-grid">

                <?php foreach ($fotos as $foto): ?>

                    <article class="polaroid-card">

                        <!-- IMAGEM -->
                        <div class="polaroid-imagem">
                            <img 
                                src="<?= $baseUrl ?>/assets/uploads/mural/<?= htmlspecialchars($foto['caminho_foto']) ?>" 
                                alt="Foto do mural"
                            >
                        </div>

                        <!-- CONTEÚDO POLAROID -->
                        <div class="polaroid-conteudo">

                            <!-- DESCRIÇÃO -->
                            <?php if (!empty($foto['descricao'])): ?>
                                <p class="polaroid-descricao">
                                    <?= htmlspecialchars($foto['descricao']) ?>
                                </p>
                            <?php endif; ?>

                            <!-- RODAPÉ -->
                            <div class="polaroid-footer">

                                <!-- USUÁRIO -->
                                <div class="polaroid-usuario">
                                    <?php if (!empty($foto['usuario_foto'])): ?>
                                        <img 
                                            src="<?= $baseUrl ?>/assets/uploads/perfil/<?= htmlspecialchars($foto['usuario_foto']) ?>" 
                                            alt="Avatar"
                                            class="polaroid-avatar"
                                        >
                                    <?php else: ?>
                                        <div class="polaroid-avatar-placeholder">
                                            <?= mb_substr($foto['usuario_nome'], 0, 1) ?>
                                        </div>
                                    <?php endif; ?>
                                    <span class="polaroid-nome">
                                        <?= htmlspecialchars($foto['usuario_nome']) ?>
                                    </span>
                                </div>

                                <!-- AÇÕES -->
                                <?php if ($usuarioId == $foto['usuario_id']): ?>
                                    <div class="polaroid-acoes">
                                        <a href="delete.php?id=<?= (int)$foto['id'] ?>" 
                                           class="polaroid-deletar"
                                           onclick="return confirm('Excluir esta foto?')">
                                            🗑️
                                        </a>
                                    </div>
                                <?php endif; ?>

                            </div>

                            <!-- DATA -->
                            <div class="polaroid-data">
                                <?= date('d/m/Y', strtotime($foto['data_criacao'])) ?>
                            </div>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

            <!-- =====================================================
                 PAGINAÇÃO
            ====================================================== -->
            <?php if ($totalPaginas > 1): ?>
                <div class="mural-paginacao">
                    <?php if ($paginaAtual > 1): ?>
                        <a href="?page=1" class="paginacao-link">« Primeira</a>
                        <a href="?page=<?= $paginaAtual - 1 ?>" class="paginacao-link">‹ Anterior</a>
                    <?php endif; ?>

                    <span class="paginacao-info">
                        Página <?= $paginaAtual ?> de <?= $totalPaginas ?>
                    </span>

                    <?php if ($paginaAtual < $totalPaginas): ?>
                        <a href="?page=<?= $paginaAtual + 1 ?>" class="paginacao-link">Próxima ›</a>
                        <a href="?page=<?= $totalPaginas ?>" class="paginacao-link">Última »</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        <?php else: ?>

            <!-- =====================================================
                 MURAL VAZIO
            ====================================================== -->
            <div class="mural-vazio">
                <h3>📸 Mural vazio</h3>
                <p>Seja o primeiro a compartilhar uma foto!</p>
                <button class="mural-botao-novo" onclick="abrirUpload()">
                    + Adicionar Foto
                </button>
            </div>

        <?php endif; ?>

    </div>

</div>

<!-- =====================================================
     MODAL DE UPLOAD
====================================================== -->
<div id="uploadModal" class="modal">
    <div class="modal-conteudo">
        <span class="modal-fechar" onclick="fecharUpload()">&times;</span>

        <h2>Adicionar Foto ao Mural</h2>

        <form id="formUpload" class="upload-form">
            
            <!-- CAPTURA DE FOTO -->
            <div class="upload-secao">
                <label class="upload-label">Foto</label>
                <div class="upload-area" id="uploadArea">
                    <input 
                        type="file" 
                        id="fotoInput" 
                        accept="image/*" 
                        capture="environment"
                        required
                    >
                    <p>Toque para capturar ou selecionar foto</p>
                </div>
                <div id="previewContainer" class="preview-container" style="display: none;">
                    <img id="fotoPreview" alt="Preview">
                    <button type="button" onclick="limparFoto()">Alterar foto</button>
                </div>
            </div>

            <!-- DESCRIÇÃO -->
            <div class="upload-secao">
                <label for="descricao">Legenda (opcional)</label>
                <textarea 
                    id="descricao" 
                    name="descricao" 
                    placeholder="Conte uma história sobre esta foto..."
                    maxlength="500"
                    rows="4"
                ></textarea>
                <small id="charCount">0/500</small>
            </div>

            <!-- BOTÕES -->
            <div class="upload-botoes">
                <button type="button" class="botao-cancelar" onclick="fecharUpload()">
                    Cancelar
                </button>
                <button type="submit" class="botao-enviar">
                    Enviar Foto
                </button>
            </div>

        </form>
    </div>
</div>

<script src="<?= $baseUrl ?>/assets/js/mural.js"></script>

<?php include "../../includes/footer.php"; ?>