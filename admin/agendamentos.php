<?php
/* ============================================================
 * MECÂNICA SILVA — Painel Administrativo
 * Gerenciamento de agendamentos (visualizar / editar / excluir)
 * ============================================================ */

require_once __DIR__ . '/auth.php';
exigir_login_pagina();

pagina_header('Agendamentos', 'agendamentos');
?>

<div class="bloco">
    <h2>Agendamentos</h2>

    <div class="tabela-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th><th>Cliente</th><th>Telefone</th><th>Unidade</th>
                    <th>Veículo</th><th>Serviço</th><th>Data</th><th>Horário</th>
                    <th>Status</th><th>Ações</th>
                </tr>
            </thead>
            <tbody id="lista">
                <tr><td colspan="10" class="vazio">Carregando...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
async function carregar() {
    const tb = document.getElementById('lista');
    try {
        const { agendamentos } = await api('/agendamentos.php');
        if (!agendamentos.length) {
            tb.innerHTML = '<tr><td colspan="10" class="vazio">Nenhum agendamento registrado.</td></tr>';
            return;
        }
        tb.innerHTML = agendamentos.map(a => `
            <tr>
                <td>${a.id}</td>
                <td>${esc(a.nome)}</td>
                <td>${esc(a.telefone) || '—'}</td>
                <td>${esc(a.unidade)}</td>
                <td>${esc(a.modelo)}</td>
                <td>${esc(a.servico)}</td>
                <td>${esc(a.data)}</td>
                <td>${esc(a.horario)}</td>
                <td><span class="badge ${esc(a.status)}">${esc(a.status)}</span></td>
                <td>
                    <div class="acoes">
                        <button class="btn btn-vermelho" onclick="mudarStatus(${a.id},'confirmado')">Confirmar</button>
                        <button class="btn btn-escuro" onclick="mudarStatus(${a.id},'cancelado')">Cancelar</button>
                        <button class="btn btn-excluir" onclick="excluir(${a.id})">Excluir</button>
                    </div>
                </td>
            </tr>`).join('');
    } catch (e) {
        tb.innerHTML = '<tr><td colspan="10" class="vazio">Erro ao carregar.</td></tr>';
        toast(e.message, 'erro');
    }
}

async function mudarStatus(id, status) {
    try {
        await api('/agendamentos.php', { method: 'PUT', body: JSON.stringify({ id, status }) });
        toast('Agendamento atualizado.');
        carregar();
    } catch (e) { toast(e.message, 'erro'); }
}

async function excluir(id) {
    if (!confirm('Deseja realmente excluir este agendamento?')) return;
    try {
        await api('/agendamentos.php', { method: 'DELETE', body: JSON.stringify({ id }) });
        toast('Agendamento removido.');
        carregar();
    } catch (e) { toast(e.message, 'erro'); }
}

carregar();
</script>

<?php pagina_footer(); ?>
