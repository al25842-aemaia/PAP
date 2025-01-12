<?php
// Conectar ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pap_futebol";

// Criação da conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificação de erro de conexão
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Pegar a posição
$position = isset($_GET['position']) ? $_GET['position'] : '';

// Buscar jogadores disponíveis para a posição
$sql = "SELECT j.id_jogador, j.nome_jogador, j.imagem_jogador, c.nome_clube 
        FROM jogador j
        JOIN clube c ON j.id_clube = c.id_clube
        WHERE j.id_jogador NOT IN (SELECT id_jogador FROM jogador_posicoes WHERE id_posicao = (SELECT id_posicao FROM posicoes WHERE nome_posicao = '$position'))";
$result = $conn->query($sql);

$players = [];
while ($row = $result->fetch_assoc()) {
    $players[] = $row;
}

echo json_encode($players);

// Fechar a conexão
$conn->close();
?>
