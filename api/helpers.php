<?php
/* ============================================================
 * MECÂNICA SILVA — Backend API
 * Funções auxiliares (respostas JSON, validação, autenticação)
 * ============================================================ */

require_once __DIR__ . '/db.php';

/**
 * Envia uma resposta em JSON e encerra a execução.
 */
function json_response($dados, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * Lê o corpo da requisição (JSON ou formulário) como array.
 */
function corpo(): array
{
    $raw = file_get_contents('php://input');
    if ($raw !== false && trim($raw) !== '') {
        $json = json_decode($raw, true);
        if (is_array($json)) {
            return $json;
        }
    }
    return is_array($_POST) ? $_POST : [];
}

/**
 * Método HTTP da requisição.
 */
function metodo(): string
{
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
}

/**
 * Inicia a sessão (se ainda não estiver ativa).
 */
function iniciar_sessao(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

/**
 * Verifica se há um administrador logado.
 */
function admin_logado(): bool
{
    iniciar_sessao();
    return !empty($_SESSION['admin_id']);
}

/**
 * Bloqueia a requisição caso não haja administrador logado.
 */
function exigir_admin(): void
{
    if (!admin_logado()) {
        json_response([
            'sucesso'  => false,
            'mensagem' => 'Acesso restrito. Faça login como administrador.',
        ], 401);
    }
}

/**
 * Limpa espaços de uma string.
 */
function limpar($valor): string
{
    return trim((string) $valor);
}

/**
 * Valida um e-mail.
 */
function email_valido(string $email): bool
{
    return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Conta os caracteres de um texto (usa mbstring quando disponível).
 */
function tamanho(string $texto): int
{
    return function_exists('mb_strlen') ? mb_strlen($texto) : strlen($texto);
}

/**
 * Converte "R$ 1.234,56" ou "1234.56" em float.
 */
function para_float($valor): float
{
    if (is_numeric($valor)) {
        return (float) $valor;
    }
    $valor = str_replace(['R$', ' ', '.'], '', (string) $valor);
    $valor = str_replace(',', '.', $valor);
    return (float) $valor;
}
