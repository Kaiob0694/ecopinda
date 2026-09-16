<?php

require_once __DIR__ . "/../config/conexao.php";

class Hotel
{
    private $conexao;

    public function __construct()
    {
        $db = new Conexao();
        $this->conexao = $db->conectar();
    }

    // =========================================================
    // LISTAR
    // =========================================================

    public function listar()
    {
        $sql = "SELECT * FROM hotel ORDER BY id DESC";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================================================
    // BUSCAR POR ID
    // =========================================================

    public function buscarPorId($id)
    {
        $sql = "SELECT * FROM hotel WHERE id = :id";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // =========================================================
    // CADASTRAR
    // =========================================================

    public function cadastrar(
        $nome,
        $endereco,
        $cidade,
        $estado,
        $cep,
        $telefone,
        $email,
        $quantidade_quartos,
        $possui_wifi,
        $possui_estacionamento,
        $data_cadastro
    ) {

        // Converte Sim/Não para 1/0
        $possui_wifi = ($possui_wifi === 'Sim') ? 1 : 0;
        $possui_estacionamento = ($possui_estacionamento === 'Sim') ? 1 : 0;

        $sql = "
            INSERT INTO hotel
            (
                nome,
                endereco,
                cidade,
                estado,
                cep,
                telefone,
                email,
                quantidade_quartos,
                possui_wifi,
                possui_estacionamento,
                data_cadastro
            )
            VALUES
            (
                :nome,
                :endereco,
                :cidade,
                :estado,
                :cep,
                :telefone,
                :email,
                :quantidade_quartos,
                :possui_wifi,
                :possui_estacionamento,
                :data_cadastro
            )
        ";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':endereco', $endereco);
        $stmt->bindValue(':cidade', $cidade);
        $stmt->bindValue(':estado', $estado);
        $stmt->bindValue(':cep', $cep);
        $stmt->bindValue(':telefone', $telefone);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':quantidade_quartos', $quantidade_quartos);
        $stmt->bindValue(':possui_wifi', $possui_wifi, PDO::PARAM_INT);
        $stmt->bindValue(':possui_estacionamento', $possui_estacionamento, PDO::PARAM_INT);
        $stmt->bindValue(':data_cadastro', $data_cadastro);

        return $stmt->execute();
    }

    // =========================================================
    // EDITAR
    // =========================================================

    public function editar(
        $nome,
        $endereco,
        $cidade,
        $estado,
        $cep,
        $telefone,
        $email,
        $quantidade_quartos,
        $possui_wifi,
        $possui_estacionamento,
        $data_cadastro,
        $id
    ) {

        // Converte Sim/Não para 1/0
        $possui_wifi = ($possui_wifi === 'Sim') ? 1 : 0;
        $possui_estacionamento = ($possui_estacionamento === 'Sim') ? 1 : 0;

        $sql = "
            UPDATE hotel
            SET
                nome = :nome,
                endereco = :endereco,
                cidade = :cidade,
                estado = :estado,
                cep = :cep,
                telefone = :telefone,
                email = :email,
                quantidade_quartos = :quantidade_quartos,
                possui_wifi = :possui_wifi,
                possui_estacionamento = :possui_estacionamento,
                data_cadastro = :data_cadastro
            WHERE id = :id
        ";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':endereco', $endereco);
        $stmt->bindValue(':cidade', $cidade);
        $stmt->bindValue(':estado', $estado);
        $stmt->bindValue(':cep', $cep);
        $stmt->bindValue(':telefone', $telefone);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':quantidade_quartos', $quantidade_quartos);
        $stmt->bindValue(':possui_wifi', $possui_wifi, PDO::PARAM_INT);
        $stmt->bindValue(':possui_estacionamento', $possui_estacionamento, PDO::PARAM_INT);
        $stmt->bindValue(':data_cadastro', $data_cadastro);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // =========================================================
    // EXCLUIR
    // =========================================================

    public function excluir($id)
    {
        $sql = "DELETE FROM hotel WHERE id = :id";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // =========================================================
    // BUSCAR TODOS
    // =========================================================

    public function buscarTodos()
    {
        $sql = "SELECT * FROM hotel ORDER BY id DESC";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}