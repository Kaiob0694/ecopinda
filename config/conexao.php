<?php

class Conexao
{
    
    private $host = "localhost";
    private $usuario = "root";
    private $senha = "1234";
    private $banco = "pindaeco";
    private $porta = 3306;

    public function __construct()
    {
        $servidor = strtolower($_SERVER['SERVER_NAME'] ?? 'localhost');

        // Configuração ONLINE - InfinityFree
        if (in_array($servidor, [
            'pindaeco.rf.gd',
            'www.pindaeco.rf.gd'
        ], true)) {

            $this->host = "sql303.infinityfree.com";
            $this->usuario = "if0_42806347";
            $this->senha = "INJAAAKy48b";
            $this->banco = "if0_42806347_pindaeco";
        }
    }

    public function conectar()
    {
        try {

            $pdo = new PDO(
                "mysql:host={$this->host};port={$this->porta};dbname={$this->banco};charset=utf8mb4",
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

            die(
                "Erro na conexão com o banco de dados: " .
                $erro->getMessage()
            );
        }
    }
}

if (!defined('CODIGO_CADASTRO_ADMIN')) {
    define('CODIGO_CADASTRO_ADMIN', 'ecopinda-admin-2026');
}