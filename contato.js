/* ============================================================
 * MECÂNICA SILVA — Formulário de Contato
 * Validação dos dados (lado do cliente) + envio para a API
 * ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

    const form     = document.getElementById('form-contato');
    const alerta   = document.getElementById('alerta');
    const btnEnviar = document.getElementById('btn-enviar');

    const campos = {
        nome:     document.getElementById('nome'),
        email:    document.getElementById('email'),
        telefone: document.getElementById('telefone'),
        assunto:  document.getElementById('assunto'),
        mensagem: document.getElementById('mensagem'),
    };

    /* ---------------- Validação ---------------- */

    function validar() {
        const erros = {};

        const nome = campos.nome.value.trim();
        if (nome === '') {
            erros.nome = 'Informe o seu nome.';
        } else if (nome.length < 3) {
            erros.nome = 'O nome deve ter pelo menos 3 caracteres.';
        }

        const email = campos.email.value.trim();
        const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email === '') {
            erros.email = 'Informe o seu e-mail.';
        } else if (!regexEmail.test(email)) {
            erros.email = 'Informe um e-mail válido.';
        }

        const telefone = campos.telefone.value.trim();
        if (telefone !== '') {
            const digitos = telefone.replace(/\D/g, '');
            if (digitos.length < 10 || digitos.length > 11) {
                erros.telefone = 'Informe um telefone válido (com DDD).';
            }
        }

        const mensagem = campos.mensagem.value.trim();
        if (mensagem === '') {
            erros.mensagem = 'Escreva a sua mensagem.';
        } else if (mensagem.length < 10) {
            erros.mensagem = 'A mensagem deve ter pelo menos 10 caracteres.';
        }

        return erros;
    }

    function mostrarErros(erros) {
        // Limpa tudo
        Object.keys(campos).forEach((nome) => {
            const campo = campos[nome].closest('.campo');
            const aviso = campo ? campo.querySelector('.erro') : null;
            if (campo) campo.classList.remove('invalido');
            if (aviso) aviso.textContent = '';
        });

        // Aplica os erros
        Object.keys(erros).forEach((nome) => {
            const campo = campos[nome].closest('.campo');
            const aviso = campo ? campo.querySelector('.erro') : null;
            if (campo) campo.classList.add('invalido');
            if (aviso) aviso.textContent = erros[nome];
        });

        // Tremor visual no formulário
        if (Object.keys(erros).length) {
            form.classList.add('tremor');
            setTimeout(() => form.classList.remove('tremor'), 400);
        }
    }

    function mostrarAlerta(texto, tipo) {
        alerta.textContent = texto;
        alerta.className = 'alerta ' + tipo;
    }

    function limparAlerta() {
        alerta.className = 'alerta';
        alerta.textContent = '';
    }

    /* ---------------- Máscara de telefone ---------------- */

    campos.telefone.addEventListener('input', () => {
        let v = campos.telefone.value.replace(/\D/g, '').slice(0, 11);
        if (v.length > 10) {
            v = v.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
        } else if (v.length > 6) {
            v = v.replace(/^(\d{2})(\d{4})(\d{0,4})$/, '($1) $2-$3');
        } else if (v.length > 2) {
            v = v.replace(/^(\d{2})(\d{0,5})$/, '($1) $2');
        } else if (v.length > 0) {
            v = v.replace(/^(\d{0,2})$/, '($1');
        }
        campos.telefone.value = v;
    });

    /* ---------------- Envio ---------------- */

    form.addEventListener('submit', async (evento) => {
        evento.preventDefault();
        limparAlerta();

        const erros = validar();
        mostrarErros(erros);

        if (Object.keys(erros).length) {
            mostrarAlerta('Verifique os campos destacados.', 'falha');
            return;
        }

        const dados = {
            nome:     campos.nome.value.trim(),
            email:    campos.email.value.trim(),
            telefone: campos.telefone.value.trim(),
            assunto:  campos.assunto.value,
            mensagem: campos.mensagem.value.trim(),
        };

        btnEnviar.disabled = true;
        btnEnviar.textContent = 'ENVIANDO...';

        try {
            const resposta = await fetch('api/contato.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(dados),
            });

            const retorno = await resposta.json();

            if (!resposta.ok || !retorno.sucesso) {
                if (retorno.erros) {
                    mostrarErros(retorno.erros);
                }
                throw new Error(retorno.mensagem || 'Não foi possível enviar a mensagem.');
            }

            mostrarAlerta(retorno.mensagem, 'sucesso');
            form.reset();
        } catch (erro) {
            mostrarAlerta(erro.message, 'falha');
        } finally {
            btnEnviar.disabled = false;
            btnEnviar.textContent = 'ENVIAR MENSAGEM';
        }
    });

    /* ---------------- Limpa erro ao digitar ---------------- */

    Object.values(campos).forEach((campo) => {
        campo.addEventListener('input', () => {
            const wrapper = campo.closest('.campo');
            const aviso = wrapper ? wrapper.querySelector('.erro') : null;
            if (wrapper) wrapper.classList.remove('invalido');
            if (aviso) aviso.textContent = '';
        });
    });
});
