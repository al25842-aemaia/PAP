<?php
// Conexão com a base de dados
$conn = new mysqli('localhost', 'root', '', 'pap_futebol');
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Conta o número total de jogadores
$result = $conn->query("SELECT COUNT(*) AS total FROM jogador");
$row = $result->fetch_assoc();
echo json_encode(['total' => $row['total']]);

$conn->close();
?>
