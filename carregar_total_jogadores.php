<?php
require_once 'db_connection.php';

// Conta o número total de jogadores
$result = $conn->query("SELECT COUNT(*) AS total FROM jogador");
$row = $result->fetch_assoc();
echo json_encode(['total' => $row['total']]);

$conn->close();
?>
