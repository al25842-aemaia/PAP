<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_posicao = $_POST['nome_posicao'];

    // Insere uma nova posição
    $stmt = $pdo->prepare("INSERT INTO Posicao (nome) VALUES (:nome)");
    $stmt->bindParam(':nome', $nome_posicao);
    $stmt->execute();

    echo "Posição adicionada com sucesso!";
}
?>
