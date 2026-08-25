<?php
 
class Conexao
{
    private $host = "localhost";
    private $usuario = "root";
    private $senha = "1234";
    private $banco = "pindaeco";
 
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
 
        } catch (PDOException $e) {
 
            die("Erro na conexão: " . $e->getMessage());
 
        }
    }
}
