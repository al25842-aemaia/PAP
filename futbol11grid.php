<?php session_start(); ?>
<?php
include 'db_connection.php';

// Função para encontrar nacionalidades comuns a todos os clubes selecionados
function encontrarNacionalidadesComuns($conn, $clubesIds) {
    $clubesIdsStr = implode(',', $clubesIds);
    
    // Encontra nacionalidades que existem em TODOS os clubes selecionados
    $sql = "SELECT n.id_nacionalidade, n.nacionalidade, n.imagem_nacionalidade
            FROM nacionalidade n
            WHERE NOT EXISTS (
                SELECT c.id_clube 
                FROM clube c 
                WHERE c.id_clube IN ($clubesIdsStr)
                AND NOT EXISTS (
                    SELECT 1 
                    FROM jogador j 
                    WHERE j.id_clube = c.id_clube 
                    AND j.id_nacionalidade = n.id_nacionalidade
                )
            )
            ORDER BY RAND() LIMIT 3";
    
    $result = $conn->query($sql);
    $nacionalidades = [];
    while ($row = $result->fetch_assoc()) {
        $nacionalidades[] = $row;
    }
    
    return $nacionalidades;
}

// 1. Seleciona 3 clubes aleatórios que têm jogadores
$sql = "SELECT c.id_clube, c.nome_clube, c.imagem_clube 
        FROM clube c
        WHERE EXISTS (SELECT 1 FROM jogador j WHERE j.id_clube = c.id_clube)
        ORDER BY RAND() LIMIT 3";
$result = $conn->query($sql);
$clubes = [];
while ($row = $result->fetch_assoc()) {
    $clubes[] = $row;
}

// 2. Encontra 3 nacionalidades que tenham jogadores em TODOS os 3 clubes selecionados
$clubesIds = array_column($clubes, 'id_clube');
$nacionalidades = encontrarNacionalidadesComuns($conn, $clubesIds);

// Se não encontrou 3 nacionalidades, tenta novamente com outros clubes
$tentativas = 0;
while (count($nacionalidades) < 3 && $tentativas < 10) {
    $result = $conn->query($sql);
    $clubes = [];
    while ($row = $result->fetch_assoc()) {
        $clubes[] = $row;
    }
    $clubesIds = array_column($clubes, 'id_clube');
    $nacionalidades = encontrarNacionalidadesComuns($conn, $clubesIds);
    $tentativas++;
}

// 3. Carrega todos os jogadores para essas combinações
$nacionalidadesIds = array_column($nacionalidades, 'id_nacionalidade');
$nacionalidadesIdsStr = implode(',', $nacionalidadesIds);
$clubesIdsStr = implode(',', $clubesIds);

$sql = "SELECT j.id_jogador, j.nome_jogador, j.imagem_jogador, j.id_clube, j.id_nacionalidade
        FROM jogador j
        WHERE j.id_clube IN ($clubesIdsStr) AND j.id_nacionalidade IN ($nacionalidadesIdsStr)";
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
                <p>Digite o nome de um jogador que corresponda ao clube e nacionalidade</p>
            </div>
            <div class="game-stats">
                <div class="stat-item">
                    <i class="fas fa-check-circle"></i>
                    <span id="correct-count">0</span>/9
                </div>
                <div class="stat-item">
                    <i class="fas fa-clock"></i>
                    <span id="time">05:00</span>
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
                <input type="text" id="playerInput" placeholder="Digite o nome do jogador..." autocomplete="off">
                <button class="verify-btn" id="verifyBtn">
                    <i class="fas fa-check"></i> VERIFICAR
                </button>
            </div>
        </div>

        <div class="game-modal" id="win-modal">
            <div class="modal-content">
                <h3><i class="fas fa-trophy"></i> GANHASTE!</h3>
                <p>Completaste o grid em <span id="final-time">00:00</span>!</p>
                <button class="restart-btn" onclick="restartGame()">
                    <i class="fas fa-redo"></i> RECOMEÇAR
                </button>
            </div>
        </div>
    </main>

    <script>
    // Garanta que os dados estão sendo passados corretamente
    window.jogadores = <?php echo json_encode($jogadores); ?>;
    window.clubes = <?php echo json_encode($clubes); ?>;
    window.nacionalidades = <?php echo json_encode($nacionalidades); ?>;
    
    // Debug: Verifique no console se os dados estão carregando
    console.log('Jogadores:', window.jogadores);
    console.log('Clubes:', window.clubes);
    console.log('Nacionalidades:', window.nacionalidades);
</script>
    
    <?php include 'footer.php'; ?>
    <script src="js/futbol11grid.js"></script>
</body>
</html>