<?php
/**
 * API REST de eventos.
 *
 * GET    /api/events.php            -> lista todos os eventos (público)
 * POST   /api/events.php            -> cria um evento (apenas usuário master)
 * PUT    /api/events.php?id=1       -> atualiza um evento (apenas usuário master)
 * DELETE /api/events.php?id=1       -> remove um evento (apenas usuário master)
 */

session_start();

header('Content-Type: application/json; charset=utf8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require __DIR__ . '/../config/conexao.php'; // ajuste o caminho se o arquivo estiver em outro lugar

$conexao = new Conexao();
$pdo = $conexao->conectar();

$metodo = $_SERVER['REQUEST_METHOD'];

function usuarioEhMaster()
{
    return isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'master';
}

function exigirMaster()
{
    if (!usuarioEhMaster()) {
        http_response_code(403);
        echo json_encode(['erro' => 'Apenas o usuário master pode gerenciar eventos.']);
        exit;
    }
}

switch ($metodo) {

    case 'GET':
        // Leitura é pública - qualquer visitante pode ver os eventos
        $stmt = $pdo->query("SELECT * FROM eventos ORDER BY data_inicio ASC");
        $eventos = $stmt->fetchAll();

        $resposta = array_map(function ($ev) {
            return [
                'id'         => (int) $ev['id'],
                'title'      => $ev['titulo'],
                'start'      => $ev['data_inicio'],
                'end'        => $ev['data_fim'],
                'allDay'     => (bool) $ev['dia_inteiro'],
                'color'      => $ev['cor'],
                'imagem'     => $ev['imagem'],
                'local'      => $ev['local'],
                'categoria'  => $ev['categoria'],
                'formato'    => $ev['formato'],
                'gratuito'   => (bool) $ev['gratuito'],
                'extendedProps' => [
                    'descricao' => $ev['descricao'],
                ],
            ];
        }, $eventos);

        echo json_encode([
            'eventos'  => $resposta,
            'ehMaster' => usuarioEhMaster(),
        ]);
        break;

    case 'POST':
        exigirMaster();

        $dados = json_decode(file_get_contents('php://input'), true);

        if (empty($dados['titulo']) || empty($dados['data_inicio'])) {
            http_response_code(400);
            echo json_encode(['erro' => 'Campos "titulo" e "data_inicio" são obrigatórios.']);
            exit;
        }

        $stmt = $pdo->prepare("
            INSERT INTO eventos (titulo, descricao, data_inicio, data_fim, cor, dia_inteiro, imagem, local, categoria, formato, gratuito)
            VALUES (:titulo, :descricao, :data_inicio, :data_fim, :cor, :dia_inteiro, :imagem, :local, :categoria, :formato, :gratuito)
        ");
        $stmt->execute([
            ':titulo'      => $dados['titulo'],
            ':descricao'   => $dados['descricao'] ?? null,
            ':data_inicio' => $dados['data_inicio'],
            ':data_fim'    => $dados['data_fim'] ?? null,
            ':cor'         => $dados['cor'] ?? '#3788d8',
            ':dia_inteiro' => !empty($dados['dia_inteiro']) ? 1 : 0,
            ':imagem'      => $dados['imagem'] ?? null,
            ':local'       => $dados['local'] ?? null,
            ':categoria'   => $dados['categoria'] ?? 'Evento',
            ':formato'     => $dados['formato'] ?? 'Presencial',
            ':gratuito'    => !empty($dados['gratuito']) ? 1 : 0,
        ]);

        echo json_encode(['id' => $pdo->lastInsertId(), 'mensagem' => 'Evento criado com sucesso.']);
        break;

    case 'PUT':
        exigirMaster();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['erro' => 'Parâmetro "id" é obrigatório.']);
            exit;
        }

        $dados = json_decode(file_get_contents('php://input'), true);

        $campos = [];
        $params = [':id' => $id];

        foreach (['titulo', 'descricao', 'data_inicio', 'data_fim', 'cor', 'imagem', 'local', 'categoria', 'formato'] as $campo) {
            if (array_key_exists($campo, $dados)) {
                $campos[] = "$campo = :$campo";
                $params[":$campo"] = $dados[$campo];
            }
        }
        if (array_key_exists('dia_inteiro', $dados)) {
            $campos[] = "dia_inteiro = :dia_inteiro";
            $params[':dia_inteiro'] = !empty($dados['dia_inteiro']) ? 1 : 0;
        }
        if (array_key_exists('gratuito', $dados)) {
            $campos[] = "gratuito = :gratuito";
            $params[':gratuito'] = !empty($dados['gratuito']) ? 1 : 0;
        }

        if (empty($campos)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Nenhum campo para atualizar.']);
            exit;
        }

        $sql = "UPDATE eventos SET " . implode(', ', $campos) . " WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        echo json_encode(['mensagem' => 'Evento atualizado com sucesso.']);
        break;

    case 'DELETE':
        exigirMaster();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['erro' => 'Parâmetro "id" é obrigatório.']);
            exit;
        }

        // Busca a imagem do evento antes de excluir, para remover o arquivo físico
        $stmt = $pdo->prepare("SELECT imagem FROM eventos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $evento = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("DELETE FROM eventos WHERE id = :id");
        $stmt->execute([':id' => $id]);

        if ($evento && !empty($evento['imagem']) && strpos($evento['imagem'], '/assets/uploads/eventos/') === 0) {

            $caminhoArquivo = __DIR__ . '/..' . $evento['imagem'];

            if (file_exists($caminhoArquivo)) {
                @unlink($caminhoArquivo);
            }

        }

        echo json_encode(['mensagem' => 'Evento removido com sucesso.']);
        break;

    default:
        http_response_code(405);
        echo json_encode(['erro' => 'Método não permitido.']);
}