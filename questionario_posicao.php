<?php session_start(); 
    proibirNaoAdmin();
    require_once 'db_connection.php';
?>
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_posicao = $_POST['nome_posicao'];

    // Inserindo a posição na tabela posicoes
    $sql = "INSERT INTO posicoes (nome_posicao) VALUES (?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome_posicao]);

    echo "Posição cadastrada com sucesso!";
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/questionario.css">
    <link rel="stylesheet" href="css/menu.css">  <!-- CSS do menu -->
    <link rel="stylesheet" href="css/footer.css"> <!-- CSS do footer -->
    <title>Questionário de Posição</title>
</head>
<body>
    <!-- Incluindo o menu -->
    <?php include 'menu.php'; ?>

    <form method="POST" action="questionario_posicao.php">
        <label for="nome_posicao">Nome da Posição:</label>
        <input type="text" id="nome_posicao" name="nome_posicao" required><br>

        <input type="submit" value="Enviar">
    </form>

    <!-- Incluindo o footer -->
    <?php include 'footer.php'; ?>
</body>
</html>
