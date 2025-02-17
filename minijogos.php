<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minijogos Futebol11</title>
    <link rel="stylesheet" href="css/menu.css"> <!-- CSS do menu -->
    <link rel="stylesheet" href="css/footer.css"> <!-- CSS do footer -->
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/minijogos.css"> <!-- CSS específico para os minijogos -->
</head>
<body>
    <?php include 'menu.php'; ?> <!-- Inclusão do menu comum a todas as páginas -->
    
    <h1>Selecione o minijogo que deseja jogar:</h1>
    <div class="game-grid">
        <!-- Exemplo do primeiro minijogo -->
        <div class="game-item" onclick="navigateTo('adivinharjogador.php')">
            <img src="imagens/adivinharjogador.jpg" alt="divinharjogador"> <!-- Imagem do minijogo 1 -->
            <button>Play</button>
        </div>

        <!-- Exemplo do segundo minijogo -->
        <div class="game-item" onclick="navigateTo('futebol11clubes.php')">
            <img src="imagens/futebol11clubes.jpg" alt="futebol11clubes"> <!-- Imagem do minijogo 2 -->
            <button>Play</button>
        </div>

        <!-- Exemplo do terceiro minijogo -->
        <div class="game-item" onclick="navigateTo('futbol11grid.php')">
            <img src="imagens/futbol12grid.png" alt="futebol12grid"> <!-- Imagem do minijogo 3 -->
            <button>Play</button>
        </div>
        <!-- Exemplo do quarto minijogo -->
        <div class="game-item" onclick="navigateTo('quizFutebol.php')">
            <img src="imagens/futbol12grid.png" alt="quiz do futebol"> <!-- Imagem do minijogo 4 -->
            <button>Play</button>
        </div>

        <!-- Continue adicionando mais minijogos seguindo o mesmo formato -->
    </div>

    <?php include 'footer.php'; ?> <!-- Inclusão do footer comum a todas as páginas -->

    <script src="js/minijogos.js"></script> <!-- JavaScript específico para os minijogos -->
</body>
</html>
