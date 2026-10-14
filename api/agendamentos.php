<?php
/* ============================================================
 * MECÂNICA SILVA — API
 * Endpoint: /api/agendamentos.php
 *
 * POST   (público) — cria um agendamento
 * GET    (admin)   — lista os agendamentos
 * PUT    (admin)   — atualiza um agendamento
 * DELETE (admin)   — remove um agendamento
 * ============================================================ */

require_once __DIR__ . '/helpers.php';

$pdo = db();

switch (metodo()) {

    // --------------------------------------------------------
    // CRIAR AGENDAMENTO (público)
    // --------------------------------------------------------
    case 'POST':
        $d = corpo();

        $nome     = limpar($d['nome'] ?? '');
        $telefone = limpar($d['telefone'] ?? '');
        $unidade  = limpar($d['unidade'] ?? '');
        $modelo   = limpar($d['modelo'] ?? '');
        $servico  = limpar($d['servico'] ?? '');
        $data     = limpar($d['data'] ?? '');
        $horario  = limpar($d['horario'] ?? '');

        $erros = [];
        if ($nome === '')     { $erros['nome']     = 'Informe o seu nome.'; }
        if ($unidade === '')  { $erros['unidade']  = 'Selecione uma unidade.'; }
        if ($modelo === '')   { $erros['modelo']   = 'Informe o modelo do veículo.'; }
        if ($servico === '')  { $erros['servico']  = 'Selecione o serviço.'; }
        if ($data === '')     { $erros['data']     = 'Selecione a data.'; }
        if ($horario === '')  { $erros['horario']  = 'Selecione o horário.'; }

        if ($erros) {
            json_response([
                'sucesso'  => false,
                'mensagem' => 'Preencha todos os campos obrigatórios.',
                'erros'    => $erros,
            ], 422);
        }

        $stmt = $pdo->prepare(
            'INSERT INTO agendamentos (nome, telefone, unidade, modelo, servico, data, horario)
             VALUES (:nome, :telefone, :unidade, :modelo, :servico, :data, :horario)'
        );
        $stmt->execute([
            ':nome'     => $nome,
            ':telefone' => $telefone,
            ':unidade'  => $unidade,
            ':modelo'   => $modelo,
            ':servico'  => $servico,
            ':data'     => $data,
            ':horario'  => $horario,
        ]);

        json_response([
            'sucesso'  => true,
            'mensagem' => 'Agendamento realizado com sucesso!',
            'id'       => (int) $pdo->lastInsertId(),
        ], 201);
        break;

    // --------------------------------------------------------
    // LISTAR AGENDAMENTOS (admin)
    // --------------------------------------------------------
    case 'GET':
        exigir_admin();
        $stmt = $pdo->query('SELECT * FROM agendamentos ORDER BY id DESC');
        json_response(['sucesso' => true, 'agendamentos' => $stmt->fetchAll()]);
        break;

    // --------------------------------------------------------
    // ATUALIZAR AGENDAMENTO (admin)
    // --------------------------------------------------------
    case 'PUT':
        exigir_admin();

        $d = corpo();
        $id = (int) ($d['id'] ?? 0);
        if ($id <= 0) {
            json_response(['sucesso' => false, 'mensagem' => 'ID inválido.'], 422);
        }

        $campos = ['nome', 'telefone', 'unidade', 'modelo', 'servico', 'data', 'horario', 'status'];
        $sets = [];
        $params = [':id' => $id];
        foreach ($campos as $campo) {
            if (array_key_exists($campo, $d)) {
                $sets[] = "$campo = :$campo";
                $params[":$campo"] = limpar($d[$campo]);
            }
        }

        if (!$sets) {
            json_response(['sucesso' => false, 'mensagem' => 'Nada para atualizar.'], 422);
        }

        $stmt = $pdo->prepare('UPDATE agendamentos SET ' . implode(', ', $sets) . ' WHERE id = :id');
        $stmt->execute($params);

        json_response(['sucesso' => true, 'mensagem' => 'Agendamento atualizado.']);
        break;

    // --------------------------------------------------------
    // REMOVER AGENDAMENTO (admin)
    // --------------------------------------------------------
    case 'DELETE':
        exigir_admin();

        $d  = corpo();
        $id = (int) ($d['id'] ?? ($_GET['id'] ?? 0));
        if ($id <= 0) {
            json_response(['sucesso' => false, 'mensagem' => 'ID inválido.'], 422);
        }

        $stmt = $pdo->prepare('DELETE FROM agendamentos WHERE id = :id');
        $stmt->execute([':id' => $id]);

        json_response(['sucesso' => true, 'mensagem' => 'Agendamento removido.']);
        break;

    default:
        json_response(['sucesso' => false, 'mensagem' => 'Método não permitido.'], 405);
}
