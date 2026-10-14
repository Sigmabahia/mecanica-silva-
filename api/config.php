<?php
/* ============================================================
 * MECÂNICA SILVA — Backend API
 * Arquivo de configuração geral
 * ============================================================ */

// Caminho do banco de dados SQLite (criado automaticamente no primeiro acesso)
define('DB_PATH', __DIR__ . '/data/mecanica.sqlite');

// Credenciais padrão do administrador (criadas automaticamente)
define('ADMIN_PADRAO_USUARIO', 'admin');
define('ADMIN_PADRAO_SENHA',   'admin123');

// Fuso horário
date_default_timezone_set('America/Sao_Paulo');

// Ambiente: em produção mude para false
define('DEBUG', true);

if (DEBUG) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}
