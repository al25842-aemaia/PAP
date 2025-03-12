<?php
include 'db_connection.php';

$ligaEscolhida = $_GET['liga'] ?? '';

// Buscar um jogador aleatório da liga escolhida
$query = "SELECT j.nome_jogador, j.imagem_jogador, j.numero_camisola, 
                 n.nacionalidade, n.imagem_nacionalidade, 
                 c.nome_clube, c.imagem_clube,
                 GROUP_CONCAT(p.nome_posicao SEPARATOR ', ') AS posicoes
          FROM jogador j
          JOIN clube c ON j.id_clube = c.id_clube
          JOIN nacionalidade n ON j.id_nacionalidade = n.id_nacionalidade
          JOIN jogador_posicoes jp ON j.id_jogador = jp.id_jogador
          JOIN posicoes p ON jp.id_posicao = p.id_posicao
          WHERE c.local_clube = ?
          GROUP BY j.id_jogador
          ORDER BY RAND()
          LIMIT 1";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $ligaEscolhida);
$stmt->execute();
$result = $stmt->get_result();
$jogador = $result->fetch_assoc();

if (!$jogador) {
    die("Nenhum jogador encontrado para esta liga: " . htmlspecialchars($ligaEscolhida));
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abrindo Pack</title>
    <link rel="stylesheet" href="css/packs.css">
    <script src="js/packs.js" defer></script>
</head>
<body>
    <h1 id="pack-status">Clique para Abrir o Pack</h1>
    
    <div id="pack-container">
        <img id="pack-image" src="imagens/pack.png" alt="Pack Fechado" onclick="abrirPack()">
    </div>

    <div id="pack-animation" class="hidden">
        <img id="nacionalidade-img" src="<?= htmlspecialchars($jogador['imagem_nacionalidade']) ?>" class="hidden">
        <span id="posicao-text" class
