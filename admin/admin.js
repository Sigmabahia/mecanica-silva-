/* ============================================================
 * MECÂNICA SILVA — Painel Administrativo
 * Funções compartilhadas (chamadas à API + notificações)
 * ============================================================ */

const API = '../api';

/**
 * Faz uma requisição à API e devolve o JSON.
 */
async function api(url, options = {}) {
    const resp = await fetch(API + url, {
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json' },
        ...options,
    });

    let dados = {};
    try {
        dados = await resp.json();
    } catch (e) {
        dados = {};
    }

    if (!resp.ok) {
        throw new Error(dados.mensagem || 'Erro ao comunicar com o servidor.');
    }
    return dados;
}

/**
 * Exibe uma notificação temporária.
 */
function toast(mensagem, tipo = 'ok') {
    const el = document.getElementById('toast');
    if (!el) return;
    el.textContent = mensagem;
    el.className = 'toast mostrar ' + tipo;
    clearTimeout(el._timer);
    el._timer = setTimeout(() => {
        el.className = 'toast ' + tipo;
    }, 3000);
}

/**
 * Escapa texto para inserir em HTML com segurança.
 */
function esc(texto) {
    const div = document.createElement('div');
    div.textContent = texto ?? '';
    return div.innerHTML;
}

/**
 * Formata um número como moeda brasileira.
 */
function moeda(valor) {
    return 'R$ ' + Number(valor || 0).toFixed(2).replace('.', ',');
}
