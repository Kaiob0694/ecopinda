<?php
session_start();

require_once(__DIR__ . "/../src/conexao.php");

$sql = "SELECT * FROM restaurante";
$result = mysqli_query($conexao, $sql);

if (!$result) {
    die("Erro ao buscar restaurantes: " . mysqli_error($conexao));
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gastronomia - PindaEco Tour</title>

    <link rel="stylesheet" href="../assets/css/styleGastronomia.css">
</head>

<body>
<?php include '../includes/header.php'; ?> 

<section class="banner"></section>

<section class="titulo">
    <h2>Gastronomia de Pindamonhangaba</h2>
    <p>Conheça alguns dos restaurantes da cidade.</p>
</section>

<div class="botao-cadastro">
    <a href="formularioRestaurante.php">
        + Cadastrar Novo Restaurante
    </a>
</div>

<section class="restaurantes">

<?php if (mysqli_num_rows($result) > 0): ?>

    <?php while ($row = mysqli_fetch_assoc($result)): ?>

        <article class="card">

            <?php if (!empty($row['imagem'])): ?>

                <img
                    src="<?php echo htmlspecialchars($row['imagem']); ?>"
                    alt="<?php echo htmlspecialchars($row['nome']); ?>"
                >

            <?php else: ?>

                <div class="sem-imagem">
                    Sem imagem
                </div>

            <?php endif; ?>

            <h3>
                <?php echo htmlspecialchars($row['nome']); ?>
            </h3>

            <p>
                <strong>Categoria:</strong>
                <?php echo htmlspecialchars($row['categoria'] ?? 'Não informado'); ?>
            </p>

            <p>
                <strong>Cidade:</strong>
                <?php echo htmlspecialchars($row['cidade'] ?? 'Não informado'); ?>
            </p>

            <p>
                <strong>Horário:</strong>
                <?php echo htmlspecialchars($row['horario_funcionamento'] ?? 'Não informado'); ?>
            </p>

            <p>
                <strong>Delivery:</strong>
                <?php echo !empty($row['possui_delivery']) ? 'Sim' : 'Não'; ?>
            </p>

            <p>
                <strong>Wi-Fi:</strong>
                <?php echo !empty($row['possui_wifi']) ? 'Sim' : 'Não'; ?>
            </p>

            <div class="acoes">

                <a
                    href="../src/editarRestaurante.php?id=<?php echo $row['id']; ?>"
                    class="btn-editar"
                >
                    Editar
                </a>

                <a
                    href="../src/deletarRestaurante.php?id=<?php echo $row['id']; ?>"
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

<footer>
    <p>&copy; 2026 PindaEco Tour - Todos os direitos reservados.</p>
</footer>

</body>
</html>