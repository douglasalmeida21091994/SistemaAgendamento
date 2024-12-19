$(document).ready(function() {
    var table;

    // Função para inicializar ou reinicializar o DataTable
    function initDataTable() {
        if ($.fn.dataTable.isDataTable('#medicosTabela')) {
            table.destroy(); // Destruir tabela atual antes de recriar
            $('#medicosTabela tbody').empty(); // Limpa o conteúdo da tabela
        }
        
        table = $('#medicosTabela').DataTable({
            searching: true,
            paging: true,
            pageLength: 20,
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
    const medicosTabela = document.getElementById("medicos-tabela");

    // Função para mostrar loading
    function showLoading() {
        medicosTabela.innerHTML = "<tr><td colspan='5' class='text-center'>Carregando médicos...</td></tr>";
    }

    // Função para limpar a tabela
    function limparTabela() {
        if ($.fn.dataTable.isDataTable('#medicosTabela')) {
            table.destroy();
        }
        $('#medicosTabela tbody').empty();
    }

    // Função para buscar médicos com base na unidade selecionada
    function buscarMedicos(unidadeId) {
        // Limpar tabela atual
        limparTabela();
        
        if (!unidadeId || unidadeId === "") {
            console.error("ID da unidade inválido");
            return;
        }

        showLoading();
        corpoClinicoSection.classList.remove("hidden");
        
        fetch(`queries/buscar_medicos.php?unidade_id=${encodeURIComponent(unidadeId)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na rede');
                }
                return response.text();
            })
            .then(data => {
                medicosTabela.innerHTML = data;
                initDataTable();
            })
            .catch(error => {
                console.error('Erro:', error);
                medicosTabela.innerHTML = "<tr><td colspan='5'>Erro ao carregar médicos</td></tr>";
            });
    }

    // Event listener para mudanças no select de unidade
    unidadeSelect.addEventListener("change", function() {
        const unidadeId = this.value;

        if (unidadeId === "selecione") {
            corpoClinicoSection.classList.add("hidden");
            limparTabela();
        } else {
            buscarMedicos(unidadeId);
        }
    });

    // Ao carregar a página
    if (unidadeSelect.value === "selecione") {
        corpoClinicoSection.classList.add("hidden");
    } else {
        buscarMedicos(unidadeSelect.value);
    }
});