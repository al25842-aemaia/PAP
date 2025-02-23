// Carregar clubes e escolher um aleatório
let selectedClub = null;
fetch('getClubs.php')
    .then(response => response.json())
    .then(data => {
        if (data.length > 0) {
            const randomClub = data[Math.floor(Math.random() * data.length)];
            document.getElementById("club-image").src = randomClub.imagem_clube;
            document.getElementById("club-name").textContent = randomClub.nome_clube;
            selectedClub = randomClub.id_clube;
        }
    });

const positionsMap = {
    "4-3-3": [["GR"], ["DC1", "DC2", "DE", "DD"], ["MC1", "MCO", "MC2"], ["EE", "PL1", "ED"]],
    "4-4-2": [["GR"], ["DC1", "DC2", "DE", "DD"], ["ME", "MCO", "MDC", "MD"], ["PL1", "PL2"]],
    "3-5-2": [["GR"], ["DC1", "DC2", "DC3"], ["ME", "MC1", "MDC", "MC2", "MD"], ["PL1", "PL2"]],
    "5-3-2": [["GR"], ["DE", "DC1", "DC2", "DC3", "DD"], ["MC1", "MDC", "MC2"], ["PL1", "PL2"]],
    "4-2-3-1": [["GR"], ["DC1", "DC2", "DE", "DD"], ["MDC1", "MDC2"], ["EE", "MCO", "ED"], ["PL1"]]
};

function generateField(tactic) {
    const field = document.getElementById("field");
    field.innerHTML = "";
    const positions = positionsMap[tactic];
    const rows = positions.length;
    const fieldHeight = field.clientHeight;
    const rowHeight = fieldHeight / rows;

    positions.forEach((row, rowIndex) => {
        const rowY = (rows - rowIndex - 1) * rowHeight + rowHeight / 2; // De baixo para cima
        const cols = row.length;
        const fieldWidth = field.clientWidth;
        const colWidth = fieldWidth / cols;

        row.forEach((pos, colIndex) => {
            const positionDiv = document.createElement("div");
            positionDiv.classList.add("position");
            positionDiv.setAttribute("data-pos", pos);
            positionDiv.innerHTML = pos;

            const colX = colIndex * colWidth + colWidth / 2;
            positionDiv.style.left = `${colX}px`;
            positionDiv.style.top = `${rowY}px`;
            positionDiv.style.transform = "translate(-50%, -50%)";

            positionDiv.addEventListener("click", function () {
                document.querySelectorAll(".position").forEach(p => p.classList.remove("selected"));
                this.classList.add("selected");
            });

            field.appendChild(positionDiv);
        });
    });
}

document.addEventListener("DOMContentLoaded", () => {
    const tacticSelect = document.getElementById("tactic");
    generateField(tacticSelect.value);
    tacticSelect.addEventListener("change", (e) => {
        generateField(e.target.value);
    });
});

document.getElementById("addPlayer").addEventListener("click", function () {
    let name = document.getElementById("playerName").value.trim();
    let positionElement = document.querySelector(".position.selected");

    if (!name) {
        alert("Por favor, insira um nome de jogador.");
        return;
    }

    if (!positionElement) {
        alert("Por favor, selecione uma posição.");
        return;
    }

    let position = positionElement.getAttribute("data-pos");

    fetch(`checkPlayer2.php?name=${encodeURIComponent(name)}&club=${selectedClub}&position=${position}`)
        .then(response => response.json())
        .then(data => {
            if (!data.exists) {
                alert("Jogador não encontrado ou posição/clube incorretos!");
                return;
            }

            // Verifica se a posição já está ocupada
            if (positionElement.querySelector(".player-image")) {
                alert("Essa posição já está ocupada!");
                return;
            }

            positionElement.innerHTML = `
                <img src="${data.image}" class="player-image" alt="${data.name}" style="width: 50px; height: 50px; border-radius: 50%;">
                <div class="player-name" style="font-size: 12px; margin-top: 5px;">${data.name}</div>
            `;
        })
        .catch(error => console.error("Erro ao buscar jogador:", error));
});
