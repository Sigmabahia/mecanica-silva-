const listaHorarios = document.querySelector(".horarios");
const btagendar = document.getElementById("btagendar");

const modeloSelect = document.getElementById("modelo");
const outroModelo = document.getElementById("outro");

const trabalhoSelect = document.getElementById("trabalho");
const outroTrabalho = document.getElementById("outrot");

const selectLocal = document.getElementById("local");
const endereco = document.getElementById("endereco");

// CALENDÁRIO
const monthYear = document.getElementById("month-year");
const daysContainer = document.getElementById("days");
const prevMonthBtn = document.getElementById("prev-month");
const nextMonthBtn = document.getElementById("next-month");

let currentDate = new Date();
let dataSelecionada = null;

const horariosDisponiveis = {
    1: ["09:00", "10:00", "11:00"],
    2: ["10:00", "11:00", "14:00"],
    3: ["09:00", "13:00", "15:00"],
    4: ["10:00", "12:00", "16:00"],
    5: ["09:00", "11:00", "14:00"],
    6: ["08:00", "10:00", "12:00"]
};

let horarioSelecionado = null;

// ENDEREÇOS DAS UNIDADES
selectLocal.addEventListener("change", () => {

    switch (selectLocal.value) {

        case "Jundiaí Centro":
            endereco.textContent =
                "LOCAL: AV. NOVE DE JULHO, 500 - CENTRO";
            break;

        case "Vila Arens":
            endereco.textContent =
                "LOCAL: RUA BARÃO DE TEFFÉ, 250 - VILA ARENS";
            break;

        case "Medeiros":
            endereco.textContent =
                "LOCAL: AV. REYNALDO PORCARI, 1000 - MEDEIROS";
            break;

        default:
            endereco.textContent =
                "LOCAL: SELECIONE UMA UNIDADE";
    }

});

// CAMPO OUTRO MODELO
modeloSelect.addEventListener("change", () => {

    outroModelo.style.display =
        modeloSelect.value === "outro"
            ? "block"
            : "none";

});

// CAMPO OUTRO SERVIÇO
trabalhoSelect.addEventListener("change", () => {

    outroTrabalho.style.display =
        trabalhoSelect.value === "outro"
            ? "block"
            : "none";

});

btagendar.disabled = true;

// CALENDÁRIO
function renderCalendar(date) {

    const year = date.getFullYear();
    const month = date.getMonth();

    monthYear.textContent =
        `${date.toLocaleString("pt-BR", {
            month: "long"
        })} ${year}`;

    const firstDayOfMonth =
        new Date(year, month, 1).getDay();

    const lastDateOfMonth =
        new Date(year, month + 1, 0).getDate();

    daysContainer.innerHTML = "";

    for (let i = 0; i < firstDayOfMonth; i++) {

        const vazio = document.createElement("div");
        daysContainer.appendChild(vazio);

    }

    for (let i = 1; i <= lastDateOfMonth; i++) {

        const dayElement =
            document.createElement("div");

        dayElement.textContent = i;

        const dataDia =
            new Date(year, month, i);

        const hoje = new Date();
        hoje.setHours(0, 0, 0, 0);

        if (dataDia < hoje) {

            dayElement.classList.add("disabled");

        } else {

            dayElement.addEventListener("click", () => {

                document
                    .querySelectorAll("#days div")
                    .forEach((d) =>
                        d.classList.remove("active")
                    );

                dayElement.classList.add("active");

                dataSelecionada = dataDia;

                carregarHorarios(dataDia);

            });

        }

        daysContainer.appendChild(dayElement);

    }

}

// CARREGAR HORÁRIOS
function carregarHorarios(dataEscolhida) {

    const diaSemana =
        dataEscolhida.getDay();

    removerAntigos();

    horarioSelecionado = null;
    btagendar.disabled = true;

    if (diaSemana === 0) {

        Msg("Oficina fechada aos domingos.");
        return;

    }

    const horarios =
        horariosDisponiveis[diaSemana] || [];

    const divBt =
        document.createElement("div");

    divBt.classList.add("btHorarios");

    horarios.forEach((horario) => {

        const bt =
            document.createElement("button");

        bt.textContent = horario;
        bt.classList.add("btHorario");

        bt.addEventListener("click", () => {

            document
                .querySelectorAll(".btHorario")
                .forEach((btn) =>
                    btn.classList.remove("selecionado")
                );

            bt.classList.add("selecionado");

            horarioSelecionado = horario;
            btagendar.disabled = false;

        });

        divBt.appendChild(bt);

    });

    listaHorarios.appendChild(divBt);

}

// BOTÃO AGENDAR
btagendar.addEventListener("click", () => {

    const local =
        document.getElementById("local").value;

    let modelo =
        document.getElementById("modelo").value;

    let trabalho =
        document.getElementById("trabalho").value;

    if (modelo === "outro") {
        modelo = outroModelo.value.trim();
    }

    if (trabalho === "outro") {
        trabalho = outroTrabalho.value.trim();
    }

    if (!local) {
        Msg("Selecione uma unidade.");
        return;
    }

    if (!modelo) {
        Msg("Selecione o modelo do carro.");
        return;
    }

    if (!trabalho) {
        Msg("Selecione um serviço.");
        return;
    }

    if (!dataSelecionada) {
        Msg("Selecione uma data.");
        return;
    }

    if (!horarioSelecionado) {
        Msg("Selecione um horário.");
        return;
    }

    const dataFormatada =
        dataSelecionada.toLocaleDateString("pt-BR");

    Msg(
`✅ Agendamento confirmado!

Unidade: ${local}
Carro: ${modelo}
Serviço: ${trabalho}
Data: ${dataFormatada}
Horário: ${horarioSelecionado}`,
        "green"
    );

});

// LIMPAR HORÁRIOS
function removerAntigos() {

    const listaAntiga =
        listaHorarios.querySelector(".btHorarios");

    if (listaAntiga) {
        listaAntiga.remove();
    }

    const mensagem =
        document.getElementById("mensagem");

    if (mensagem) {
        mensagem.textContent = "";
    }

}

// MENSAGENS
function Msg(texto, cor = "red") {

    const mensagem =
        document.getElementById("mensagem");

    if (!mensagem) return;

    mensagem.textContent = texto;
    mensagem.style.color = cor;
    mensagem.style.fontSize = "18px";
    mensagem.style.fontWeight = "bold";
    mensagem.style.textAlign = "center";
    mensagem.style.marginTop = "20px";
    mensagem.style.whiteSpace = "pre-line";

}

// BOTÕES DO CALENDÁRIO
prevMonthBtn.addEventListener("click", () => {

    currentDate.setMonth(
        currentDate.getMonth() - 1
    );

    renderCalendar(currentDate);

});

nextMonthBtn.addEventListener("click", () => {

    currentDate.setMonth(
        currentDate.getMonth() + 1
    );

    renderCalendar(currentDate);

});

// INICIAR CALENDÁRIO
renderCalendar(currentDate);