<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require "../config/conexao.php";

$conexaoObj = new Conexao();
$pdo = $conexaoObj->conectar();


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../pages/cadastro.php");
    exit;
}


$nome = trim($_POST['nome'] ?? '');

$cpf = preg_replace('/\D/', '', $_POST['cpf'] ?? '');

$email = trim($_POST['email'] ?? '');

$telefone = preg_replace('/\D/', '', $_POST['telefone'] ?? '');

$cep = preg_replace('/\D/', '', $_POST['cep'] ?? '');

$senha = trim($_POST['senha'] ?? '');

$senha_confirma = trim($_POST['senha_confirma'] ?? '');

$codigo_admin = trim($_POST['codigo_admin'] ?? '');


// Todo cadastro público será usuário
$tipo_usuario = 'usuario';


$erros = [];


// Guarda os dados digitados

$_SESSION['cadastro_dados'] = [

    'nome' => $nome,

    'cpf' => $cpf,

    'email' => $email,

    'telefone' => $telefone,

    'cep' => $cep,

];


// VALIDAÇÕES

if ($nome === '') {
    $erros[] = "O nome não pode ficar em branco.";
}


if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erros[] = "Informe um e-mail válido.";
}


if ($cpf !== '' && strlen($cpf) !== 11) {
    $erros[] = "O CPF deve conter 11 números.";
}


if ($cep !== '' && strlen($cep) !== 8) {
    $erros[] = "O CEP deve conter 8 números.";
}


if (strlen($senha) < 6) {
    $erros[] = "A senha deve ter no mínimo 6 caracteres.";
}


if ($senha !== $senha_confirma) {
    $erros[] = "A confirmação de senha não confere.";
}


// VERIFICA SE EMAIL OU CPF JÁ EXISTEM

if (empty($erros)) {

    $sqlCheck = "
        SELECT id
        FROM usuarios
        WHERE email = ?
        OR (cpf = ? AND cpf <> '')
    ";

    $stmtCheck = $pdo->prepare($sqlCheck);

    $stmtCheck->execute([
        $email,
        $cpf
    ]);

    if ($stmtCheck->fetch()) {

        $erros[] = "Já existe uma conta com este e-mail ou CPF.";

    }

}


// SE EXISTIR ERRO

if (!empty($erros)) {

    $_SESSION['cadastro_erros'] = $erros;

    header("Location: ../pages/cadastro.php");

    exit;

}


// CRIPTOGRAFA SENHA

$senha_hash = password_hash(
    $senha,
    PASSWORD_DEFAULT
);


// INSERE USUÁRIO

$sqlInsert = "
    INSERT INTO usuarios
    (
        nome,
        cpf,
        email,
        senha,
        telefone,
        cep,
        tipo_usuario
    )
    VALUES
    (?, ?, ?, ?, ?, ?, ?)
";


$stmtInsert = $pdo->prepare($sqlInsert);


if ($stmtInsert->execute([
    $nome,
    $cpf,
    $email,
    $senha_hash,
    $telefone,
    $cep,
    $tipo_usuario
])) {

    $novo_id = $pdo->lastInsertId();


    unset($_SESSION['cadastro_dados']);


    // LOGA USUÁRIO AUTOMATICAMENTE

    $_SESSION['usuario_id'] = $novo_id;

    $_SESSION['usuario_nome'] = $nome;

    $_SESSION['usuario_email'] = $email;

    $_SESSION['usuario_foto'] = '';

    $_SESSION['usuario_tipo'] = $tipo_usuario;


    header("Location: ../pages/profile.php");

    exit;

}


$erros[] = "Erro ao criar a conta.";

$_SESSION['cadastro_erros'] = $erros;

header("Location: ../pages/cadastro.php");

exit;