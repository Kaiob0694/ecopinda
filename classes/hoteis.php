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

    public function listar()
    {

        $sql = "SELECT * FROM hotel";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id)
    {

        $sql = "SELECT * FROM hotel WHERE id_hotel = :id";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id', $id);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function cadastrar(
        $nome,
        $endereco, 
        $cidade,
        $cep,
        $telefone,
        $email,
        $quantidade_quartos,
        $possui_wifi,
        $possui_estacionamento,
        $data_cadastro 
    ) {

        // O select do formulario manda "Sim"/"Nao", mas a coluna no
        // banco e tinyint(1), entao convertemos para 1/0 antes de gravar.
        $possui_wifi = ($possui_wifi === 'Sim') ? 1 : 0;
        $possui_estacionamento = ($possui_estacionamento === 'Sim') ? 1 : 0;

        $sql = "
            INSERT INTO hotel
            (
                nome,
                endereco, 
                cidade,
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

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':cep', $cep);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':quantidade_quartos', $quantidade_quartos);
        $stmt->bindParam(':possui_wifi', $possui_wifi);
        $stmt->bindParam(':possui_estacionamento', $possui_estacionamento);
        $stmt->bindParam(':data_cadastro', $data_cadastro);

        return $stmt->execute();
    }

    public function editar(
        $nome,
        $endereco,
        $cidade,
        $cep,
        $telefone,
        $email,
        $quantidade_quartos,
        $possui_wifi,
        $possui_estacionamento,
        $data_cadastro,
        $id
    ) {

        $possui_wifi = ($possui_wifi === 'Sim') ? 1 : 0;
        $possui_estacionamento = ($possui_estacionamento === 'Sim') ? 1 : 0;

        $sql = "
            UPDATE hotel
            SET
                nome = :nome,
                endereco = :endereco,
                cidade = :cidade,
                cep = :cep,
                telefone = :telefone,
                email = :email,
                quantidade_quartos = :quantidade_quartos,
                possui_wifi = :possui_wifi,
                possui_estacionamento = :possui_estacionamento,
                data_cadastro = :data_cadastro
            WHERE id_hotel = :id
        ";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':cep', $cep);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':quantidade_quartos', $quantidade_quartos);
        $stmt->bindParam(':possui_wifi', $possui_wifi);
        $stmt->bindParam(':possui_estacionamento', $possui_estacionamento);
        $stmt->bindParam(':data_cadastro', $data_cadastro);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function excluir($id)
    {

        $sql = "DELETE FROM hotel WHERE id_hotel = :id";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

   public function buscarTodos()
{
        $sql = "SELECT * FROM hotel";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
