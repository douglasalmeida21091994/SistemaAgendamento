$(document).ready(function () {
    var table;

    function initDataTable() {
        if ($.fn.dataTable.isDataTable('#medicosTabela')) {
            table.destroy();
        }

        table = $('#medicosTabela').DataTable({
            searching: true,
            paging: true,
            pageLength: 15,
            lengthChange: false,
            info: true,
            language: {
                search: "Buscar:",
                paginate: {
                    first: "Primeira",
                    previous: "Anterior",
                    next: "Próxima",
                    last: "Última"
                },
                info: "Exibindo _START_ até _END_ de _TOTAL_ médicos",
                infoEmpty: "Nenhum médico encontrado",
                zeroRecords: "Nenhum médico encontrado"
            }
        });
    }

    const unidadeSelect = document.getElementById("unidade");
    const corpoClinicoSection = document.getElementById("corpo-clinico");
    const especialidadeSelect = document.getElementById("especialidade");

    function limparTabela() {
        if ($.fn.dataTable.isDataTable('#medicosTabela')) {
            table.destroy();
        }
        $('#medicosTabela tbody').empty();
    }

    function buscarMedicos(unidadeId) {
        if (!unidadeId || unidadeId === "") {
            console.error("ID da unidade inválido");
            return;
        }

        limparTabela();
        corpoClinicoSection.classList.remove("hidden");

        fetch(`queries/buscar_medicos.php?unidade_id=${encodeURIComponent(unidadeId)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na rede');
                }
                return response.text();
            })
            .then(data => {
                $('#medicosTabela tbody').empty();
                $('#medicosTabela tbody').html(data);
                initDataTable();
            })
            .catch(error => {
                console.error('Erro:', error);
                $('#medicosTabela tbody').html("<tr><td colspan='5'>Erro ao carregar médicos</td></tr>");
            });
    }

    function buscarEspecialidades(medicoId) {
        return fetch(`queries/buscar_medicos.php?medico_id=${encodeURIComponent(medicoId)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na rede');
                }
                return response.text();
            })
            .catch(error => {
                console.error('Erro ao buscar especialidades:', error);
                return '<option value="">Erro ao carregar especialidades</option>';
            });
    }


    // VALIDAÇÃO PARA EXIBIR O CORPO CLÍNICO DAS UNIDADES
    unidadeSelect.addEventListener("change", function () {
        const unidadeId = this.value;

        if (unidadeId == "selecione") {
            // console.log(`A unidade selecionada é: ${unidadeId}`)
            corpoClinicoSection.classList.add("hidden");
            limparTabela();
        } else {
            // console.log(`A unidade selecionada é: ${unidadeId}`)
            buscarMedicos(unidadeId);
        }
    });

    // // RETIREI/COMENTEI O SELECIONE DO INDEX
    // if (unidadeSelect.value !== "selecione") {
    //     buscarMedicos(unidadeSelect.value);
    // } else {
    //     corpoClinicoSection.classList.add("hidden");
    // }

    // Modal e funções relacionadas
    const modal = document.getElementById('agenda-modal');
    const span = document.querySelector('.close');

    function abrirModal(medicoData) {
        limparCamposModal();

        if (medicoData) {
            carregarDadosMedico(medicoData);
            document.querySelector('#agenda-modal h1').textContent = medicoData.nome;

            // Buscar e carregar especialidades
            buscarEspecialidades(medicoData.cod)
                .then(options => {
                    if (especialidadeSelect) {
                        especialidadeSelect.innerHTML = '<option value="">Escolha</option>' + options;
                    }
                });
        }

        modal.style.removeProperty('display');
        modal.classList.add('modal-active');
        document.body.style.overflow = 'hidden';
    }

    function fecharModal() {
        modal.classList.remove('modal-active');
        document.body.style.overflow = '';
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
        limparCamposModal();
    }

    function limparCamposModal() {
        document.getElementById('vigencia-inicio').value = '';
        document.getElementById('vigencia-fim').value = '';

        const botoesDias = document.querySelectorAll('#dias button');
        botoesDias.forEach(botao => {
            botao.classList.remove('selected');
        });

        document.getElementById('tipo').selectedIndex = 0;
        document.getElementById('contratacao').selectedIndex = 0;
        document.getElementById('beneficiarios').value = '';
        if (especialidadeSelect) {
            especialidadeSelect.innerHTML = '<option value="">Escolha</option>';
        }
    }

    function carregarDadosMedico(medicoData) {
        if (medicoData.vigencia_inicio) {
            document.getElementById('vigencia-inicio').value = medicoData.vigencia_inicio;
        }
        if (medicoData.vigencia_fim) {
            document.getElementById('vigencia-fim').value = medicoData.vigencia_fim;
        }
        if (medicoData.dias) {
            const botoesDias = document.querySelectorAll('#dias button');
            medicoData.dias.forEach(dia => {
                const botao = Array.from(botoesDias).find(b => b.textContent === dia);
                if (botao) botao.classList.add('selected');
            });
        }
        if (medicoData.tipo) {
            const tipoSelect = document.getElementById('tipo');
            Array.from(tipoSelect.options).forEach((option, index) => {
                if (option.text === medicoData.tipo) tipoSelect.selectedIndex = index;
            });
        }
    }

    span.onclick = fecharModal;

    // FUNÇÃO PARA SELECIONAR OS DIAS DA SEMANA  ----------------------------- ANTIGA FUNÇÃO PARA SELECIONAR OS DIAS
    document.querySelectorAll('#dias button').forEach(button => {
        button.addEventListener('click', function () {
            this.classList.toggle('selected');
            this.classList.toggle('active'); // Add both classes
        });
    });

    // Quando o formulário for enviado
    document.getElementById('agenda-form').onsubmit = function () {
        // Adiciona os dias ao campo "dia_agendamento" antes de enviar o formulário
        document.querySelector('input[name="dia_agendamento"]').value = diasSelecionados.join(',');
    };

    document.addEventListener('click', function (e) {
        const iconElement = e.target.closest('.criar-agenda');

        if (iconElement) {
            const row = iconElement.closest('tr');

            if (row) {
                const medicoData = {
                    cod: row.cells[0].textContent,
                    nome: row.cells[1].textContent,
                    conselho: row.cells[2].textContent
                };

                abrirModal(medicoData);
            } else {
                abrirModal();
            }
        }
    });

    // Função de Validação dos campos para cadastrar agenda (MODAL)
    document.querySelector('#div-button button[type="submit"]').addEventListener('click', function (e) {
        e.preventDefault(); // Evita o envio do formulário diretamente

        // Seleção dos campos
        const vigenciaInicio = document.getElementById('vigencia-inicio').value.trim();
        const vigenciaFim = document.getElementById('vigencia-fim').value.trim();
        const tipo = document.getElementById('tipo').value.trim();
        const contratacao = document.getElementById('contratacao').value.trim();
        const beneficiarios = document.getElementById('beneficiarios').value.trim();
        const especialidade = document.getElementById('especialidade').value.trim();

        // Captura a data atual
        const dataAtual = new Date();

        // Zera a hora, minuto, segundo e milissegundo da data atual, mas usando UTC
        dataAtual.setUTCHours(0, 0, 0, 0);

        // Converte a data do campo para o formato Date
        const vigenciaData = new Date(vigenciaInicio);

        // Zera a hora, minuto, segundo e milissegundo da vigência, mas usando UTC
        vigenciaData.setUTCHours(0, 0, 0, 0);

        // Verifica se a data de vigência é maior ou igual à data atual
        if (vigenciaData < dataAtual) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'A data de vigência não pode ser menor do que a data atual',
            });
            return;
        }


        // Verifica se ao menos um dia foi selecionado
        const diasSelecionados = document.querySelectorAll('#dias button.selected').length > 0;

        // Validação dos campos
        if (!vigenciaInicio || !vigenciaFim || !tipo || !contratacao || !beneficiarios || !especialidade || !diasSelecionados) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Por favor, preencha todos os campos antes de cadastrar a agenda!',
            });
            return;
        }

        // Exibe sucesso caso todos os campos estejam preenchidos
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Agenda cadastrada com sucesso!',
            showConfirmButton: false,
            timer: 3500
        });

        // Verificando se a quantidade de beneficiário é zero
        if (beneficiarios == 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'A quantidade de beneficiário que serão atendidos não pode ser 0 (zero)!',
            });
            return;
        }

        // Lógica para enviar os dados ao backend
    });

    // Adiciona um novo select para procedimento
    document.getElementById('addProcedimento').addEventListener('click', function () {
        const container = document.getElementById('procedimentos-container');

        // Verifica quantos selects já existem, e limita a 3
        const selects = container.getElementsByClassName('form-procedimento-select');
        if (selects.length < 3) { // Limite de 3
            // Cria um novo select
            const novoSelect = document.createElement('div');
            novoSelect.classList.add('form-procedimento-select');

            novoSelect.innerHTML = `        
            <select class="form-select procedimento">
                <option value="">selecione...</option>
                <option value="1">EM CONSULTORIO (NO HORARIO NORMAL OU PREESTABELECIDO)</option>
                <option value="2">EM PRONTO SOCORRO</option>
                <option value="4163">CONSULTA EM PSICOLOGIA</option>
                <option value="4169">SESSAO DE PSICOTERAPIA INDIVIDUAL</option>
                <option value="4180">SESSAO DE PSICOPEDAGOGIA INDIVIDUAL</option>
                <option value="4187">SESSAO DE PSICOMOTRICIDADE INDIVIDUAL</option>
                <option value="4168">CONSULTA EM TERAPIA OCUPACIONAL</option>
                <option value="4195">SESSAO INDIVIDUAL AMBULATORIAL, EM TERAPIA OCUPACIONAL</option>
                <option value="4171">SESSAO INDIVIDUAL AMBULATORIAL DE FONOAUDIOLOGIA</option>
            </select>
        `;

            container.appendChild(novoSelect); // Adiciona o novo select ao container
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Você já atingiu o limite de 3 procedimentos.',
            });
            return;
        }
    });

    // Remove o último select de procedimento
    document.getElementById('removeProcedimento').addEventListener('click', function () {
        const container = document.getElementById('procedimentos-container');

        // Verifica se há mais de um select para remover
        const selects = container.getElementsByClassName('form-procedimento-select');
        if (selects.length > 1) {
            container.removeChild(selects[selects.length - 1]); // Remove o último select
        }
    });

});
