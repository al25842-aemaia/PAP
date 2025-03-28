<?php
include 'db_connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['equipa1'] = $_POST['equipa1'];
    $_SESSION['equipa2'] = $_POST['equipa2'];
    $_SESSION['score1'] = 0;
    $_SESSION['score2'] = 0;
}

$sql = "SELECT * FROM clube";
$result = $conn->query($sql);
$clubes = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $clubes[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulação de Futebol Ultra-Realista</title>
    <style>
        <?php if(!isset($_SESSION['equipa1'])): ?>
        /* Estilos do formulário inicial */
        body {
            font-family: 'Arial', sans-serif;
            text-align: center;
            background: linear-gradient(135deg, #1abc9c, #16a085);
            color: #fff;
            padding: 30px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: rgba(255,255,255,0.1);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
        }
        h1 {
            margin-bottom: 30px;
            font-size: 2.5em;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        label {
            font-size: 1.2em;
            margin-bottom: 5px;
            display: block;
        }
        select, button {
            padding: 12px;
            border-radius: 5px;
            border: none;
            font-size: 1em;
        }
        select {
            width: 100%;
            background: rgba(255,255,255,0.9);
        }
        button {
            background: #e74c3c;
            color: white;
            cursor: pointer;
            transition: background 0.3s;
            font-weight: bold;
            margin-top: 20px;
        }
        button:hover {
            background: #c0392b;
        }
        <?php else: ?>
        @keyframes grassMovement {
            0% { background-position: 0 0; }
            100% { background-position: 100px 100px; }
        }

        body { 
            margin: 0; 
            overflow: hidden; 
            background: #1a6e1a;
            font-family: 'Arial', sans-serif;
            perspective: 1000px;
        }

        .field-container {
            position: relative;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            transition: transform 0.5s cubic-bezier(0.25, 0.1, 0.25, 1);
            transform-style: preserve-3d;
        }

        .field {
            position: absolute;
            width: 120%;
            height: 120%;
            background: #3ba64c;
            top: -10%;
            left: -10%;
            background-image: 
                linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px),
                repeating-linear-gradient(180deg, rgba(59, 166, 76, 0.8), rgba(59, 166, 76, 0.8) 2px, rgba(52, 152, 68, 0.8) 2px, rgba(52, 152, 68, 0.8) 4px);
            background-size: 50px 50px, 50px 50px, 100% 100%;
            animation: grassMovement 20s linear infinite;
            transform: translateZ(-50px);
        }

        .field-overlay {
            position: absolute;
            width: 100%;
            height: 100%;
            background: radial-gradient(ellipse at center, transparent 30%, rgba(0,0,0,0.3) 100%);
            pointer-events: none;
            z-index: 3;
        }

        /* Linhas do campo com efeito 3D */
        .field-line {
            position: absolute;
            background: white;
            z-index: 2;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .half-line {
            top: 0;
            left: 50%;
            width: 4px;
            height: 100%;
            transform: translateX(-50%);
            box-shadow: 0 0 10px rgba(255,255,255,0.5);
        }

        .center-circle {
            top: 50%;
            left: 50%;
            width: 120px;
            height: 120px;
            border: 4px solid white;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 0 0 15px rgba(255,255,255,0.3);
        }

        .center-spot {
            top: 50%;
            left: 50%;
            width: 12px;
            height: 12px;
            background: white;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 0 0 8px rgba(255,255,255,0.7);
        }

        .penalty-area {
            width: 220px;
            height: 440px;
            border: 4px solid white;
            top: 50%;
            transform: translateY(-50%);
            box-shadow: 0 0 15px rgba(255,255,255,0.3);
        }

        .left-penalty { left: 0; }
        .right-penalty { right: 0; }

        .small-area {
            width: 120px;
            height: 240px;
            border: 4px solid white;
            top: 50%;
            transform: translateY(-50%);
            box-shadow: 0 0 12px rgba(255,255,255,0.3);
        }

        .left-small { left: 0; }
        .right-small { right: 0; }

        .penalty-spot {
            width: 12px;
            height: 12px;
            background: white;
            border-radius: 50%;
            top: 50%;
            box-shadow: 0 0 8px rgba(255,255,255,0.7);
        }

        .left-spot { left: 170px; }
        .right-spot { right: 170px; }

        /* Jogadores com efeito 3D */
        .player {
            position: absolute;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            transition: all 0.3s cubic-bezier(0.25, 0.1, 0.25, 1);
            z-index: 10;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-weight: bold;
            font-size: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            will-change: transform;
            transform-style: preserve-3d;
            background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.8) 0%, rgba(255,255,255,0) 60%);
        }

        .player::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: inherit;
            filter: brightness(0.9);
            transform: translateZ(-1px);
        }

        .player::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 50%;
            width: 20px;
            height: 6px;
            background: rgba(0,0,0,0.4);
            border-radius: 50%;
            transform: translateX(-50%);
            z-index: -1;
            filter: blur(2px);
        }

        .player.running {
            animation: runCycle 0.6s infinite ease-in-out;
        }

        @keyframes runCycle {
            0%, 100% { transform: translateY(0) rotate(0deg) scaleX(1); }
            25% { transform: translateY(-4px) rotate(2deg) scaleX(1.05); }
            50% { transform: translateY(0) rotate(0deg) scaleX(1); }
            75% { transform: translateY(-2px) rotate(-2deg) scaleX(0.95); }
        }

        .player.shooting {
            animation: shootAnim 0.5s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
        }

        @keyframes shootAnim {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(8px, -8px) scale(1.1); }
            100% { transform: translate(0, 0) scale(1); }
        }

        .player.tackling {
            animation: tackleAnim 0.4s ease-out;
        }

        @keyframes tackleAnim {
            0% { transform: translate(0, 0); }
            50% { transform: translate(5px, 0) rotate(10deg); }
            100% { transform: translate(0, 0); }
        }

        /* Bola com efeito 3D e textura */
        .ball {
            position: absolute;
            width: 18px;
            height: 18px;
            background: white;
            border-radius: 50%;
            z-index: 20;
            box-shadow: 
                0 0 10px rgba(255,255,255,0.8),
                0 2px 10px rgba(0,0,0,0.5),
                inset -5px -5px 10px rgba(0,0,0,0.2);
            transition: left 0.1s ease-out, top 0.1s ease-out;
            will-change: transform;
            background-image: 
                radial-gradient(circle at 30% 30%, #f5f5f5 0%, #e0e0e0 100%),
                repeating-linear-gradient(45deg, transparent, transparent 5px, rgba(0,0,0,0.1) 5px, rgba(0,0,0,0.1) 6px);
            transform-style: preserve-3d;
        }

        .ball::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: inherit;
            filter: brightness(0.9);
            transform: translateZ(-1px);
        }

        .ball.rolling {
            animation: ballRoll 0.5s infinite linear;
        }

        @keyframes ballRoll {
            0% { transform: rotateX(0) rotateY(0) rotateZ(0); }
            100% { transform: rotateX(360deg) rotateY(360deg) rotateZ(360deg); }
        }

        .ball.in-air {
            animation: none;
        }

        /* Árbitro */
        .referee {
            position: absolute;
            width: 20px;
            height: 20px;
            background: black;
            border-radius: 50%;
            z-index: 15;
            border: 2px solid white;
            transition: all 0.5s ease-out;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }

        /* Balizas com rede */
        .goal {
            position: absolute;
            width: 60px;
            height: 160px;
            border: 5px solid white;
            top: 50%;
            transform: translateY(-50%);
            z-index: 5;
            box-shadow: 0 0 20px rgba(255,255,255,0.4);
        }

        .left-goal { 
            left: 0; 
            background: 
                linear-gradient(90deg, rgba(0,0,0,0.3) 0%, transparent 20%),
                repeating-linear-gradient(0deg, transparent, transparent 9px, rgba(255,255,255,0.3) 9px, rgba(255,255,255,0.3) 10px),
                repeating-linear-gradient(90deg, transparent, transparent 9px, rgba(255,255,255,0.3) 9px, rgba(255,255,255,0.3) 10px);
        }

        .right-goal { 
            right: 0; 
            border-left: none;
            background: 
                linear-gradient(270deg, rgba(0,0,0,0.3) 0%, transparent 20%),
                repeating-linear-gradient(0deg, transparent, transparent 9px, rgba(255,255,255,0.3) 9px, rgba(255,255,255,0.3) 10px),
                repeating-linear-gradient(90deg, transparent, transparent 9px, rgba(255,255,255,0.3) 9px, rgba(255,255,255,0.3) 10px);
        }

        /* Informações do jogo */
        .team-name {
            position: absolute;
            top: 20px;
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-shadow: 2px 2px 6px rgba(0,0,0,0.8);
            z-index: 30;
            padding: 5px 15px;
            border-radius: 5px;
            background: rgba(0,0,0,0.5);
        }

        .left-team { left: 20px; }
        .right-team { right: 20px; }

        .scoreboard {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-size: 36px;
            font-weight: bold;
            text-shadow: 2px 2px 6px rgba(0,0,0,0.8);
            z-index: 30;
            background: rgba(0,0,0,0.7);
            padding: 8px 25px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
            display: flex;
            gap: 10px;
        }

        .score-team {
            min-width: 40px;
            text-align: center;
        }

        .goal-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 120px;
            font-weight: bold;
            color: red;
            z-index: 100;
            animation: goalFade 2.5s forwards;
            text-shadow: 0 0 20px rgba(255,0,0,0.7);
            pointer-events: none;
        }

        @keyframes goalFade {
            0% { opacity: 0; transform: scale(0.5); }
            20% { opacity: 1; transform: scale(1.3); }
            80% { opacity: 1; transform: scale(1.3); }
            100% { opacity: 0; transform: scale(1.5); display: none; }
        }

        .possession-bar {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            width: 250px;
            height: 12px;
            background: rgba(255,255,255,0.3);
            border-radius: 6px;
            overflow: hidden;
            z-index: 30;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }

        .possession-fill {
            height: 100%;
            background: linear-gradient(90deg, #e74c3c, #f39c12);
            transition: width 0.7s ease;
            box-shadow: inset 0 0 10px rgba(255,255,255,0.5);
        }

        .foul-animation {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 28px;
            z-index: 40;
            animation: foulFade 2.5s forwards;
            text-shadow: 0 0 10px rgba(255,255,255,0.5);
            box-shadow: 0 0 20px rgba(0,0,0,0.7);
            border: 2px solid #e74c3c;
        }

        @keyframes foulFade {
            0% { opacity: 0; transform: translate(-50%, -50%) scale(0.8); }
            20% { opacity: 1; transform: translate(-50%, -50%) scale(1.1); }
            80% { opacity: 1; transform: translate(-50%, -50%) scale(1.1); }
            100% { opacity: 0; transform: translate(-50%, -50%) scale(1.3); display: none; }
        }

        .controls {
            position: absolute;
            bottom: 50px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 50;
            display: flex;
            gap: 15px;
            background: rgba(0,0,0,0.6);
            padding: 10px 20px;
            border-radius: 30px;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
        }

        .controls button {
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s;
            backdrop-filter: blur(5px);
        }

        .controls button:hover {
            background: rgba(255,255,255,0.4);
            transform: translateY(-2px);
        }

        .time-display {
            position: absolute;
            top: 80px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-size: 18px;
            background: rgba(0,0,0,0.6);
            padding: 5px 15px;
            border-radius: 20px;
            z-index: 30;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }

        .stadium {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, #1a3a1a 0%, #0d1f0d 100%);
            z-index: -1;
            overflow: hidden;
        }

        .crowd {
            position: absolute;
            width: 100%;
            height: 30%;
            bottom: 0;
            background: 
                repeating-linear-gradient(0deg, 
                    rgba(200,50,50,0.1), 
                    rgba(200,50,50,0.1) 10px, 
                    rgba(50,50,200,0.1) 10px, 
                    rgba(50,50,200,0.1) 20px);
            animation: crowdNoise 0.5s infinite alternate;
        }

        @keyframes crowdNoise {
            0% { transform: translateY(0); }
            100% { transform: translateY(5px); }
        }

        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: rgba(255,255,255,0.7);
            border-radius: 50%;
            animation: float 10s infinite linear;
        }

        @keyframes float {
            0% { transform: translateY(0) translateX(0); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-100vh) translateX(20px); opacity: 0; }
        }

        <?php endif; ?>
    </style>
</head>
<body>
    <?php if(!isset($_SESSION['equipa1'])): ?>
    <div class="container">
        <h1>Escolher Equipas para Simulação</h1>
        <form method="POST">
            <label for="equipa1">Equipa da Esquerda:</label>
            <select name="equipa1" required>
                <option value="">-- Escolher --</option>
                <?php foreach ($clubes as $clube): ?>
                <option value="<?= $clube['nome_clube'] ?>"><?= $clube['nome_clube'] ?></option>
                <?php endforeach; ?>
            </select>

            <label for="equipa2">Equipa da Direita:</label>
            <select name="equipa2" required>
                <option value="">-- Escolher --</option>
                <?php foreach ($clubes as $clube): ?>
                <option value="<?= $clube['nome_clube'] ?>"><?= $clube['nome_clube'] ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Iniciar Simulação</button>
        </form>
    </div>
    <?php else: ?>
    <div class="stadium">
        <div class="crowd"></div>
        <div class="particles" id="particles"></div>
    </div>

    <div class="field-container">
        <div class="field"></div>
        <div class="field-overlay"></div>
        
        <!-- Linhas do campo -->
        <div class="field-line half-line"></div>
        <div class="field-line center-circle"></div>
        <div class="field-line center-spot"></div>
        <div class="field-line penalty-area left-penalty"></div>
        <div class="field-line penalty-area right-penalty"></div>
        <div class="field-line small-area left-small"></div>
        <div class="field-line small-area right-small"></div>
        <div class="field-line penalty-spot left-spot"></div>
        <div class="field-line penalty-spot right-spot"></div>
        
        <!-- Balizas -->
        <div class="goal left-goal"></div>
        <div class="goal right-goal"></div>
        
        <!-- Informações do jogo -->
        <div class="team-name left-team"><?= $_SESSION['equipa1'] ?></div>
        <div class="team-name right-team"><?= $_SESSION['equipa2'] ?></div>
        <div class="scoreboard">
            <span class="score-team">0</span>
            <span>-</span>
            <span class="score-team">0</span>
        </div>
        <div class="time-display" id="timeDisplay">1º Tempo: 00:00</div>
        <div class="possession-bar"><div class="possession-fill" style="width: 50%"></div></div>
        
        <!-- Controles -->
        <div class="controls">
            <button id="speedUp">⏩ Acelerar</button>
            <button id="slowDown">⏪ Desacelerar</button>
            <button id="resetGame">🔄 Reiniciar</button>
        </div>
        
        <!-- Elementos dinâmicos serão adicionados pelo JavaScript -->
    </div>

    <script>
        class FootballSimulation {
            constructor() {
                this.container = document.querySelector('.field-container');
                this.players = [];
                this.ball = null;
                this.referee = null;
                this.ballOwner = null;
                this.score = [0, 0];
                this.possession = [0, 0];
                this.lastPossessionChange = 0;
                this.gameTime = 0;
                this.matchTime = 0;
                this.isFirstHalf = true;
                this.animationId = null;
                this.lastUpdate = 0;
                this.gameSpeed = 1;
                this.state = 'playing';
                this.foulTimer = 0;
                this.timeScale = 18;
                this.init();
            }

            init() {
                this.createFieldElements();
                this.createPlayers();
                this.createBall();
                this.createReferee();
                this.setupControls();
                this.createParticles();
                this.updateScore();
                this.startGame();
            }

            createFieldElements() {
                const field = document.createElement('div');
                field.className = 'field';
                this.container.insertBefore(field, this.container.firstChild);
            }

            createPlayers() {
                const leftTeamPositions = this.getFormationPositions('left');
                leftTeamPositions.forEach((pos, i) => {
                    this.createPlayer('left', i, pos);
                });

                const rightTeamPositions = this.getFormationPositions('right');
                rightTeamPositions.forEach((pos, i) => {
                    this.createPlayer('right', i, pos);
                });
            }

            createPlayer(team, id, {x, y, role, number}) {
                const player = document.createElement('div');
                player.className = `player ${team}-team ${role}`;
                
                const teamColor = team === 'left' 
                    ? `radial-gradient(circle at 30% 30%, #e74c3c 0%, #c0392b 100%)` 
                    : `radial-gradient(circle at 30% 30%, #3498db 0%, #2980b9 100%)`;
                
                player.style.background = teamColor;
                player.style.left = `${x}px`;
                player.style.top = `${y}px`;
                player.textContent = number;
                
                this.container.appendChild(player);
                
                this.players.push({
                    element: player,
                    id: `${team}-${id}`,
                    team,
                    originalX: x,
                    originalY: y,
                    x, y,
                    targetX: x,
                    targetY: y,
                    role,
                    number,
                    speed: this.getSpeedByRole(role),
                    aggression: this.getAggressionByRole(role),
                    accuracy: this.getAccuracyByRole(role),
                    stamina: 100,
                    state: 'idle',
                    teamIndex: team === 'left' ? 0 : 1,
                    hasBall: false,
                    lastActionTime: 0,
                    fatigue: 0,
                    reactionTime: this.getReactionTimeByRole(role),
                    reactionTimer: 0,
                    isReacting: false,
                    currentSpeedX: 0,
                    currentSpeedY: 0,
                    randomMovementTimer: 0,
                    randomMovementTarget: {x, y}
                });
            }

            getSpeedByRole(role) {
                const speeds = {
                    'goalkeeper': 1.8,
                    'defender': 2.0,
                    'midfielder': 2.5,
                    'attacker': 2.8
                };
                return speeds[role] || 2;
            }

            getAggressionByRole(role) {
                const aggression = {
                    'goalkeeper': 0.2,
                    'defender': 0.5,
                    'midfielder': 0.4,
                    'attacker': 0.3
                };
                return aggression[role] || 0.3;
            }

            getAccuracyByRole(role) {
                const accuracy = {
                    'goalkeeper': 0.6,
                    'defender': 0.7,
                    'midfielder': 0.8,
                    'attacker': 0.75
                };
                return accuracy[role] || 0.7;
            }

            getReactionTimeByRole(role) {
                const reactionTimes = {
                    'goalkeeper': 0.3,
                    'defender': 0.5,
                    'midfielder': 0.7,
                    'attacker': 0.6
                };
                return reactionTimes[role] || 0.6;
            }

            getFormationPositions(team) {
                const width = window.innerWidth;
                const height = window.innerHeight;
                const isLeft = team === 'left';
                
                const positions = [];
                const numbers = {
                    'goalkeeper': 1,
                    'defender': [2, 3, 4, 5],
                    'midfielder': [6, 7, 8],
                    'attacker': [9, 10, 11]
                };
                
                positions.push({
                    x: isLeft ? 40 : width - 60,
                    y: height / 2,
                    role: 'goalkeeper',
                    number: numbers.goalkeeper
                });
                
                const defX = isLeft ? [90, 90, 130, 130] : [width-130, width-130, width-90, width-90];
                const defY = [height*0.25, height*0.75, height*0.35, height*0.65];
                for (let i = 0; i < 4; i++) {
                    positions.push({
                        x: defX[i],
                        y: defY[i],
                        role: 'defender',
                        number: numbers.defender[i]
                    });
                }
                
                const midX = isLeft ? [220, 220, 270] : [width-270, width-220, width-220];
                const midY = [height*0.4, height*0.6, height*0.5];
                for (let i = 0; i < 3; i++) {
                    positions.push({
                        x: midX[i],
                        y: midY[i],
                        role: 'midfielder',
                        number: numbers.midfielder[i]
                    });
                }
                
                const attX = isLeft ? 380 : width - 380;
                positions.push({
                    x: attX,
                    y: height * 0.5,
                    role: 'attacker',
                    number: numbers.attacker[0]
                });
                positions.push({
                    x: attX - (isLeft ? -30 : 30),
                    y: height * 0.3,
                    role: 'attacker',
                    number: numbers.attacker[1]
                });
                positions.push({
                    x: attX - (isLeft ? -30 : 30),
                    y: height * 0.7,
                    role: 'attacker',
                    number: numbers.attacker[2]
                });
                
                return positions;
            }

            createBall() {
                this.ball = document.createElement('div');
                this.ball.className = 'ball';
                this.ball.style.left = `${window.innerWidth / 2}px`;
                this.ball.style.top = `${window.innerHeight / 2}px`;
                this.container.appendChild(this.ball);
                
                this.ballPhysics = {
                    x: window.innerWidth / 2,
                    y: window.innerHeight / 2,
                    vx: 0,
                    vy: 0,
                    vz: 0,
                    angularVx: 0,
                    angularVy: 0,
                    angularVz: 0,
                    airFriction: 0.99,
                    groundFriction: 0.94,
                    bounceFactor: 0.6,
                    spinFactor: 0.1,
                    kickPower: 0,
                    lastTouch: null
                };
            }

            createReferee() {
                this.referee = document.createElement('div');
                this.referee.className = 'referee';
                this.referee.style.left = `${window.innerWidth / 2 - 100}px`;
                this.referee.style.top = `${window.innerHeight / 2}px`;
                this.container.appendChild(this.referee);
                
                this.referee.x = window.innerWidth / 2 - 100;
                this.referee.y = window.innerHeight / 2;
            }

            createParticles() {
                const particlesContainer = document.getElementById('particles');
                for (let i = 0; i < 50; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'particle';
                    particle.style.left = `${Math.random() * 100}%`;
                    particle.style.top = `${Math.random() * 100}%`;
                    particle.style.width = `${Math.random() * 3 + 1}px`;
                    particle.style.height = particle.style.width;
                    particle.style.animationDelay = `${Math.random() * 10}s`;
                    particle.style.animationDuration = `${Math.random() * 10 + 10}s`;
                    particlesContainer.appendChild(particle);
                }
            }

            setupControls() {
                document.getElementById('speedUp').addEventListener('click', () => {
                    this.gameSpeed = Math.min(3, this.gameSpeed + 0.5);
                    console.log('Velocidade aumentada para:', this.gameSpeed);
                });
                
                document.getElementById('slowDown').addEventListener('click', () => {
                    this.gameSpeed = Math.max(0.5, this.gameSpeed - 0.5);
                    console.log('Velocidade diminuída para:', this.gameSpeed);
                });
                
                document.getElementById('resetGame').addEventListener('click', () => {
                    this.resetGame();
                });
            }

            resetGame() {
                this.score = [0, 0];
                this.possession = [0, 0];
                this.matchTime = 0;
                this.isFirstHalf = true;
                this.updateScore();
                this.resetAfterGoal();
                document.querySelector('.possession-fill').style.width = '50%';
                document.getElementById('timeDisplay').textContent = '1º Tempo: 00:00';
            }

            startGame() {
                this.lastUpdate = performance.now();
                this.ballOwner = null;
                this.animate();
            }

            animate() {
                const now = performance.now();
                const deltaTime = Math.min((now - this.lastUpdate) / 1000, 0.1) * this.gameSpeed;
                this.lastUpdate = now;
                
                this.gameTime += deltaTime;
                this.matchTime += deltaTime * this.timeScale;
                
                this.updateTimeDisplay();
                
                if (this.state === 'playing') {
                    this.updatePlayers(deltaTime);
                    this.updateBallPhysics(deltaTime);
                    this.updateReferee(deltaTime);
                    this.checkGoal();
                    this.checkFouls();
                    this.updatePossession(deltaTime);
                    this.enhancePlayerInteractions();
                    this.updateFatigue(deltaTime);
                    this.handleBallSeeking();
                } else if (this.state === 'foul') {
                    this.foulTimer -= deltaTime;
                    if (this.foulTimer <= 0) {
                        this.state = 'playing';
                    }
                }
                
                this.animationId = requestAnimationFrame(() => this.animate());
            }

            updateTimeDisplay() {
                const totalSeconds = Math.floor(this.matchTime);
                const minutes = Math.floor(totalSeconds / 60);
                const seconds = totalSeconds % 60;
                const half = this.isFirstHalf ? '1º' : '2º';
                document.getElementById('timeDisplay').textContent = 
                    `${half} Tempo: ${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                if (this.isFirstHalf && totalSeconds >= 2700) {
                    this.isFirstHalf = false;
                    this.matchTime = 0;
                    this.resetAfterGoal();
                }
                else if (!this.isFirstHalf && totalSeconds >= 2700) {
                    this.endMatch();
                }
            }

            endMatch() {
                cancelAnimationFrame(this.animationId);
                this.state = 'ended';
                
                const finalScore = document.createElement('div');
                finalScore.className = 'foul-animation';
                finalScore.textContent = `FIM DE JOGO! ${this.score[0]} - ${this.score[1]}`;
                this.container.appendChild(finalScore);
                
                setTimeout(() => {
                    finalScore.remove();
                }, 5000);
            }

            updatePlayers(deltaTime) {
                this.players.forEach(player => {
                    player.hasBall = false;
                    
                    const speedModifier = 1 - (player.fatigue / 200);
                    const currentSpeed = player.speed * speedModifier;
                    
                    if (player.role === 'goalkeeper') {
                        this.updateGoalkeeper(player, deltaTime, currentSpeed);
                    } else {
                        this.updatePlayerTarget(player, deltaTime);
                        this.applyTacticalMovement(player);
                        this.applyRandomMovement(player, deltaTime);
                    }
                    
                    this.movePlayer(player, deltaTime, currentSpeed);
                    this.checkPlayerBallInteraction(player);
                });
            }

            updatePlayerTarget(player, deltaTime) {
                const ballX = this.ballPhysics.x;
                const ballY = this.ballPhysics.y;
                const distToBall = Math.hypot(player.x - ballX, player.y - ballY);
                
                // Prioridade máxima: ir para a bola se estiver livre e for o mais próximo
                if (this.ballOwner === null && distToBall < 400) {
                    if (this.isClosestToBallInTeam(player)) {
                        const approachDistance = 15 + Math.random() * 10;
                        const angle = Math.atan2(ballY - player.y, ballX - player.x);
                        player.targetX = ballX - Math.cos(angle) * approachDistance;
                        player.targetY = ballY - Math.sin(angle) * approachDistance;
                        player.isReacting = true;
                        return;
                    }
                }
                
                if (!player.isReacting && distToBall < 250) {
                    player.isReacting = true;
                    player.reactionTimer = player.reactionTime;
                }
                
                if (player.isReacting) {
                    player.reactionTimer -= deltaTime;
                    
                    if (player.reactionTimer <= 0) {
                        player.isReacting = false;
                        
                        if ((this.ballOwner && this.ballOwner.team === player.team) || !this.ballOwner) {
                            if (this.isClosestToBallInTeam(player)) {
                                const approachDistance = 20 + Math.random() * 10;
                                const angle = Math.atan2(ballY - player.y, ballX - player.x);
                                player.targetX = ballX - Math.cos(angle) * approachDistance;
                                player.targetY = ballY - Math.sin(angle) * approachDistance;
                            } else {
                                this.positionForPass(player);
                            }
                        } else {
                            this.markOpponent(player);
                        }
                    }
                } else {
                    const formationOffset = this.getFormationOffset(player);
                    player.targetX = player.originalX + formationOffset.x;
                    player.targetY = player.originalY + formationOffset.y;
                }
            }

            isClosestToBallInTeam(player) {
                let minDist = Infinity;
                let closestPlayer = null;
                
                this.players.forEach(p => {
                    if (p.team === player.team && p.role !== 'goalkeeper') {
                        const dist = Math.hypot(p.x - this.ballPhysics.x, p.y - this.ballPhysics.y);
                        if (dist < minDist) {
                            minDist = dist;
                            closestPlayer = p;
                        }
                    }
                });
                
                return closestPlayer === player;
            }

            applyRandomMovement(player, deltaTime) {
                if (this.ballOwner !== null || player.isReacting) return;
                
                player.randomMovementTimer -= deltaTime;
                
                if (player.randomMovementTimer <= 0) {
                    const maxOffset = player.role === 'defender' ? 40 : 
                                    player.role === 'midfielder' ? 60 : 50;
                    
                    player.randomMovementTarget = {
                        x: player.originalX + (Math.random() * 2 - 1) * maxOffset,
                        y: player.originalY + (Math.random() * 2 - 1) * maxOffset
                    };
                    
                    player.randomMovementTarget.x = Math.max(30, Math.min(window.innerWidth - 30, player.randomMovementTarget.x));
                    player.randomMovementTarget.y = Math.max(30, Math.min(window.innerHeight - 30, player.randomMovementTarget.y));
                    
                    player.randomMovementTimer = 2 + Math.random() * 3;
                }
                
                player.targetX = (player.targetX + player.randomMovementTarget.x) / 2;
                player.targetY = (player.targetY + player.randomMovementTarget.y) / 2;
            }

            handleBallSeeking() {
                if (this.ballOwner !== null) return;
                
                let closestLeft = { player: null, distance: Infinity };
                let closestRight = { player: null, distance: Infinity };
                
                this.players.forEach(player => {
                    if (player.role === 'goalkeeper') return;
                    
                    const distance = Math.hypot(
                        player.x - this.ballPhysics.x,
                        player.y - this.ballPhysics.y
                    );
                    
                    if (player.team === 'left' && distance < closestLeft.distance) {
                        closestLeft = { player, distance };
                    } else if (player.team === 'right' && distance < closestRight.distance) {
                        closestRight = { player, distance };
                    }
                });
                
                if (closestLeft.player && (!closestRight.player || closestLeft.distance < closestRight.distance)) {
                    const angle = Math.atan2(
                        this.ballPhysics.y - closestLeft.player.y,
                        this.ballPhysics.x - closestLeft.player.x
                    );
                    const approachDistance = 15 + Math.random() * 10;
                    
                    closestLeft.player.targetX = this.ballPhysics.x - Math.cos(angle) * approachDistance;
                    closestLeft.player.targetY = this.ballPhysics.y - Math.sin(angle) * approachDistance;
                    closestLeft.player.isReacting = true;
                } else if (closestRight.player) {
                    const angle = Math.atan2(
                        this.ballPhysics.y - closestRight.player.y,
                        this.ballPhysics.x - closestRight.player.x
                    );
                    const approachDistance = 15 + Math.random() * 10;
                    
                    closestRight.player.targetX = this.ballPhysics.x - Math.cos(angle) * approachDistance;
                    closestRight.player.targetY = this.ballPhysics.y - Math.sin(angle) * approachDistance;
                    closestRight.player.isReacting = true;
                }
            }

            getFormationOffset(player) {
                const isLeft = player.team === 'left';
                const ballX = this.ballPhysics.x;
                const ballInDefensiveHalf = isLeft ? ballX < window.innerWidth/3 : ballX > window.innerWidth*2/3;
                
                if (ballInDefensiveHalf) {
                    if (player.role === 'defender') {
                        return { x: isLeft ? -15 : 15, y: 0 };
                    } else if (player.role === 'midfielder') {
                        return { 
                            x: isLeft ? -25 : 25, 
                            y: Math.sin(this.gameTime * 0.5 + player.number) * 15 
                        };
                    }
                } else {
                    if (player.role === 'midfielder') {
                        return { x: isLeft ? 35 : -35, y: 0 };
                    } else if (player.role === 'attacker') {
                        return { 
                            x: isLeft ? 45 : -45, 
                            y: Math.sin(this.gameTime * 0.8 + player.number) * 20 
                        };
                    }
                }
                
                return { x: 0, y: 0 };
            }

            applyTacticalMovement(player) {
                const isLeft = player.team === 'left';
                const ballX = this.ballPhysics.x;
                const ballY = this.ballPhysics.y;
                const distToBall = Math.hypot(player.x - ballX, player.y - ballY);
                
                if (distToBall > 100) {
                    if (this.ballOwner && this.ballOwner.team === player.team) {
                        if (player.role === 'midfielder') {
                            const angle = Math.atan2(ballY - player.y, ballX - player.x);
                            const offsetDist = 60 + Math.sin(this.gameTime) * 10;
                            player.targetX += Math.cos(angle + Math.PI/2) * offsetDist * 0.3;
                            player.targetY += Math.sin(angle + Math.PI/2) * offsetDist * 0.3;
                        }
                    } else {
                        if (player.role === 'defender') {
                            const targetPlayer = this.findNearestOpponent(player);
                            if (targetPlayer) {
                                player.targetX += (targetPlayer.x > player.x) ? 10 : -10;
                                player.targetY += (targetPlayer.y > player.y) ? 8 : -8;
                            }
                        }
                    }
                }
            }

            findNearestOpponent(player) {
                let nearest = null;
                let minDist = Infinity;
                
                this.players.forEach(p => {
                    if (p.team !== player.team) {
                        const dist = Math.hypot(p.x - player.x, p.y - player.y);
                        if (dist < minDist) {
                            minDist = dist;
                            nearest = p;
                        }
                    }
                });
                
                return nearest;
            }

            updateGoalkeeper(player, deltaTime, speed) {
                const isLeft = player.team === 'left';
                const goalX = isLeft ? 40 : window.innerWidth - 60;
                const goalY = window.innerHeight / 2;
                const ballY = this.ballPhysics.y;
                
                const areaTop = goalY - 120;
                const areaBottom = goalY + 120;
                
                let targetY = ballY;
                
                const ballDist = Math.hypot(this.ballPhysics.x - player.x, this.ballPhysics.y - player.y);
                if (ballDist < 200) {
                    player.targetY = ballY;
                } else {
                    player.targetY = Math.max(areaTop, Math.min(areaBottom, targetY));
                }
                
                player.targetX = goalX;
            }

            movePlayer(player, deltaTime, speed) {
                const dx = player.targetX - player.x;
                const dy = player.targetY - player.y;
                const distance = Math.hypot(dx, dy);
                
                if (distance > 2) {
                    const acceleration = 0.1;
                    const deceleration = 0.2;
                    
                    const currentSpeedX = player.currentSpeedX || 0;
                    const currentSpeedY = player.currentSpeedY || 0;
                    
                    const targetSpeedX = (dx / distance) * speed * 50;
                    const targetSpeedY = (dy / distance) * speed * 50;
                    
                    let newSpeedX = currentSpeedX;
                    let newSpeedY = currentSpeedY;
                    
                    if (Math.abs(targetSpeedX) > Math.abs(currentSpeedX)) {
                        newSpeedX = currentSpeedX + (targetSpeedX > 0 ? acceleration : -acceleration) * deltaTime * 60;
                    } else {
                        newSpeedX = currentSpeedX * (1 - deceleration * deltaTime * 60);
                    }
                    
                    if (Math.abs(targetSpeedY) > Math.abs(currentSpeedY)) {
                        newSpeedY = currentSpeedY + (targetSpeedY > 0 ? acceleration : -acceleration) * deltaTime * 60;
                    } else {
                        newSpeedY = currentSpeedY * (1 - deceleration * deltaTime * 60);
                    }
                    
                    const speedRatio = Math.hypot(newSpeedX, newSpeedY) / (speed * 50);
                    if (speedRatio > 1) {
                        newSpeedX /= speedRatio;
                        newSpeedY /= speedRatio;
                    }
                    
                    player.x += newSpeedX * deltaTime;
                    player.y += newSpeedY * deltaTime;
                    
                    player.currentSpeedX = newSpeedX;
                    player.currentSpeedY = newSpeedY;
                    
                    player.element.classList.add('running');
                    
                    if (Math.abs(dx) > 1) {
                        const currentScale = player.element.style.transform.includes('scaleX(-1)') ? -1 : 1;
                        const targetScale = dx > 0 ? 1 : -1;
                        
                        if (currentScale !== targetScale) {
                            player.element.style.transform = `scaleX(${targetScale})`;
                        }
                    }
                } else {
                    player.element.classList.remove('running');
                    player.currentSpeedX = 0;
                    player.currentSpeedY = 0;
                }
                
                player.x = Math.max(30, Math.min(window.innerWidth - 30, player.x));
                player.y = Math.max(30, Math.min(window.innerHeight - 30, player.y));
                
                player.element.style.left = `${player.x}px`;
                player.element.style.top = `${player.y}px`;
            }

            checkPlayerBallInteraction(player) {
                const dx = player.x - this.ballPhysics.x;
                const dy = player.y - this.ballPhysics.y;
                const distance = Math.hypot(dx, dy);
                
                if (distance < 30) {
                    if (this.ballOwner === null) {
                        this.gainBallPossession(player);
                    } else if (this.ballOwner.team !== player.team) {
                        this.attemptTackle(player, this.ballOwner);
                    }
                }
                
                if (player === this.ballOwner) {
                    this.controlBall(player);
                    
                    if (Math.random() < 0.01) {
                        this.playerAction(player);
                    }
                }
            }

            gainBallPossession(player) {
                this.ballOwner = player;
                player.hasBall = true;
                this.changePossession();
                this.ballPhysics.lastTouch = player;
                
                player.element.style.zIndex = '25';
                player.element.style.boxShadow = '0 0 20px yellow';
                setTimeout(() => {
                    player.element.style.boxShadow = '';
                }, 500);
            }

            attemptTackle(player, opponent) {
                const tackleChance = player.aggression * 0.15 * (1 - opponent.accuracy * 0.5);
                
                if (Math.random() < tackleChance) {
                    player.element.classList.add('tackling');
                    setTimeout(() => {
                        player.element.classList.remove('tackling');
                    }, 400);
                    
                    this.ballOwner = player;
                    player.hasBall = true;
                    this.changePossession();
                    
                    player.fatigue += 5;
                    opponent.fatigue += 3;
                    
                    const angle = Math.atan2(player.y - opponent.y, player.x - opponent.x);
                    const power = 8 + Math.random() * 5;
                    this.ballPhysics.vx = Math.cos(angle) * power;
                    this.ballPhysics.vy = Math.sin(angle) * power;
                    this.ballPhysics.kickPower = power;
                    this.ballPhysics.angularVx = (Math.random() - 0.5) * 10;
                    this.ballPhysics.angularVy = (Math.random() - 0.5) * 10;
                }
            }

            controlBall(player) {
                const isLeft = player.team === 'left';
                const controlDistance = 18;
                const controlAngle = Math.atan2(player.targetY - player.y, player.targetX - player.x);
                
                this.ballPhysics.x = player.x + Math.cos(controlAngle) * controlDistance * (isLeft ? 1 : -1);
                this.ballPhysics.y = player.y + Math.sin(controlAngle) * controlDistance;
                player.hasBall = true;
                
                this.ball.style.transition = 'left 0.2s ease-out, top 0.2s ease-out';
            }

            playerAction(player) {
                const isLeft = player.team === 'left';
                const goalX = isLeft ? window.innerWidth : 0;
                const goalY = window.innerHeight / 2;
                
                if (player.role === 'goalkeeper') {
                    this.goalkeeperAction(player);
                } 
                else if (player.role === 'defender') {
                    this.defenderAction(player);
                }
                else if (player.role === 'midfielder') {
                    this.midfielderAction(player);
                }
                else if (player.role === 'attacker') {
                    this.attackerAction(player);
                }
                
                setTimeout(() => {
                    this.ballOwner = null;
                }, 100);
            }

            goalkeeperAction(player) {
                const isLeft = player.team === 'left';
                const targetX = isLeft ? window.innerWidth * 0.6 : window.innerWidth * 0.4;
                const targetY = window.innerHeight * 0.3 + Math.random() * window.innerHeight * 0.4;
                
                this.kickBall(targetX, targetY, 15 + Math.random() * 5);
            }

            defenderAction(player) {
                const teammates = this.players.filter(p => 
                    p.team === player.team && 
                    p !== player &&
                    ['midfielder', 'defender'].includes(p.role)
                );
                
                if (teammates.length > 0 && Math.random() < 0.8) {
                    const receiver = teammates[Math.floor(Math.random() * teammates.length)];
                    const accuracy = player.accuracy * (1 - player.fatigue * 0.003);
                    const offsetX = (Math.random() - 0.5) * 30 * (1 - accuracy);
                    const offsetY = (Math.random() - 0.5) * 30 * (1 - accuracy);
                    
                    this.kickBall(
                        receiver.x + offsetX,
                        receiver.y + offsetY,
                        12 + Math.random() * 3
                    );
                } else {
                    const isLeft = player.team === 'left';
                    this.kickBall(
                        isLeft ? window.innerWidth * 0.7 : window.innerWidth * 0.3,
                        window.innerHeight * 0.3 + Math.random() * window.innerHeight * 0.4,
                        18 + Math.random() * 5
                    );
                }
            }

            midfielderAction(player) {
                const isLeft = player.team === 'left';
                const goalX = isLeft ? window.innerWidth : 0;
                const goalY = window.innerHeight / 2;
                const distToGoal = Math.abs(player.x - goalX);
                
                if (distToGoal < 400 && Math.random() < 0.4) {
                    const accuracy = player.accuracy * 0.8 * (1 - player.fatigue * 0.002);
                    const angleVariation = (1 - accuracy) * 0.4;
                    
                    this.shootAtGoal(
                        player,
                        goalX,
                        goalY + (Math.random() - 0.5) * 100 * angleVariation,
                        18 + Math.random() * 4
                    );
                } else {
                    const teammates = this.players.filter(p => 
                        p.team === player.team && 
                        p !== player &&
                        ['attacker', 'midfielder'].includes(p.role)
                    );
                    
                    if (teammates.length > 0) {
                        const receiver = teammates[Math.floor(Math.random() * teammates.length)];
                        const accuracy = player.accuracy * (1 - player.fatigue * 0.002);
                        const offsetX = (Math.random() - 0.5) * 20 * (1 - accuracy);
                        const offsetY = (Math.random() - 0.5) * 20 * (1 - accuracy);
                        
                        this.kickBall(
                            receiver.x + offsetX,
                            receiver.y + offsetY,
                            14 + Math.random() * 3
                        );
                    }
                }
            }

            attackerAction(player) {
                const isLeft = player.team === 'left';
                const goalX = isLeft ? window.innerWidth : 0;
                const goalY = window.innerHeight / 2;
                const distToGoal = Math.abs(player.x - goalX);
                
                if (distToGoal < 300) {
                    const accuracy = player.accuracy * (1 - player.fatigue * 0.0015);
                    const angleVariation = (1 - accuracy) * 0.3;
                    const power = 20 + (300 / distToGoal) + Math.random() * 5;
                    
                    this.shootAtGoal(
                        player,
                        goalX,
                        goalY + (Math.random() - 0.5) * 80 * angleVariation,
                        Math.min(28, power)
                    );
                } else {
                    const teammates = this.players.filter(p => 
                        p.team === player.team && 
                        p !== player &&
                        ['attacker', 'midfielder'].includes(p.role)
                    );
                    
                    if (teammates.length > 0) {
                        const receiver = teammates[Math.floor(Math.random() * teammates.length)];
                        const accuracy = player.accuracy * (1 - player.fatigue * 0.002);
                        const offsetX = (Math.random() - 0.5) * 15 * (1 - accuracy);
                        const offsetY = (Math.random() - 0.5) * 15 * (1 - accuracy);
                        
                        this.kickBall(
                            receiver.x + offsetX,
                            receiver.y + offsetY,
                            12 + Math.random() * 3
                        );
                    }
                }
            }

            shootAtGoal(shooter, targetX, targetY, power) {
                shooter.element.classList.add('shooting');
                setTimeout(() => {
                    shooter.element.classList.remove('shooting');
                }, 500);
                
                const angle = Math.atan2(targetY - this.ballPhysics.y, targetX - this.ballPhysics.x);
                const spinDirection = Math.sign(Math.random() - 0.5);
                
                this.ballPhysics.vx = Math.cos(angle) * power;
                this.ballPhysics.vy = Math.sin(angle) * power;
                this.ballPhysics.angularVx = spinDirection * power * 0.3;
                this.ballPhysics.angularVy = -spinDirection * power * 0.3;
                this.ballPhysics.kickPower = power;
                this.ballPhysics.lastTouch = shooter;
                
                this.ball.style.transition = 'left 0.1s ease-out, top 0.1s ease-out';
                
                shooter.fatigue += 3;
            }

            kickBall(targetX, targetY, power) {
                const angle = Math.atan2(targetY - this.ballPhysics.y, targetX - this.ballPhysics.x);
                
                this.ballPhysics.vx = Math.cos(angle) * power;
                this.ballPhysics.vy = Math.sin(angle) * power;
                this.ballPhysics.kickPower = power;
                this.ballPhysics.lastTouch = this.ballOwner;
                
                this.ball.style.transition = 'left 0.1s ease-out, top 0.1s ease-out';
            }

            updateBallPhysics(deltaTime) {
                if (this.ballPhysics.kickPower > 5) {
                    this.ballPhysics.vx += this.ballPhysics.angularVy * 0.02 * deltaTime * 60;
                    this.ballPhysics.vy -= this.ballPhysics.angularVx * 0.02 * deltaTime * 60;
                }
                
                this.ballPhysics.x += this.ballPhysics.vx * deltaTime * 60;
                this.ballPhysics.y += this.ballPhysics.vy * deltaTime * 60;
                
                const friction = this.ballPhysics.kickPower > 5 ? 
                    this.ballPhysics.airFriction : 
                    this.ballPhysics.groundFriction;
                
                this.ballPhysics.vx *= friction;
                this.ballPhysics.vy *= friction;
                this.ballPhysics.angularVx *= friction;
                this.ballPhysics.angularVy *= friction;
                this.ballPhysics.kickPower *= friction;
                
                if (this.ballPhysics.y >= window.innerHeight - 25 && this.ballPhysics.vy > 0) {
                    this.ballPhysics.y = window.innerHeight - 25;
                    this.ballPhysics.vy *= -this.ballPhysics.bounceFactor;
                }
                
                if (this.ballPhysics.x < 25) {
                    this.ballPhysics.x = 25;
                    this.ballPhysics.vx *= -0.7;
                    this.ballPhysics.angularVy = -this.ballPhysics.vx * 3;
                } else if (this.ballPhysics.x > window.innerWidth - 25) {
                    this.ballPhysics.x = window.innerWidth - 25;
                    this.ballPhysics.vx *= -0.7;
                    this.ballPhysics.angularVy = -this.ballPhysics.vx * 3;
                }
                
                if (this.ballPhysics.y < 25) {
                    this.ballPhysics.y = 25;
                    this.ballPhysics.vy *= -0.7;
                    this.ballPhysics.angularVx = this.ballPhysics.vy * 3;
                }
                
                const isMoving = Math.abs(this.ballPhysics.vx) > 0.3 || Math.abs(this.ballPhysics.vy) > 0.3;
                
                if (isMoving) {
                    if (this.ballPhysics.kickPower > 5) {
                        this.ball.classList.add('in-air');
                        this.ball.classList.remove('rolling');
                    } else {
                        this.ball.classList.add('rolling');
                        this.ball.classList.remove('in-air');
                        
                        const speed = Math.hypot(this.ballPhysics.vx, this.ballPhysics.vy);
                        const duration = Math.max(0.3, 1.5 - speed * 0.05);
                        this.ball.style.animationDuration = `${duration}s`;
                    }
                } else {
                    this.ball.classList.remove('rolling', 'in-air');
                }
                
                this.ball.style.left = `${this.ballPhysics.x}px`;
                this.ball.style.top = `${this.ballPhysics.y}px`;
            }

            updateReferee(deltaTime) {
                const targetX = this.ballPhysics.x + (Math.random() * 100 - 50);
                const targetY = this.ballPhysics.y + (Math.random() * 80 - 40);
                
                const dx = targetX - this.referee.x;
                const dy = targetY - this.referee.y;
                const distance = Math.hypot(dx, dy);
                
                if (distance > 120) {
                    this.referee.x += dx / distance * 2.5 * deltaTime * 60;
                    this.referee.y += dy / distance * 2.5 * deltaTime * 60;
                }
                
                this.referee.style.left = `${this.referee.x}px`;
                this.referee.style.top = `${this.referee.y}px`;
            }

            checkGoal() {
                if (this.state !== 'playing') return;
                
                const goalWidth = 60;
                const goalHeight = 160;
                const ballX = this.ballPhysics.x;
                const ballY = this.ballPhysics.y;
                
                if (ballX < -10 && 
                    ballY > (window.innerHeight - goalHeight)/2 && 
                    ballY < (window.innerHeight + goalHeight)/2) {
                    this.scoreGoal('right');
                }
                
                if (ballX > window.innerWidth + 10 && 
                    ballY > (window.innerHeight - goalHeight)/2 && 
                    ballY < (window.innerHeight + goalHeight)/2) {
                    this.scoreGoal('left');
                }
            }

            checkFouls() {
                if (this.state !== 'playing' || this.ballPhysics.kickPower < 8) return;
                
                for (let i = 0; i < this.players.length; i++) {
                    for (let j = i + 1; j < this.players.length; j++) {
                        const p1 = this.players[i];
                        const p2 = this.players[j];
                        
                        const dx = p1.x - p2.x;
                        const dy = p1.y - p2.y;
                        const distance = Math.hypot(dx, dy);
                        
                        if (distance < 22 && p1.team !== p2.team && Math.random() < 0.15) {
                            this.callFoul(p1, p2);
                            return;
                        }
                    }
                }
            }

            callFoul(p1, p2) {
                this.state = 'foul';
                this.foulTimer = 2.5;
                
                const foulDiv = document.createElement('div');
                foulDiv.className = 'foul-animation';
                foulDiv.textContent = 'FALTA!';
                this.container.appendChild(foulDiv);
                
                setTimeout(() => {
                    foulDiv.remove();
                }, 2500);
                
                p1.x = p1.originalX;
                p1.y = p1.originalY;
                p2.x = p2.originalX;
                p2.y = p2.originalY;
                
                this.ballPhysics.x = (p1.x + p2.x) / 2;
                this.ballPhysics.y = (p1.y + p2.y) / 2;
                this.ballPhysics.vx = 0;
                this.ballPhysics.vy = 0;
                this.ballPhysics.kickPower = 0;
                this.ballOwner = null;
                
                p1.fatigue += 8;
                p2.fatigue += 8;
            }

            scoreGoal(scoringTeam) {
                this.state = 'goal';
                
                if (scoringTeam === 'left') {
                    this.score[0]++;
                } else {
                    this.score[1]++;
                }
                
                this.updateScore();
                this.showGoalAnimation();
                
                setTimeout(() => {
                    this.resetAfterGoal();
                }, 3000);
            }

            updateScore() {
                document.querySelector('.scoreboard').innerHTML = `
                    <span class="score-team">${this.score[0]}</span>
                    <span>-</span>
                    <span class="score-team">${this.score[1]}</span>
                `;
            }

            showGoalAnimation() {
                const goalDiv = document.createElement('div');
                goalDiv.className = 'goal-animation';
                goalDiv.textContent = 'GOOOOOOOLO!';
                this.container.appendChild(goalDiv);
                
                if (typeof Audio !== 'undefined') {
                    try {
                        const audio = new Audio('https://www.soundjay.com/misc/sounds/crowd-cheer-01.mp3');
                        audio.volume = 0.3;
                        audio.play().catch(e => console.log('Audio playback error:', e));
                    } catch (e) {
                        console.log('Audio error:', e);
                    }
                }
                
                setTimeout(() => {
                    goalDiv.remove();
                }, 2500);
            }

            resetAfterGoal() {
                this.ballPhysics.x = window.innerWidth / 2;
                this.ballPhysics.y = window.innerHeight / 2;
                this.ballPhysics.vx = 0;
                this.ballPhysics.vy = 0;
                this.ballPhysics.kickPower = 0;
                this.ballOwner = null;
                
                this.players.forEach(player => {
                    player.x = player.originalX;
                    player.y = player.originalY;
                    player.targetX = player.originalX;
                    player.targetY = player.originalY;
                    player.randomMovementTarget = {x: player.originalX, y: player.originalY};
                    player.randomMovementTimer = 0;
                    
                    player.element.style.left = `${player.x}px`;
                    player.element.style.top = `${player.y}px`;
                });
                
                this.referee.x = window.innerWidth / 2 - 100;
                this.referee.y = window.innerHeight / 2;
                
                this.state = 'playing';
            }

            updatePossession(deltaTime) {
                if (this.ballOwner) {
                    const teamIndex = this.ballOwner.team === 'left' ? 0 : 1;
                    this.possession[teamIndex] += deltaTime;
                    
                    const total = this.possession[0] + this.possession[1];
                    const leftPercent = total > 0 ? (this.possession[0] / total) * 100 : 50;
                    document.querySelector('.possession-fill').style.width = `${leftPercent}%`;
                }
            }

            changePossession() {
                if (this.ballOwner) {
                    this.ballOwner.element.style.boxShadow = '0 0 15px rgba(255, 255, 0, 0.7)';
                    this.ballOwner.element.style.zIndex = '25';
                    
                    setTimeout(() => {
                        this.ballOwner.element.style.boxShadow = '';
                    }, 800);
                }
                
                this.lastPossessionChange = this.gameTime;
            }

            updateFatigue(deltaTime) {
                this.players.forEach(player => {
                    const dx = player.targetX - player.x;
                    const dy = player.targetY - player.y;
                    const distance = Math.sqrt(dx * dx + dy * dy);
                    
                    if (distance > 5) {
                        player.fatigue = Math.min(100, player.fatigue + distance * 0.01 * deltaTime);
                    } else {
                        player.fatigue = Math.max(0, player.fatigue - 0.5 * deltaTime);
                    }
                    
                    if (player.fatigue > 70) {
                        player.element.style.filter = `brightness(${1 - (player.fatigue - 70) * 0.01})`;
                    } else {
                        player.element.style.filter = '';
                    }
                });
            }

            enhancePlayerInteractions() {
                this.players.forEach(player => {
                    if (player.state === 'tackling') {
                        player.element.style.boxShadow = '0 0 20px rgba(255, 0, 0, 0.7)';
                    } else if (player.hasBall) {
                        player.element.style.boxShadow = '0 0 20px rgba(255, 255, 0, 0.7)';
                        player.element.style.zIndex = '25';
                    } else {
                        player.element.style.boxShadow = '';
                        player.element.style.zIndex = '10';
                    }
                    
                    player.element.onmouseenter = () => {
                        if (!player.hasBall && this.state === 'playing') {
                            player.element.style.boxShadow = '0 0 15px rgba(0, 255, 255, 0.7)';
                        }
                    };
                    
                    player.element.onmouseleave = () => {
                        if (!player.hasBall && player.state !== 'tackling') {
                            player.element.style.boxShadow = '';
                        }
                    };
                });
            }
        }

        function setupCameraEffects() {
            const container = document.querySelector('.field-container');
            let scale = 1;
            let offsetX = 0;
            let offsetY = 0;
            let targetScale = 1;
            let targetOffsetX = 0;
            let targetOffsetY = 0;
            
            function updateCamera() {
                if (!window.simulation) return;
                
                const ball = window.simulation.ballPhysics;
                const width = window.innerWidth;
                const height = window.innerHeight;
                
                targetOffsetX = width/2 - ball.x;
                targetOffsetY = height/2 - ball.y;
                
                const maxOffset = 200;
                targetOffsetX = Math.max(-maxOffset, Math.min(maxOffset, targetOffsetX));
                targetOffsetY = Math.max(-maxOffset, Math.min(maxOffset, targetOffsetY));
                
                const ballSpeed = Math.hypot(ball.vx, ball.vy);
                targetScale = Math.min(1, Math.max(0.7, 1 - ballSpeed * 0.002));
                
                offsetX += (targetOffsetX - offsetX) * 0.05;
                offsetY += (targetOffsetY - offsetY) * 0.05;
                scale += (targetScale - scale) * 0.05;
                
                container.style.transform = `scale(${scale}) translate(${offsetX}px, ${offsetY}px)`;
                requestAnimationFrame(updateCamera);
            }
            
            updateCamera();
        }

        window.onload = () => {
            window.simulation = new FootballSimulation();
            setupCameraEffects();
            
            window.addEventListener('resize', () => {
                // Em uma implementação real, você precisaria recalcular posições
            });
        };
    </script>
    <?php endif; ?>
</body>
</html>