<?php

class GuiasTuristicos
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function listar()
    {
        $sql = "SELECT * FROM guias_turisticos WHERE status = 1 ORDER BY nome ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id)
    {
        $sql = "SELECT * FROM guias_turisticos WHERE id = :id LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function cadastrar($dados)
    {
        $sql = "INSERT INTO guias_turisticos
                (
                    usuario_id,
                    nome,
                    foto_perfil,
                    descricao,
                    experiencia,
                    cidade,
                    telefone,
                    email,
                    instagram,
                    status
                )
                VALUES
                (
                    :usuario_id,
                    :nome,
                    :foto_perfil,
                    :descricao,
                    :experiencia,
                    :cidade,
                    :telefone,
                    :email,
                    :instagram,
                    :status
                )";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':usuario_id', $dados['usuario_id'] ?? null, PDO::PARAM_INT);
        $stmt->bindValue(':nome', $dados['nome']);
        $stmt->bindValue(':foto_perfil', $dados['foto_perfil'] ?? null);
        $stmt->bindValue(':descricao', $dados['descricao'] ?? null);
        $stmt->bindValue(':experiencia', $dados['experiencia'] ?? null);
        $stmt->bindValue(':cidade', $dados['cidade'] ?? null);
        $stmt->bindValue(':telefone', $dados['telefone'] ?? null);
        $stmt->bindValue(':email', $dados['email'] ?? null);
        $stmt->bindValue(':instagram', $dados['instagram'] ?? null);
        $stmt->bindValue(':status', $dados['status'] ?? 1, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function atualizar($id, $dados)
    {
        $sql = "UPDATE guias_turisticos SET
                    nome = :nome,
                    foto_perfil = :foto_perfil,
                    descricao = :descricao,
                    experiencia = :experiencia,
                    cidade = :cidade,
                    telefone = :telefone,
                    email = :email,
                    instagram = :instagram,
                    status = :status
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':nome', $dados['nome']);
        $stmt->bindValue(':foto_perfil', $dados['foto_perfil'] ?? null);
        $stmt->bindValue(':descricao', $dados['descricao'] ?? null);
        $stmt->bindValue(':experiencia', $dados['experiencia'] ?? null);
        $stmt->bindValue(':cidade', $dados['cidade'] ?? null);
        $stmt->bindValue(':telefone', $dados['telefone'] ?? null);
        $stmt->bindValue(':email', $dados['email'] ?? null);
        $stmt->bindValue(':instagram', $dados['instagram'] ?? null);
        $stmt->bindValue(':status', $dados['status'] ?? 1, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function excluir($id)
    {
        $sql = "DELETE FROM guias_turisticos WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function alterarStatus($id, $status)
    {
        $sql = "UPDATE guias_turisticos
                SET status = :status
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':status', $status, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function adicionarCategoria($guiaId, $categoriaId)
    {
        $sql = "INSERT IGNORE INTO guia_categorias
                (guia_id, categoria_id)
                VALUES
                (:guia_id, :categoria_id)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':guia_id', $guiaId, PDO::PARAM_INT);
        $stmt->bindValue(':categoria_id', $categoriaId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function removerCategoria($guiaId, $categoriaId)
    {
        $sql = "DELETE FROM guia_categorias
                WHERE guia_id = :guia_id
                AND categoria_id = :categoria_id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':guia_id', $guiaId, PDO::PARAM_INT);
        $stmt->bindValue(':categoria_id', $categoriaId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function listarCategorias($guiaId)
    {
        $sql = "SELECT c.*
                FROM categorias_turismo c
                INNER JOIN guia_categorias gc
                    ON gc.categoria_id = c.id
                WHERE gc.guia_id = :guia_id
                AND c.status = 1
                ORDER BY c.nome ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':guia_id', $guiaId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarTodasCategorias()
    {
        $sql = "SELECT * FROM categorias_turismo WHERE status = 1 ORDER BY nome ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function adicionarFoto($guiaId, $foto, $descricao = null)
    {
        $sql = "INSERT INTO guia_fotos
                (guia_id, foto, descricao)
                VALUES
                (:guia_id, :foto, :descricao)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':guia_id', $guiaId, PDO::PARAM_INT);
        $stmt->bindValue(':foto', $foto);
        $stmt->bindValue(':descricao', $descricao);

        return $stmt->execute();
    }

    public function listarFotos($guiaId)
    {
        $sql = "SELECT *
                FROM guia_fotos
                WHERE guia_id = :guia_id
                ORDER BY id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':guia_id', $guiaId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function excluirFoto($id)
    {
        $sql = "DELETE FROM guia_fotos WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}