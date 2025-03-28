<?php session_start(); ?>
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
    <title>Futebol11 Grid - Desafio de Jogadores</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/futbol11grid.css">
</head>
<body>
    <?php include 'menu.php'; ?>

    <section class="grid-hero">
        <div class="hero-content">
            <h1>FUTEBOL11 GRID</h1>
            <p>Complete o desafio encontrando os jogadores corretos</p>
        </div>
    </section>

    <main class="game-container">
        <div class="game-header">
            <div class="game-instructions">
                <h2>COMO JOGAR</h2>
                <p>Digite o nome de um jogador que atenda aos critérios do clube e nacionalidade selecionados</p>
            </div>
            <div class="game-stats">
                <div class="stat-item">
                    <i class="fas fa-check-circle"></i>
                    <span id="correct-count">0</span> Acertos
                </div>
                <div class="stat-item">
                    <i class="fas fa-clock"></i>
                    <span id="time">01:30</span>
                </div>
            </div>
        </div>

        <div class="grid-wrapper">
            <div class="grid">
                <div class="corner"></div>
                <?php foreach ($nacionalidades as $nac): ?>
                    <div class="nacionalidade">
                        <img src="<?php echo $nac['imagem_nacionalidade']; ?>" alt="<?php echo $nac['nacionalidade']; ?>">
                        <div class="flag-label"><?php echo $nac['nacionalidade']; ?></div>
                    </div>
                <?php endforeach; ?>
                
                <?php foreach ($clubes as $clube): ?>
                    <div class="clube">
                        <img src="<?php echo $clube['imagem_clube']; ?>" alt="<?php echo $clube['nome_clube']; ?>">
                        <div class="club-label"><?php echo $clube['nome_clube']; ?></div>
                    </div>
                    <?php foreach ($nacionalidades as $nac): ?>
                        <div class="cell" data-clube="<?php echo $clube['id_clube']; ?>" data-nacionalidade="<?php echo $nac['id_nacionalidade']; ?>">
                            <div class="cell-content"></div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="game-controls">
            <div class="input-container">
                <input type="text" id="playerInput" placeholder="Digite o nome do jogador...">
                <button class="verify-btn" onclick="checkPlayer()">
                    <i class="fas fa-check"></i> VERIFICAR
                </button>
            </div>
        </div>
    </main>

    <div class="game-footer">
        <div class="about-section">
            <h3><i class="fas fa-futbol"></i> FUTEBOL12</h3>
            <p>O melhor portal sobre futebol que existe. Notícias atualizadas ao minuto, análises detalhadas e estatísticas completas.</p>
        </div>
        <div class="contact-section">
            <h4><i class="fas fa-envelope"></i> CONTATO</h4>
            <p><i class="fas fa-at"></i> at2582@semala.com</p>
            <p><i class="fas fa-at"></i> at25842@semala.com</p>
        </div>
    </div>

    <script>
        let jogadores = <?php echo json_encode($jogadores); ?>;
    </script>
    
    <?php include 'footer.php'; ?>
    <script src="js/futbol11grid.js"></script>
</body>
</html>