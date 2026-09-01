<?php
require_once __DIR__ . "/../config/conexao.php";

class RestauranteFoto
{

    private $conexao;

    public function __construct()
    {

        $db = new Conexao();
        $this->conexao = $db->conectar();
    }

    public function adicionar($id_restaurante, $caminho)
    {

        $sql = "INSERT INTO restaurante_foto (id_restaurante, caminho) VALUES (:id_restaurante, :caminho)";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id_restaurante', $id_restaurante);
        $stmt->bindParam(':caminho', $caminho);

        return $stmt->execute();
    }

    public function listarPorRestaurante($id_restaurante)
    {

        $sql = "SELECT * FROM restaurante_foto WHERE id_restaurante = :id_restaurante ORDER BY id ASC";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id_restaurante', $id_restaurante);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id)
    {

        $sql = "SELECT * FROM restaurante_foto WHERE id = :id";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id', $id);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function excluir($id)
    {

        $sql = "DELETE FROM restaurante_foto WHERE id = :id";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function excluirPorRestaurante($id_restaurante)
    {

        $sql = "DELETE FROM restaurante_foto WHERE id_restaurante = :id_restaurante";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id_restaurante', $id_restaurante);

        return $stmt->execute();
    }
}
