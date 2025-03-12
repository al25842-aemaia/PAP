<?php
session_start();
require 'db_connection.php';

if (!isset($_GET['liga_id'])) {
    echo json_encode(["error" => "Liga não especificada."]);
    exit;
}

$liga_id = intval($_GET['liga_id']);

$sql = "SELECT j.*, n.nacionalidade, p.nome_posicao, c.nome_clube, c.imagem_clube 
        FROM jogador j
        JOIN nacionalidade n ON j.id_nacionalidade = n.id_nacionalidade
        JOIN jogador_posicoes jp ON j.id_jogador = jp.id_jogador
        JOIN posicoes p ON jp.id_posicao = p.id_posicao
        JOIN clube c ON j.id_clube = c.id_clube
        WHERE c.id_liga = $liga_id
        ORDER BY RAND() LIMIT 1";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(["error" => "Nenhum jogador encontrado."]);
}
?>
