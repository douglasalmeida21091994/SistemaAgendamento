$(document).ready(function() {
    var table;

    function initDataTable() {
        if ($.fn.dataTable.isDataTable('#medicosTabela')) {
            table.destroy();
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

    function limparTabela() {
        if ($.fn.dataTable.isDataTable('#medicosTabela')) {
            table.destroy();
            $('#medicosTabela tbody').empty();
        }
    }

    function buscarMedicos(unidadeId) {
        if (!unidadeId || unidadeId === "") {
            console.error("ID da unidade inválido");
            return;
        }

        // Limpar a tabela antes de buscar novos dados
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
                // Limpar e adicionar novos dados
                $('#medicosTabela tbody').empty();
                $('#medicosTabela tbody').html(data);
                
                // Reinicializar DataTable com novos dados
                initDataTable();
            })
            .catch(error => {
                console.error('Erro:', error);
                $('#medicosTabela tbody').html("<tr><td colspan='5'>Erro ao carregar médicos</td></tr>");
            });
    }

    unidadeSelect.addEventListener("change", function() {
        const unidadeId = this.value;
        
        if (unidadeId === "selecione") {
            corpoClinicoSection.classList.add("hidden");
            limparTabela();
        } else {
            // Sempre buscar novos dados ao trocar de unidade
            buscarMedicos(unidadeId);
        }
    });

    // Inicialização ao carregar a página
    if (unidadeSelect.value === "selecione") {
        corpoClinicoSection.classList.add("hidden");
    } else {
        buscarMedicos(unidadeSelect.value);
    }
});
