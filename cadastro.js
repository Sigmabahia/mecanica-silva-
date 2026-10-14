document.addEventListener("DOMContentLoaded", () => {
    // Seleciona os elementos usando a estrutura do seu CSS
    const btnCadastrar = document.querySelector("button");
    const nomeInput = document.querySelector("input[type='text']");
    const emailInput = document.querySelector("input[type='email']");
    const senhaInput = document.querySelector("input[type='password']");
    const cardCadastro = document.querySelector(".cadastro"); // Sua classe do card principal

    btnCadastrar.addEventListener("click", () => {
        // Checa se os campos estão vazios
        const nomeVazio = nomeInput.value.trim() === "";
        const emailVazio = emailInput.value.trim() === "";
        const senhaVazia = senhaInput.value.trim() === "";

        if (nomeVazio || emailVazio || senhaVazia) {
            // Faz o card inteiro tremer
            cardCadastro.classList.add("erro-shake");

            // Aplica o erro visual no campo Nome
            if (nomeVazio) {
                nomeInput.style.borderColor = "#D61F1F";
                nomeInput.style.backgroundColor = "#FFF5F5";
            }
            // Aplica o erro visual no campo E-mail
            if (emailVazio) {
                emailInput.style.borderColor = "#D61F1F";
                emailInput.style.backgroundColor = "#FFF5F5";
            }
            // Aplica o erro visual no campo Senha
            if (senhaVazia) {
                senhaInput.style.borderColor = "#D61F1F";
                senhaInput.style.backgroundColor = "#FFF5F5";
            }

            // Remove o efeito de tremer para que possa acontecer de novo
            setTimeout(() => {
                cardCadastro.classList.remove("erro-shake");
            }, 400);

        } else {
            // Se tudo estiver preenchido
            alert("Cadastro realizado com sucesso!");
        }
    });

    // Remove o estilo de erro assim que o usuário começar a digitar novamente
    nomeInput.addEventListener("input", () => {
        nomeInput.style.borderColor = "transparent";
        nomeInput.style.backgroundColor = "#F0F0F0";
    });

    emailInput.addEventListener("input", () => {
        emailInput.style.borderColor = "transparent";
        emailInput.style.backgroundColor = "#F0F0F0";
    });

    senhaInput.addEventListener("input", () => {
        senhaInput.style.borderColor = "transparent";
        senhaInput.style.backgroundColor = "#F0F0F0";
    });
});