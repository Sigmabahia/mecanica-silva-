<?php
/* ============================================================
 * MECÂNICA SILVA — Painel Administrativo
 * Autenticação de página + layout compartilhado
 * ============================================================ */

require_once __DIR__ . '/../api/helpers.php';

iniciar_sessao();

/**
 * Bloqueia o acesso às páginas do painel quando não há login.
 */
function exigir_login_pagina(): void
{
    if (!admin_logado()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Nome do administrador logado.
 */
function admin_nome(): string
{
    return $_SESSION['admin_nome'] ?? 'Administrador';
}

/**
 * Cabeçalho padrão do painel.
 */
function pagina_header(string $titulo, string $ativo = ''): void
{
    $nome = htmlspecialchars(admin_nome());
    $menu = [
        'index'        => ['Dashboard', '📊'],
        'contatos'     => ['Contatos', '✉️'],
        'agendamentos' => ['Agendamentos', '🗓️'],
        'produtos'     => ['Produtos', '🔧'],
    ];
    ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo) ?> — Painel Mecânica Silva</title>
    <link rel="stylesheet" href="admin.css">
    <script src="admin.js"></script>
</head>
<body>
<div class="layout">

    <aside class="sidebar">
        <div class="marca">Mecânica <span>Silva</span></div>
        <div class="marca-sub">Painel Administrativo</div>

        <nav class="menu">
            <?php foreach ($menu as $slug => [$label, $icone]): ?>
                <a href="<?= $slug ?>.php" class="<?= $ativo === $slug ? 'ativo' : '' ?>">
                    <span class="icone"><?= $icone ?></span>
                    <?= $label ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <a href="../mecanica.html" class="link-site">← Ver o site</a>
        <a href="logout.php" class="sair">Sair</a>
    </aside>

    <main class="conteudo">
        <header class="topo">
            <h1><?= htmlspecialchars($titulo) ?></h1>
            <div class="usuario">Olá, <strong><?= $nome ?></strong></div>
        </header>

        <div class="pagina">
    <?php
}

/**
 * Rodapé padrão do painel.
 */
function pagina_footer(): void
{
    ?>
        </div>
    </main>
</div>
<div id="toast" class="toast"></div>
</body>
</html>
    <?php
}
