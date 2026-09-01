<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

require_once(__DIR__ . "/../src/conexao.php");

$usuarioId = $_SESSION['usuario_id'];

/*
|--------------------------------------------------------------------------
| BUSCAR POSTAGENS
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT 
        p.id,
        p.texto,
        p.imagem,
        p.criado_em,
        u.id AS usuario_id,
        u.nome,
        u.foto
    FROM postagens p
    INNER JOIN usuarios u ON u.id = p.usuario_id
    ORDER BY p.criado_em DESC
";

$resultado = mysqli_query($conexao, $sql);


/*
|--------------------------------------------------------------------------
| INICIAIS
|--------------------------------------------------------------------------
*/

function iniciaisFeed($nome)
{
    $partes = preg_split('/\s+/', trim($nome));

    $iniciais = mb_substr($partes[0] ?? '', 0, 1);

    if (count($partes) > 1) {
        $iniciais .= mb_substr(end($partes), 0, 1);
    }

    return strtoupper($iniciais);
}

?>

<?php include '../includes/head.php'; ?>

<link rel="stylesheet" href="../assets/css/feed.css">

<?php include '../includes/header.php'; ?>


<main class="feed-page">

    <div class="feed-container">

        <!-- TÍTULO -->

        <div class="feed-title">

            <h1>
                <i class="fa-solid fa-comments"></i>
                Comunidade EcoPinda
            </h1>

            <p>
                Compartilhe momentos, informações e novidades com a comunidade.
            </p>

        </div>


        <!-- CRIAR POSTAGEM -->

        <div class="post-create">

            <div class="post-create-header">

                <div class="post-avatar">

                    <?php if (!empty($_SESSION['usuario_foto'])): ?>

                        <img
                            src="../assets/uploads/perfil/<?= htmlspecialchars($_SESSION['usuario_foto']) ?>"
                            alt="Foto de perfil"
                        >

                    <?php else: ?>

                        <?= htmlspecialchars(
                            iniciaisFeed($_SESSION['usuario_nome'] ?? 'Usuário')
                        ) ?>

                    <?php endif; ?>

                </div>

                <div>
                    <strong>
                        <?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário') ?>
                    </strong>

                    <span>
                        Criar uma postagem
                    </span>
                </div>

            </div>


            <form
                action="../src/criar_postagem.php"
                method="POST"
                enctype="multipart/form-data"
            >

                <textarea
                    name="texto"
                    placeholder="O que você quer compartilhar?"
                    maxlength="2000"
                    required
                ></textarea>


                <div class="post-create-footer">

                    <label class="upload-image">

                        <i class="fa-solid fa-image"></i>

                        Adicionar imagem

                        <input
                            type="file"
                            name="imagem"
                            accept="image/jpeg,image/png,image/webp"
                        >

                    </label>


                    <button type="submit">

                        <i class="fa-solid fa-paper-plane"></i>

                        Publicar

                    </button>

                </div>

            </form>

        </div>


        <!-- FEED -->

        <div class="posts">

            <?php if (mysqli_num_rows($resultado) === 0): ?>

                <div class="empty-feed">

                    <i class="fa-solid fa-comments"></i>

                    <h2>Nenhuma postagem ainda</h2>

                    <p>
                        Seja o primeiro a compartilhar alguma coisa!
                    </p>

                </div>

            <?php endif; ?>


            <?php while ($post = mysqli_fetch_assoc($resultado)): ?>

                <article class="post">

                    <!-- CABEÇALHO -->

                    <div class="post-header">

                        <div class="post-avatar">

                            <?php if (!empty($post['foto'])): ?>

                                <img
                                    src="../assets/uploads/perfil/<?= htmlspecialchars($post['foto']) ?>"
                                    alt="Foto de <?= htmlspecialchars($post['nome']) ?>"
                                >

                            <?php else: ?>

                                <?= htmlspecialchars(
                                    iniciaisFeed($post['nome'])
                                ) ?>

                            <?php endif; ?>

                        </div>


                        <div class="post-user">

                            <strong>
                                <?= htmlspecialchars($post['nome']) ?>
                            </strong>

                            <span>

                                <?= date(
                                    'd/m/Y \à\s H:i',
                                    strtotime($post['criado_em'])
                                ) ?>

                            </span>

                        </div>


                        <?php if ((int)$post['usuario_id'] === (int)$usuarioId): ?>

                            <form
                                action="../src/excluir_postagem.php"
                                method="POST"
                                onsubmit="return confirm('Deseja excluir esta postagem?');"
                            >

                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= (int)$post['id'] ?>"
                                >

                                <button
                                    type="submit"
                                    class="delete-post"
                                    title="Excluir postagem"
                                >

                                    <i class="fa-solid fa-trash"></i>

                                </button>

                            </form>

                        <?php endif; ?>

                    </div>


                    <!-- TEXTO -->

                    <div class="post-text">

                        <?= nl2br(
                            htmlspecialchars($post['texto'])
                        ) ?>

                    </div>


                    <!-- IMAGEM -->

                    <?php if (!empty($post['imagem'])): ?>

                        <div class="post-image">

                            <img
                                src="../assets/uploads/postagens/<?= htmlspecialchars($post['imagem']) ?>"
                                alt="Imagem da postagem"
                            >

                        </div>

                    <?php endif; ?>

                </article>

            <?php endwhile; ?>

        </div>

    </div>

</main>


<?php include '../includes/footer.php'; ?>