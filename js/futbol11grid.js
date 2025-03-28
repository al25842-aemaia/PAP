document.addEventListener('DOMContentLoaded', function() {
    // Verifica se o grid foi renderizado
    const grid = document.querySelector('.grid');
    if (!grid) {
        console.error('Grid não encontrado');
        return;
    }

    // Configura o botão de verificação
    const verifyBtn = document.getElementById('verifyBtn');
    const playerInput = document.getElementById('playerInput');

    verifyBtn.addEventListener('click', checkPlayer);
    playerInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') checkPlayer();
    });

    function checkPlayer() {
        const nome = playerInput.value.trim();
        
        if (!nome) {
            alert('Por favor, digite o nome de um jogador');
            playerInput.focus();
            return;
        }

        // Busca flexível pelo jogador
        const jogador = window.jogadores.find(j => 
            j.nome_jogador.toLowerCase().includes(nome.toLowerCase())
        );

        if (!jogador) {
            alert('Jogador não encontrado!\nVerifique o nome e tente novamente.');
            playerInput.focus();
            return;
        }

        // Encontra células correspondentes
        const celulas = document.querySelectorAll(`
            .cell[data-clube="${jogador.id_clube}"][data-nacionalidade="${jogador.id_nacionalidade}"]
        `);

        if (celulas.length === 0) {
            alert('Este jogador não corresponde a nenhuma célula no grid atual');
            return;
        }

        // Preenche a primeira célula vazia
        let preenchido = false;
        celulas.forEach(celula => {
            if (!celula.hasChildNodes()) {
                celula.innerHTML = `
                    <img src="${jogador.imagem_jogador}" 
                         alt="${jogador.nome_jogador}"
                         title="${jogador.nome_jogador}">
                `;
                preenchido = true;
            }
        });

        if (!preenchido) {
            alert('Todas as células para este jogador já estão preenchidas!');
        }

        playerInput.value = '';
        playerInput.focus();
    }
});

// Fallback para JavaScript desativado
document.documentElement.classList.add('js');