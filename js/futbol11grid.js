document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("player-input");
    const guessBtn = document.getElementById("guess-btn");

    guessBtn.addEventListener("click", () => {
        const guess = input.value.trim().toLowerCase(); // Convertendo para minúsculas

        if (guess === "") {
            alert("Please enter a player's name!");
            return;
        }

        const cells = document.querySelectorAll(".cell.guess");

        let matchFound = false;
        cells.forEach(cell => {
            if (!cell.textContent && cell.dataset.clube && cell.dataset.nacionalidade) {
                // Verificação mais precisa: devemos comparar o nome do jogador, clube e nacionalidade
                if (guess === "trubin" && cell.dataset.clube === "Benfica" && cell.dataset.nacionalidade === "Ucrânia") {  // Ajuste para o nome, clube e nacionalidade corretos
                    cell.textContent = guess;

                    // Altere a imagem do jogador (no exemplo estamos usando o nome para formar a imagem)
                    const img = document.createElement('img');
                    img.src = `imagens_jogador/${guess}.jpg`;  // Certifique-se de que a imagem existe
                    img.alt = guess;
                    img.style.width = "50px";
                    img.style.height = "auto";

                    // Coloca a imagem na célula
                    cell.appendChild(img);

                    // Altera o fundo da célula para indicar que o palpite foi correto
                    cell.style.backgroundColor = "#2ecc71"; // Verde para correto
                    matchFound = true;
                }
            }
        });

        if (!matchFound) {
            alert("Player not found or already guessed!");
        }

        input.value = ""; // Limpa o campo
    });
});
