<?php session_start(); ?>
<?php
require_once 'db_connection.php';
session_start();

// Selecionar um clube aleatório da base de dados
$query = "SELECT id_clube, nome_clube, imagem_clube FROM clube ORDER BY RAND() LIMIT 1";
$result = mysqli_query($conn, $query);
$clube = mysqli_fetch_assoc($result);

$clubeId = $clube['id_clube'];
$clubeNome = $clube['nome_clube'];
$clubeImagem = $clube['imagem_clube'];
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Futebol 11 Clubes</title>
    <link rel="stylesheet" href="css/futebol11clubes.css">
</head>
<body>
    <header>
        <h1>Simulador de Equipas</h1>
    </header>
    <main>
        <div id="tactic-selection">
            <label for="tactic">Escolha a Tática:</label>
            <select id="tactic">
                <option value="4-3-3">4-3-3 (Atacante)</option>
                <option value="4-4-2">4-4-2</option>
                <option value="3-5-2">3-5-2</option>
                <option value="5-3-2">5-3-2</option>
                <option value="4-2-3-1">4-2-3-1</option>
            </select>
        </div>

        <div id="field"></div>
        <div id="player-selection">
        <input type="text" id="playerName" placeholder="Digite o nome do jogador">
            <select id="playerPosition">
                <option value="GR">GR</option>
                <option value="DC1">DC1</option>
                <option value="DC2">DC3</option>
                <option value="DC2">DC3</option>
                <option value="DD">DD</option>
                <option value="DE">DE</option>
                <option value="MDC">MDC</option>
                <option value="MC1">MC1</option>
                <option value="MC2">MC2</option>
                <option value="ME">ME</option>
                <option value="MD">MD</option>
                <option value="MCO">MCO</option>
                <option value="EE">EE</option>
                <option value="ED">ED</option>
                <option value="SA">SA</option>
                <option value="PL1">PL1</option>
                <option value="PL2">PL2</option>
            </select>
            <button id="addPlayer">Adicionar Jogador</button>
        <div id="small-square">
            <img src="<?php echo $clubeImagem; ?>" alt="Clube" id="club-image">
            <span id="club-name"><?php echo $clubeNome; ?></span>
        </div>
    </main>
    <script src="js/futebol11clubes.js"></script>
</body>
</html>
