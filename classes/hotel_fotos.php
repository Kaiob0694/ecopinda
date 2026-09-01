<?php
require_once __DIR__ . "/../config/conexao.php";

class HotelFoto
{

    private $conexao;

    public function __construct()
    {

        $db = new Conexao();
        $this->conexao = $db->conectar();
    }

    public function adicionar($id_hotel, $caminho)
    {

        $sql = "INSERT INTO hotel_foto (id_hotel, caminho) VALUES (:id_hotel, :caminho)";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id_hotel', $id_hotel);
        $stmt->bindParam(':caminho', $caminho);

        return $stmt->execute();
    }

    public function listarPorHotel($id_hotel)
    {

        $sql = "SELECT * FROM hotel_foto WHERE id_hotel = :id_hotel ORDER BY id ASC";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id_hotel', $id_hotel);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id)
    {

        $sql = "SELECT * FROM hotel_foto WHERE id = :id";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id', $id);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function excluir($id)
    {

        $sql = "DELETE FROM hotel_foto WHERE id = :id";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function excluirPorHotel($id_hotel)
    {

        $sql = "DELETE FROM hotel_foto WHERE id_hotel = :id_hotel";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id_hotel', $id_hotel);

        return $stmt->execute();
    }
}
