<?php
require_once __DIR__ . "/../config/conexao.php";

class PontoTuristico
{

    private $conexao;

    public function __construct()
    {

        $db = new Conexao();
        $this->conexao = $db->conectar();
    }

    public function listar()
    {

        $sql = "SELECT * FROM ponto_turistico";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id)
    {

        $sql = "SELECT * FROM ponto_turistico WHERE id = :id";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id', $id);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function cadastrar(
        $nome,
        $descricao,
        $endereco,
        $cidade,
        $estado,
        $cep,
        $telefone,
        $email,
        $categoria,
        $horario_funcionamento,
        $entrada_gratuita,
        $possui_estacionamento,
        $data_cadastro
    ) {

        // O select do formulario manda "Sim"/"Nao", mas a coluna no
        // banco e tinyint(1), entao convertemos para 1/0 antes de gravar.
        $entrada_gratuita = ($entrada_gratuita === 'Sim') ? 1 : 0;
        $possui_estacionamento = ($possui_estacionamento === 'Sim') ? 1 : 0;

        $sql = "
            INSERT INTO ponto_turistico
            (
                nome,
                descricao,
                endereco,
                cidade,
                estado,
                cep,
                telefone,
                email,
                categoria,
                horario_funcionamento,
                entrada_gratuita,
                possui_estacionamento,
                data_cadastro
            )
            VALUES
            (
                :nome,
                :descricao,
                :endereco,
                :cidade,
                :estado,
                :cep,
                :telefone,
                :email,
                :categoria,
                :horario_funcionamento,
                :entrada_gratuita,
                :possui_estacionamento,
                :data_cadastro
            )
        ";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':cep', $cep);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':horario_funcionamento', $horario_funcionamento);
        $stmt->bindParam(':entrada_gratuita', $entrada_gratuita);
        $stmt->bindParam(':possui_estacionamento', $possui_estacionamento);
        $stmt->bindParam(':data_cadastro', $data_cadastro);

        if (!$stmt->execute()) {
            return false;
        }

        // Retorna o id recem-criado, para poder vincular as fotos.
        return $this->conexao->lastInsertId();
    }

    public function editar(
        $id,
        $nome,
        $descricao,
        $endereco,
        $cidade,
        $estado,
        $cep,
        $telefone,
        $email,
        $categoria,
        $horario_funcionamento,
        $entrada_gratuita,
        $possui_estacionamento,
        $data_cadastro
    ) {

        $entrada_gratuita = ($entrada_gratuita === 'Sim') ? 1 : 0;
        $possui_estacionamento = ($possui_estacionamento === 'Sim') ? 1 : 0;

        $sql = "
            UPDATE ponto_turistico
            SET
                nome = :nome,
                descricao = :descricao,
                endereco = :endereco,
                cidade = :cidade,
                estado = :estado,
                cep = :cep,
                telefone = :telefone,
                email = :email,
                categoria = :categoria,
                horario_funcionamento = :horario_funcionamento,
                entrada_gratuita = :entrada_gratuita,
                possui_estacionamento = :possui_estacionamento,
                data_cadastro = :data_cadastro
            WHERE id = :id
        ";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':cep', $cep);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':horario_funcionamento', $horario_funcionamento);
        $stmt->bindParam(':entrada_gratuita', $entrada_gratuita);
        $stmt->bindParam(':possui_estacionamento', $possui_estacionamento);
        $stmt->bindParam(':data_cadastro', $data_cadastro);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function excluir($id)
    {

        $sql = "DELETE FROM ponto_turistico WHERE id = :id";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}
