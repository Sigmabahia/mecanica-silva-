<?php
/* ============================================================
 * MECÂNICA SILVA — API
 * Endpoint: /api/stats.php  (admin)
 * Retorna os números exibidos no painel administrativo.
 * ============================================================ */

require_once __DIR__ . '/helpers.php';

exigir_admin();

$pdo = db();

$stats = [
    'contatos_total'      => (int) $pdo->query('SELECT COUNT(*) FROM contatos')->fetchColumn(),
    'contatos_novos'      => (int) $pdo->query("SELECT COUNT(*) FROM contatos WHERE status = 'novo'")->fetchColumn(),
    'agendamentos_total'  => (int) $pdo->query('SELECT COUNT(*) FROM agendamentos')->fetchColumn(),
    'agendamentos_pend'   => (int) $pdo->query("SELECT COUNT(*) FROM agendamentos WHERE status = 'pendente'")->fetchColumn(),
    'produtos_total'      => (int) $pdo->query('SELECT COUNT(*) FROM produtos')->fetchColumn(),
];

json_response(['sucesso' => true, 'stats' => $stats]);
