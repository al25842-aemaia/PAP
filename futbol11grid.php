<?php session_start(); ?>
<?php require_once 'db_connection.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Futbol11 Grid</title>
    <link rel="stylesheet" href="css/futbol11grid.css">
    <link rel="stylesheet" href="css/menu.css">
    <script defer src="js/futbol11grid.js"></script>
</head>
<body>
    <?php require_once 'db_connection.php'; ?>
    <?php include 'menu.php';?>
    <div class="grid-container">
        <div class="header">FUTBOL11 GRID</div>
        <div class="grid">
        <?php
        // Conexão com a base de dados
        $conn = new mysqli('localhost', 'root', '', 'pap_futebol');

        if ($conn->connect_error) {
            die("Erro de conexão: " . $conn->connect_error);
        }

// Busca 3 nacionalidades aleatórias
$nacionalidades = $conn->query("SELECT nacionalidade, imagem_nacionalidade FROM nacionalidade ORDER BY RAND() LIMIT 3");
$nacionalidadeData = $nacionalidades->fetch_all(MYSQLI_ASSOC);

// Busca 3 clubes aleatórios
$clubes = $conn->query("SELECT nome_clube, imagem_clube FROM clube ORDER BY RAND() LIMIT 3");
$clubesData = $clubes->fetch_all(MYSQLI_ASSOC);

// Geração do cabeçalho das bandeiras (Nacionalidades)
echo '<div class="row">';
echo '<div class="cell empty"></div>'; // Célula vazia no canto superior esquerdo
foreach ($nacionalidadeData as $nacionalidade) {
    echo '<div class="cell flag"><img src="' . $nacionalidade['imagem_nacionalidade'] . '" alt="' . $nacionalidade['nacionalidade'] . '" style="width: 50px; height: auto;"></div>';
}
echo '</div>';

// Geração das linhas de clubes (Clubes e suas nacionalidades)
foreach ($clubesData as $clube) {
    echo '<div class="row">';
    echo '<div class="cell club"><img src="' . $clube['imagem_clube'] . '" alt="' . $clube['nome_clube'] . '" style="width: 50px; height: auto;"></div>';
    foreach ($nacionalidadeData as $nacionalidade) {
        // Aqui associamos o clube com a nacionalidade nas células
        echo '<div class="cell guess" data-clube="' . $clube['nome_clube'] . '" data-nacionalidade="' . $nacionalidade['nacionalidade'] . '"></div>';
    }
    echo '</div>';
}

        $conn->close();
        ?>
        </div>

        <div class="input-area">
            <input type="text" id="player-input" placeholder="Digite o nome do jogador">
            <button id="guess-btn">Verificar</button>
        </div>
    </div>

    <script>
        document.getElementById('guess-btn').addEventListener('click', () => {
            const playerName = document.getElementById('player-input').value.trim().toLowerCase();
            const cells = document.querySelectorAll('.cell.guess');

            cells.forEach(cell => {
                const jogadores = cell.getAttribute('data-jogadores') ? cell.getAttribute('data-jogadores').split(',') : [];

                jogadores.forEach(jogador => {
                    const [nome, imagem] = jogador.split("|");
                    if (nome.toLowerCase() === playerName && imagem) {
                        cell.innerHTML = `<img src="data:image/webp;base64,${imagem}" alt="${nome}" style="width: 50px; height: auto;">`;
                        cell.classList.add('correct');
                    }
                });
            });

            document.getElementById('player-input').value = '';
        });
    </script>
</body>
</html>