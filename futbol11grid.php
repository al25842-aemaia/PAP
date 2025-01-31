<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Futbol11 Grid</title>
    <link rel="stylesheet" href="css/futbol11grid.css">
    <script defer src="js/futbol11grid.js"></script>
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="grid-container">
        <div class="header">FUTBOL12 GRID</div>
        <div class="grid">
        <?php
        // Conexão com a base de dados
        $conn = new mysqli('localhost', 'root', '', 'pap_futebol');

        if ($conn->connect_error) {
            die("Erro de conexão: " . $conn->connect_error);
        }

        // Busca 2 clubes aleatórios
        $clubes = $conn->query("SELECT nome_clube, imagem_clube, id_clube FROM clube ORDER BY RAND() LIMIT 2");
        $clubesData = $clubes->fetch_all(MYSQLI_ASSOC);

        if (!$clubesData) {
            die("Erro: Nenhum clube encontrado.");
        }

        $clubIds = array_column($clubesData, 'id_clube');

        // Busca nacionalidades com jogadores nos dois clubes
        $nacionalidades = $conn->query("
            SELECT n.nacionalidade, n.imagem_nacionalidade
            FROM nacionalidade n
            INNER JOIN jogador j1 ON n.id_nacionalidade = j1.id_nacionalidade AND j1.id_clube = {$clubIds[0]}
            INNER JOIN jogador j2 ON n.id_nacionalidade = j2.id_nacionalidade AND j2.id_clube = {$clubIds[1]}
            GROUP BY n.nacionalidade, n.imagem_nacionalidade
        ");
        $nacionalidadeData = $nacionalidades->fetch_all(MYSQLI_ASSOC);

        if (count($nacionalidadeData) < 3) {
            $conn->close();
            die('Não há nacionalidades suficientes para realizar o jogo.');
        }

        // Embaralha as nacionalidades e seleciona 3
        shuffle($nacionalidadeData);
        $nacionalidadeData = array_slice($nacionalidadeData, 0, 3);

        // Busca jogadores para cada clube e nacionalidade
        $jogadores = $conn->query("
            SELECT 
                jogador.nome_jogador, 
                jogador.imagem_jogador, 
                clube.nome_clube, 
                nacionalidade.nacionalidade 
            FROM jogador
            INNER JOIN clube ON jogador.id_clube = clube.id_clube
            INNER JOIN nacionalidade ON jogador.id_nacionalidade = nacionalidade.id_nacionalidade
            WHERE jogador.id_clube IN ({$clubIds[0]}, {$clubIds[1]})
        ");

        $jogadoresData = [];
        while ($row = $jogadores->fetch_assoc()) {
            $jogadoresData[$row['nome_clube']][$row['nacionalidade']][] = [
                'nome' => $row['nome_jogador'],
                'imagem' => !empty($row['imagem_jogador']) ? base64_encode(file_get_contents($row['imagem_jogador'])) : null
            ];
        }

        // Geração do cabeçalho com bandeiras das nacionalidades
        echo '<div class="row">';
        echo '<div class="cell empty"></div>'; // Célula vazia no canto superior esquerdo
        foreach ($nacionalidadeData as $nacionalidade) {
            echo '<div class="cell flag"><img src="data:image/png;base64,' . base64_encode(file_get_contents($nacionalidade['imagem_nacionalidade'])) . '" alt="' . $nacionalidade['nacionalidade'] . '" style="width: 50px; height: auto;"></div>';
        }
        echo '</div>';

        // Geração das linhas dos clubes
        foreach ($clubesData as $clube) {
            echo '<div class="row">';
            echo '<div class="cell club"><img src="data:image/png;base64,' . base64_encode(file_get_contents($clube['imagem_clube'])) . '" alt="' . $clube['nome_clube'] . '" style="width: 50px; height: auto;"></div>';
            
            foreach ($nacionalidadeData as $nacionalidade) {
                $jogadores = $jogadoresData[$clube['nome_clube']][$nacionalidade['nacionalidade']] ?? [];
                $jogadoresInfo = array_map(function($jogador) {
                    return $jogador['nome'] . "|" . $jogador['imagem'];
                }, $jogadores);
                echo '<div class="cell guess" data-clube="' . $clube['nome_clube'] . '" data-nacionalidade="' . $nacionalidade['nacionalidade'] . '" data-jogadores="' . implode(',', $jogadoresInfo) . '"></div>';
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
        // JavaScript para validar jogadores e exibir as imagens
        document.getElementById('guess-btn').addEventListener('click', () => {
            const playerName = document.getElementById('player-input').value.trim().toLowerCase();
            const cells = document.querySelectorAll('.cell.guess');

            cells.forEach(cell => {
                const jogadores = cell.getAttribute('data-jogadores').split(',');
                jogadores.forEach(jogador => {
                    const [nome, imagem] = jogador.split("|");
                    if (nome.toLowerCase() === playerName && imagem) {
                        cell.innerHTML = `<img src="data:image/png;base64,${imagem}" alt="${nome}" style="width: 50px; height: auto;">`;
                        cell.classList.add('correct');
                    }
                });
            });

            document.getElementById('player-input').value = ''; // Limpa o campo
        });
    </script>
</body>
</html>
