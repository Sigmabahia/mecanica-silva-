<?php
/* ============================================================
 * MECÂNICA SILVA — API
 * Endpoint: /api/produtos.php
 *
 * GET    (público) — lista os produtos
 * POST   (admin)   — cadastra um produto
 * PUT    (admin)   — atualiza um produto
 * DELETE (admin)   — remove um produto
 * ============================================================ */

require_once __DIR__ . '/helpers.php';

$pdo = db();

switch (metodo()) {

    // --------------------------------------------------------
    // LISTAR PRODUTOS (público)
    // --------------------------------------------------------
    case 'GET':
        $categoria = limpar($_GET['categoria'] ?? '');
        if ($categoria !== '') {
            $stmt = $pdo->prepare('SELECT * FROM produtos WHERE categoria = :c ORDER BY id DESC');
            $stmt->execute([':c' => $categoria]);
        } else {
            $stmt = $pdo->query('SELECT * FROM produtos ORDER BY id DESC');
        }
        json_response(['sucesso' => true, 'produtos' => $stmt->fetchAll()]);
        break;

    // --------------------------------------------------------
    // CADASTRAR PRODUTO (admin)
    // --------------------------------------------------------
    case 'POST':
        exigir_admin();
        $d = corpo();

        $nome      = limpar($d['nome'] ?? '');
        $categoria = limpar($d['categoria'] ?? '');
        $preco     = para_float($d['preco'] ?? 0);
        $imagem    = limpar($d['imagem'] ?? '');
        $descricao = limpar($d['descricao'] ?? '');
        $estoque   = (int) ($d['estoque'] ?? 0);

        if ($nome === '') {
            json_response(['sucesso' => false, 'mensagem' => 'Informe o nome do produto.'], 422);
        }

        $stmt = $pdo->prepare(
            'INSERT INTO produtos (nome, categoria, preco, imagem, descricao, estoque)
             VALUES (:nome, :categoria, :preco, :imagem, :descricao, :estoque)'
        );
        $stmt->execute([
            ':nome'      => $nome,
            ':categoria' => $categoria,
            ':preco'     => $preco,
            ':imagem'    => $imagem,
            ':descricao' => $descricao,
            ':estoque'   => $estoque,
        ]);

        json_response(['sucesso' => true, 'mensagem' => 'Produto cadastrado.', 'id' => (int) $pdo->lastInsertId()], 201);
        break;

    // --------------------------------------------------------
    // ATUALIZAR PRODUTO (admin)
    // --------------------------------------------------------
    case 'PUT':
        exigir_admin();
        $d  = corpo();
        $id = (int) ($d['id'] ?? 0);
        if ($id <= 0) {
            json_response(['sucesso' => false, 'mensagem' => 'ID inválido.'], 422);
        }

        $nome      = limpar($d['nome'] ?? '');
        $categoria = limpar($d['categoria'] ?? '');
        $preco     = para_float($d['preco'] ?? 0);
        $imagem    = limpar($d['imagem'] ?? '');
        $descricao = limpar($d['descricao'] ?? '');
        $estoque   = (int) ($d['estoque'] ?? 0);

        if ($nome === '') {
            json_response(['sucesso' => false, 'mensagem' => 'Informe o nome do produto.'], 422);
        }

        $stmt = $pdo->prepare(
            'UPDATE produtos
                SET nome = :nome, categoria = :categoria, preco = :preco,
                    imagem = :imagem, descricao = :descricao, estoque = :estoque
              WHERE id = :id'
        );
        $stmt->execute([
            ':nome'      => $nome,
            ':categoria' => $categoria,
            ':preco'     => $preco,
            ':imagem'    => $imagem,
            ':descricao' => $descricao,
            ':estoque'   => $estoque,
            ':id'        => $id,
        ]);

        json_response(['sucesso' => true, 'mensagem' => 'Produto atualizado.']);
        break;

    // --------------------------------------------------------
    // REMOVER PRODUTO (admin)
    // --------------------------------------------------------
    case 'DELETE':
        exigir_admin();
        $d  = corpo();
        $id = (int) ($d['id'] ?? ($_GET['id'] ?? 0));
        if ($id <= 0) {
            json_response(['sucesso' => false, 'mensagem' => 'ID inválido.'], 422);
        }

        $stmt = $pdo->prepare('DELETE FROM produtos WHERE id = :id');
        $stmt->execute([':id' => $id]);

        json_response(['sucesso' => true, 'mensagem' => 'Produto removido.']);
        break;

    default:
        json_response(['sucesso' => false, 'mensagem' => 'Método não permitido.'], 405);
}
