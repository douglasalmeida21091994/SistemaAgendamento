// ATIVANDO OS BOTÕES DE ACORDO COM A SECTION SELECIONADA
$(document).ready(function () {
    // Função para adicionar a classe 'active' ao botão clicado
    function ativarBotao() {
        // Pega todos os botões
        const buttons = document.querySelectorAll('.btn-option');
        
        // Remove a classe 'active' de todos os botões
        buttons.forEach(btn => btn.classList.remove('active'));
        
        // Verifica qual seção está na URL e ativa o botão correspondente
        const urlParams = new URLSearchParams(window.location.search);
        const section = urlParams.get('section');
        
        if (section) {
            // Encontra o botão correspondente ao parâmetro da URL e adiciona a classe 'active'
            const botaoAtivo = document.querySelector(`.btn-option[href="?section=${section}"]`);
            if (botaoAtivo) {
                botaoAtivo.classList.add('active');
            }
        }
    }

    // Chama a função para ativar o botão correto ao carregar a página
    ativarBotao();

    // Adiciona evento de clique para cada botão
    const buttons = document.querySelectorAll('.btn-option');
    buttons.forEach(button => {
        button.addEventListener('click', function () {
            // Chama a função para adicionar a classe 'active' ao botão clicado
            ativarBotao();
        });
    });
});

