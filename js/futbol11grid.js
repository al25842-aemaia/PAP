cells.forEach(cell => {
    const jogadoresData = cell.getAttribute('data-jogadores');
    if (!jogadoresData || jogadoresData.trim() === "") return; // Verifica se há dados de jogadores

    const jogadores = jogadoresData.split(',');
    jogadores.forEach(jogador => {
        const [nome, imagem] = jogador.split("|").map(str => str.trim()); // Remove espaços extras

        if (nome.toLowerCase() === playerName.toLowerCase()) {
            if (!cell.classList.contains('correct')) { // Evita sobrescrita se já estiver preenchido
                const imgSrc = imagem === 'fallback-image.png' 
                    ? 'path/to/fallback-image.png' 
                    : `data:image/png;base64,${imagem}`;
                
                cell.innerHTML = `
                    <img src="${imgSrc}" alt="${nome}" style="width: 50px; height: auto;">
                    <p>${nome}</p>
                `;
                cell.classList.add('correct');
            }
        }
    });
});

// Limpa o campo de entrada fora do loop
if (playerInput) {
    playerInput.value = "";
}
