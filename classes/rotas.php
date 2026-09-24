<?php

require_once __DIR__ . "/../config/conexao.php";

class Rotas
{
    private $pdo;

    public function __construct()
    {
        $conexao = new Conexao();
        $this->pdo = $conexao->conectar();
    }

    public function criar(
        $nome,
        $descricao,
        $categoria,
        $dificuldade,
        $duracao,
        $transporte,
        $imagem = null
    ) {
        $sql = "INSERT INTO rotas
                (
                    nome,
                    descricao,
                    categoria,
                    dificuldade,
                    duracao,
                    transporte,
                    imagem
                )
                VALUES
                (
                    :nome,
                    :descricao,
                    :categoria,
                    :dificuldade,
                    :duracao,
                    :transporte,
                    :imagem
                )";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":nome" => $nome,
            ":descricao" => $descricao,
            ":categoria" => $categoria,
            ":dificuldade" => $dificuldade,
            ":duracao" => $duracao,
            ":transporte" => $transporte,
            ":imagem" => $imagem
        ]);

        return $this->pdo->lastInsertId();
    }

    public function listar()
    {
        $sql = "SELECT *
                FROM rotas
                ORDER BY criado_em DESC";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscar($id)
    {
        $sql = "SELECT *
                FROM rotas
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":id" => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function adicionarPonto($rotaId, $pontoId, $ordem)
    {
        $sql = "INSERT INTO rota_pontos
                (
                    rota_id,
                    ponto_id,
                    ordem
                )
                VALUES
                (
                    :rota_id,
                    :ponto_id,
                    :ordem
                )";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":rota_id" => $rotaId,
            ":ponto_id" => $pontoId,
            ":ordem" => $ordem
        ]);
    }

    public function pontosDaRota($rotaId)
    {
        $sql = "SELECT
                    rp.id,
                    rp.ordem,
                    pt.id AS ponto_id,
                    pt.nome,
                    pt.latitude,
                    pt.longitude
                FROM rota_pontos rp

                INNER JOIN pontos_turisticos pt
                    ON pt.id = rp.ponto_id

                WHERE rp.rota_id = :rota_id

                ORDER BY rp.ordem ASC";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":rota_id" => $rotaId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}