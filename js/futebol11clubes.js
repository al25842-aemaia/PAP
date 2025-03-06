document.addEventListener("DOMContentLoaded", function() {
    if (window.location.pathname.includes("jogo.php")) {
        gerarCampo();
        iniciarTemporizador();
        carregarClube();
    }
});

function carregarClube() {
    document.getElementById("clubeImagem").src = clubeImagem;
    document.getElementById("clubeNome").innerText = clubeNome;
}

function iniciarJogo() {
    let tactica = document.getElementById("tactica").value;
    let tempo = document.getElementById("tempo").value;

    sessionStorage.setItem("tactica", tactica);
    sessionStorage.setItem("tempo", tempo);

    window.location.href = "jogo.php";
}

function gerarCampo() {
    let tactica = sessionStorage.getItem("tactica");
    let posicoes = {
        "4-3-3": {
            ataque: ["EE", "PL", "ED"],
            meio: ["MC", "MCO", "MC"],
            defesa: ["DE", "DC", "DC", "DD"],
            gr: ["GR"]
        },
        "4-4-2": {
            ataque: ["PL", "PL"],
            meio: ["ME", "MC", "MC", "MD"],
            defesa: ["DE", "DC", "DC", "DD"],
            gr: ["GR"]
        }
    };

    let campo = document.getElementById("campo");
    campo.innerHTML = "";
    campo.style.display = "grid";
    campo.style.gridTemplateRows = "repeat(4, 1fr)";
    campo.style.justifyContent = "center";

    if (posicoes[tactica]) {
        Object.values(posicoes[tactica]).forEach((linha) => {
            let linhaDiv = document.createElement("div");
            linhaDiv.style.display = "flex";
            linhaDiv.style.justifyContent = "center";
            linhaDiv.style.gap = "10px";

            linha.forEach(pos => {
                let div = document.createElement("div");
                div.classList.add("posicao");
                div.innerText = pos;
                div.dataset.posicao = pos;
                div.addEventListener("click", selecionarPosicao);
                linhaDiv.appendChild(div);
            });

            campo.appendChild(linhaDiv);
        });
    }
}

function selecionarPosicao(event) {
    let posicaoSelecionada = event.target;
    let jogador = sessionStorage.getItem("jogadorSelecionado");

    if (jogador) {
        posicaoSelecionada.innerText = jogador;
        posicaoSelecionada.style.backgroundColor = "blue";
        sessionStorage.removeItem("jogadorSelecionado");
    }
}

document.getElementById("adicionarJogador").addEventListener("click", function() {
    let input = document.getElementById("pesquisaJogador");
    let jogador = input.value.trim();

    if (jogador !== "") {
        sessionStorage.setItem("jogadorSelecionado", jogador);
        input.value = "";
        alert("Agora clique numa posição no campo para adicionar o jogador.");
    } else {
        alert("Digite um nome de jogador.");
    }
});

function iniciarTemporizador() {
    let tempoRestante = parseInt(sessionStorage.getItem("tempo"));
    let displayTempo = document.getElementById("tempoRestante");

    if (tempoRestante > 0) {
        let interval = setInterval(() => {
            tempoRestante--;
            displayTempo.innerText = "Tempo: " + tempoRestante + "s";

            if (tempoRestante <= 0) {
                clearInterval(interval);
                alert("O tempo acabou!");
                window.location.href = "futebol11clubes.php";
            }
        }, 1000);
    }
}

function voltarInicio() {
    window.location.href = "futebol11clubes.php";
}
document.addEventListener("DOMContentLoaded", function() {
    if (window.location.pathname.includes("jogo.php")) {
        gerarCampo();
        iniciarTemporizador();
        carregarClube();
    }
});

function carregarClube() {
    if (clube) {
        document.getElementById("clubeImagem").src = clube.imagem_clube;
        document.getElementById("clubeImagem").alt = clube.nome_clube;
        document.getElementById("clubeNome").innerText = clube.nome_clube;
    } else {
        console.error("Erro: Clube não encontrado.");
    }
}
