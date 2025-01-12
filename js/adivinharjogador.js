let vidas = 6;
let jogadorAtual = null;
let sequenciaAtual = 0;
let melhorSequencia = 0;
let jogadoresUsados = [];
let dicasMostradas = 0;
let totalJogadores = 0;

function carregarJogador() {
    fetch('carregarjogador.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                mostrarErro(data.error);
                return;
            }

            // Evita carregar o mesmo jogador
            if (jogadoresUsados.includes(data.id_jogador)) {
                carregarJogador(); // Recursão para carregar um novo jogador
                return;
            }

            // Adiciona o jogador à lista de usados
            jogadoresUsados.push(data.id_jogador);
            jogadorAtual = data;

            // Atualiza a interface com o novo jogador
            atualizarInterfaceJogador(data);
            reiniciarDicas();
            verificarFimDeJogo();
        })
        .catch(error => console.error('Erro ao carregar jogador:', error));
}

function mostrarErro(mensagem) {
    document.getElementById('jogador').innerHTML = mensagem;
}

function atualizarInterfaceJogador(data) {
    console.log("Jogador Atual:", jogadorAtual); // Verifique se o jogador está sendo carregado corretamente
    document.getElementById('jogador').innerHTML = `<img src="${data.imagem_jogador}" alt="${data.nome_jogador}">`;
}

function reiniciarDicas() {
    dicasMostradas = 0; // Reinicia as dicas para o novo jogador
    limparDicas(); // Limpa dicas anteriores na área de dicas
}

function verificarFimDeJogo() {
    // Verifica se todos os jogadores foram adivinhados
    if (jogadoresUsados.length >= totalJogadores) {
        alert("Parabéns! Você adivinhou todos os jogadores!");
        desabilitarCampos();
    }
}

function desabilitarCampos() {
    // Desabilita os campos de entrada e o botão
    document.getElementById('nome_jogador').disabled = true;
    document.querySelector('button').disabled = true;
}

function atualizarVidas() {
    const vidasContainer = document.getElementById('vidas');
    vidasContainer.innerHTML = '';

    // Atualiza a quantidade de vidas com os ícones correspondentes
    for (let i = 0; i < vidas; i++) {
        const vidaIcone = document.createElement('img');
        vidaIcone.src = 'imagens/bola.png'; // Caminho da imagem da bola de futebol
        vidaIcone.alt = 'Vida';
        vidaIcone.classList.add('vida-icone');
        vidasContainer.appendChild(vidaIcone);
    }
}

function atualizarSequencia() {
    document.getElementById('sequencia_atual').innerText = sequenciaAtual;
    document.getElementById('melhor_sequencia').innerText = melhorSequencia;
}

function mostrarDica() {
    const dicasContainer = document.getElementById('dicas');
    const dica = document.createElement('p');
    dicasMostradas++;

    // Dependendo da quantidade de dicas mostradas, exibe a dica correspondente
    switch (dicasMostradas) {
        case 1:
            dica.textContent = `Número da camisola: ${jogadorAtual.numero_camisola}`;
            break;
        case 2:
            dica.textContent = `Status: ${jogadorAtual.aposentado === 1 ? 'Aposentado' : 'Ativo'}`;
            break;
        case 3:
            dica.textContent = `Local do Clube: ${jogadorAtual.local_clube}`;
            break;
        case 4:
            dica.textContent = `Nacionalidade: ${jogadorAtual.nacionalidade}`;
            break;
        case 5:
            dica.textContent = `Posição: ${jogadorAtual.nome_posicao || 'Posição não disponível'}`;
            break;
        case 6:
            dica.textContent = `Clube: ${jogadorAtual.nome_clube}`;
            break;
    }

    dicasContainer.appendChild(dica);
}

function limparDicas() {
    document.getElementById('dicas').innerHTML = ''; // Limpa as dicas
}

function adivinhar() {
    const resposta = document.getElementById('nome_jogador').value.trim();

    // Verifica se a resposta está correta
    if (resposta.toLowerCase() === jogadorAtual.nome_jogador.toLowerCase()) {
        alert("Correto! Vamos para o próximo jogador.");
        sequenciaAtual++;

        // Atualiza a melhor sequência de acertos
        if (sequenciaAtual > melhorSequencia) {
            melhorSequencia = sequenciaAtual;
        }

        atualizarSequencia();
        carregarJogador(); // Carrega um novo jogador após acerto
        document.getElementById('nome_jogador').value = ''; // Limpa o campo de resposta
    } else {
        vidas--;
        sequenciaAtual = 0;
        atualizarVidas();
        atualizarSequencia();
        mostrarDica();

        if (vidas <= 0) {
            alert("Você perdeu! Fim de jogo.");
            desabilitarCampos();
        } else {
            alert("Errado! Tente novamente.");
        }
    }
}

function carregarTotalJogadores() {
    fetch('carregar_total_jogadores.php')
        .then(response => response.json())
        .then(data => {
            totalJogadores = data.total; // Atualiza o total de jogadores
            carregarJogador(); // Carrega o primeiro jogador
        })
        .catch(error => console.error('Erro ao carregar o total de jogadores:', error));
}

// Chama a função para carregar os totalJogadores quando o script for carregado
carregarTotalJogadores();
