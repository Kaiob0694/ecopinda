<?php

session_start();

require_once(__DIR__ . "/../config/conexao.php");

$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

try {

    // Cria a conexão PDO
    $pdo = new Conexao();
    $pdo = $pdo->conectar();

    // Busca o usuário pelo e-mail
    $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':email' => $email
    ]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {

        // Verifica a senha
        if (password_verify($senha, $row['senha'])) {

            $_SESSION['usuario_id']    = $row['id'];
            $_SESSION['usuario_nome']  = $row['nome'];
            $_SESSION['usuario_email'] = $row['email'];
            $_SESSION['usuario_foto']  = $row['foto'] ?? '';
            $_SESSION['usuario_tipo']  = $row['tipo_usuario'] ?? 'usuario';

            header("Location: /ecopinda/pages/profile.php");
            exit;

        } else {

            $_SESSION['login_erro']  = "Senha incorreta";
            $_SESSION['login_email'] = $email;

            header("Location: /ecopinda/pages/login.php");
            exit;
        }

    } else {

        $_SESSION['login_erro']  = "Usuário não encontrado";
        $_SESSION['login_email'] = $email;

        header("Location: /ecopinda/pages/login.php");
        exit;
    }

} catch (PDOException $e) {

    $_SESSION['login_erro'] = "Erro ao conectar ao banco de dados.";

    header("Location: /ecopinda/pages/login.php");
    exit;
}