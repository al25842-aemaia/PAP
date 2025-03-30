<?php
require_once 'db_connection.php';

// Busca 8 jogadores aleatórios com suas nacionalidades
$sql = "SELECT j.id_jogador, j.nome_jogador, n.nacionalidade 
        FROM jogador j 
        JOIN nacionalidade n ON j.id_nacionalidade = n.id_nacionalidade 
        ORDER BY RAND() LIMIT 8";

$result = $conn->query($sql);
$perguntas = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id_jogador = $row['id_jogador'];
        $nome_jogador = $row['nome_jogador'];
        $resposta_correta = $row['nacionalidade'];

        // Buscar 3 nacionalidades diferentes como opções erradas
        $sql_opcoes = "SELECT nacionalidade FROM nacionalidade 
                       WHERE nacionalidade != '$resposta_correta' 
                       ORDER BY RAND() LIMIT 3";

        $res_opcoes = $conn->query($sql_opcoes);
        $opcoes = [];

        while ($row_op = $res_opcoes->fetch_assoc()) {
            $opcoes[] = $row_op['nacionalidade'];
        }

        // Adiciona a resposta correta e embaralha
        $opcoes[] = $resposta_correta;
        shuffle($opcoes);

        $perguntas[] = [
            "jogador" => $nome_jogador,
            "opcoes" => $opcoes,
            "resposta" => $resposta_correta
        ];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz de Nacionalidades de Futebol</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/quizFutebol.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    
    <div class="quiz-app">
        <header class="quiz-header">
            <div class="logo">⚽</div>
            <h1>Quiz de Nacionalidades</h1>
            <p class="subtitle">Teste seu conhecimento sobre jogadores de futebol</p>
        </header>

        <main class="quiz-content">
            <?php foreach ($perguntas as $index => $pergunta): ?>
            <div class="question-card <?= $index === 0 ? 'active' : '' ?>">
                <div class="question-header">
                    <span class="question-number"><?= $index + 1 ?>/8</span>
                    <h2 class="question-text">Qual é a nacionalidade do jogador <?= $pergunta['jogador'] ?>?</h2>
                </div>
                <div class="options-grid">
                    <?php foreach ($pergunta['opcoes'] as $opcao): ?>
                    <button class="option-btn" 
                            data-correct="<?= $opcao === $pergunta['resposta'] ? 'true' : 'false' ?>">
                        <?= $opcao ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>

            <div class="quiz-controls">
                <button class="nav-btn prev-btn" disabled>Anterior</button>
                <span class="progress-text">1 de 8</span>
                <button class="nav-btn next-btn">Próxima</button>
                <button class="submit-btn">Verificar Respostas</button>
            </div>

            <div class="result-container hidden">
                <h3 class="result-title">Resultado Final</h3>
                <p class="result-score">Você acertou <span id="score">0</span> de 8 perguntas</p>
                <button class="restart-btn">
                    <span class="restart-icon">🔄</span> Jogar Novamente
                </button>
            </div>
        </main>

        <footer class="quiz-footer">
            <p>© <?= date('Y') ?> Quiz de Futebol | Perguntas geradas aleatoriamente</p>
        </footer>
    </div>

    <script src="js/quizFutebol.js"></script>
    <?php include 'footer.php'; ?>
</body>
</html>