<?php
/* ============================================================
 * MECÂNICA SILVA — API
 * Endpoint: /api/contato.php
 *
 * POST   (público) — recebe um contato/inscrição do formulário
 * GET    (admin)   — lista os contatos
 * PUT    (admin)   — atualiza o status de um contato
 * DELETE (admin)   — remove um contato
 * ============================================================ */

require_once __DIR__ . '/helpers.php';

$pdo = db();

switch (metodo()) {

    // --------------------------------------------------------
    // RECEBER UM NOVO CONTATO (formulário público)
    // --------------------------------------------------------
    case 'POST':
        $dados = corpo();

        $nome     = limpar($dados['nome'] ?? '');
        $email    = limpar($dados['email'] ?? '');
        $telefone = limpar($dados['telefone'] ?? '');
        $assunto  = limpar($dados['assunto'] ?? 'Contato pelo site');
        $mensagem = limpar($dados['mensagem'] ?? '');

        // Validação no servidor
        $erros = [];
        if ($nome === '') {
            $erros['nome'] = 'Informe o seu nome.';
        } elseif (tamanho($nome) < 3) {
            $erros['nome'] = 'O nome deve ter pelo menos 3 caracteres.';
        }
        if ($email === '') {
            $erros['email'] = 'Informe o seu e-mail.';
        } elseif (!email_valido($email)) {
            $erros['email'] = 'Informe um e-mail válido.';
        }
        if ($mensagem === '') {
            $erros['mensagem'] = 'Escreva a sua mensagem.';
        } elseif (tamanho($mensagem) < 10) {
            $erros['mensagem'] = 'A mensagem deve ter pelo menos 10 caracteres.';
        }

        if ($erros) {
            json_response([
                'sucesso' => false,
                'mensagem' => 'Verifique os campos destacados.',
                'erros'   => $erros,
            ], 422);
        }

        $stmt = $pdo->prepare(
            'INSERT INTO contatos (nome, email, telefone, assunto, mensagem)
             VALUES (:nome, :email, :telefone, :assunto, :mensagem)'
        );
        $stmt->execute([
            ':nome'     => $nome,
            ':email'    => $email,
            ':telefone' => $telefone,
            ':assunto'  => $assunto,
            ':mensagem' => $mensagem,
        ]);

        json_response([
            'sucesso'  => true,
            'mensagem' => 'Mensagem enviada com sucesso! Em breve entraremos em contato.',
            'id'       => (int) $pdo->lastInsertId(),
        ], 201);
        break;

    // --------------------------------------------------------
    // LISTAR CONTATOS (admin)
    // --------------------------------------------------------
    case 'GET':
        exigir_admin();

        $status = limpar($_GET['status'] ?? '');
        if ($status !== '') {
            $stmt = $pdo->prepare('SELECT * FROM contatos WHERE status = :s ORDER BY id DESC');
            $stmt->execute([':s' => $status]);
        } else {
            $stmt = $pdo->query('SELECT * FROM contatos ORDER BY id DESC');
        }

        json_response([
            'sucesso'  => true,
            'contatos' => $stmt->fetchAll(),
        ]);
        break;

    // --------------------------------------------------------
    // ATUALIZAR STATUS (admin)
    // --------------------------------------------------------
    case 'PUT':
        exigir_admin();

        $dados  = corpo();
        $id     = (int) ($dados['id'] ?? 0);
        $status = limpar($dados['status'] ?? '');

        $permitidos = ['novo', 'lido', 'respondido'];
        if ($id <= 0 || !in_array($status, $permitidos, true)) {
            json_response(['sucesso' => false, 'mensagem' => 'Dados inválidos.'], 422);
        }

        $stmt = $pdo->prepare('UPDATE contatos SET status = :s WHERE id = :id');
        $stmt->execute([':s' => $status, ':id' => $id]);

        json_response(['sucesso' => true, 'mensagem' => 'Status atualizado.']);
        break;

    // --------------------------------------------------------
    // REMOVER CONTATO (admin)
    // --------------------------------------------------------
    case 'DELETE':
        exigir_admin();

        $dados = corpo();
        $id    = (int) ($dados['id'] ?? ($_GET['id'] ?? 0));

        if ($id <= 0) {
            json_response(['sucesso' => false, 'mensagem' => 'ID inválido.'], 422);
        }

        $stmt = $pdo->prepare('DELETE FROM contatos WHERE id = :id');
        $stmt->execute([':id' => $id]);

        json_response(['sucesso' => true, 'mensagem' => 'Contato removido.']);
        break;

    default:
        json_response(['sucesso' => false, 'mensagem' => 'Método não permitido.'], 405);
}
