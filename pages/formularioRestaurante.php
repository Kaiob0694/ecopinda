<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <title>Cadastro de Restaurante</title>
    <link rel="stylesheet" href="/ecopinda/assets/css/formulario.css">

</head>

<body>

    <?php include '../includes/header.php'; ?>
    <?php include '../includes/head.php'; ?>

    <main class="pagina-formulario">

        <div class="container">

            <h1>Cadastro de Restaurante</h1>

            <form action="cadastroRestaurante.php" method="POST" enctype="multipart/form-data">

                <label>Foto do Restaurante:</label>
                <input type="file" name="foto" accept="image/*">

                <label for="nome">Nome:</label>
                <input
                    type="text"
                    id="nome"
                    name="nome"
                    required
                    minlength="2"
                    maxlength="100"
                    pattern=".*\S.*"
                    title="Digite o nome do restaurante (2 a 100 caracteres).">

                <label for="logradouro">Logradouro:</label>
                <input
                    type="text"
                    id="logradouro"
                    name="logradouro"
                    required
                    minlength="3"
                    maxlength="150"
                    pattern=".*\S.*"
                    title="Digite um logradouro válido.">

                <label for="numero">Número:</label>
                <input
                    type="number"
                    id="numero"
                    name="numero"
                    required
                    min="1"
                    max="999999"
                    title="Digite um número de endereço válido.">

                <label for="cidade">Cidade:</label>
                <input
                    type="text"
                    id="cidade"
                    name="cidade"
                    required
                    minlength="2"
                    maxlength="100"
                    pattern=".*\S.*"
                    title="Digite o nome da cidade.">

                <label for="cep">CEP:</label>
                <input
                    type="text"
                    id="cep"
                    name="cep"
                    required
                    pattern="[0-9]{5}-?[0-9]{3}"
                    placeholder="12345-678">

                <label for="telefone">Telefone:</label>
                <input
                    type="text"
                    id="telefone"
                    name="telefone"
                    required
                    pattern="\([0-9]{2}\) [0-9]{5}-[0-9]{4}"
                    placeholder="(11) 12345-6789">

                <label for="email">E-mail:</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    maxlength="150"
                    placeholder="exemplo@email.com">

                <label for="categoria">Categoria:</label>

                <select
                    id="categoria"
                    name="categoria"
                    required
                    onchange="mostrarOutraCategoria()">

                    <option value="">Selecione uma categoria</option>
                    <option value="Restaurante">Restaurante</option>
                    <option value="Lanchonete">Lanchonete</option>
                    <option value="Pizzaria">Pizzaria</option>
                    <option value="Cafeteria">Cafeteria</option>
                    <option value="Padaria">Padaria</option>
                    <option value="Outro">Outro</option>

                </select>

                <div id="outra_categoria">

                    <label for="categoria_outro">
                        Digite a categoria:
                    </label>

                    <input
                        type="text"
                        id="categoria_outro"
                        name="categoria_outro"
                        minlength="2"
                        maxlength="50">

                </div>

                <label for="possui_delivery">
                    Possui Delivery?
                </label>

                <select
                    id="possui_delivery"
                    name="possui_delivery"
                    required>

                    <option value="">Selecione</option>
                    <option value="1">Sim</option>
                    <option value="0">Não</option>

                </select>

                <label for="possui_wifi">
                    Possui Wi-Fi?
                </label>

                <select
                    id="possui_wifi"
                    name="possui_wifi"
                    required>

                    <option value="">Selecione</option>
                    <option value="1">Sim</option>
                    <option value="0">Não</option>

                </select>

                <label for="horario_funcionamento">
                    Horário de Funcionamento:
                </label>

                <select
                    id="horario_funcionamento"
                    name="horario_funcionamento"
                    required>

                    <option value="">
                        Selecione o horário
                    </option>

                    <option value="Segunda a sexta, das 08:00 às 18:00">
                        Segunda a sexta, das 08:00 às 18:00
                    </option>

                    <option value="Segunda a sábado, das 08:00 às 18:00">
                        Segunda a sábado, das 08:00 às 18:00
                    </option>

                    <option value="Segunda a sábado, das 08:00 às 22:00">
                        Segunda a sábado, das 08:00 às 22:00
                    </option>

                    <option value="Todos os dias, das 08:00 às 18:00">
                        Todos os dias, das 08:00 às 18:00
                    </option>

                    <option value="Todos os dias, das 08:00 às 22:00">
                        Todos os dias, das 08:00 às 22:00
                    </option>

                </select>

                <div class="botoes">

                    <button type="submit">
                        Cadastrar Restaurante
                    </button>

                    <a
                        href="restaurante.php"
                        class="botao-voltar">
                        Voltar
                    </a>

                </div>

            </form>

        </div>

    </main>

    <script>
        function mostrarOutraCategoria() {

            const categoria =
                document.getElementById('categoria');

            const outraCategoria =
                document.getElementById('outra_categoria');

            const campoOutro =
                document.getElementById('categoria_outro');

            if (categoria.value === 'Outro') {

                outraCategoria.style.display = 'block';

                campoOutro.required = true;

            } else {

                outraCategoria.style.display = 'none';

                campoOutro.required = false;

                campoOutro.value = '';

            }

        }

        document.getElementById('cep')
            .addEventListener('blur', function() {

                let cep =
                    this.value.replace(/\D/g, '');

                if (cep.length !== 8) {
                    alert('Digite um CEP válido.');
                    return;
                }

                fetch(
                        'https://viacep.com.br/ws/' +
                        cep +
                        '/json/'
                    )

                    .then(response => response.json())

                    .then(data => {

                        if (data.erro) {

                            alert('CEP não encontrado.');
                            return;

                        }

                        document.getElementById('logradouro').value =
                            data.logradouro || '';

                        document.getElementById('cidade').value =
                            data.localidade || '';

                    })

                    .catch(error => {

                        console.error(
                            'Erro ao consultar o ViaCEP:',
                            error
                        );

                        alert('Erro ao consultar o CEP.');

                    });

            });
    </script>

</body>

</html>