cells.forEach(cell => {
    const jogadoresData = cell.getAttribute('data-jogadores');
    if (!jogadoresData || jogadoresData.trim() === "") return; // Verifica se há dados de jogadores

    const jogadores = jogadoresData.split(',');
    jogadores.forEach(jogador => {
        const [nome, imagem] = jogador.split("|");
        if (nome.toLowerCase() === playerName) {
            const imgSrc = imagem === 'fallback-image.png' 
                ? 'path/to/fallback-image.png' 
                : `data:image/png;base64,${imagem}`;
            cell.innerHTML = `<img src="${imgSrc}" alt="${nome}" style="width: 50px; height: auto;">`;
            cell.classList.add('correct');
        }

        input.value = ""; // Limpa o campo
    });
});

// Limpa o campo de entrada fora do loop
playerInput.value = ""; 
