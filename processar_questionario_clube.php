<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_clube = $_POST['nome_clube'];
    $local_clube = $_POST['local_clube'];
    $imagem_clube = $_POST['imagem_clube'];

    // Insere um novo clube
    $stmt = $pdo->prepare("INSERT INTO Clube (nome, local, imagem) VALUES (:nome, :local, :imagem)");
    $stmt->bindParam(':nome', $nome_clube);
    $stmt->bindParam(':local', $local_clube);
    $stmt->bindParam(':imagem', $imagem_clube);
    $stmt->execute();

    echo "Clube adicionado com sucesso!";
}
?>
