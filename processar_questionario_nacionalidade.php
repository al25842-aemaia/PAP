<?php
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_nacionalidade = $_POST['nome_nacionalidade'];
    $imagem_nacionalidade = $_POST['imagem_nacionalidade'];

    // Insere uma nova nacionalidade
    $stmt = $pdo->prepare("INSERT INTO Nacionalidade (nome, imagem) VALUES (:nome, :imagem)");
    $stmt->bindParam(':nome', $nome_nacionalidade);
    $stmt->bindParam(':imagem', $imagem_nacionalidade);
    $stmt->execute();

    echo "Nacionalidade adicionada com sucesso!";
}
?>
