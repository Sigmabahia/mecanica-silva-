<?php
/* ============================================================
 * MECÂNICA SILVA — Painel Administrativo
 * Gerenciamento de contatos (visualizar / marcar / excluir)
 * ============================================================ */

require_once __DIR__ . '/auth.php';
exigir_login_pagina();

pagina_header('Contatos', 'contatos');
?>

<div class="bloco">
    <h2>Mensagens recebidas</h2>

    <div class="campo" style="max-width:260px;margin-bottom:18px;">
        <label>Filtrar por status</label>
        <select id="filtro-status" onchange="carregarContatos()">
            <option value="">Todos</option>
            <option value="novo">Novo</option>
            <option value="lido">Lido</option>
            <option value="respondido">Respondido</option>
        </select>
    </div>

    <div class="tabela-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th><th>Nome</th><th>E-mail</th><th>Telefone</th>
                    <th>Assunto</th><th>Mensagem</th><th>Status</th><th>Ações</th>
                </tr>
            </thead>
            <tbody id="lista">
                <tr><td colspan="8" class="vazio">Carregando...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
async function carregarContatos() {
    const status = document.getElementById('filtro-status').value;
    const tb = document.getElementById('lista');
    try {
        const { contatos } = await api('/contato.php' + (status ? '?status=' + status : ''));
        if (!contatos.length) {
            tb.innerHTML = '<tr><td colspan="8" class="vazio">Nenhum contato encontrado.</td></tr>';
            return;
        }
        tb.innerHTML = contatos.map(c => `
            <tr>
                <td>${c.id}</td>
                <td>${esc(c.nome)}</td>
                <td>${esc(c.email)}</td>
                <td>${esc(c.telefone) || '—'}</td>
                <td>${esc(c.assunto)}</td>
                <td style="max-width:280px;">${esc(c.mensagem)}</td>
                <td><span class="badge ${esc(c.status)}">${esc(c.status)}</span></td>
                <td>
                    <div class="acoes">
                        <button class="btn btn-escuro" onclick="mudarStatus(${c.id},'lido')">Lido</button>
                        <button class="btn btn-vermelho" onclick="mudarStatus(${c.id},'respondido')">Respondido</button>
                        <button class="btn btn-excluir" onclick="excluir(${c.id})">Excluir</button>
                    </div>
                </td>
            </tr>`).join('');
    } catch (e) {
        tb.innerHTML = '<tr><td colspan="8" class="vazio">Erro ao carregar.</td></tr>';
        toast(e.message, 'erro');
    }
}

async function mudarStatus(id, status) {
    try {
        await api('/contato.php', { method: 'PUT', body: JSON.stringify({ id, status }) });
        toast('Status atualizado.');
        carregarContatos();
    } catch (e) { toast(e.message, 'erro'); }
}

async function excluir(id) {
    if (!confirm('Deseja realmente excluir este contato?')) return;
    try {
        await api('/contato.php', { method: 'DELETE', body: JSON.stringify({ id }) });
        toast('Contato removido.');
        carregarContatos();
    } catch (e) { toast(e.message, 'erro'); }
}

carregarContatos();
</script>

<?php pagina_footer(); ?>
