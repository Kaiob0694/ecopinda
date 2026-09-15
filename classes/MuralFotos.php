<?php

class MuralFotos
{
    private $pdo;
    private $tabela = 'mural_fotos';

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Criar nova foto no mural
     */
    public function criar($usuarioId, $caminhoFoto, $descricao = '')
    {
        try {
            $sql = "INSERT INTO {$this->tabela} 
                    (usuario_id, caminho_foto, descricao, data_criacao) 
                    VALUES 
                    (?, ?, ?, NOW())";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$usuarioId, $caminhoFoto, $descricao]);

            return $this->pdo->lastInsertId();

        } catch (PDOException $erro) {
            throw new Exception("Erro ao criar foto: " . $erro->getMessage());
        }
    }

    /**
     * Listar todas as fotos (com dados do usuário)
     */
    public function listar($limite = 50, $offset = 0)
    {
        try {
            $sql = "SELECT 
                        m.id,
                        m.usuario_id,
                        m.caminho_foto,
                        m.descricao,
                        m.data_criacao,
                        m.coins_pendentes,
                        u.nome as usuario_nome,
                        u.foto as usuario_foto
                    FROM {$this->tabela} m
                    INNER JOIN usuarios u ON m.usuario_id = u.id
                    ORDER BY m.data_criacao DESC
                    LIMIT ? OFFSET ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$limite, $offset]);

            return $stmt->fetchAll();

        } catch (PDOException $erro) {
            throw new Exception("Erro ao listar fotos: " . $erro->getMessage());
        }
    }

    /**
     * Listar fotos de um usuário específico
     */
    public function listarPorUsuario($usuarioId, $limite = 50, $offset = 0)
    {
        try {
            $sql = "SELECT 
                        m.id,
                        m.usuario_id,
                        m.caminho_foto,
                        m.descricao,
                        m.data_criacao,
                        m.coins_pendentes,
                        u.nome as usuario_nome,
                        u.foto as usuario_foto
                    FROM {$this->tabela} m
                    INNER JOIN usuarios u ON m.usuario_id = u.id
                    WHERE m.usuario_id = ?
                    ORDER BY m.data_criacao DESC
                    LIMIT ? OFFSET ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$usuarioId, $limite, $offset]);

            return $stmt->fetchAll();

        } catch (PDOException $erro) {
            throw new Exception("Erro ao listar fotos do usuário: " . $erro->getMessage());
        }
    }

    /**
     * Obter detalhes de uma foto
     */
    public function obter($id)
    {
        try {
            $sql = "SELECT 
                        m.*,
                        u.nome as usuario_nome,
                        u.foto as usuario_foto
                    FROM {$this->tabela} m
                    INNER JOIN usuarios u ON m.usuario_id = u.id
                    WHERE m.id = ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);

            return $stmt->fetch();

        } catch (PDOException $erro) {
            throw new Exception("Erro ao obter foto: " . $erro->getMessage());
        }
    }

    /**
     * Atualizar descrição da foto
     */
    public function atualizar($id, $descricao)
    {
        try {
            $sql = "UPDATE {$this->tabela} 
                    SET descricao = ? 
                    WHERE id = ?";

            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$descricao, $id]);

        } catch (PDOException $erro) {
            throw new Exception("Erro ao atualizar foto: " . $erro->getMessage());
        }
    }

    /**
     * Deletar foto (arquivo + registro)
     */
    public function deletar($id, $caminhoArquivo)
    {
        try {
            // Deletar arquivo
            if (file_exists($caminhoArquivo)) {
                unlink($caminhoArquivo);
            }

            // Deletar registro
            $sql = "DELETE FROM {$this->tabela} WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id]);

        } catch (PDOException $erro) {
            throw new Exception("Erro ao deletar foto: " . $erro->getMessage());
        }
    }

    /**
     * Contar total de fotos
     */
    public function contar()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$this->tabela}";
            $stmt = $this->pdo->query($sql);
            $resultado = $stmt->fetch();
            return $resultado['total'] ?? 0;

        } catch (PDOException $erro) {
            throw new Exception("Erro ao contar fotos: " . $erro->getMessage());
        }
    }

    /**
     * Contar fotos de um usuário
     */
    public function contarPorUsuario($usuarioId)
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$this->tabela} WHERE usuario_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$usuarioId]);
            $resultado = $stmt->fetch();
            return $resultado['total'] ?? 0;

        } catch (PDOException $erro) {
            throw new Exception("Erro ao contar fotos do usuário: " . $erro->getMessage());
        }
    }
}