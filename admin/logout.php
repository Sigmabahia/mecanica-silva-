<?php
/* ============================================================
 * MECÂNICA SILVA — Painel Administrativo
 * Encerra a sessão do administrador
 * ============================================================ */

require_once __DIR__ . '/../api/helpers.php';

iniciar_sessao();

$_SESSION = [];
session_destroy();

header('Location: login.php');
exit;
