<?php 
session_start();

// if (!isset($_SESSION['usuario'])) {
//     header('Location: /hoteis.php');
//     exit();
// }

require_once __DIR__ . "/../src/conexao.php";

$sql = "SELECT * FROM hotel";
$result = mysqli_query($conexao, $sql);

if (!$result) {
    die("Erro na consulta: " . mysqli_error($conexao));
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Hotéis - PindaEco Tour</title>

    <link rel="stylesheet" href="../pages/assets/css/style_hoteis.css">
</head>

<body>

<header>
    <div class="header-container">

        <div class="logo">
            <h1>PindaEco Tour</h1>
        </div>

        <nav>
            <a href="index.php">Início</a>
            <a href="cidade.php">Cidade</a>
            <a href="turismo.php">Turismo</a>
            <a href="hoteis.php"  Class="ativo">Hotéis</a>
            <a href="gastronomia.php">Gastronomia</a>
        </nav>
    </div>
</header>
<a href="formulariohotel.php">Cadastrar Novo hotel</a>
<section class="banner"></section>

<section class="titulo">
    <h2>Hotéis de Pindamonhangaba</h2>
    <p>Conheça alguns dos hotéis  da cidade.</p>
</section>

<section class="restaurantes">

<?php while ($row = mysqli_fetch_assoc($result)) { ?>

    <article class="card">

        <img src="img/default.jpg" alt="Hotel">

        <h3><?php echo $row['nome']; ?></h3>

        <p>Endereço: <?php echo $row['endereco']; ?></p>

        <p>Cidade: <?php echo $row['cidade']; ?></p>

        <p>Telefone: <?php echo $row['telefone']; ?></p>

        <p>Email: <?php echo $row['email']; ?></p>

        <p>Quantidade de quartos: <?php echo $row['quantidade_quartos']; ?></p>

        <p>Possui Wi-Fi: <?php echo $row['possui_wifi'] ? 'Sim' : 'Não'; ?></p>

        <p>Possui Estacionamento: <?php echo $row['possui_estacionamento'] ? 'Sim' : 'Não'; ?></p>

         <a 
        href="../src/deletarhotel.php?id=1<?php echo $row['id']; ?>"
        onclick="return confirm('Tem certeza que deseja excluir este hotel?');">
            Excluir Hotel
        </a>

    </article>

<?php } ?>

</section>

<footer>
    <p>&copy; 2026 PindaEco Tour - Todos os direitos reservados.</p>
</footer>

</body>
</html>