<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../config/conexao.php";

$baseUrl = 'https://pindaeco.rf.gd';

// Apenas o usuário master pode cadastrar eventos
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'master') {
    header("Location: index.php");
    exit;
}

$conexao = new Conexao();
$pdo = $conexao->conectar();

$erro = '';

// Pasta física onde as imagens serão salvas
$pastaUploads = __DIR__ . '/../../assets/uploads/eventos/';

// Caminho público (usado para acessar a imagem pelo navegador)
$caminhoPublico = '/assets/uploads/eventos/';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $titulo      = trim($_POST['titulo'] ?? '');
    $descricao   = trim($_POST['descricao'] ?? '');
    $local       = trim($_POST['local'] ?? '');
    $categoria   = trim($_POST['categoria'] ?? '') ?: 'Evento';
    $formato     = $_POST['formato'] ?? 'Presencial';
    $data_inicio = $_POST['data_inicio'] ?? '';
    $data_fim    = $_POST['data_fim'] ?? '';
    $cor         = $_POST['cor'] ?? '#ff7a1a';
    $dia_inteiro = isset($_POST['dia_inteiro']) ? 1 : 0;
    $gratuito    = isset($_POST['gratuito']) && $_POST['gratuito'] === '1' ? 1 : 0;

    $imagem = null;

    if (empty($titulo) || empty($data_inicio)) {

        $erro = 'Preencha ao menos o título e a data/hora de início.';

    } else {

        // ============================
        // TRATAMENTO DO UPLOAD DA IMAGEM
        // ============================

        if (!empty($_FILES['imagem']['name']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {

            $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            $tamanhoMaximo = 5 * 1024 * 1024; // 5 MB

            $nomeOriginal = $_FILES['imagem']['name'];
            $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
            $tamanho = $_FILES['imagem']['size'];
            $tmpPath = $_FILES['imagem']['tmp_name'];

            if (!in_array($extensao, $extensoesPermitidas)) {

                $erro = 'Formato de imagem inválido. Use JPG, PNG, WEBP ou GIF.';

            } elseif ($tamanho > $tamanhoMaximo) {

                $erro = 'A imagem deve ter no máximo 5MB.';

            } elseif (!getimagesize($tmpPath)) {

                // Confirma que o arquivo é realmente uma imagem
                $erro = 'O arquivo enviado não é uma imagem válida.';

            } else {

                // Garante que a pasta existe
                if (!is_dir($pastaUploads)) {
                    mkdir($pastaUploads, 0755, true);
                }

                // Nome de arquivo único para evitar sobrescrever outras imagens
                $nomeArquivo = uniqid('evento_', true) . '.' . $extensao;
                $destino = $pastaUploads . $nomeArquivo;

                if (move_uploaded_file($tmpPath, $destino)) {

                    $imagem = $caminhoPublico . $nomeArquivo;

                } else {

                    $erro = 'Não foi possível salvar a imagem enviada.';

                }

            }

        }

        // Só continua se não houver erro de imagem
        if (empty($erro)) {

            try {

                $stmt = $pdo->prepare("
                    INSERT INTO eventos (titulo, descricao, data_inicio, data_fim, cor, dia_inteiro, imagem, local, categoria, formato, gratuito)
                    VALUES (:titulo, :descricao, :data_inicio, :data_fim, :cor, :dia_inteiro, :imagem, :local, :categoria, :formato, :gratuito)
                ");

                $stmt->execute([
                    ':titulo'      => $titulo,
                    ':descricao'   => $descricao !== '' ? $descricao : null,
                    ':data_inicio' => str_replace('T', ' ', $data_inicio) . ':00',
                    ':data_fim'    => $data_fim !== '' ? str_replace('T', ' ', $data_fim) . ':00' : null,
                    ':cor'         => $cor,
                    ':dia_inteiro' => $dia_inteiro,
                    ':imagem'      => $imagem,
                    ':local'       => $local !== '' ? $local : null,
                    ':categoria'   => $categoria,
                    ':formato'     => $formato,
                    ':gratuito'    => $gratuito,
                ]);

                header("Location: index.php");
                exit;

            } catch (PDOException $e) {

                $erro = 'Erro ao salvar o evento: ' . $e->getMessage();

            }

        }

    }
}

include "../../includes/head.php";
include "../../includes/header.php";
?>

<!-- Tag do CSS com parâmetro para ignorar o cache do navegador -->
<link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/cadastrar-evento.css?v=<?= time(); ?>">

<main class="cadastro-evento-container">
    <div class="cadastro-evento-painel">

        <div class="cadastro-evento-topo">
            <h2 class="cadastro-evento-titulo">Cadastrar Evento</h2>
        </div>

        <?php if (!empty($erro)): ?>
            <div class="erros-upload">
                <p><?= htmlspecialchars($erro) ?></p>
            </div>
        <?php endif; ?>

        <form method="POST" class="formulario-evento" enctype="multipart/form-data">

            <div class="formulario-evento-grid">

                <div class="campo-evento largo">
                    <label>Título <span class="obrigatorio">*</span></label>
                    <input type="text" name="titulo" placeholder="Ex: Mutirão de plantio" required>
                </div>

                <div class="campo-evento largo">
                    <label>Descrição</label>
                    <textarea name="descricao" rows="3" placeholder="Detalhes do evento"></textarea>
                </div>

                <div class="campo-evento largo">
                    <label>Imagem do evento</label>
                    <input type="file" name="imagem" accept="image/png, image/jpeg, image/webp, image/gif">
                </div>

                <div class="campo-evento">
                    <label>Local</label>
                    <input type="text" name="local" placeholder="Ex: Shopping Pátio Pinda">
                </div>

                <div class="campo-evento">
                    <label>Categoria</label>
                    <input type="text" name="categoria" placeholder="Ex: Evento, Show" value="Evento">
                </div>

                <div class="campo-evento">
                    <label>Formato <span class="obrigatorio">*</span></label>
                    <select name="formato" required>
                        <option value="Presencial">Presencial</option>
                        <option value="Online">Online</option>
                    </select>
                </div>

                <div class="campo-evento">
                    <label>Gratuito?</label>
                    <select name="gratuito">
                        <option value="0">Não</option>
                        <option value="1">Sim</option>
                    </select>
                </div>

                <div class="campo-evento">
                    <label>Início <span class="obrigatorio">*</span></label>
                    <input type="datetime-local" name="data_inicio" required>
                </div>

                <div class="campo-evento">
                    <label>Fim</label>
                    <input type="datetime-local" name="data_fim">
                </div>

                <div class="campo-evento">
                    <label>Cor</label>
                    <input type="color" name="cor" value="#ff7a1a">
                </div>

                <div class="campo-evento checkbox">
                    <label>
                        <input type="checkbox" name="dia_inteiro" value="1">
                        Evento de dia inteiro
                    </label>
                </div>

            </div>

            <div class="formulario-evento-acoes">
                <a href="index.php" class="botao-voltar-evento">Voltar</a>
                <button type="submit" class="botao-salvar-evento">Salvar</button>
            </div>

        </form>

    </div>
</main>

<?php
include "../../includes/footer.php";
?>