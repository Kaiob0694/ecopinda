<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// $host = "sql206.infinityfree.com";
// $usuario = "if0_42297225";
// $senha = "zgbGSOdQNMrhcud";
// $banco = "if0_42297225_pindaeco";

$host = "localhost";
$usuario = "root";
$senha = "1234";
$banco = "pindaeco";

$conexao = mysqli_connect($host, $usuario,$senha, $banco);

if (!$conexao) {
    die("Erro na Conexão: " . mysqli_connect_error());
}

mysqli_set_charset($conexao, "utf8mb4");

class Conexao
{
    private $host = "localhost";
    private $dbname = "escola";
    private $usuario = "root";
    private $senha = "1234";

    public function conectar()
    {
        try {

            $conexao = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8",
                $this->usuario,
                $this->senha
            );

            $conexao->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            return $conexao;

        } catch (PDOException $erro) {

            die("Erro na conexão: " . $erro->getMessage());

        }
    }
}

