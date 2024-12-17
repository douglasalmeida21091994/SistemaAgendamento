document.addEventListener("DOMContentLoaded", () => {
    // Toggle Corpo Clínico visibility based on Unidade selection
    const unidadeSelect = document.getElementById("unidade");
    const corpoClinicoSection = document.getElementById("corpo-clinico");

    unidadeSelect.addEventListener("change", () => {
        if (unidadeSelect.value) {
            corpoClinicoSection.classList.remove("hidden");
        } else {
            corpoClinicoSection.classList.add("hidden");
        }
    });

    // Toggle Agendamento Form visibility
    const btnAgendar = document.getElementById("btn-agendar");
    const formAgendamento = document.getElementById("form-agendamento");

    btnAgendar.addEventListener("click", () => {
        formAgendamento.classList.toggle("hidden");
    });

    // Show Disponibilidade Result
    const buscarDisponibilidadeBtn = document.getElementById("buscar-disponibilidade");
    const resultadoDisponibilidade = document.getElementById("resultado-disponibilidade");

    buscarDisponibilidadeBtn.addEventListener("click", () => {
        resultadoDisponibilidade.classList.remove("hidden");
    });

    // Prevent form submission on Agendamento Sequencial
    const agendamentoSequencialForm = document.querySelector("#agendamento-sequencial form");

    agendamentoSequencialForm.addEventListener("submit", (event) => {
        event.preventDefault();
        alert("Agendamento Sequencial Confirmado!");
    });
});
