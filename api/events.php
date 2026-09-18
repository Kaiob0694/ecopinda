<?php
/**
 * API REST de eventos do calendário.
 *
 * GET    /api/events.php            -> lista todos os eventos (formato FullCalendar)
 * POST   /api/events.php            -> cria um evento
 * PUT    /api/events.php?id=1       -> atualiza um evento (inclui mover via drag-and-drop)
 * DELETE /api/events.php?id=1       -> remove um evento
 */

header('Content-Type: application/json; charset=utf8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require __DIR__ . '/../config/Conexao.php'; // ajuste o caminho se o arquivo estiver em outro lugar

$conexao = new Conexao();
$pdo = $conexao->conectar();

$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {

    case 'GET':
        $stmt = $pdo->query("SELECT * FROM eventos ORDER BY data_inicio ASC");
        $eventos = $stmt->fetchAll();

        // Converte para o formato que o FullCalendar espera
        $resposta = array_map(function ($ev) {
            return [
                'id'         => $ev['id'],
                'title'      => $ev['titulo'],
                'start'      => $ev['data_inicio'],
                'end'        => $ev['data_fim'],
                'allDay'     => (bool) $ev['dia_inteiro'],
                'color'      => $ev['cor'],
                'extendedProps' => [
                    'descricao' => $ev['descricao'],
                ],
            ];
        }, $eventos);

        echo json_encode($resposta);
        break;

    case 'POST':
        $dados = json_decode(file_get_contents('php://input'), true);

        if (empty($dados['titulo']) || empty($dados['data_inicio'])) {
            http_response_code(400);
            echo json_encode(['erro' => 'Campos "titulo" e "data_inicio" são obrigatórios.']);
            exit;
        }

        $stmt = $pdo->prepare("
            INSERT INTO eventos (titulo, descricao, data_inicio, data_fim, cor, dia_inteiro)
            VALUES (:titulo, :descricao, :data_inicio, :data_fim, :cor, :dia_inteiro)
        ");
        $stmt->execute([
            ':titulo'      => $dados['titulo'],
            ':descricao'   => $dados['descricao'] ?? null,
            ':data_inicio' => $dados['data_inicio'],
            ':data_fim'    => $dados['data_fim'] ?? null,
            ':cor'         => $dados['cor'] ?? '#3788d8',
            ':dia_inteiro' => !empty($dados['dia_inteiro']) ? 1 : 0,
        ]);

        echo json_encode(['id' => $pdo->lastInsertId(), 'mensagem' => 'Evento criado com sucesso.']);
        break;

    case 'PUT':
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['erro' => 'Parâmetro "id" é obrigatório.']);
            exit;
        }

        $dados = json_decode(file_get_contents('php://input'), true);

        $campos = [];
        $params = [':id' => $id];

        foreach (['titulo', 'descricao', 'data_inicio', 'data_fim', 'cor'] as $campo) {
            if (array_key_exists($campo, $dados)) {
                $campos[] = "$campo = :$campo";
                $params[":$campo"] = $dados[$campo];
            }
        }
        if (array_key_exists('dia_inteiro', $dados)) {
            $campos[] = "dia_inteiro = :dia_inteiro";
            $params[':dia_inteiro'] = !empty($dados['dia_inteiro']) ? 1 : 0;
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
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['erro' => 'Parâmetro "id" é obrigatório.']);
            exit;
        }

        $stmt = $pdo->prepare("DELETE FROM eventos WHERE id = :id");
        $stmt->execute([':id' => $id]);

        echo json_encode(['mensagem' => 'Evento removido com sucesso.']);
        break;

    default:
        http_response_code(405);
        echo json_encode(['erro' => 'Método não permitido.']);
}
