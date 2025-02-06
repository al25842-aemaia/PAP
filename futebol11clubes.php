<?php
require_once 'db_connection.php';
session_start();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Futebol 11 Clubes</title>
    <link rel="stylesheet" href="css/futebol11clubes.css">
</head>
<body>
    <header>
        <h1>Simulador de Equipas</h1>
    </header>
    <main>
        <div id="tactic-selection">
            <label for="tactic">Escolha a Tática:</label>
            <select id="tactic">
                <option value="4-3-3">4-3-3 (Atacante)</option>
                <option value="4-4-2">4-4-2</option>
                <option value="3-5-2">3-5-2</option>
            </select>
        </div>

        <div id="field"></div>

        <div id="player-selection">
            <input type="text" id="playerName" placeholder="Digite o nome do jogador">
            <select id="playerPosition">
                <option value="GR">GR</option>
                <option value="DC1">DC1</option>
                <option value="DC2">DC3</option>
                <option value="DC2">DC3</option>
                <option value="DD">DD</option>
                <option value="DE">DE</option>
                <option value="MDC">MDC</option>
                <option value="MC1">MC1</option>
                <option value="MC2">MC2</option>
                <option value="ME">ME</option>
                <option value="MD">MD</option>
                <option value="MCO">MCO</option>
                <option value="EE">EE</option>
                <option value="ED">ED</option>
                <option value="SA">SA</option>
                <option value="PL1">PL1</option>
                <option value="PL2">PL2</option>
            </select>
            <button id="addPlayer">Adicionar Jogador</button>
            <div id="small-square" style="display: flex; align-items: center; justify-content: center; border: 2px solid white; width: 150px; height: 150px; background-color: #333; margin: 10px auto;">
    <img src="" alt="Clube" id="club-image" style="width: 50px; height: 50px;">
    <span id="club-name" style="color: white; margin-left: 10px;"></span>
</div>
    </main>
    <script src="js/futebol11clubes.js"></script>
</body>
</html>