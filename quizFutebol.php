<?php
require_once 'db_connection.php';

$sql = "SELECT j.id_jogador, j.nome_jogador, n.nacionalidade 
        FROM jogador j 
        JOIN nacionalidade n ON j.id_nacionalidade = n.id_nacionalidade 
        ORDER BY RAND() LIMIT 10";

$result = $conn->query($sql);
$perguntas = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id_jogador = $row['id_jogador'];
        $nome_jogador = $row['nome_jogador'];
        $resposta_correta = $row['nacionalidade'];

        // Buscar três outras nacionalidades diferentes
        $sql_opcoes = "SELECT DISTINCT nacionalidade FROM nacionalidade 
                       WHERE nacionalidade != '$resposta_correta' 
                       ORDER BY RAND() LIMIT 3";

        $res_opcoes = $conn->query($sql_opcoes);
        $opcoes = [];

        while ($row_op = $res_opcoes->fetch_assoc()) {
            $opcoes[] = $row_op['nacionalidade'];
        }

        // Garantir que a resposta correta está na lista
        $opcoes[] = $resposta_correta;
        shuffle($opcoes);

        $perguntas[] = [
            "pergunta" => "Qual é a nacionalidade do jogador $nome_jogador?",
            "opcoes" => $opcoes,
            "resposta" => $resposta_correta
        ];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz de Futebol</title>
    <link rel="stylesheet" href="css/quizFutebol.css">
    <link rel="stylesheet" href="css/menu.css">
</head>
<body>
<?php include 'menu.php';?>
    <main>
    <div class="container">
        <h1>Quiz de Nacionalidades</h1>
        <div id="quiz">
            <?php foreach ($perguntas as $index => $pergunta): ?>
                <div class="pergunta" data-resposta="<?= $pergunta['resposta']; ?>">
                    <h3><?= $pergunta['pergunta']; ?></h3>
                    <?php foreach ($pergunta['opcoes'] as $opcao): ?>
                        <button class="opcao"><?= $opcao; ?></button>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <button id="verificar">Verificar Respostas</button>
        <p id="resultado"></p>
        <button id="jogar-novamente" style="display:none;">Jogar Novamente</button>
    </div>
    <script src="js/quizFutebol.js"></script>
    </main>
</body>
</html>
