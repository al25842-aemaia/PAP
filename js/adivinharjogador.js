let vidas = 6;
let jogadorAtual = null;
let sequenciaAtual = 0;
let melhorSequencia = localStorage.getItem('melhorSequencia') || 0;
let jogadoresUsados = [];
let dicasMostradas = 0;
let totalJogadores = 0;

document.addEventListener('DOMContentLoaded', function() {
    carregarTotalJogadores();
    atualizarVidas();
    atualizarSequencia();
    
    // Adiciona evento de tecla para o campo de adivinhação
    document.getElementById('nome_jogador').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            adivinhar();
        }
    });
});

function carregarJogador() {
    fetch('carregarjogador.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                mostrarErro(data.error);
                return;
            }

            if (jogadoresUsados.includes(data.id_jogador)) {
                carregarJogador();
                return;
            }

            jogadoresUsados.push(data.id_jogador);
            jogadorAtual = data;
            atualizarInterfaceJogador(data);
            reiniciarDicas();
            verificarFimDeJogo();
        })
        .catch(error => console.error('Erro ao carregar jogador:', error));
}

function mostrarErro(mensagem) {
    document.getElementById('jogador').innerHTML = `<p class="error-message">${mensagem}</p>`;
}

function atualizarInterfaceJogador(data) {
    const playerImage = document.getElementById('jogador');
    playerImage.innerHTML = '';
    
    const img = document.createElement('img');
    img.src = data.imagem_jogador;
    img.alt = data.nome_jogador;
    
    if (vidas > 0) {
        img.classList.add('silhouette');
    }
    
    playerImage.appendChild(img);
}

function reiniciarDicas() {
    dicasMostradas = 0;
    document.getElementById('dicas').innerHTML = '';
}

function verificarFimDeJogo() {
    if (jogadoresUsados.length >= totalJogadores) {
        mostrarResultado("Parabéns! Você adivinhou todos os jogadores!", true);
        desabilitarCampos();
    }
}

function desabilitarCampos() {
    document.getElementById('nome_jogador').disabled = true;
    document.querySelector('.guess-btn').disabled = true;
}

function atualizarVidas() {
    const vidasContainer = document.getElementById('vidas');
    vidasContainer.innerHTML = '';
    
    for (let i = 0; i < vidas; i++) {
        const icone = document.createElement('i');
        icone.className = 'fas fa-heart';
        vidasContainer.appendChild(icone);
    }
}

function atualizarSequencia() {
    document.getElementById('sequencia_atual').textContent = sequenciaAtual;
    document.getElementById('melhor_sequencia').textContent = melhorSequencia;
    
    if (sequenciaAtual > melhorSequencia) {
        melhorSequencia = sequenciaAtual;
        localStorage.setItem('melhorSequencia', melhorSequencia);
    }
}

function mostrarDica() {
    const dicasContainer = document.getElementById('dicas');
    const dica = document.createElement('div');
    dica.className = 'hint-item';
    
    if (dicasMostradas === 0) {
        dica.textContent = `Número: ${jogadorAtual.numero_camisola}`;
    } else if (dicasMostradas === 1) {
        dica.textContent = `Status: ${jogadorAtual.aposentado ? 'Aposentado' : 'Ativo'}`;
    } else if (dicasMostradas === 2) {
        dica.textContent = `Nacionalidade: ${jogadorAtual.nacionalidade}`;
    } else if (dicasMostradas === 3) {
        dica.textContent = `Posição: ${jogadorAtual.nome_posicao}`;
    } else if (dicasMostradas === 4) {
        dica.textContent = `Clube: ${jogadorAtual.nome_clube}`;
    }
    
    dicasContainer.appendChild(dica);
    dicasMostradas++;
}

function adivinhar() {
    const resposta = document.getElementById('nome_jogador').value.trim();
    
    if (!resposta) {
        alert("Por favor, digite um nome!");
        return;
    }
    
    if (resposta.toLowerCase() === jogadorAtual.nome_jogador.toLowerCase()) {
        mostrarResultado("Correto!", true);
        sequenciaAtual++;
        
        if (sequenciaAtual > melhorSequencia) {
            melhorSequencia = sequenciaAtual;
            localStorage.setItem('melhorSequencia', melhorSequencia);
        }
    } else {
        vidas--;
        sequenciaAtual = 0;
        atualizarVidas();
        
        if (vidas <= 0) {
            mostrarGameOver();
        } else {
            mostrarDica();
            document.getElementById('nome_jogador').value = '';
            document.getElementById('nome_jogador').focus();
        }
    }
    
    atualizarSequencia();
}

function mostrarResultado(mensagem, acertou = false) {
    const modal = document.getElementById('result-modal');
    const title = document.getElementById('result-title');
    const message = document.getElementById('result-message');
    
    title.textContent = acertou ? "✅ Correto!" : "❌ Errado!";
    title.style.color = acertou ? "#00b894" : "#e74c3c";
    message.textContent = mensagem;
    
    document.getElementById('revealed-image').src = jogadorAtual.imagem_jogador;
    document.getElementById('revealed-name').textContent = jogadorAtual.nome_jogador;
    document.getElementById('revealed-details').innerHTML = `
        Clube: ${jogadorAtual.nome_clube}<br>
        Posição: ${jogadorAtual.nome_posicao}<br>
        Nacionalidade: ${jogadorAtual.nacionalidade}
    `;
    
    modal.classList.add('active');
}

function mostrarGameOver() {
    document.getElementById('final-sequence').textContent = sequenciaAtual;
    document.getElementById('final-best').textContent = melhorSequencia;
    document.getElementById('game-over-modal').classList.add('active');
    desabilitarCampos();
}

function reiniciarJogo() {
    vidas = 6;
    sequenciaAtual = 0;
    jogadoresUsados = [];
    dicasMostradas = 0;
    
    atualizarVidas();
    atualizarSequencia();
    document.getElementById('game-over-modal').classList.remove('active');
    document.getElementById('nome_jogador').disabled = false;
    document.getElementById('nome_jogador').value = '';
    document.querySelector('.guess-btn').disabled = false;
    
    carregarJogador();
}

function nextPlayer() {
    document.getElementById('result-modal').classList.remove('active');
    document.getElementById('nome_jogador').value = '';
    carregarJogador();
}

function carregarTotalJogadores() {
    fetch('carregar_total_jogadores.php')
        .then(response => response.json())
        .then(data => {
            totalJogadores = data.total;
            carregarJogador();
        })
        .catch(error => console.error('Erro ao carregar o total de jogadores:', error));
}