<?php
session_start();
include("db_connection.php");

// Simulação de um clube selecionado (ajuste conforme o seu banco de dados)
$id_clube = 1; // ID do clube fixo para teste

// Buscar clube no banco de dados
$query = "SELECT nome_clube, imagem_clube FROM clube WHERE id_clube = $id_clube";
$result = mysqli_query($conn, $query);
$clube = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogo - Simulador de Equipas</title>
    <link rel="stylesheet" href="css/futebol11clubes.css">
    <script defer>
        const clube = <?php echo json_encode($clube); ?>;
    </script>
    <script src="js/futebol11clubes.js" defer></script>
</head>
<body>

<div class="container">
    <h2>Cria a tua Equipa!</h2>
    <p id="tempoRestante"></p>

    <!-- Campo de Jogo -->
    <div id="campo-container">
        <div id="campo"></div>
    </div>

    <!-- Informações do Clube e Controles -->
    <div class="controles">
    <img id="clubeImagem" src="<?php echo $imagens_clube; ?>" alt="Clube">
        <span id="clubeNome"></span>
        <input type="text" id="pesquisaJogador" placeholder="Nome do jogador...">
        <button id="adicionarJogador">Adicionar Jogador</button>
        <button id="desistir" onclick="voltarInicio()">Desistir</button>
    </div>
</div>

</body>
</html>
