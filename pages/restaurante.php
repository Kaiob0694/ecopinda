<?php
session_start();
include_once("../src/conexao.php");
 
$sql = "SELECT * FROM restaurante";
$result = mysqli_query($conexao, $sql);
?>
 
<!DOCTYPE html>
<html lang="pt-BR">
 
<head>
    <meta charset="UTF-8">
    <title>Gastronomia - PindaEco Tour</title>
 
    <link rel="stylesheet" href="../assets/css/styleGastronomia.css">
</head>
 
<body>
 
<header>
    <div class="header-container">
 
        <div class="logo">
            <h1>PindaEco Tour</h1>
        </div>
 
        <nav>
            <a href="index.html">Início</a>
            <a href="cidade.html">Cidade</a>
            <a href="turismo.html">Turismo</a>
            <a href="hoteis.html">Hotéis</a>
            <a href="gastronomia.php" class="ativo">Gastronomia</a>
        </nav>
 
    </div>
</header>

<section class="banner"></section>
 
<section class="titulo">
    <h2>Gastronomia de Pindamonhangaba</h2>
    <p>Conheça alguns dos restaurantes da cidade.</p>
</section>
<a href="formularioRestaurante.php">Cadastrar Novo Restaurante</a>
<section class="restaurantes">
 
<?php while ($row = mysqli_fetch_assoc($result)) { ?>
 
    <article class="card">
 
        <img src="<?php echo $row['imagem']; ?>" alt="<?php echo $row['nome']; ?>">
 
        <h3><?php echo $row['nome']; ?></h3>
 
        <p>Categoria: <?php echo $row['categoria']; ?></p>
 
        <p>Cidade: <?php echo $row['cidade']; ?></p>
 
        <p>Horário de funcionamento: <?php echo $row['horario_funcionamento']; ?></p>
 
        <p>Delivery: <?php echo $row['possui_delivery']; ?></p>
 
        <p>Wi-Fi: <?php echo $row['possui_wifi']; ?></p>

        <a 
        href="../src/deletarRestaurante.php?id=<?php echo $row['id']; ?>"
        onclick="return confirm('Tem certeza que deseja excluir este restaurante?');">
            Excluir Restaurante
        </a>

                <a 
        href="../src/deletarRestaurante.php?id=<?php echo $row['id']; ?>"
        onclick="return confirm('Tem certeza que deseja excluir este restaurante?');">
            Editar
        </a>




        
 
    </article>
 
<?php } ?>
 
</section>
 
<footer>
    <p>&copy; 2026 PindaEco Tour - Todos os direitos reservados.</p>
</footer>
 
</body>
</html>


