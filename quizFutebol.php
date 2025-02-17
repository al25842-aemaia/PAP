<?php
require_once 'db_connection.php';
session_start();

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// Buscar perguntas variadas
$perguntas = [
    "Qual jogador do clube %s joga como %s?",
    "Qual é o clube do jogador %s?",
    "Qual é a nacionalidade do jogador %s?"
];

$query = "
    SELECT j.nome_jogador, c.nome_clube, p.nome_posicao, n.nacionalidade
    FROM jogador j
    JOIN clube c ON j.id_clube = c.id_clube
    JOIN nacionalidade n ON j.id_nacionalidade = n.id_nacionalidade
    JOIN jogador_posicoes jp ON j.id_jogador = jp.id_jogador
    JOIN posicoes p ON jp.id_posicao = p.id_posicao
    ORDER BY RAND() LIMIT 10
";

$stmt = $pdo->query($query);
$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

$quiz = [];

foreach ($dados as $dado) {
    $tipoPergunta = rand(0, 2);
    if ($tipoPergunta == 0) {
        $pergunta = sprintf($perguntas[0], $dado['nome_clube'], $dado['nome_posicao']);
        $respostaCorreta = $dado['nome_jogador'];
    } elseif ($tipoPergunta == 1) {
        $pergunta = sprintf($perguntas[1], $dado['nome_jogador']);
        $respostaCorreta = $dado['nome_clube'];
    } else {
        $pergunta = sprintf($perguntas[2], $dado['nome_jogador']);
        $respostaCorreta = $dado['nacionalidade'];
    }

    $opcoes = [$respostaCorreta];

    while (count($opcoes) < 4) {
        $randIndex = array_rand($dados);
        $opcaoErrada = $tipoPergunta == 1 ? $dados[$randIndex]['nome_clube'] : ($tipoPergunta == 2 ? $dados[$randIndex]['nacionalidade'] : $dados[$randIndex]['nome_jogador']);
        if (!in_array($opcaoErrada, $opcoes)) {
            $opcoes[] = $opcaoErrada;
        }
    }

    shuffle($opcoes);
    $quiz[] = [
        "pergunta" => $pergunta,
        "opcoes" => $opcoes,
        "correta" => $respostaCorreta
    ];
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz de Futebol</title>
    <link rel="stylesheet" href="css/quizFutebol.css">
</head>
<body>
    <div class="container">
        <h1>Quiz de Futebol</h1>
        <div id="quiz-container">
            <?php foreach ($quiz as $index => $q): ?>
                <div class="pergunta" data-resposta="<?= $q['correta'] ?>">
                    <p><?= $q['pergunta'] ?></p>
                    <?php foreach ($q['opcoes'] as $opcao): ?>
                        <button class="opcao" onclick="selecionarResposta(this, <?= $index ?>)"><?= $opcao ?></button>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
            <button id="verificar" onclick="verificarRespostas()">Verificar Respostas</button>
            <button id="jogar-novamente" onclick="location.reload()" style="display: none;">Jogar de Novo</button>
        </div>
        <div id="resultado"></div>
    </div>
    <script src="js/quizFutebol.js"></script>
</body>
</html>
