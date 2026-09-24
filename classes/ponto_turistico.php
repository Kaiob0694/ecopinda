<?php

require_once __DIR__ . "/../config/conexao.php";

class PontosTuristicos
{
    private $pdo;

    public function __construct()
    {
        $conexao = new Conexao();
        $this->pdo = $conexao->conectar();
    }

    /**
     * Lista todos os pontos turísticos
     */
    public function listar()
    {
        $sql = "
            SELECT *
            FROM pontos_turisticos
            ORDER BY nome ASC
        ";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca um ponto turístico pelo ID
     */
    public function buscar($id)
    {
        $sql = "
            SELECT *
            FROM pontos_turisticos
            WHERE id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":id" => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Cadastra um novo ponto turístico
     */
    public function criar(
        $nome,
        $descricao,
        $endereco,
        $latitude,
        $longitude,
        $imagem = null
    ) {
        $sql = "
            INSERT INTO pontos_turisticos
            (
                nome,
                descricao,
                endereco,
                latitude,
                longitude,
                imagem
            )
            VALUES
            (
                :nome,
                :descricao,
                :endereco,
                :latitude,
                :longitude,
                :imagem
            )
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":nome" => $nome,
            ":descricao" => $descricao,
            ":endereco" => $endereco,
            ":latitude" => $latitude,
            ":longitude" => $longitude,
            ":imagem" => $imagem
        ]);
    }

    /**
     * Atualiza um ponto turístico
     */
    public function atualizar(
        $id,
        $nome,
        $descricao,
        $endereco,
        $latitude,
        $longitude,
        $imagem = null
    ) {
        $sql = "
            UPDATE pontos_turisticos
            SET
                nome = :nome,
                descricao = :descricao,
                endereco = :endereco,
                latitude = :latitude,
                longitude = :longitude,
                imagem = :imagem
            WHERE id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":id" => $id,
            ":nome" => $nome,
            ":descricao" => $descricao,
            ":endereco" => $endereco,
            ":latitude" => $latitude,
            ":longitude" => $longitude,
            ":imagem" => $imagem
        ]);
    }

    /**
     * Exclui um ponto turístico
     */
    public function excluir($id)
    {
        $sql = "
            DELETE FROM pontos_turisticos
            WHERE id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":id" => $id
        ]);
    }
}