document.addEventListener("DOMContentLoaded", () => {
    // Seleciona os elementos baseados no seu HTML
    const btnEntrar = document.querySelector("button");
    const emailInput = document.querySelector("input[type='email']");
    const senhaInput = document.querySelector("input[type='password']");
    const cardLogin = document.querySelector(".login");

    btnEntrar.addEventListener("click", () => {
        // Limpa espaços em branco e checa se está vazio
        const emailVazio = emailInput.value.trim() === "";
        const senhaVazia = senhaInput.value.trim() === "";

        if (emailVazio || senhaVazia) {
            // Ativa a animação de tremer a tela do CSS
            cardLogin.classList.add("erro-shake");

            // Se o e-mail estiver vazio, deixa a borda vermelha
            if (emailVazio) {
                emailInput.style.borderColor = "#D61F1F";
                emailInput.style.backgroundColor = "#FFF5F5";
            }
            // Se a senha estiver vazia, deixa a borda vermelha
            if (senhaVazia) {
                senhaInput.style.borderColor = "#D61F1F";
                senhaInput.style.backgroundColor = "#FFF5F5";
            }

            // Remove a classe de tremer após 0.4s para poder tremer de novo no próximo clique
            setTimeout(() => {
                cardLogin.classList.remove("erro-shake");
            }, 400);

        } else {
            // Se tudo estiver certo
            alert("Validando dados... Entrando!");
        }
    });

    // Remove o aviso de erro em vermelho assim que o usuário começar a digitar de novo
    emailInput.addEventListener("input", () => {
        emailInput.style.borderColor = "transparent";
        emailInput.style.backgroundColor = "#F0F0F0";
    });

    senhaInput.addEventListener("input", () => {
        senhaInput.style.borderColor = "transparent";
        senhaInput.style.backgroundColor = "#F0F0F0";
    });
});