<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minijogos - Futebol12</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/minijogos.css">
</head>
<body>
    <?php include 'menu.php'; ?>

    <section class="games-hero">
        <div class="hero-content">
            <h1>MINIJOGOS DE FUTEBOL</h1>
            <p>Escolha seu desafio e mostre seu conhecimento futebolístico</p>
        </div>
    </section>

    <main class="games-container">
        <div class="games-grid">
            <!-- Minijogo 1 -->
            <div class="game-card" onclick="navigateTo('adivinharjogador.php')">
                <div class="game-image">
                    <img src="imagens/adivinharjogador.jpg" alt="Adivinhar Jogador">
                    <div class="game-overlay"></div>
                </div>
                <div class="game-info">
                    <h3>ADIVINHAR JOGADOR</h3>
                    <p>Adivinhe o jogador com base nas dicas</p>
                    <button class="play-button">
                        <i class="fas fa-play"></i> JOGAR
                    </button>
                </div>
            </div>

            <!-- Minijogo 2 -->
            <div class="game-card" onclick="navigateTo('packs.php')">
                <div class="game-image">
                    <img src="imagens/packs.jpg" alt="Packs de Jogadores">
                    <div class="game-overlay"></div>
                </div>
                <div class="game-info">
                    <h3>ESCOLHA UM PACK</h3>
                    <p>Abra packs e descubra jogadores incríveis</p>
                    <button class="play-button">
                        <i class="fas fa-play"></i> JOGAR
                    </button>
                </div>
            </div>

            <!-- Minijogo 3 -->
            <div class="game-card" onclick="navigateTo('futbol11grid.php')">
                <div class="game-image">
                    <img src="imagens/futbol12grid.png" alt="Futebol 12 Grid">
                    <div class="game-overlay"></div>
                </div>
                <div class="game-info">
                    <h3>FUTBOL12 GRID</h3>
                    <p>Complete o grid com os jogadores corretos</p>
                    <button class="play-button">
                        <i class="fas fa-play"></i> JOGAR
                    </button>
                </div>
            </div>

            <!-- Minijogo 4 -->
            <div class="game-card" onclick="navigateTo('quizFutebol.php')">
                <div class="game-image">
                    <img src="imagens/quizfutebol.jpg" alt="Quiz de Futebol">
                    <div class="game-overlay"></div>
                </div>
                <div class="game-info">
                    <h3>QUIZ FUTEBOLÍSTICO</h3>
                    <p>Teste seu conhecimento sobre futebol</p>
                    <button class="play-button">
                        <i class="fas fa-play"></i> JOGAR
                    </button>
                </div>
            </div>
            <!-- Minijogo 5 -->
            <div class="game-card" onclick="navigateTo('sm.php')">
                <div class="game-image">
                    <img src="imagens/SOCCER MANAGER.jpg" alt="Quiz de Futebol">
                    <div class="game-overlay"></div>
                </div>
                <div class="game-info">
                    <h3>SOCCER MANAGER</h3>
                    <p>Teste seu conhecimento sobre futebol</p>
                    <button class="play-button">
                        <i class="fas fa-play"></i> JOGAR
                    </button>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script>
        function navigateTo(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>