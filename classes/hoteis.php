<?php
require_once __DIR__ . "/../config/conexao.php";

class Aluno
{

    private $conexao;

    public function __construct()
    {

        $db = new Conexao();
        $this->conexao = $db->conectar();
    }

    public function listar()
    {

        $sql = "SELECT * FROM aluno";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id)
    {

        $sql = "SELECT * FROM aluno WHERE id_aluno = :id";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id', $id);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function cadastrar(
        $nome,
        $data_nasc,
        $cpf,
        $endereco,
        $telefone
    ) {

        $sql = "
            INSERT INTO aluno
            (
                nome,
                data_nasc,
                cpf,
                endereco,
                telefone
            )
            VALUES
            (
                :nome,
                :data_nasc,
                :cpf,
                :endereco,
                :telefone
            )
        ";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':data_nasc', $data_nasc);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':telefone', $telefone);

        return $stmt->execute();
    }

    public function editar(
        $id,
        $nome,
        $data_nasc,
        $cpf,
        $endereco,
        $telefone
    ) {

        $sql = "
            UPDATE aluno
            SET
                nome = :nome,
                data_nasc = :data_nasc,
                cpf = :cpf,
                endereco = :endereco,
                telefone = :telefone
            WHERE id_aluno = :id
        ";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':data_nasc', $data_nasc);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':telefone', $telefone);

        return $stmt->execute();
    }

    public function excluir($id)
    {

        $sql = "DELETE FROM aluno WHERE id_aluno = :id";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function listarNaoMatriculados()
    {
        $sql = "
        SELECT *
        FROM aluno
        WHERE id_aluno NOT IN
        (
            SELECT id_aluno
            FROM matricula
        )
        ORDER BY nome
    ";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
