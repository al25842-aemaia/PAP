<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abrir Pack</title>
    <link rel="stylesheet" href="css/packs.css">
</head>
<body>
    <?php
    include 'db_connection.php';

    $liga = isset($_GET['liga']) ? $_GET['liga'] : '';

    $sql = "SELECT j.*, c.nome_clube, c.imagem_clube, n.nacionalidade, n.imagem_nacionalidade, p.nome_posicao 
            FROM jogador j
            JOIN clube c ON j.id_clube = c.id_clube
            JOIN nacionalidade n ON j.id_nacionalidade = n.id_nacionalidade
            JOIN jogador_posicoes jp ON j.id_jogador = jp.id_jogador
            JOIN posicoes p ON jp.id_posicao = p.id_posicao
            WHERE c.local_clube = '$liga' 
            ORDER BY RAND() LIMIT 1";

    $result = $conn->query($sql);
    $player = $result->fetch_assoc();

    $conn->close();
    ?>

    <div id="animation-container">
        <p id="pack-status">Pack a abrir...</p>

        <div class="animation-stage hidden" id="stage-1">
            <img src="<?php echo $player['imagem_nacionalidade']; ?>" alt="Nacionalidade">
        </div>

        <div class="animation-stage hidden" id="stage-2">
            <p><?php echo $player['nome_posicao']; ?></p>
        </div>

        <div class="animation-stage hidden" id="stage-3">
            <img src="<?php echo $player['imagem_clube']; ?>" alt="Clube">
        </div>

        <div id="final-card" class="hidden">
            <img src="<?php echo $player['imagem_jogador']; ?>" alt="Jogador">
            <p><strong>Nome:</strong> <?php echo $player['nome_jogador']; ?></p>
            <p><strong>Clube:</strong> <?php echo $player['nome_clube']; ?></p>
            <p><strong>Posição:</strong> <?php echo $player['nome_posicao']; ?></p>
            <p><strong>Número:</strong> <?php echo $player['numero_camisola']; ?></p>
            <p><strong>Nacionalidade:</strong> <?php echo $player['nacionalidade']; ?></p>
        </div>
    </div>

    <script src="js/packs.js"></script>
</body>
</html>