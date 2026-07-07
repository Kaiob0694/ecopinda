<?php 
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /hoteis.php');
    exit();
}

require_once __DIR__ . "/../src/conexao.php";

$sql = "SELECT * FROM hoteis";
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


<style>
body{
    font-family: Arial, sans-serif;
    background:#d9d9d9;
    margin:0;
    padding:50px;
}

h1{
    text-align:center;
    margin-bottom:40px;
}

/* GRID AUTOMÁTICA */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(340px, 340px));
    justify-content:center;
    gap:35px;
}

.card{
    width:340px;
    height:256px;
    background:#fffefe;
    border-radius:70px;
    padding:15px;
    text-align:center;
    box-sizing:border-box;
    transition:0.3s;

    border:3px solid #f8f5f5;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
}

/* HOVER MODERNO */
.card:hover{
    transform:translateY(-8px);
    border-color:#6f6f6f;
    box-shadow:0 12px 30px rgba(0,0,0,0.18);
}

.card img{
    width:100%;
    height:120px;
    object-fit:cover;
    border-radius:40px;
    transition:0.3s;
}

.card:hover img{
    transform:scale(1.05);
}

.card h3{
    margin:10px 0 5px;
}

.card p{
    margin:2px 0;
    font-size:14px;
}

.card span{
    display:block;
    margin-top:8px;
    font-weight:bold;
}
</style>
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
    <div class="img-box">
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
</div>

</body>
</html>


