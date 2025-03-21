function checkPlayer() {
    let playerName = document.getElementById("playerInput").value.trim();
    if (playerName === "") {
        alert("Digite o nome do jogador.");
        return;
    }

    let found = jogadores.find(jogador => jogador.nome_jogador.toLowerCase() === playerName.toLowerCase());

    if (found) {
        let cells = document.querySelectorAll(".cell");
        cells.forEach(cell => {
            if (cell.dataset.clube == found.id_clube && cell.dataset.nacionalidade == found.id_nacionalidade) {
                if (!cell.innerHTML) {  // Só insere se a célula estiver vazia
                    cell.innerHTML = `<img src="imagens_jogador/${found.imagem_jogador}" alt="${found.nome_jogador}">`;
                } else {
                    alert("Essa célula já tem um jogador!");
                }
            }
        });
    } else {
        alert("Jogador não encontrado ou incorreto!");
    }

    document.getElementById("playerInput").value = "";
}
