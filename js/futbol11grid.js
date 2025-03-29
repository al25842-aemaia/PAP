document.addEventListener('DOMContentLoaded', function() {
    // Debug inicial
    console.log('Script carregado - Dados disponíveis:', {
        jogadores: window.jogadores,
        clubes: window.clubes,
        nacionalidades: window.nacionalidades
    });

    // Elementos do DOM
    const playerInput = document.getElementById('playerInput');
    const verifyBtn = document.getElementById('verifyBtn');
    const correctCountEl = document.getElementById('correct-count');
    const timeEl = document.getElementById('time');
    const winModal = document.getElementById('win-modal');
    const finalTimeEl = document.getElementById('final-time');
    
    // Variáveis do jogo
    let correctCount = 0;
    let timer = 300;
    let timerInterval;
    let gameActive = true;
    let jogadoresUtilizados = [];
    
    // Função para mostrar mensagens de debug
    function debugLog(message) {
        console.log('[DEBUG]', message);
    }

    // Iniciar o jogo
    function initGame() {
        debugLog('Iniciando novo jogo');
        correctCount = 0;
        timer = 300;
        gameActive = true;
        jogadoresUtilizados = [];
        updateCorrectCount();
        startTimer();
        clearGrid();
        playerInput.disabled = false;
        verifyBtn.disabled = false;
        playerInput.value = '';
        playerInput.focus();
    }
    
    // Limpar o grid
    function clearGrid() {
        debugLog('Limpando grid');
        document.querySelectorAll('.cell').forEach(cell => {
            cell.classList.remove('correct', 'incorrect');
            const content = cell.querySelector('.cell-content');
            if (content) content.innerHTML = '';
        });
    }
    
    // Atualizar contador
    function updateCorrectCount() {
        correctCountEl.textContent = `${correctCount}/9`;
        debugLog(`Acertos atualizados: ${correctCount}/9`);
    }
    
    // Timer
    function startTimer() {
        clearInterval(timerInterval);
        updateTimerDisplay();
        timerInterval = setInterval(() => {
            timer--;
            updateTimerDisplay();
            
            if (timer <= 0) {
                endGame(false);
            }
        }, 1000);
        debugLog('Timer iniciado');
    }
    
    function updateTimerDisplay() {
        const minutes = Math.floor(timer / 60);
        const seconds = timer % 60;
        timeEl.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }
    
    // Verificação do jogador (FUNÇÃO PRINCIPAL CORRIGIDA)
    function checkPlayer() {
        if (!gameActive) {
            debugLog('Jogo não está ativo');
            return;
        }
        
        const nome = playerInput.value.trim();
        debugLog(`Verificando jogador: "${nome}"`);
        
        if (!nome) {
            showFeedback('Por favor, digite um nome', 'error');
            playerInput.focus();
            return;
        }

        // Busca com correspondência parcial (case insensitive)
        const jogadorEncontrado = window.jogadores.find(j => {
            const nomeJogador = j.nome_jogador.toLowerCase();
            return nomeJogador.includes(nome.toLowerCase()) && 
                   !jogadoresUtilizados.includes(j.id_jogador);
        });

        if (!jogadorEncontrado) {
            debugLog('Jogador não encontrado ou já utilizado');
            showFeedback('Jogador não encontrado!', 'error');
            playerInput.focus();
            return;
        }

        debugLog('Jogador encontrado:', jogadorEncontrado);

        // Encontrar célula correta
        const celula = document.querySelector(`.cell[data-clube="${jogadorEncontrado.id_clube}"][data-nacionalidade="${jogadorEncontrado.id_nacionalidade}"]`);
        
        if (!celula) {
            debugLog('Célula não encontrada para este jogador');
            showFeedback('Combinação inválida!', 'error');
            return;
        }

        // Verificar se célula já está preenchida
        if (celula.querySelector('.cell-content img')) {
            debugLog('Célula já preenchida');
            showFeedback('Célula já preenchida!', 'error');
            return;
        }

        // PREENCHER CÉLULA (PARTE CRÍTICA)
        const cellContent = celula.querySelector('.cell-content');
        cellContent.innerHTML = `
            <img src="${jogadorEncontrado.imagem_jogador}" alt="${jogadorEncontrado.nome_jogador}" class="player-image">
            <div class="player-name">${jogadorEncontrado.nome_jogador}</div>
        `;
        
        celula.classList.add('correct');
        jogadoresUtilizados.push(jogadorEncontrado.id_jogador);
        correctCount++;
        
        updateCorrectCount();
        showFeedback('Resposta correta!', 'success');
        playerInput.value = '';
        playerInput.focus();

        // Verificar vitória
        if (correctCount === 9) {
            endGame(true);
        }
    }
    
    // Feedback visual
    function showFeedback(message, type) {
        debugLog(`Feedback: ${type} - ${message}`);
        const feedback = document.createElement('div');
        feedback.className = `feedback ${type}`;
        feedback.textContent = message;
        
        // Remove feedbacks anteriores
        document.querySelectorAll('.feedback').forEach(el => el.remove());
        
        document.body.appendChild(feedback);
        setTimeout(() => feedback.remove(), 3000);
    }
    
    // Finalizar jogo
    function endGame(win) {
        debugLog(`Jogo finalizado: ${win ? 'VITÓRIA' : 'DERROTA'}`);
        gameActive = false;
        clearInterval(timerInterval);
        
        if (win) {
            finalTimeEl.textContent = formatTime(300 - timer);
            winModal.classList.add('active');
        } else {
            showFeedback('Tempo esgotado!', 'error');
        }
    }
    
    // Reiniciar
    function restartGame() {
        debugLog('Reiniciando jogo');
        winModal.classList.remove('active');
        initGame();
    }
    
    // Formatar tempo
    function formatTime(seconds) {
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }
    
    // Event listeners (CORRIGIDO)
    verifyBtn.addEventListener('click', function(e) {
        e.preventDefault();
        checkPlayer();
    });
    
    playerInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            checkPlayer();
        }
    });
    
    // Iniciar
    initGame();
});