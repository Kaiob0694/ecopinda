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
                $this->senha
            );

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;

        } catch (PDOException $erro) {
            die("Erro na conexão: " . $erro->getMessage());
        }
    }
}