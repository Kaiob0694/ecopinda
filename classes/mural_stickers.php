<?php

class MuralStickers
{
    private $pdo;
    private $tabela = 'mural_stickers';

    /**
     * Lista de adesivos permitidos. Validado sempre no
     * servidor, nunca confiando no valor vindo do cliente.
     */
    private $emojisPermitidos = [
        '❤️', '😍', '👏', '🔥', '😂', '🌟'
    ];

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Emojis disponíveis para colar (usado pelo front-end
     * para montar o seletor, mantendo uma única fonte de verdade)
     */
    public function getEmojisPermitidos()
    {
        return $this->emojisPermitidos;
    }

    private function emojiPermitido($emoji)
    {
        return in_array($emoji, $this->emojisPermitidos, true);
    }

    /**
     * Colar um adesivo em uma foto. A posição e a rotação
     * são geradas aqui no servidor (nunca recebidas do cliente),
     * para o efeito ficar orgânico e não poder ser manipulado.
     */
    public function adicionar($fotoId, $usuarioId, $emoji)
    {
        if (!$this->emojiPermitido($emoji)) {
            throw new Exception("Adesivo inválido.");
        }

        try {
            $posicao = $this->gerarPosicaoAleatoria();

            $sql = "INSERT INTO {$this->tabela}
                    (foto_id, usuario_id, emoji, posicao_top, posicao_left, rotacao, data_criacao)
                    VALUES
                    (?, ?, ?, ?, ?, ?, NOW())";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $fotoId,
                $usuarioId,
                $emoji,
                $posicao['top'],
                $posicao['left'],
                $posicao['rotacao']
            ]);

            $id = $this->pdo->lastInsertId();

            return [
                'id'           => $id,
                'foto_id'      => $fotoId,
                'usuario_id'   => $usuarioId,
                'emoji'        => $emoji,
                'posicao_top'  => $posicao['top'],
                'posicao_left' => $posicao['left'],
                'rotacao'      => $posicao['rotacao']
            ];

        } catch (PDOException $erro) {
            throw new Exception("Erro ao adicionar adesivo: " . $erro->getMessage());
        }
    }

    /**
     * Listar adesivos de uma única foto
     */
    public function listarPorFoto($fotoId)
    {
        try {
            $sql = "SELECT id, foto_id, usuario_id, emoji, posicao_top, posicao_left, rotacao
                    FROM {$this->tabela}
                    WHERE foto_id = ?
                    ORDER BY data_criacao ASC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$fotoId]);

            return $stmt->fetchAll();

        } catch (PDOException $erro) {
            throw new Exception("Erro ao listar adesivos: " . $erro->getMessage());
        }
    }

    /**
     * Listar adesivos de várias fotos de uma vez, já
     * agrupados por foto_id. Evita rodar uma query por
     * foto (N+1) na listagem do mural.
     */
    public function listarPorFotos(array $fotoIds)
    {
        $agrupado = [];

        if (empty($fotoIds)) {
            return $agrupado;
        }

        try {
            $placeholders = implode(',', array_fill(0, count($fotoIds), '?'));

            $sql = "SELECT id, foto_id, usuario_id, emoji, posicao_top, posicao_left, rotacao
                    FROM {$this->tabela}
                    WHERE foto_id IN ({$placeholders})
                    ORDER BY data_criacao ASC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(array_values($fotoIds));

            foreach ($stmt->fetchAll() as $sticker) {
                $agrupado[$sticker['foto_id']][] = $sticker;
            }

            return $agrupado;

        } catch (PDOException $erro) {
            throw new Exception("Erro ao listar adesivos: " . $erro->getMessage());
        }
    }

    /**
     * Remover um adesivo. Só é removido se o usuário
     * informado for realmente o dono do adesivo.
     */
    public function remover($id, $usuarioId)
    {
        try {
            $sql = "DELETE FROM {$this->tabela} WHERE id = ? AND usuario_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id, $usuarioId]);

            return $stmt->rowCount() > 0;

        } catch (PDOException $erro) {
            throw new Exception("Erro ao remover adesivo: " . $erro->getMessage());
        }
    }

    /**
     * Contar adesivos de uma foto
     */
    public function contarPorFoto($fotoId)
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$this->tabela} WHERE foto_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$fotoId]);
            $resultado = $stmt->fetch();
            return $resultado['total'] ?? 0;

        } catch (PDOException $erro) {
            throw new Exception("Erro ao contar adesivos: " . $erro->getMessage());
        }
    }

    /**
     * Gera uma posição orgânica ao redor da foto: escolhe
     * uma das 8 "zonas" perto das bordas/cantos do card e
     * aplica um pequeno desvio aleatório, para não empilhar
     * todos os adesivos exatamente no mesmo lugar.
     */
    private function gerarPosicaoAleatoria()
    {
        $zonas = [
            ['top' => -8,  'left' => -8],  // canto superior esquerdo
            ['top' => -8,  'left' => 78],  // canto superior direito
            ['top' => 85,  'left' => -8],  // canto inferior esquerdo
            ['top' => 85,  'left' => 78],  // canto inferior direito
            ['top' => -10, 'left' => 40],  // topo centro
            ['top' => 88,  'left' => 40],  // base centro
            ['top' => 35,  'left' => -10], // esquerda centro
            ['top' => 35,  'left' => 88],  // direita centro
        ];

        $zona = $zonas[array_rand($zonas)];

        $jitterTop  = random_int(-4, 4);
        $jitterLeft = random_int(-4, 4);

        return [
            'top'      => $zona['top'] + $jitterTop,
            'left'     => $zona['left'] + $jitterLeft,
            'rotacao'  => random_int(-25, 25)
        ];
    }
}
