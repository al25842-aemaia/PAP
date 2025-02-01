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
        <div id="field">
            <!-- Quadrados das posições serão inseridos dinamicamente -->
        </div>
        <div id="tactic-selection">
            <label for="tactic">Escolha a Tática:</label>
            <select id="tactic">
                <option value="4-3-3">4-3-3 (Atacante)</option>
                <option value="4-4-2">4-4-2</option>
                <option value="3-5-2">3-5-2</option>
            </select>
        </div>
        <div id="player-selection">
            <input type="text" id="playerName" placeholder="Digite o nome do jogador">
            <select id="playerPosition">
                <option value="GR">GR</option>
                <option value="DC">DC</option>
                <option value="DD">DD</option>
                <option value="MDC">MDC</option>
                <option value="MC">MC</option>
                <option value="MCO">MCO</option>
                <option value="EE">EE</option>
                <option value="ED">ED</option>
                <option value="SA">SA</option>
                <option value="PL">PL</option>
            </select>
            <button id="addPlayer">Adicionar Jogador</button>
        </div>
    </main>
    <script src="js/futebol11clubes.js"></script>
</body>
</html>
