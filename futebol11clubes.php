<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulador de Equipas</title>
    <link rel="stylesheet" href="css/futebol11clubes.css">
    <script src="js/futebol11clubes.js" defer></script>
</head>
<body>

<div class="container">
    <h1>Bem-vindo ao Simulador de Equipas!</h1>
    <p>Escolhe uma tática e um tempo para começar.</p>

    <label>Tempo:</label>
    <select id="tempo">
        <option value="0">Sem Tempo</option>
        <option value="120">120 segundos</option>
        <option value="60">60 segundos</option>
        <option value="30">30 segundos</option>
    </select>

    <label>Tática:</label>
    <select id="tactica">
        <option value="4-3-3">4-3-3</option>
        <option value="4-4-2">4-4-2</option>
        <option value="3-5-2">3-5-2</option>
        <option value="5-3-2">5-3-2</option>
        <option value="4-2-3-1">4-2-3-1</option>
        <option value="3-4-3">3-4-3</option>
        <option value="4-5-1">4-5-1</option>
        <option value="5-4-1">5-4-1</option>
        <option value="3-6-1">3-6-1</option>
        <option value="4-2-4">4-2-4</option>
    </select>

    <button onclick="iniciarJogo()">Começar Jogo</button>
</div>

</body>
</html>
