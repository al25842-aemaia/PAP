<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "pap_futebol");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Buscar clubes
$sql_clubes = "SELECT nome_clube, imagem_clube FROM clube";
$result_clubes = $conn->query($sql_clubes);
$clubes = [];
if ($result_clubes->num_rows > 0) {
    while ($row = $result_clubes->fetch_assoc()) {
        $clubes[] = $row;
    }
}

// Buscar jogadores
$sql_jogadores = "SELECT jogador.nome_jogador, jogador.imagem_jogador, posicoes.nome_posicao, clube.nome_clube 
                  FROM jogador 
                  JOIN jogador_posicoes ON jogador.id_jogador = jogador_posicoes.id_jogador 
                  JOIN posicoes ON jogador_posicoes.id_posicao = posicoes.id_posicao 
                  JOIN clube ON jogador.id_clube = clube.id_clube";
$result_jogadores = $conn->query($sql_jogadores);
$jogadores = [];
if ($result_jogadores->num_rows > 0) {
    while ($row = $result_jogadores->fetch_assoc()) {
        $jogadores[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulador de Futebol</title>
    <link rel="stylesheet" href="css/futebol11clubes.css">
    
</head>
<body>
<?php include 'menu.php'; ?>
    <div id="gameContainer">
        <div id="clubContainer">
            <img id="clubImage" alt="Clube">
            <span id="clubName"></span>
        </div>
        <div id="tacticGrid"></div>
        <div id="controls">
            <!-- Pesquisa de jogadores -->
            <input type="text" id="playerSearch" placeholder="Digite o nome do jogador">
            <div id="positionContainer">
                <!-- Posições do jogador aparecerão aqui -->
            </div>
            <button id="addPlayerButton">Adicionar Jogador</button>
        </div>
    </div>
    <script>
        const clubs = <?php echo json_encode($clubes); ?>;
        const players = <?php echo json_encode($jogadores); ?>;
    </script>
    <?php include 'footer.php'; ?>
    <script src="js/futebol11clubes.js"></script>
</body>
</html>
