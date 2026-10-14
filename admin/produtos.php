<?php
/* ============================================================
 * MECÂNICA SILVA — Painel Administrativo
 * Gerenciamento de produtos (CRUD completo)
 * ============================================================ */

require_once __DIR__ . '/auth.php';
exigir_login_pagina();

pagina_header('Produtos', 'produtos');
?>

<div class="bloco">
    <h2 id="form-titulo">Novo produto</h2>

    <form id="form-produto" class="form-grid" onsubmit="salvar(event)">
        <input type="hidden" id="id" value="">

        <div class="campo">
            <label>Nome *</label>
            <input type="text" id="nome" placeholder="Ex.: Óleo 5W-30" required>
        </div>

        <div class="campo">
            <label>Categoria</label>
            <input type="text" id="categoria" placeholder="Ex.: Óleos">
        </div>

        <div class="campo">
            <label>Preço (R$)</label>
            <input type="text" id="preco" placeholder="Ex.: 89,90">
        </div>

        <div class="campo">
            <label>Estoque</label>
            <input type="number" id="estoque" placeholder="0" min="0">
        </div>

        <div class="campo full">
            <label>URL da imagem</label>
            <input type="text" id="imagem" placeholder="https://...">
        </div>

        <div class="campo full">
            <label>Descrição</label>
            <textarea id="descricao" rows="2" placeholder="Descrição do produto"></textarea>
        </div>

        <div class="form-acoes full">
            <button type="submit" class="btn btn-vermelho">Salvar</button>
            <button type="button" class="btn btn-claro" onclick="limparForm()">Cancelar</button>
        </div>
    </form>
</div>

<div class="bloco">
    <h2>Produtos cadastrados</h2>
    <div class="tabela-wrap">
        <table>
            <thead>
                <tr><th>#</th><th>Imagem</th><th>Nome</th><th>Categoria</th><th>Preço</th><th>Estoque</th><th>Ações</th></tr>
            </thead>
            <tbody id="lista">
                <tr><td colspan="7" class="vazio">Carregando...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
let produtos = [];

async function carregar() {
    const tb = document.getElementById('lista');
    try {
        const dados = await api('/produtos.php');
        produtos = dados.produtos;
        if (!produtos.length) {
            tb.innerHTML = '<tr><td colspan="7" class="vazio">Nenhum produto cadastrado.</td></tr>';
            return;
        }
        tb.innerHTML = produtos.map(p => `
            <tr>
                <td>${p.id}</td>
                <td>${p.imagem ? `<img src="${esc(p.imagem)}" alt="" style="width:50px;height:50px;object-fit:contain;">` : '—'}</td>
                <td>${esc(p.nome)}</td>
                <td>${esc(p.categoria) || '—'}</td>
                <td>${moeda(p.preco)}</td>
                <td>${p.estoque}</td>
                <td>
                    <div class="acoes">
                        <button class="btn btn-escuro" onclick="editar(${p.id})">Editar</button>
                        <button class="btn btn-excluir" onclick="excluir(${p.id})">Excluir</button>
                    </div>
                </td>
            </tr>`).join('');
    } catch (e) {
        tb.innerHTML = '<tr><td colspan="7" class="vazio">Erro ao carregar.</td></tr>';
        toast(e.message, 'erro');
    }
}

function dadosForm() {
    return {
        id:        document.getElementById('id').value,
        nome:      document.getElementById('nome').value.trim(),
        categoria: document.getElementById('categoria').value.trim(),
        preco:     document.getElementById('preco').value.trim(),
        estoque:   document.getElementById('estoque').value || 0,
        imagem:    document.getElementById('imagem').value.trim(),
        descricao: document.getElementById('descricao').value.trim(),
    };
}

async function salvar(ev) {
    ev.preventDefault();
    const dados = dadosForm();
    if (!dados.nome) { toast('Informe o nome do produto.', 'erro'); return; }

    try {
        if (dados.id) {
            await api('/produtos.php', { method: 'PUT', body: JSON.stringify(dados) });
            toast('Produto atualizado.');
        } else {
            await api('/produtos.php', { method: 'POST', body: JSON.stringify(dados) });
            toast('Produto cadastrado.');
        }
        limparForm();
        carregar();
    } catch (e) { toast(e.message, 'erro'); }
}

function editar(id) {
    const p = produtos.find(x => x.id == id);
    if (!p) return;
    document.getElementById('id').value        = p.id;
    document.getElementById('nome').value      = p.nome;
    document.getElementById('categoria').value = p.categoria;
    document.getElementById('preco').value     = p.preco;
    document.getElementById('estoque').value   = p.estoque;
    document.getElementById('imagem').value    = p.imagem;
    document.getElementById('descricao').value = p.descricao;
    document.getElementById('form-titulo').textContent = 'Editar produto #' + p.id;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function limparForm() {
    document.getElementById('form-produto').reset();
    document.getElementById('id').value = '';
    document.getElementById('form-titulo').textContent = 'Novo produto';
}

async function excluir(id) {
    if (!confirm('Deseja realmente excluir este produto?')) return;
    try {
        await api('/produtos.php', { method: 'DELETE', body: JSON.stringify({ id }) });
        toast('Produto removido.');
        carregar();
    } catch (e) { toast(e.message, 'erro'); }
}

carregar();
</script>

<?php pagina_footer(); ?>
