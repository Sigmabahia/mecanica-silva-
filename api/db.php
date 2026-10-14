<?php
/* ============================================================
 * MECÂNICA SILVA — Backend API
 * Conexão com o banco de dados (PDO + SQLite)
 * O banco e as tabelas são criados automaticamente.
 * ============================================================ */

require_once __DIR__ . '/config.php';

/**
 * Retorna a conexão PDO (singleton).
 */
function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dir = dirname(DB_PATH);
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }

    try {
        $pdo = new PDO('sqlite:' . DB_PATH);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->exec('PRAGMA foreign_keys = ON');
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'sucesso'  => false,
            'mensagem' => 'Erro ao conectar ao banco de dados.',
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    criarSchema($pdo);

    return $pdo;
}

/**
 * Cria as tabelas necessárias (se ainda não existirem)
 * e insere o administrador padrão.
 */
function criarSchema(PDO $pdo): void
{
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS contatos (
            id        INTEGER PRIMARY KEY AUTOINCREMENT,
            nome      TEXT    NOT NULL,
            email     TEXT    NOT NULL,
            telefone  TEXT,
            assunto   TEXT,
            mensagem  TEXT    NOT NULL,
            status    TEXT    NOT NULL DEFAULT 'novo',
            criado_em TEXT    NOT NULL DEFAULT (datetime('now','localtime'))
        );
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS agendamentos (
            id        INTEGER PRIMARY KEY AUTOINCREMENT,
            nome      TEXT    NOT NULL,
            telefone  TEXT,
            unidade   TEXT,
            modelo    TEXT,
            servico   TEXT,
            data      TEXT,
            horario   TEXT,
            status    TEXT    NOT NULL DEFAULT 'pendente',
            criado_em TEXT    NOT NULL DEFAULT (datetime('now','localtime'))
        );
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS produtos (
            id        INTEGER PRIMARY KEY AUTOINCREMENT,
            nome      TEXT    NOT NULL,
            categoria TEXT,
            preco     REAL    NOT NULL DEFAULT 0,
            imagem    TEXT,
            descricao TEXT,
            estoque   INTEGER NOT NULL DEFAULT 0,
            criado_em TEXT    NOT NULL DEFAULT (datetime('now','localtime'))
        );
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS admins (
            id        INTEGER PRIMARY KEY AUTOINCREMENT,
            usuario   TEXT    NOT NULL UNIQUE,
            senha     TEXT    NOT NULL,
            criado_em TEXT    NOT NULL DEFAULT (datetime('now','localtime'))
        );
    ");

    // Administrador padrão
    $total = (int) $pdo->query('SELECT COUNT(*) FROM admins')->fetchColumn();
    if ($total === 0) {
        $stmt = $pdo->prepare('INSERT INTO admins (usuario, senha) VALUES (:u, :s)');
        $stmt->execute([
            ':u' => ADMIN_PADRAO_USUARIO,
            ':s' => password_hash(ADMIN_PADRAO_SENHA, PASSWORD_DEFAULT),
        ]);
    }

    // Produtos de exemplo (apenas se a tabela estiver vazia)
    $totalProd = (int) $pdo->query('SELECT COUNT(*) FROM produtos')->fetchColumn();
    if ($totalProd === 0) {
        $produtos = [
            ['Óleo Liqui Moly 5W-30', 'Óleos',   89.90,  'https://www.liquimoly.com.br/cdn/shop/products/9047_1596620471368_ctx-1.png?v=1720918458&width=1517', 'Óleo sintético de alta performance.', 25],
            ['Filtro de Ar Tecfil',    'Filtros', 119.99, 'https://stgecomm.blob.core.windows.net/imagesprod2/0355958_filtro-de-ar-audi-a3-2014-2016-tecfil-arl6077.jpeg', 'Filtro de ar para Audi A3 2014-2016.', 12],
            ['Pastilha Brembo',        'Freios',  899.99, 'https://acdn-us.mitiendanube.com/stores/001/414/802/products/1000497350-8d8b412e05c0bf902317679001373034-1024-1024.webp', 'Pastilha de freio Brembo.', 8],
            ['Cera Carnaúba Soft99',   'Estética', 35.26, 'https://http2.mlstatic.com/D_NQ_NP_2X_696010-MLA99351155090_112025-F.webp', 'Cera para proteção da pintura.', 40],
            ['Velas NGK Iridium',      'Performance', 335.00, 'https://http2.mlstatic.com/D_NQ_NP_2X_734643-MLB91524614195_092025-F.webp', 'Jogo de velas NGK Iridium.', 15],
        ];
        $stmt = $pdo->prepare('INSERT INTO produtos (nome, categoria, preco, imagem, descricao, estoque) VALUES (?,?,?,?,?,?)');
        foreach ($produtos as $p) {
            $stmt->execute($p);
        }
    }
}
