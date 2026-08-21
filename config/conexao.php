<?php

class Conexao
{
    private $host = "localhost";
    private $dbname = "pindaeco";
    private $usuario = "root";
    private $senha = "1234";

    public function conectar()
    {
        try {
            $conexao = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
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