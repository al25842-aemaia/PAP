<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Potência 12 Gênio - Adivinhar Jogador</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/adivinharjogador.css">
</head>
<body>
    <?php include 'menu.php'; ?>

    <section class="guess-hero">
        <div class="hero-content">
            <h1>POTÊNCIA 12 GÊNIO</h1>
            <p>Desafie seu conhecimento futebolístico</p>
        </div>
    </section>

    <main class="game-container">
        <div class="game-stats">
            <div class="stat-box">
                <span class="stat-label">Sequência Atual</span>
                <span class="stat-value" id="sequencia_atual">0</span>
            </div>
            <div class="stat-box highlight">
                <span class="stat-label">Melhor Sequência</span>
                <span class="stat-value" id="melhor_sequencia">0</span>
            </div>
            <div class="stat-box lives-box">
                <span class="stat-label">Tentativas</span>
                <div class="lives" id="vidas">
                    <i class="fas fa-heart"></i>
                    <i class="fas fa-heart"></i>
                    <i class="fas fa-heart"></i>
                    <i class="fas fa-heart"></i>
                    <i class="fas fa-heart"></i>
                    <i class="fas fa-heart"></i>
                </div>
            </div>
        </div>

        <div class="game-content">
            <div class="player-card">
                <div class="player-image" id="jogador">
                    <div class="silhouette-overlay"></div>
                    <div class="reveal-progress"></div>
                </div>
            </div>
            
            <div class="hints-card">
                <h3><i class="fas fa-lightbulb"></i> DICAS</h3>
                <div class="hints-list" id="dicas"></div>
            </div>
        </div>

        <div class="guess-form">
            <input type="text" id="nome_jogador" placeholder="Digite o nome do jogador..." autocomplete="off">
            <button class="guess-btn" onclick="adivinhar()">
                <i class="fas fa-search"></i> ADIVINHAR
            </button>
        </div>

        <div class="result-modal" id="result-modal">
            <div class="modal-content">
                <h3 id="result-title"></h3>
                <p id="result-message"></p>
                <div class="player-revealed">
                    <img id="revealed-image" src="" alt="Jogador Revelado">
                    <div class="player-info">
                        <h4 id="revealed-name"></h4>
                        <p id="revealed-details"></p>
                    </div>
                </div>
                <button class="next-btn" onclick="nextPlayer()">
                    <i class="fas fa-forward"></i> PRÓXIMO JOGADOR
                </button>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script src="js/adivinharjogador.js"></script>
</body>
</html>