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
    "4-3-3": [["EE", "PL1", "ED"], ["MC1", "MCO", "MC2"], ["DE", "DC1", "DC2", "DD"], ["GR"]],
    "4-4-2": [["PL1", "PL2"], ["ME", "MCO", "MDC", "MD"], ["DE", "DC1", "DC2", "DD"], ["GR"]],
    "3-5-2": [["PL1", "PL2"], ["ME", "MC1", "MDC", "MC2", "MD"], ["DC1", "DC2", "DC3"], ["GR"]],
    "5-3-2": [["PL1", "PL2"], ["MC1", "MDC", "MC2"], ["DE", "DC1", "DC2", "DC3", "DD"], ["GR"]],
    "4-2-3-1": [["PL1"], ["EE", "MCO", "ED"], ["MDC1", "MDC2"], ["DE", "DC1", "DC2", "DD"], ["GR"]]
};

function generateField(tactic) {
    const field = document.getElementById("field");
    field.innerHTML = "";
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

            const colX = colIndex * colWidth + colWidth / 2;
            positionDiv.style.left = `${colX}px`;
            positionDiv.style.top = `${rowY}px`;
            positionDiv.style.transform = "translate(-50%, -50%)";

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
    let name = document.getElementById("playerName").value;
    let position = document.getElementById("playerPosition").value;

    if (!name.trim()) {
        alert("Por favor, insira um nome de jogador.");
        return;
    }

    fetch(`checkPlayer2.php?name=${name}&club=${selectedClub}`)
        .then(response => response.json())
        .then(data => {
            if (!data.exists) {
                alert("Esse jogador não existe ou não pertence ao clube!");
                return;
            }

            let positionDiv = document.querySelector(`.position[data-pos="${position}"]`);
            if (!positionDiv || positionDiv.innerHTML !== position) {
                alert("Essa posição já está ocupada!");
                return;
            }

            positionDiv.innerHTML = `<div class='player'>${name}</div>`;
        });
});
document.getElementById("addPlayer").addEventListener("click", function () {
    let name = document.getElementById("playerName").value.trim();
    let positionElement = document.querySelector(".position.selected"); // Obtém a posição selecionada

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

            // Adicionar jogador à posição escolhida
            positionElement.innerHTML = `
                <img src="${data.image}" class="player-image" alt="${data.name}">
                <div class="player-name">${data.name}</div>
            `;
        })
        .catch(error => console.error("Erro ao buscar jogador:", error));
});

// Permite selecionar uma posição antes de adicionar um jogador
document.querySelectorAll(".position").forEach(pos => {
    pos.addEventListener("click", function () {
        document.querySelectorAll(".position").forEach(p => p.classList.remove("selected"));
        this.classList.add("selected");
    });
});
