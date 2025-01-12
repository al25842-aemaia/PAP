document.addEventListener("DOMContentLoaded", () => {
    const tacticGrid = document.getElementById("tacticGrid");
    const playerSearch = document.getElementById("playerSearch");
    const addPlayerButton = document.getElementById("addPlayerButton");
    const clubImage = document.getElementById("clubImage");
    const clubName = document.getElementById("clubName");

    // Selecionar um clube aleatório
    let currentClub = clubs[Math.floor(Math.random() * clubs.length)];
    clubName.textContent = currentClub.nome_clube;
    clubImage.src = currentClub.imagem_clube;

    // Gerar a tática (exemplo: 4-4-2)
    const tactics = {
        "4-4-2": [
            { position: "PL", filled: false }, { position: "PL", filled: false },
            { position: "MCO", filled: false }, { position: "MC", filled: false },
            { position: "MC", filled: false }, { position: "MDC", filled: false },
            { position: "DE", filled: false }, { position: "DC", filled: false },
            { position: "DC", filled: false }, { position: "DD", filled: false },
            { position: "GR", filled: false }
        ]
    };

    const selectedTactic = tactics["4-4-2"]; // Tática fixa para teste

    // Limpar e configurar a grade
    function renderGrid() {
        tacticGrid.innerHTML = ""; // Limpa o conteúdo anterior
        selectedTactic.forEach((player) => {
            const gridItem = document.createElement("div");
            gridItem.classList.add("grid-item");
            gridItem.dataset.position = player.position;

            if (player.filled && player.image && player.name) {
                // Se preenchido, exibe o jogador
                gridItem.innerHTML = `
                    <img src="${player.image}" alt="${player.name}" />
                    <span>${player.name}</span>
                `;
            } else {
                // Se vazio, exibe apenas a posição
                gridItem.textContent = player.position;
            }

            tacticGrid.appendChild(gridItem);
        });
    }

    renderGrid(); // Renderizar a grade inicialmente

    // Verificar jogador
    addPlayerButton.addEventListener("click", () => {
        const playerName = playerSearch.value.trim();
        if (!playerName) {
            alert("Digite o nome do jogador.");
            return;
        }

        // Buscar jogador no banco de dados
        const player = players.find(p => p.nome_jogador.toLowerCase() === playerName.toLowerCase());

        if (!player) {
            alert("Jogador não encontrado.");
            return;
        }

        // Verificar se o jogador pertence ao clube atual
        if (player.nome_clube !== currentClub.nome_clube) {
            alert(`Jogador ${player.nome_jogador} não pertence ao clube ${currentClub.nome_clube}.`);
            return;
        }

        // Verificar se a posição está disponível
        const availablePosition = selectedTactic.find(p => p.position === player.nome_posicao && !p.filled);
        if (!availablePosition) {
            alert(`A posição ${player.nome_posicao} já está ocupada ou não faz parte da tática.`);
            return;
        }

        // Marcar a posição como preenchida
        availablePosition.filled = true;
        availablePosition.image = player.imagem_jogador;
        availablePosition.name = player.nome_jogador;

        renderGrid(); // Atualizar a grade

        // Selecionar o próximo clube
        currentClub = clubs[Math.floor(Math.random() * clubs.length)];
        clubName.textContent = currentClub.nome_clube;
        clubImage.src = currentClub.imagem_clube;

        // Verificar se todos os jogadores foram inseridos
        if (selectedTactic.every(p => p.filled)) {
            alert("Parabéns! Você completou o time.");
        }
    });
});
