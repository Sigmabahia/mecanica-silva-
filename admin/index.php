<?php
/* ============================================================
 * MECÂNICA SILVA — Painel Administrativo
 * Dashboard (visão geral)
 * ============================================================ */

require_once __DIR__ . '/auth.php';
exigir_login_pagina();

pagina_header('Dashboard', 'index');
?>

<div class="stats" id="cards-stats">
    <div class="stat"><div class="num" id="s-contatos">—</div><div class="rot">Contatos recebidos</div></div>
    <div class="stat"><div class="num" id="s-novos">—</div><div class="rot">Contatos não lidos</div></div>
    <div class="stat escuro"><div class="num" id="s-agenda">—</div><div class="rot">Agendamentos</div></div>
    <div class="stat escuro"><div class="num" id="s-pend">—</div><div class="rot">Agendamentos pendentes</div></div>
    <div class="stat"><div class="num" id="s-prod">—</div><div class="rot">Produtos cadastrados</div></div>
</div>

<div class="bloco">
    <h2>Últimos contatos</h2>
    <div class="tabela-wrap">
        <table>
            <thead>
                <tr><th>Nome</th><th>E-mail</th><th>Assunto</th><th>Status</th><th>Data</th></tr>
            </thead>
            <tbody id="ultimos-contatos">
                <tr><td colspan="5" class="vazio">Carregando...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="bloco">
    <h2>Próximos agendamentos</h2>
    <div class="tabela-wrap">
        <table>
            <thead>
                <tr><th>Cliente</th><th>Veículo</th><th>Serviço</th><th>Data</th><th>Horário</th><th>Status</th></tr>
            </thead>
            <tbody id="ultimos-agendamentos">
                <tr><td colspan="6" class="vazio">Carregando...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
async function carregarDashboard() {
    try {
        const { stats } = await api('/stats.php');
        document.getElementById('s-contatos').textContent = stats.contatos_total;
        document.getElementById('s-novos').textContent    = stats.contatos_novos;
        document.getElementById('s-agenda').textContent   = stats.agendamentos_total;
        document.getElementById('s-pend').textContent     = stats.agendamentos_pend;
        document.getElementById('s-prod').textContent     = stats.produtos_total;
    } catch (e) {
        toast(e.message, 'erro');
    }

    try {
        const { contatos } = await api('/contato.php');
        const tb = document.getElementById('ultimos-contatos');
        if (!contatos.length) {
            tb.innerHTML = '<tr><td colspan="5" class="vazio">Nenhum contato recebido ainda.</td></tr>';
        } else {
            tb.innerHTML = contatos.slice(0, 5).map(c => `
                <tr>
                    <td>${esc(c.nome)}</td>
                    <td>${esc(c.email)}</td>
                    <td>${esc(c.assunto)}</td>
                    <td><span class="badge ${esc(c.status)}">${esc(c.status)}</span></td>
                    <td>${esc(c.criado_em)}</td>
                </tr>`).join('');
        }
    } catch (e) { /* silencioso */ }

    try {
        const { agendamentos } = await api('/agendamentos.php');
        const tb = document.getElementById('ultimos-agendamentos');
        if (!agendamentos.length) {
            tb.innerHTML = '<tr><td colspan="6" class="vazio">Nenhum agendamento registrado.</td></tr>';
        } else {
            tb.innerHTML = agendamentos.slice(0, 5).map(a => `
                <tr>
                    <td>${esc(a.nome)}</td>
                    <td>${esc(a.modelo)}</td>
                    <td>${esc(a.servico)}</td>
                    <td>${esc(a.data)}</td>
                    <td>${esc(a.horario)}</td>
                    <td><span class="badge ${esc(a.status)}">${esc(a.status)}</span></td>
                </tr>`).join('');
        }
    } catch (e) { /* silencioso */ }
}

carregarDashboard();
</script>

<?php pagina_footer(); ?>
