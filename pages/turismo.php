<?php
session_start();

require_once(__DIR__ . "/../src/conexao.php");

$sql = "SELECT * FROM restaurante";
$result = mysqli_query($conexao, $sql);

if (!$result) {
    die("Erro ao buscar restaurantes: " . mysqli_error($conexao));
}
?>


<?php include '../includes/head.php'; ?>
<link rel="stylesheet" href="../assets/css/style_cidade.css"> <!-- // fazer css turismo -->
<?php include '../includes/header.php'; ?>

<section class="hero">
    <video autoplay muted loop playsinline class="video-bg">
        <source src="../assets/video/cidade.mp4" type="video/mp4">
        Seu navegador não suporta a tag de vídeo.
    </video>
</section>

<section class="pontos turisticos">
    <div id="titulo">
        <h3>Descubra Pindamonhangaba</h3>
        <h1>Uma cidade onde história, natureza, cultura e belas paisagens se encontram.</h1>
        <p>Que tal conhecer uma cidade cheia de história, natureza, cultura e lugares encantadores?

        Em Pindamonhangaba, você encontra paisagens incríveis, áreas verdes, construções históricas e experiências para aproveitar com a família, amigos ou até mesmo para aquele passeio tranquilo. 

         Explore as belezas da Serra da Mantiqueira, conheça nossos pontos turísticos, descubra nossa história e aproveite a hospitalidade de uma cidade que tem muito a oferecer!

         Pindamonhangaba, um destino para descobrir, viver e se apaixonar!

         Venha conhecer Pinda e descubra por que vale a pena colocar nossa cidade no seu roteiro!

        #Pindamonhangaba #TurismoPinda #VisitePindamonhangaba #Turismo #ValeDoParaíba #ConheçaPinda #TurismoSP</p>
    </div>
</section>

<section class="pontosturisticos">
<?php if (mysqli_num_rows($result) > 0): ?> <!-- se a condição for verdadeira faça o que está abaixo -->

 <?php while ($row = mysqli_fetch_assoc($result)): ?> <!-- pega resistros do banco um por um  -->

 <article class="card"> <!--criando bloco de conteúdo  -->

  <?php if (!empty($row['imagem'])): ?> <!--verificar se o ponto turistico tem imagem  -->

 <img
 src="../<?= htmlspecialchars($row['imagem']) ?>"
 alt="<?= htmlspecialchars($row['nome']) ?>"  
 > <!-- buscar imagem cadastrada no banco  -->


<?php else: ?> <!-- caso contrario  -->

                <div class="sem-imagem">
                    Sem imagem
                </div>

          <?php endif; ?>   <!-- fim do que começou anteriormente -->

            <h3>
                <?= htmlspecialchars($row['nome']) ?>
            </h3>

            <p>
                <strong>Categoria:</strong>
                <?= htmlspecialchars($row['categoria'] ?? 'Não informado') ?>
            </p>

            <p>
                <strong>Cidade:</strong>
                <?= htmlspecialchars($row['cidade'] ?? 'Não informado') ?>
            </p>

            <p>
                <strong>Horário:</strong>
                <?= htmlspecialchars($row['horario_funcionamento'] ?? 'Não informado') ?>
            </p>

            <p>
                <strong>Delivery:</strong>
                <?= !empty($row['possui_delivery']) ? 'Sim' : 'Não' ?>
            </p>

            <p>
                <strong>Wi-Fi:</strong>
                <?= !empty($row['possui_wifi']) ? 'Sim' : 'Não' ?>
            </p>

            <div class="acoes">

                <a
                    href="../src/editarRestaurante.php?id=<?= $row['id'] ?>"
                    class="btn-editar"
                >
                    Editar
                </a>

                <a
                    href="../src/deletarRestaurante.php?id=<?= $row['id'] ?>"
                    class="btn-excluir"
                    onclick="return confirm('Tem certeza que deseja excluir este restaurante?');"
                >
                    Excluir
                </a>

            </div>

        </article>

    <?php endwhile; ?>

<?php else: ?>

    <div class="nenhum-restaurante">
        <h3>Nenhum restaurante cadastrado</h3>
        <p>Ainda não existem restaurantes cadastrados.</p>
    </div>

<?php endif; ?>

</section>