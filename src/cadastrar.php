<?php
session_start();
require "conexao.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../pages/cadastro.php");
    exit;
}

$nome           = trim($_POST['nome'] ?? '');
$cpf            = preg_replace('/\D/', '', $_POST['cpf'] ?? '');
$email          = trim($_POST['email'] ?? '');
$telefone       = preg_replace('/\D/', '', $_POST['telefone'] ?? '');
$cep            = preg_replace('/\D/', '', $_POST['cep'] ?? '');
$senha          = trim($_POST['senha'] ?? '');
$senha_confirma = trim($_POST['senha_confirma'] ?? '');
$codigo_admin   = trim($_POST['codigo_admin'] ?? '');

// Nível de acesso: só vira "admin" se o código correto for informado.
// Por padrão, todo cadastro público é criado como "usuario".
$tipo_usuario = ($codigo_admin !== '' && hash_equals(CODIGO_CADASTRO_ADMIN, $codigo_admin))
    ? 'admin'
    : 'usuario';

$erros = [];

// Guarda os dados digitados para reexibir o formulário em caso de erro
$_SESSION['cadastro_dados'] = [
    'nome'     => $nome,
    'cpf'      => $cpf,
    'email'    => $email,
    'telefone' => $telefone,
    'cep'      => $cep,
];

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

// Verifica se e-mail ou CPF já existem
if (empty($erros)) {
    $sqlCheck = "SELECT id FROM usuarios WHERE email = ? OR (cpf = ? AND cpf <> '')";
    $stmtCheck = mysqli_prepare($conexao, $sqlCheck);
    mysqli_stmt_bind_param($stmtCheck, "ss", $email, $cpf);
    mysqli_stmt_execute($stmtCheck);
    $resCheck = mysqli_stmt_get_result($stmtCheck);

    if (mysqli_fetch_assoc($resCheck)) {
        $erros[] = "Já existe uma conta com este e-mail ou CPF.";
    }
    mysqli_stmt_close($stmtCheck);
}

if (!empty($erros)) {
    $_SESSION['cadastro_erros'] = $erros;
    header("Location: ../pages/cadastro.php");
    exit;
}

// Insere o novo usuário
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

$sqlInsert = "INSERT INTO usuarios (nome, cpf, email, senha, telefone, cep, tipo_usuario) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmtInsert = mysqli_prepare($conexao, $sqlInsert);
mysqli_stmt_bind_param($stmtInsert, "sssssss", $nome, $cpf, $email, $senha_hash, $telefone, $cep, $tipo_usuario);

if (mysqli_stmt_execute($stmtInsert)) {

    $novo_id = mysqli_insert_id($conexao);
    mysqli_stmt_close($stmtInsert);

    unset($_SESSION['cadastro_dados']);

    // Loga o usuário automaticamente após o cadastro
    $_SESSION['usuario_id']    = $novo_id;
    $_SESSION['usuario_nome']  = $nome;
    $_SESSION['usuario_email'] = $email;
    $_SESSION['usuario_foto']  = '';
    $_SESSION['usuario_tipo']  = $tipo_usuario;

    header("Location: ../pages/profile.php");
    exit;

} else {
    $erros[] = "Erro ao criar a conta: " . mysqli_error($conexao);
    $_SESSION['cadastro_erros'] = $erros;
    mysqli_stmt_close($stmtInsert);
    header("Location: ../pages/cadastro.php");
    exit;
}