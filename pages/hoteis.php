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
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Hotéis em Pindamonhangaba</title>
<link rel="stylesheet" href="assets/css/style_hoteis.css">


</head>

<body>

<div class="container">


<h1>Onde se Hospedar em Pindamonhangaba</h1>

<p>
    <a href="formulariohotel.php">Cadastrar novo hotel</a> |
    <a href="logout.php">Sair</a>
</p>

<div class="grid">

<?php while ($hotel = mysqli_fetch_assoc($result)) { ?>
    <div class="card">
        <div class="img-box">
            <a href="#">
                <img src="<?php echo $hotel['imagem']; ?>" alt="<?php echo $hotel['nome']; ?>">
            </a>
        </div>

        <h3><?php echo $hotel['nome']; ?></h3>
        <p><?php echo $hotel['endereco']; ?></p>
        <span><?php echo $hotel['telefone']; ?></span>
    </div>
<?php } ?>
</div>
</body>
    <!-- <div class="img-box">
        <a href="#">
            <img src="img/Hotel Vitória.jpg" alt="Hotel Vitória">
        </a>
    </div>

    <h3>Hotel Vitória</h3>
    <p>Rua José Maria Monteiro, 36 - Jardim Imperial </p>
    <p>Jardim Imperial - CEP: 12412-380</p>
    <span>(12) 3642-1623</span>

</div>

<div class="card domum">

    <div class="img-box">
        <a href="#">
            <img src="img/Domum.jfif" alt="DOMUM Hotel">
        </a>
    </div>

    <h3>DOMUM Hotel</h3>
    <p>Travessa Dr. Antonio Pinheiro Júnior, 91</p>
    <p>Centro Pindamonhangaba</p>
    <span>(12) 98283-6018</span>

</div>
<div class="card">

    <div class="img-box">
        <a href="#">
            <img src="img/avenida_plaza_hotel.jpg" alt="Hotel Avenida">
        </a>
    </div>

    <h3>Hotel Avenida</h3>
    <p>José Monteiro Machado César, 403</p>
    <p>Moreira Cesar</p>
    <span>(12) 3637-1744</span>

</div>

<div class="card">

    <div class="img-box">
        <a href="#">
            <img src="img/hotel_brasil.jpg" alt="Hotel Brasil">
        </a>
    </div>

    <h3>Hotel Brasil</h3>
    <p>R. Dez de Julho, 48</p>
    <p>Centro Pindamonhangaba</p>
    <span>(12) 3643-2229</span>

</div>

<div class="card">

    <div class="img-box">
        <a href="#">
            <img src="img/hotel_central_pet_friendly.jpg" alt="Hotel Center">
        </a>
    </div>

    <h3>Hotel Central</h3>
    <p>Rua dos Expedicionários, 182</p>
    <p>Centro Pindamonhangaba</p>
    <span>(12) 3642-3164</span>

</div>

<div class="card">

    <div class="img-box">
        <a href="#">
            <img src="img/hotel_fazenda_pe_da_serra_pet_friendly.jpg" alt="Hotel Fazenda Pé da Serra">
        </a>
    </div>

    <h3>Hotel Fazenda Pé da Serra</h3>
    <p>Rod. Dr. Caio Gomes Figueiredo, km 157</p>
    <p>Centro Pindamonhangaba</p>
    <span>(12) 99676-1525</span>

</div>
</div>
</div> -->

</body>
</html>


