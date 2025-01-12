<?php
// Conexão com a base de dados
$conn = new mysqli('localhost', 'root', '', 'pap_futebol');
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adivinhar Jogador</title>
    <link rel="stylesheet" href="css/menu.css"> <!-- CSS do menu -->
    <link rel="stylesheet" href="css/footer.css"> <!-- CSS do footer -->
    <link rel="stylesheet" href="css/adivinharjogador.css"> <!-- CSS específico da página -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
</head>
<body>

<?php include 'menu.php'; ?>

<div class="container">
    <h1>Adivinhar o Jogador</h1>
    
    <div class="jogador-container">
        <div id="jogador" class="jogador-imagem"></div>
        <div id="dicas-container">
            <h3>Dicas</h3>
            <div id="dicas"></div>
        </div>
    </div>

    <input type="text" id="nome_jogador" placeholder="Digite o nome do jogador">
    <button onclick="adivinhar()"><i class="fa fa-search"></i> Adivinhar!</button>
    
    <div id="vidas" class="vidas">
        <img src="imagens/bola.png" alt="Vida" class="vida-icone">
        <img src="imagens/bola.png" alt="Vida" class="vida-icone">
        <img src="imagens/bola.png" alt="Vida" class="vida-icone">
        <img src="imagens/bola.png" alt="Vida" class="vida-icone">
        <img src="imagens/bola.png" alt="Vida" class="vida-icone">
        <img src="imagens/bola.png" alt="Vida" class="vida-icone">
    </div>
    
    <p>Sequência atual: <span id="sequencia_atual">0</span> | Melhor sequência: <span id="melhor_sequencia">0</span></p>
</div>

<?php include 'footer.php'; ?>

<script src="js/adivinharjogador.js"></script>
</body>
</html>
