<?php

require_once "../../classes/restaurante.php";

$restaurante = new Restaurante();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_restaurante = $restaurante->cadastrar(
        $_POST['nome'],
        $_POST['logradouro'],
        $_POST['numero'],
        $_POST['cidade'],
        $_POST['cep'],
        $_POST['telefone'],
        $_POST['email'],
        $_POST['categoria'],
        $_POST['possui_delivery'],
        $_POST['possui_wifi'],
        $_POST['horario_funcionamento']
    );

    if ($id_restaurante) {
        header("Location: read.php");
        exit;
    }
}

include "../../includes/head.php";
include "../../includes/header.php";
?>

<link rel="stylesheet" href="/ecopinda/assets/css/cadastrar-restaurante.css?v=<?= time(); ?>">

<main class="cadastro-restaurante-container">

    <div class="cadastro-restaurante-painel">

        <div class="cadastro-restaurante-topo">
            <h2 class="cadastro-restaurante-titulo">
                Cadastrar Restaurante
            </h2>
        </div>

        <form method="POST" class="formulario-restaurante">

            <div class="formulario-restaurante-grid">

                <!-- NOME -->
                <div class="campo-restaurante largo">
                    <label>
                        Nome <span class="obrigatorio">*</span>
                    </label>

                    <input
                        type="text"
                        name="nome"
                        placeholder="Digite o nome do restaurante"
                        required
                    >
                </div>


                <!-- LOGRADOURO -->
                <div class="campo-restaurante largo">
                    <label>
                        Logradouro <span class="obrigatorio">*</span>
                    </label>

                    <input
                        type="text"
                        name="logradouro"
                        placeholder="Ex: Av. Nossa Senhora do Bom Sucesso"
                        required
                    >
                </div>


                <!-- NÚMERO -->
                <div class="campo-restaurante">
                    <label>
                        Número <span class="obrigatorio">*</span>
                    </label>

                    <input
                        type="text"
                        name="numero"
                        placeholder="Ex: 123"
                        required
                    >
                </div>


                <!-- CIDADE -->
                <div class="campo-restaurante">
                    <label>
                        Cidade <span class="obrigatorio">*</span>
                    </label>

                    <input
                        type="text"
                        name="cidade"
                        placeholder="Ex: Pindamonhangaba"
                        required
                    >
                </div>


                <!-- CEP -->
                <div class="campo-restaurante">
                    <label>
                        CEP <span class="obrigatorio">*</span>
                    </label>

                    <input
                        type="text"
                        name="cep"
                        placeholder="00000-000"
                        required
                    >
                </div>


                <!-- TELEFONE -->
                <div class="campo-restaurante">
                    <label>Telefone</label>

                    <input
                        type="text"
                        name="telefone"
                        placeholder="(00) 00000-0000"
                    >
                </div>


                <!-- EMAIL -->
                <div class="campo-restaurante">
                    <label>Email</label>

                    <input
                        type="email"
                        name="email"
                        placeholder="contato@restaurante.com"
                    >
                </div>


                <!-- CATEGORIA -->
                <div class="campo-restaurante">
                    <label>
                        Categoria <span class="obrigatorio">*</span>
                    </label>

                    <select name="categoria" required>

                        <option value="">
                            Selecione uma categoria
                        </option>

                        <option value="Brasileira">
                            Brasileira
                        </option>

                        <option value="Italiana">
                            Italiana
                        </option>

                        <option value="Japonesa">
                            Japonesa
                        </option>

                        <option value="Hamburgueria">
                            Hamburgueria
                        </option>

                        <option value="Pizzaria">
                            Pizzaria
                        </option>

                        <option value="Churrascaria">
                            Churrascaria
                        </option>

                        <option value="Lanchonete">
                            Lanchonete
                        </option>

                        <option value="Outro">
                            Outro
                        </option>

                    </select>
                </div>


                <!-- DELIVERY -->
                <div class="campo-restaurante">

                    <label>
                        Possui Delivery <span class="obrigatorio">*</span>
                    </label>

                    <select name="possui_delivery" required>

                        <option value="Sim">
                            Sim
                        </option>

                        <option value="Não">
                            Não
                        </option>

                    </select>

                </div>


                <!-- WIFI -->
                <div class="campo-restaurante">

                    <label>
                        Possui Wi-Fi <span class="obrigatorio">*</span>
                    </label>

                    <select name="possui_wifi" required>

                        <option value="Sim">
                            Sim
                        </option>

                        <option value="Não">
                            Não
                        </option>

                    </select>

                </div>


                <!-- HORÁRIO -->
                <div class="campo-restaurante largo">

                    <label>
                        Horário de Funcionamento
                        <span class="obrigatorio">*</span>
                    </label>

                    <input
                        type="text"
                        name="horario_funcionamento"
                        placeholder="Ex: Segunda a Domingo, das 11:00 às 23:00"
                        required
                    >

                </div>

            </div>


            <!-- BOTÕES -->

            <div class="formulario-restaurante-acoes">

                <a
                    href="read.php"
                    class="botao-voltar-restaurante"
                >
                    Voltar
                </a>

                <button
                    type="submit"
                    class="botao-salvar-restaurante"
                >
                    Salvar
                </button>

            </div>

        </form>

    </div>

</main>

<?php
include "../../includes/footer.php";
?>