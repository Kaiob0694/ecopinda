<?php
require_once __DIR__ . "/../config/conexao.php";

class GuiaFoto
{

    private $conexao;

    public function __construct()
    {

        $db = new Conexao();
        $this->conexao = $db->conectar();
    }

    public function adicionar($id_guia, $caminho)
    {

        $sql = "INSERT INTO guia_foto (id_guia, caminho) VALUES (:id_guia, :caminho)";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id_guia', $id_guia);
        $stmt->bindParam(':caminho', $caminho);

        return $stmt->execute();
    }

    public function listarPorGuia($id_guia)
    {

        $sql = "SELECT * FROM guia_foto WHERE id_guia = :id_guia ORDER BY id ASC";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id_guia', $id_guia);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id)
    {

        $sql = "SELECT * FROM guia_foto WHERE id = :id";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id', $id);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function excluir($id)
    {

        $sql = "DELETE FROM guia_foto WHERE id = :id";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function excluirPorGuia($id_guia)
    {

        $sql = "DELETE FROM guia_foto WHERE id_guia = :id_guia";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id_guia', $id_guia);

        return $stmt->execute();
    }
}