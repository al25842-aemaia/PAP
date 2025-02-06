const positionsMap = {
    "4-3-3": [
        ["EE", "PL1", "ED"],
        ["MC1", "MCO", "MC2"],
        ["DE", "DC1", "DC2", "DD"],
        ["GR"]
    ],
    "4-4-2": [
        ["PL1", "PL2"],
        ["MC1", "MCO", "MDC", "MC2"],
        ["DE", "DC", "DC", "DD"],
        ["GR"]
    ],
    "3-5-2": [
        ["PL1", "PL2"],
        ["ME", "MC1", "MDC", "MC2", "MD"],
        ["DC1", "DC2", "DC3"],
        ["GR"]
    ]
};

function generateField(tactic) {
    const field = document.getElementById("field");
    field.innerHTML = ""; // Limpa o campo

    const positions = positionsMap[tactic];
    const rows = positions.length;
    const fieldHeight = field.clientHeight;
    const rowHeight = fieldHeight / rows;

    positions.forEach((row, rowIndex) => {
        const rowY = rowIndex * rowHeight + rowHeight / 2;
        const cols = row.length;
        const fieldWidth = field.clientWidth;
        const colWidth = fieldWidth / cols;

        row.forEach((pos, colIndex) => {
            const positionDiv = document.createElement("div");
            positionDiv.classList.add("position");
            positionDiv.setAttribute("data-pos", pos);
            positionDiv.innerHTML = pos;

            // Posicionamento no campo
            const colX = colIndex * colWidth + colWidth / 2;
            positionDiv.style.left = `${colX}px`;
            positionDiv.style.top = `${rowY}px`;
            positionDiv.style.transform = "translate(-50%, -50%)";

            field.appendChild(positionDiv);
        });
    });
}

// Inicializa o campo com a tática padrão ao carregar a página
document.addEventListener("DOMContentLoaded", () => {
    const tacticSelect = document.getElementById("tactic");
    generateField(tacticSelect.value);

    // Atualiza o campo quando a tática for alterada
    tacticSelect.addEventListener("change", (e) => {
        generateField(e.target.value);
    });
});

// Adicionar jogador ao campo
document.getElementById("addPlayer").addEventListener("click", function() {
    let name = document.getElementById("playerName").value;
    let position = document.getElementById("playerPosition").value;

    if (name.trim() === "") {
        alert("Por favor, insira um nome de jogador.");
        return;
    }

    let positionDiv = document.querySelector(`.position[data-pos="${position}"]`);
    if (!positionDiv) {
        alert("Essa posição não existe no esquema atual!");
        return;
    }

    if (positionDiv.innerHTML.trim() !== position) {
        alert("Já existe um jogador nesta posição!");
        return;
    }

    positionDiv.innerHTML = `<div class="player">${name}</div>`;
});
