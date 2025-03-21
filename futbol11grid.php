<?php
include 'db_connection.php';

// Seleciona 3 clubes aleatórios
$sql = "SELECT id_clube, nome_clube, imagem_clube FROM clube ORDER BY RAND() LIMIT 3";
$result = $conn->query($sql);
$clubes = [];
while ($row = $result->fetch_assoc()) {
    $clubes[] = $row;
}

// Obtém os IDs dos clubes selecionados
$clubesIds = array_column($clubes, 'id_clube');
$clubesIdsStr = implode(',', $clubesIds);

// Seleciona 3 nacionalidades que tenham jogadores nesses clubes
$sql = "SELECT DISTINCT n.id_nacionalidade, n.nacionalidade, n.imagem_nacionalidade 
        FROM jogador j
        JOIN nacionalidade n ON j.id_nacionalidade = n.id_nacionalidade
        WHERE j.id_clube IN ($clubesIdsStr)
        GROUP BY n.id_nacionalidade
        HAVING COUNT(DISTINCT j.id_clube) = 3
        ORDER BY RAND() LIMIT 3";

$result = $conn->query($sql);
$nacionalidades = [];
while ($row = $result->fetch_assoc()) {
    $nacionalidades[] = $row;
}

// Carrega todos os jogadores desses clubes e nacionalidades
$sql = "SELECT j.id_jogador, j.nome_jogador, j.imagem_jogador, j.id_clube, j.id_nacionalidade 
        FROM jogador j
        WHERE j.id_clube IN ($clubesIdsStr)";
$result = $conn->query($sql);
$jogadores = [];
while ($row = $result->fetch_assoc()) {
    $jogadores[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Futebol11 Grid</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/futbol11grid.css">
    <link rel="stylesheet" href="css/menu.css"> <!-- CSS do menu -->
    <link rel="stylesheet" href="css/footer.css"> <!-- CSS do footer -->
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="grid-container">
        <div class="grid-header">
            <h1>FUTEBOL11 GRID</h1>
        </div>
        <div class="grid">
            <div class="corner"></div>
            <?php foreach ($nacionalidades as $nac): ?>
                <div class="nacionalidade">
                    <img src="imagens_nacionalidade/<?php echo $nac['imagem_nacionalidade']; ?>" alt="<?php echo $nac['nacionalidade']; ?>">
                </div>
            <?php endforeach; ?>
            
            <?php foreach ($clubes as $clube): ?>
                <div class="clube">
                    <img src="imagens_clube/<?php echo $clube['imagem_clube']; ?>" alt="<?php echo $clube['nome_clube']; ?>">
                </div>
                <?php foreach ($nacionalidades as $nac): ?>
                    <div class="cell" data-clube="<?php echo $clube['id_clube']; ?>" data-nacionalidade="<?php echo $nac['id_nacionalidade']; ?>"></div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
        <input type="text" id="playerInput" placeholder="Digite o nome do jogador">
        <button onclick="checkPlayer()">Verificar</button>
    </div>

    <script>
        let jogadores = <?php echo json_encode($jogadores); ?>;
    </script>
    
    <?php include 'footer.php'; ?>
    <script src="js/futbol11grid.js"></script>
</body>
</html>
