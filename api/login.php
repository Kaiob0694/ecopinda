<?php
// api/login.php
// Endpoint JSON: POST api/login.php  (email, senha)
// Mesma lógica do src/auth.php, mas responde em JSON em vez de redirecionar.

session_start();
header("Content-Type: application/json; charset=utf-8");
require __DIR__ . "/../src/conexao.php";

// 1) Só aceita POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["erro" => "Método não permitido. Use POST."]);
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

if ($email === '' || $senha === '') {
    http_response_code(400); // Bad Request
    echo json_encode(["erro" => "Informe email e senha."]);
    exit;
}

$sql = "SELECT * FROM usuarios WHERE email = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($resultado)) {

    // Aceita senhas com hash (novos cadastros) e senhas antigas em texto puro,
    // para não travar contas criadas antes do password_hash()
    $senha_confere = password_verify($senha, $row['senha']) || $row['senha'] === $senha;

    if ($senha_confere) {

        $_SESSION['usuario_id']    = $row['id'];
        $_SESSION['usuario_nome']  = $row['nome'];
        $_SESSION['usuario_email'] = $row['email'];
        $_SESSION['usuario_foto']  = $row['foto'] ?? '';

        http_response_code(200); // OK
        echo json_encode([
            "sucesso" => true,
            "usuario" => [
                "id"    => (int) $row['id'],
                "nome"  => $row['nome'],
                "email" => $row['email'],
                "foto"  => $row['foto'] ?? '',
            ],
        ]);
        exit;

    } else {

        http_response_code(401); // Unauthorized
        echo json_encode(["erro" => "Senha incorreta"]);
        exit;

    }

} else {

    http_response_code(404); // Not Found
    echo json_encode(["erro" => "Usuário não encontrado"]);
    exit;

}