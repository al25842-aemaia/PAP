<?php session_start(); ?>
<?php require_once 'db_connection.php'; ?>
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
        <div class="header">FUTBOL11 GRID</div>
        <div class="grid">
        <?php
        // Conexão com a base de dados
        $conn = new mysqli('localhost', 'root', '', 'pap_futebol');

        if ($conn->connect_error) {
            die("Erro de conexão: " . $conn->connect_error);
        }

        // Busca todos os clubes
        $clubes = $conn->query("SELECT id_clube, nome_clube, imagem_clube FROM clube");
        $clubesData = $clubes->fetch_all(MYSQLI_ASSOC);

        // Busca todas as nacionalidades com jogadores em pelo menos 2 clubes diferentes
        $nacionalidades = $conn->query("SELECT n.id_nacionalidade, n.nacionalidade, n.imagem_nacionalidade FROM nacionalidade n INNER JOIN jogador j ON n.id_nacionalidade = j.id_nacionalidade GROUP BY n.id_nacionalidade, n.nacionalidade, n.imagem_nacionalidade HAVING COUNT(DISTINCT j.id_clube) >= 2");
        $nacionalidadeData = $nacionalidades->fetch_all(MYSQLI_ASSOC);

        shuffle($nacionalidadeData);
        $nacionalidadeData = array_slice($nacionalidadeData, 0, 3);

        // Cabeçalho com bandeiras das nacionalidades
        echo '<div class="row">';
        echo '<div class="cell empty"></div>';
        foreach ($nacionalidadeData as $nacionalidade) {
            echo '<div class="cell flag">';
            echo '<img src="imagens_nacionalidade/' . $nacionalidade['imagem_nacionalidade'] . '" alt="' . $nacionalidade['nacionalidade'] . '" style="width: 50px; height: auto;">';
            echo '</div>';
        }
        echo '</div>';

        // Geração das linhas dos clubes
        foreach ($clubesData as $clube) {
            echo '<div class="row">';
            echo '<div class="cell club">';
            echo '<img src="imagens_clube/' . $clube['imagem_clube'] . '" alt="' . $clube['nome_clube'] . '" style="width: 50px; height: auto;">';
            echo '</div>';

            foreach ($nacionalidadeData as $nacionalidade) {
                $jogadores = $conn->query("SELECT nome_jogador, imagem_jogador FROM jogador WHERE id_clube = {$clube['id_clube']} AND id_nacionalidade = {$nacionalidade['id_nacionalidade']}");
                $jogadoresData = [];

                while ($row = $jogadores->fetch_assoc()) {
                    $caminhoImagem = "imagens_jogador/" . $row['imagem_jogador'];
                    if (file_exists($caminhoImagem) && is_file($caminhoImagem)) {
                        $imagemBase64 = base64_encode(file_get_contents($caminhoImagem));
                        $jogadoresData[] = $row['nome_jogador'] . "|" . $imagemBase64;
                    }
                }

                echo '<div class="cell guess" data-clube="' . $clube['nome_clube'] . '" data-nacionalidade="' . $nacionalidade['nacionalidade'] . '" data-jogadores="' . implode(',', $jogadoresData) . '"></div>';
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
                const jogadores = cell.getAttribute('data-jogadores').split(',');
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