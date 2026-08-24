<?php

require_once __DIR__ . "/../config/conexao.php";

class Restaurante
{
    private $conexao;

    public function __construct()
    {
        $db = new Conexao();
        $this->conexao = $db->conectar();
    }

    // ==========================================
    // LISTAR RESTAURANTES
    // ==========================================
    public function listar()
    {
        $sql = "SELECT * FROM restaurante ORDER BY id DESC";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // ==========================================
    // BUSCAR RESTAURANTE POR ID
    // ==========================================
    public function buscarPorId($id)
    {
        $sql = "SELECT * FROM restaurante WHERE id = :id";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // ==========================================
    // CADASTRAR RESTAURANTE
    // ==========================================
    public function cadastrar(
        $nome,
        $logradouro,
        $numero,
        $cidade,
        $cep,
        $telefone,
        $email,
        $categoria,
        $possui_delivery,
        $possui_wifi,
        $horario_funcionamento
    ) {

        // Converte Sim/Não para 1/0
        $possui_delivery = ($possui_delivery === 'Sim') ? 1 : 0;
        $possui_wifi = ($possui_wifi === 'Sim') ? 1 : 0;

        $sql = "
            INSERT INTO restaurante
            (
                nome,
                logradouro,
                numero,
                cidade,
                cep,
                telefone,
                email,
                categoria,
                possui_delivery,
                possui_wifi,
                horario_funcionamento
            )
            VALUES
            (
                :nome,
                :logradouro,
                :numero,
                :cidade,
                :cep,
                :telefone,
                :email,
                :categoria,
                :possui_delivery,
                :possui_wifi,
                :horario_funcionamento
            )
        ";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':logradouro', $logradouro);
        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':cep', $cep);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':possui_delivery', $possui_delivery, PDO::PARAM_INT);
        $stmt->bindParam(':possui_wifi', $possui_wifi, PDO::PARAM_INT);
        $stmt->bindParam(':horario_funcionamento', $horario_funcionamento);

        if (!$stmt->execute()) {
            return false;
        }

        // Retorna o ID do restaurante cadastrado
        return $this->conexao->lastInsertId();
    }


    // ==========================================
    // EDITAR RESTAURANTE
    // ==========================================
    public function editar(
        $id,
        $nome,
        $logradouro,
        $numero,
        $cidade,
        $cep,
        $telefone,
        $email,
        $categoria,
        $possui_delivery,
        $possui_wifi,
        $horario_funcionamento
    ) {

        // Converte Sim/Não para 1/0
        $possui_delivery = ($possui_delivery === 'Sim') ? 1 : 0;
        $possui_wifi = ($possui_wifi === 'Sim') ? 1 : 0;

        $sql = "
            UPDATE restaurante
            SET
                nome = :nome,
                logradouro = :logradouro,
                numero = :numero,
                cidade = :cidade,
                cep = :cep,
                telefone = :telefone,
                email = :email,
                categoria = :categoria,
                possui_delivery = :possui_delivery,
                possui_wifi = :possui_wifi,
                horario_funcionamento = :horario_funcionamento
            WHERE id = :id
        ";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':logradouro', $logradouro);
        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':cep', $cep);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':possui_delivery', $possui_delivery, PDO::PARAM_INT);
        $stmt->bindParam(':possui_wifi', $possui_wifi, PDO::PARAM_INT);
        $stmt->bindParam(':horario_funcionamento', $horario_funcionamento);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }


    // ==========================================
    // EXCLUIR RESTAURANTE
    // ==========================================
    public function excluir($id)
    {
        $sql = "DELETE FROM restaurante WHERE id = :id";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }


    // ==========================================
    // BUSCAR TODOS
    // ==========================================
    public function buscarTodos()
    {
        $sql = "SELECT * FROM restaurante ORDER BY id DESC";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}