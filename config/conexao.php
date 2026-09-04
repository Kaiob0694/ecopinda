<?php

class Conexao
{
    private $host = "sql303.infinityfree.com";
    private $usuario = "if0_42806347";
    private $senha = "INJAAAKy48b";
    private $banco = "if0_42806347_pindaeco";

    public function conectar()
    {
        try {
            $pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->banco};charset=utf8mb4",
                $this->usuario,
                $this->senha,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );

            return $pdo;

        } catch (PDOException $erro) {
            die("Erro na conexão com o banco de dados: " . $erro->getMessage());
        }
    }
}

if (!defined('CODIGO_CADASTRO_ADMIN')) {
    define('CODIGO_CADASTRO_ADMIN', 'ecopinda-admin-2026');
}