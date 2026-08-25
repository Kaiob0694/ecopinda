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

        $sql = "SELECT * FROM hotel WHERE id = :id";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':id', $id);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

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
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':estado', $estado);
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
    $id,
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
            WHERE id  = :id
        ";

        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':cep', $cep);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':quantidade_quartos', $quantidade_quartos);
        $stmt->bindParam(':possui_wifi', $possui_wifi);
        $stmt->bindParam(':possui_estacionamento', $possui_estacionamento);
        $stmt->bindParam(':data_cadastro', $data_cadastro);
       

        return $stmt->execute();
    }

    public function excluir($id)
    {

        $sql = "DELETE FROM hotel WHERE id  = :id";

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
