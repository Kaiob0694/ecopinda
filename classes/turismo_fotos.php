<?php
require_once __DIR__ . "/../config/conexao.php";

class PontoTuristicoFoto
{

    private $conexao;

    public function __construct()
    {

        $db = new Conexao();
        $this->conexao = $db->conectar();
    }

    public function adicionar($id_ponto_turistico, $caminho)
    {

        $sql = "INSERT INTO ponto_turistico_foto (id_ponto_turistico, caminho) VALUES (:id_ponto_turistico, :caminho)";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id_ponto_turistico', $id_ponto_turistico);
        $stmt->bindParam(':caminho', $caminho);

        return $stmt->execute();
    }

    public function listarPorPonto($id_ponto_turistico)
    {

        $sql = "SELECT * FROM ponto_turistico_foto WHERE id_ponto_turistico = :id_ponto_turistico ORDER BY id ASC";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id_ponto_turistico', $id_ponto_turistico);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id)
    {

        $sql = "SELECT * FROM ponto_turistico_foto WHERE id = :id";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id', $id);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function excluir($id)
    {

        $sql = "DELETE FROM ponto_turistico_foto WHERE id = :id";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function excluirPorPonto($id_ponto_turistico)
    {

        $sql = "DELETE FROM ponto_turistico_foto WHERE id_ponto_turistico = :id_ponto_turistico";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id_ponto_turistico', $id_ponto_turistico);

        return $stmt->execute();
    }
}
