const positionsMap = {
    "4-3-3": [
        // Linha 1 (Ataque)
        ["EE", "PL", "ED"],
        // Linha 2 (Meio-campo)
        ["MC", "MCO", "MC"],
        // Linha 3 (Defesa)
        ["DE", "DC", "DC", "DD"],
        // Linha 4 (Goleiro)
        ["GR"]
    ],
    "4-4-2": [
        // Linha 1 (Ataque)
        ["PL", "SA"],
        // Linha 2 (Meio-campo)
        ["MC", "MCO", "MDC", "MC"],
        // Linha 3 (Defesa)
        ["DE", "DC", "DC", "DD"],
        // Linha 4 (Goleiro)
        ["GR"]
    ],
    "3-5-2": [
        // Linha 1 (Ataque)
        ["PL", "SA"],
        // Linha 2 (Meio-campo)
        ["MC", "MCO", "MDC", "MC", "MDC"],
        // Linha 3 (Defesa)
        ["DC", "DC", "DD"],
        // Linha 4 (Goleiro)
        ["GR"]
    ]
};

// Função para gerar o campo com base na tática escolhida
function generateField(tactic) {
    const field = document.getElementById("field");
    field.innerHTML = ""; // Limpar campo atual

    const positions = positionsMap[tactic];

    // Adiciona as posições começando de baixo para cima
    positions.forEach((row, rowIndex) => {
        row.forEach((pos, colIndex) => {
            const positionDiv = document.createElement("div");
            positionDiv.classList.add("position");
            positionDiv.setAttribute("data-pos", pos);
            positionDiv.innerHTML = pos;
            field.appendChild(positionDiv);
        });
    });
}

// Inicializa o campo com a tática selecionada ao carregar a página
document.addEventListener("DOMContentLoaded", () => {
    const tacticSelect = document.getElementById("tactic");
    generateField(tacticSelect.value); // Gera campo com tática padrão (4-3-3)

    // Atualiza o campo quando a tática for alterada
    tacticSelect.addEventListener("change", (e) => {
        generateField(e.target.value);
    });
});

document.getElementById("addPlayer").addEventListener("click", function() {
    let name = document.getElementById("playerName").value;
    let position = document.getElementById("playerPosition").value;

    if (name.trim() === "") {
        alert("Por favor, insira um nome de jogador.");
        return;
    }

    let positionDiv = document.querySelector(`.position[data-pos="${position}"]`);
    if (positionDiv.innerHTML.trim() !== "") {
        alert("Já existe um jogador nesta posição!");
        return;
    }

    positionDiv.innerHTML = `<div class="player">${name}</div>`;
});
