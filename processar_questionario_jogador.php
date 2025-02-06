<?php
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_jogador = $_POST['nome_jogador'];
    $aposentado = $_POST['aposentado'];
    $numero_camisola = $_POST['numero_camisola'];
    $imagem_jogador = $_POST['imagem_jogador'];
    $posicoes = $_POST['posicoes']; // Array de IDs das posições

    // Insere o jogador na tabela Jogador
    $stmt = $pdo->prepare("INSERT INTO Jogador (nome, aposentado, numero_camisola, imagem) VALUES (:nome, :aposentado, :numero_camisola, :imagem)");
    $stmt->bindParam(':nome', $nome_jogador);
    $stmt->bindParam(':aposentado', $aposentado);
    $stmt->bindParam(':numero_camisola', $numero_camisola);
    $stmt->bindParam(':imagem', $imagem_jogador);
    $stmt->execute();

    // Recupera o ID do jogador recém-inserido
    $jogador_id = $pdo->lastInsertId();

    // Insere as posições do jogador na tabela Jogador_Posicoes
    foreach ($posicoes as $posicao_id) {
        $stmt = $pdo->prepare("INSERT INTO Jogador_Posicoes (jogador_id, posicao_id) VALUES (:jogador_id, :posicao_id)");
        $stmt->bindParam(':jogador_id', $jogador_id);
        $stmt->bindParam(':posicao_id', $posicao_id);
        $stmt->execute();
    }

    echo "Jogador e suas posições adicionados com sucesso!";
}
?>
