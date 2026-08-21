<?php
require_once __DIR__ . "/../src/conexao.php";

class Restaurante
{
    private $conexao;

    public function __construct()
    {
        global $conexao;
        $this->conexao = $conexao;
    }

    public function buscarPorId($id)
    {
        $sql = "SELECT * FROM restaurante WHERE id = ?";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $resultado = $stmt->get_result();

        return $resultado->fetch_assoc();
    }

    public function editar(
        $id,
        $nome,
        $imagem,
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

        $sql = "
            UPDATE restaurante
            SET
                nome = ?,
                imagem = ?,
                logradouro = ?,
                numero = ?,
                cidade = ?,
                cep = ?,
                telefone = ?,
                email = ?,
                categoria = ?,
                possui_delivery = ?,
                possui_wifi = ?,
                horario_funcionamento = ?
            WHERE id = ?
        ";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bind_param(
            "sssisssssiisi",
            $nome,
            $imagem,
            $logradouro,
            $numero,
            $cidade,
            $cep,
            $telefone,
            $email,
            $categoria,
            $possui_delivery,
            $possui_wifi,
            $horario_funcionamento,
            $id
        );

        return $stmt->execute();
    }
}
