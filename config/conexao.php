```php
<?php

class Conexao
{
    private $host = "sql303.infinityfree.com";
    private $dbname = "if0_42806347_pindaeco";
    private $usuario = "if0_42806347";
    private $senha = "INJAAAKy48b";

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

            $conexao->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );

            return $conexao;

        } catch (PDOException $erro) {

            die("ERRO: " . $erro->getMessage());

        }
    }
}
