<?php
// Inicia a sessão
session_start();

// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'fwm2024');
define('DB_USER', 'root');
define('DB_PASS', '');

// Conexão com o banco de dados
try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ATTR_ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::ATTR_DEFAULT_FETCH_MODE);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

// ID do time do usuário (pode vir da sessão ou ser fixo para exemplo)
$team_id = 1; // Time padrão

// Buscar jogadores do time principal
$query = "SELECT * FROM players WHERE team_id = :team_id AND is_youth = 0";
$stmt = $pdo->prepare($query);
$stmt->execute([':team_id' => $team_id]);
$players = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar jogadores da base/juventude
$query = "SELECT * FROM players WHERE team_id = :team_id AND is_youth = 1";
$stmt = $pdo->prepare($query);
$stmt->execute([':team_id' => $team_id]);
$youthPlayers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar jogadores disponíveis para transferência
$query = "SELECT * FROM players WHERE team_id != :team_id AND transfer_listed = 1";
$stmt = $pdo->prepare($query);
$stmt->execute([':team_id' => $team_id]);
$transferPlayers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar informações do time
$query = "SELECT * FROM teams WHERE id = :team_id";
$stmt = $pdo->prepare($query);
$stmt->execute([':team_id' => $team_id]);
$teamData = $stmt->fetch(PDO::FETCH_ASSOC);

// Buscar instalações do clube
$query = "SELECT * FROM facilities WHERE team_id = :team_id";
$stmt = $pdo->prepare($query);
$stmt->execute([':team_id' => $team_id]);
$facilitiesData = $stmt->fetch(PDO::FETCH_ASSOC);

// Buscar jogos/calendário
$query = "SELECT f.*, 
                 ht.name as home_team_name, 
                 at.name as away_team_name 
          FROM fixtures f
          JOIN teams ht ON f.home_team_id = ht.id
          JOIN teams at ON f.away_team_id = at.id
          WHERE home_team_id = :team_id OR away_team_id = :team_id 
          ORDER BY date ASC";
$stmt = $pdo->prepare($query);
$stmt->execute([':team_id' => $team_id]);
$fixtures = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar tabela da liga
$query = "SELECT t.name, 
                 lt.points, 
                 lt.wins, 
                 lt.draws, 
                 lt.losses, 
                 lt.goals_for, 
                 lt.goals_against 
          FROM league_teams lt
          JOIN teams t ON lt.team_id = t.id
          ORDER BY lt.points DESC, (lt.goals_for - lt.goals_against) DESC, lt.goals_for DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$leagueTeams = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inicializar notícias
$news = [
    "Bem-vindo ao Football Web Manager 2024!",
    "Temporada 2023/24 está prestes a começar."
];
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Web Manager 2024</title>
    <style>
        :root {
            --primary-color: #1a3e72;
            --secondary-color: #e74c3c;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --youth-color: #9b59b6;
            --facility-color: #3498db;
            --background-gradient: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: var(--background-gradient);
            color: #333;
            min-height: 100vh;
        }
        
        .container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 280px;
            background-color: var(--dark-color);
            color: white;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.2);
            position: relative;
            z-index: 10;
        }
        
        .sidebar h2 {
            color: white;
            margin-bottom: 30px;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
            background-color: rgba(255,255,255,0.9);
            position: relative;
        }
        
        .menu-item {
            padding: 12px 15px;
            margin: 8px 0;
            cursor: pointer;
            border-radius: 6px;
            transition: var(--transition);
            display: flex;
            align-items: center;
            font-size: 0.95rem;
        }
        
        .menu-item i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            font-size: 1rem;
        }
        
        .menu-item:hover {
            background-color: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        
        .menu-item.active {
            background-color: var(--primary-color);
            font-weight: 600;
            box-shadow: var(--card-shadow);
        }
        
        .team-info {
            background: linear-gradient(135deg, var(--primary-color), #2980b9);
            color: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: var(--card-shadow);
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary-color) 0%, #2980b9 100%);
        }
        
        .team-info::after {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }
        
        .team-info h2 {
            margin-top: 0;
            font-size: 1.8rem;
            position: relative;
            z-index: 1;
        }
        
        .team-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .stat-card {
            background-color: rgba(255,255,255,0.15);
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            backdrop-filter: blur(5px);
            transition: var(--transition);
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .stat-value {
            font-size: 28px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .squad {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
        }
        
        .player-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            border-left: 5px solid var(--primary-color);
            position: relative;
            cursor: pointer;
        }
        
        .player-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .youth-player {
            border-left-color: var(--youth-color);
        }
        
        .player-rating {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 36px;
            height: 36px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .youth-rating {
            background-color: var(--youth-color);
        }
        
        .player-potential {
            height: 6px;
            background-color: #eee;
            margin-top: 12px;
            border-radius: 3px;
            overflow: hidden;
        }
        
        .potential-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--success-color), #2ecc71);
            border-radius: 3px;
        }
        
        .fixture {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: white;
            padding: 15px 20px;
            margin-bottom: 15px;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }
        
        .fixture:hover {
            transform: translateX(5px);
        }
        
        .fixture-date {
            font-weight: bold;
            color: var(--primary-color);
            min-width: 80px;
        }
        
        .fixture-teams {
            flex-grow: 1;
            text-align: center;
            padding: 0 15px;
            font-weight: 500;
        }
        
        .fixture-result {
            font-weight: bold;
            padding: 5px 12px;
            border-radius: 20px;
            background-color: var(--primary-color);
            color: white;
            min-width: 60px;
            text-align: center;
        }
        
        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: var(--transition);
            margin: 5px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }
        
        .btn i {
            font-size: 0.9rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #0d2b57;
            transform: translateY(-2px);
        }
        
        .btn-success {
            background-color: var(--success-color);
            color: white;
        }
        
        .btn-success:hover {
            background-color: #219653;
            transform: translateY(-2px);
        }
        
        .btn-warning {
            background-color: var(--warning-color);
            color: white;
        }
        
        .btn-warning:hover {
            background-color: #e67e22;
            transform: translateY(-2px);
        }
        
        .btn-youth {
            background-color: var(--youth-color);
            color: white;
        }
        
        .btn-youth:hover {
            background-color: #8e44ad;
            transform: translateY(-2px);
        }
        
        .btn-facility {
            background-color: var(--facility-color);
            color: white;
        }
        
        .btn-facility:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(5px);
        }
        
        .modal-content {
            background-color: white;
            border-radius: 12px;
            width: 85%;
            max-width: 700px;
            max-height: 85vh;
            overflow-y: auto;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            animation: modalFadeIn 0.3s ease-out;
        }
        
        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .close-modal {
            float: right;
            font-size: 28px;
            cursor: pointer;
            color: #777;
            transition: var(--transition);
        }
        
        .close-modal:hover {
            color: #333;
            transform: rotate(90deg);
        }
        
        .transfer-list {
            margin-top: 20px;
        }
        
        .transfer-player {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
            transition: var(--transition);
        }
        
        .transfer-player:hover {
            background-color: #f9f9f9;
        }
        
        .progress-bar {
            height: 10px;
            background-color: #eee;
            border-radius: 5px;
            margin-top: 8px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), #3498db);
            width: 0%;
            transition: width 0.5s;
        }
        
        .match-simulation {
            text-align: center;
            margin: 20px 0;
        }
        
        .match-score {
            font-size: 32px;
            font-weight: bold;
            margin: 20px 0;
            color: var(--primary-color);
        }
        
        .match-event {
            padding: 10px;
            margin: 8px 0;
            border-radius: 6px;
            background-color: #f8f9fa;
            transition: var(--transition);
        }
        
        .goal-event {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid var(--success-color);
        }
        
        .card-event {
            background-color: #fff3cd;
            color: #856404;
            border-left: 4px solid var(--warning-color);
        }
        
        .injury-event {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid var(--danger-color);
        }
        
        .tabs {
            display: flex;
            margin-bottom: 25px;
            border-bottom: 1px solid #ddd;
        }
        
        .tab {
            padding: 12px 25px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            font-weight: 500;
            transition: var(--transition);
            position: relative;
        }
        
        .tab:hover {
            background-color: #f5f5f5;
        }
        
        .tab.active {
            border-bottom-color: var(--primary-color);
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .tab-content.active {
            display: block;
        }
        
        .attribute-bar {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .attribute-name {
            width: 140px;
            font-weight: 500;
        }
        
        .attribute-value {
            flex-grow: 1;
        }
        
        .formation-pitch {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            padding: 30px;
            border-radius: 12px;
            margin-top: 25px;
            position: relative;
            min-height: 500px;
            box-shadow: var(--card-shadow);
        }
        
        .player-position {
            position: absolute;
            width: 50px;
            height: 50px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transition: var(--transition);
            z-index: 2;
        }
        
        .player-position:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 12px rgba(0,0,0,0.3);
        }
        
        .calendar-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .youth-players-container {
            margin-top: 35px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
        }
        
        .youth-player-tag {
            display: inline-block;
            background-color: var(--youth-color);
            color: white;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 0.75rem;
            margin-left: 8px;
            font-weight: 600;
        }
        
        .league-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 25px;
            box-shadow: var(--card-shadow);
            border-radius: 10px;
            overflow: hidden;
        }
        
        .league-table th, .league-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .league-table th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            position: sticky;
            top: 0;
        }
        
        .league-table tr:nth-child(even) {
            background-color: #f5f5f5;
        }
        
        .league-table tr:hover {
            background-color: #e6e6e6;
        }
        
        .league-table tr:last-child td {
            border-bottom: none;
        }
        
        .facilities-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
            margin-top: 25px;
        }
        
        .facility-card {
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--card-shadow);
            border-left: 5px solid var(--facility-color);
            transition: var(--transition);
        }
        
        .facility-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .facility-card h3 {
            margin-top: 0;
            color: var(--facility-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .facility-level {
            font-weight: bold;
            margin: 15px 0;
            color: #555;
        }
        
        .facility-benefits {
            margin: 15px 0;
            font-size: 0.95rem;
            color: #555;
        }
        
        .facility-benefits p {
            margin: 8px 0;
        }
        
        .facility-upgrade {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        
        .facility-upgrade-cost {
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .action-buttons {
            margin: 20px 0;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .screen {
            animation: fadeIn 0.3s ease-out;
        }
        
        .news-item {
            padding: 12px;
            border-bottom: 1px solid #eee;
            transition: var(--transition);
        }
        
        .news-item:hover {
            background-color: #f9f9f9;
        }
        
        .news-item:first-child {
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        
        .news-item:last-child {
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            border-bottom: none;
        }
        
        .finance-info {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        
        .finance-card {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: var(--card-shadow);
            text-align: center;
        }
        
        .finance-value {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 5px 0;
            color: var(--primary-color);
        }
        
        /* Responsive adjustments */
        @media (max-width: 1200px) {
            .container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                padding: 15px;
            }
            
            .main-content {
                padding: 20px;
            }
            
            .squad {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
            
            .facilities-container {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            }
        }
        
        @media (max-width: 768px) {
            .team-stats {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .tabs {
                flex-wrap: wrap;
            }
            
            .tab {
                padding: 10px 15px;
                flex: 1;
                text-align: center;
            }
            
            .formation-pitch {
                min-height: 400px;
                padding: 15px;
            }
            
            .player-position {
                width: 40px;
                height: 40px;
                font-size: 0.8rem;
            }
        }
        
        @media (max-width: 480px) {
            .team-stats {
                grid-template-columns: 1fr;
            }
            
            .squad {
                grid-template-columns: 1fr;
            }
            
            .facilities-container {
                grid-template-columns: 1fr;
            }
            
            .fixture {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            
            .fixture-date, .fixture-teams, .fixture-result {
                width: 100%;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2><i class="fas fa-futbol"></i> FWM 2024</h2>
            <div class="menu-item active" data-screen="team">
                <i class="fas fa-home"></i> Visão Geral
            </div>
            <div class="menu-item" data-screen="squad">
                <i class="fas fa-users"></i> Elenco
            </div>
            <div class="menu-item" data-screen="youth">
                <i class="fas fa-child"></i> Base/Juventude
            </div>
            <div class="menu-item" data-screen="fixtures">
                <i class="fas fa-calendar-alt"></i> Calendário
            </div>
            <div class="menu-item" data-screen="league">
                <i class="fas fa-trophy"></i> Liga e Tabelas
            </div>
            <div class="menu-item" data-screen="transfers">
                <i class="fas fa-exchange-alt"></i> Transferências
            </div>
            <div class="menu-item" data-screen="tactics">
                <i class="fas fa-chess-board"></i> Táticas
            </div>
            <div class="menu-item" data-screen="training">
                <i class="fas fa-running"></i> Treinamento
            </div>
            <div class="menu-item" data-screen="facilities">
                <i class="fas fa-building"></i> Instalações
            </div>
            
            <div style="margin-top: 30px; padding: 20px; background-color: rgba(0,0,0,0.2); border-radius: 10px;">
                <div><i class="fas fa-coins"></i> <span id="money">€<?= number_format($teamData['money'] / 1000000, 1) ?>M</span></div>
                <div><i class="fas fa-calendar-week"></i> Semana <span id="week">1</span></div>
                <div><i class="fas fa-trophy"></i> <span id="league-position"><?= $teamData['position'] ?>ª posição</span></div>
            </div>
        </div>
        
        <div class="main-content">
            <!-- Tela de Visão Geral -->
            <div id="team-screen" class="screen">
                <div class="team-info">
                    <h2 id="team-name"><?= $teamData['name'] ?></h2>
                    <div id="team-reputation">Reputação: <?= $teamData['reputation'] ?></div>
                    <div id="manager-name">Treinador: Você</div>
                    <div id="stadium">Estádio: <?= $teamData['stadium'] ?></div>
                    
                    <div class="team-stats">
                        <div class="stat-card">
                            <div>Classificação</div>
                            <div class="stat-value" id="team-position"><?= $teamData['position'] ?>º</div>
                        </div>
                        <div class="stat-card">
                            <div>Jogadores</div>
                            <div class="stat-value" id="team-players"><?= count($players) ?></div>
                        </div>
                        <div class="stat-card">
                            <div>Média de Idade</div>
                            <div class="stat-value" id="team-age">
                                <?= number_format(array_reduce($players, function($sum, $player) { return $sum + $player['age']; }, 0) / count($players), 1) ?>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div>Rating Médio</div>
                            <div class="stat-value" id="team-rating">
                                <?= number_format(array_reduce($players, function($sum, $player) { return $sum + $player['rating']; }, 0) / count($players), 1) ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3><i class="fas fa-calendar-day"></i> Próximo Jogo</h3>
                    <div id="next-match" class="fixture">
                        <?php 
                        $nextMatch = array_filter($fixtures, function($f) { return !$f['played']; })[0] ?? null;
                        if ($nextMatch): ?>
                            <div class="fixture-date"><?= $nextMatch['date'] ?></div>
                            <div class="fixture-teams">
                                <?= $nextMatch['home_team_id'] == $team_id ? $teamData['name'] : $nextMatch['away_team_name'] ?> 
                                vs 
                                <?= $nextMatch['home_team_id'] == $team_id ? $nextMatch['away_team_name'] : $teamData['name'] ?>
                            </div>
                            <div class="fixture-result">-:-</div>
                        <?php else: ?>
                            <div class="fixture-teams" style="grid-column: 1 / -1; text-align: center;">Temporada encerrada</div>
                        <?php endif; ?>
                    </div>
                    <div class="action-buttons">
                        <button class="btn btn-primary" id="simulate-btn" <?= !$nextMatch ? 'style="display:none;"' : '' ?>>
                            <i class="fas fa-play"></i> Simular Jogo
                        </button>
                        <button class="btn btn-warning" id="advance-week-btn">
                            <i class="fas fa-forward"></i> Avançar Semana
                        </button>
                    </div>
                </div>
                
                <div style="margin-top: 30px;">
                    <h3><i class="fas fa-newspaper"></i> Notícias</h3>
                    <div id="news-feed" style="background-color: white; border-radius: 8px; box-shadow: var(--card-shadow);">
                        <?php foreach($news as $item): ?>
                            <div class="news-item"><?= $item ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- Tela de Elenco -->
            <div id="squad-screen" class="screen" style="display: none;">
                <h2><i class="fas fa-users"></i> Elenco</h2>
                
                <div class="tabs">
                    <div class="tab active" data-tab="all">Todos</div>
                    <div class="tab" data-tab="gk">Goleiros</div>
                    <div class="tab" data-tab="df">Defensores</div>
                    <div class="tab" data-tab="mf">Meio-Campo</div>
                    <div class="tab" data-tab="fw">Atacantes</div>
                </div>
                
                <div class="squad" id="player-list">
                    <?php foreach($players as $player): 
                        $potentialPercentage = (($player['potential'] - $player['rating']) / (100 - $player['rating'])) * 100;
                    ?>
                        <div class="player-card" onclick="showPlayerModal(<?= $player['id'] ?>, false)">
                            <div class="player-rating"><?= $player['rating'] ?></div>
                            <h4><?= $player['name'] ?></h4>
                            <div><?= $positionNames[$player['position']] ?> | <?= $player['age'] ?> anos</div>
                            <div>Potencial: <?= $player['potential'] ?></div>
                            <div class="player-potential">
                                <div class="potential-fill" style="width: <?= $potentialPercentage ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Tela de Base/Juventude -->
            <div id="youth-screen" class="screen" style="display: none;">
                <h2><i class="fas fa-child"></i> Base/Juventude</h2>
                
                <div class="youth-players-container">
                    <h3>Novos Talentos da Base</h3>
                    <p>Todo mês, novos jogadores surgem da sua base. Avalie-os e promova os melhores para o time principal!</p>
                    
                    <div id="youth-player-list" class="squad">
                        <?php if(empty($youthPlayers)): ?>
                            <p style="grid-column: 1 / -1; text-align: center; color: #777;">Nenhum jogador na base no momento. Gere novos jogadores no próximo mês.</p>
                        <?php else: 
                            foreach($youthPlayers as $player): 
                                $potentialPercentage = (($player['potential'] - $player['rating']) / (100 - $player['rating'])) * 100;
                        ?>
                                <div class="player-card youth-player" onclick="showPlayerModal(<?= $player['id'] ?>, false, true)">
                                    <div class="player-rating youth-rating"><?= $player['rating'] ?></div>
                                    <h4><?= $player['name'] ?> <span class="youth-player-tag">Base</span></h4>
                                    <div><?= $positionNames[$player['position']] ?> | <?= $player['age'] ?> anos</div>
                                    <div>Potencial: <?= $player['potential'] ?></div>
                                    <div class="player-potential">
                                        <div class="potential-fill" style="width: <?= $potentialPercentage ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <div class="action-buttons">
                        <button class="btn btn-youth" id="generate-youth-btn">
                            <i class="fas fa-plus"></i> Gerar Novos Jogadores (Próximo Mês)
                        </button>
                        <button class="btn btn-primary" id="improve-youth-btn">
                            <i class="fas fa-arrow-up"></i> Melhorar Base (€5M)
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Tela de Calendário -->
            <div id="fixtures-screen" class="screen" style="display: none;">
                <h2><i class="fas fa-calendar-alt"></i> Calendário</h2>
                
                <div class="calendar-controls">
                    <div class="action-buttons">
                        <button class="btn btn-primary" id="simulate-next-btn">
                            <i class="fas fa-play"></i> Simular Próximo Jogo
                        </button>
                        <button class="btn btn-warning" id="advance-week-btn-2">
                            <i class="fas fa-forward"></i> Avançar Semana
                        </button>
                    </div>
                    <div>
                        <span id="current-month" style="font-weight: 600; color: var(--primary-color);">Agosto 2023</span>
                    </div>
                </div>
                
                <div id="fixtures-list">
                    <?php foreach($fixtures as $fixture): ?>
                        <div class="fixture">
                            <div class="fixture-date"><?= $fixture['date'] ?></div>
                            <div class="fixture-teams">
                                <?= $fixture['home_team_name'] ?> <?= $fixture['played'] ? $fixture['home_goals'] : '' ?> - 
                                <?= $fixture['played'] ? $fixture['away_goals'] : '' ?> <?= $fixture['away_team_name'] ?>
                            </div>
                            <div class="fixture-result" style="background-color: <?= 
                                $fixture['played'] ? 
                                    ($fixture['home_team_id'] == $team_id ? 
                                        ($fixture['home_goals'] > $fixture['away_goals'] ? 'var(--success-color)' : 
                                         ($fixture['home_goals'] < $fixture['away_goals'] ? 'var(--danger-color)' : 'var(--warning-color)')) : 
                                        ($fixture['away_goals'] > $fixture['home_goals'] ? 'var(--success-color)' : 
                                         ($fixture['away_goals'] < $fixture['home_goals'] ? 'var(--danger-color)' : 'var(--warning-color)'))) : 
                                    'var(--primary-color)' ?>">
                                <?= $fixture['played'] ? "{$fixture['home_goals']}-{$fixture['away_goals']}" : '-:-' ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Tela de Liga e Tabelas -->
            <div id="league-screen" class="screen" style="display: none;">
                <h2><i class="fas fa-trophy"></i> Liga e Tabelas</h2>
                
                <div class="tabs">
                    <div class="tab active" data-tab="league">Classificação</div>
                    <div class="tab" data-tab="stats">Estatísticas</div>
                </div>
                
                <div class="tab-content active" id="league-table-content">
                    <h3>Classificação da Liga</h3>
                    <table class="league-table" id="league-table">
                        <thead>
                            <tr>
                                <th>Pos</th>
                                <th>Time</th>
                                <th>J</th>
                                <th>V</th>
                                <th>E</th>
                                <th>D</th>
                                <th>GP</th>
                                <th>GC</th>
                                <th>SG</th>
                                <th>Pts</th>
                            </tr>
                        </thead>
                        <tbody id="league-table-body">
                            <?php foreach($leagueTeams as $index => $team): ?>
                                <tr <?= $team['name'] == $teamData['name'] ? 'style="font-weight:bold;background-color:#e6f7ff;"' : '' ?>>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= $team['name'] ?></td>
                                    <td><?= $team['wins'] + $team['draws'] + $team['losses'] ?></td>
                                    <td><?= $team['wins'] ?></td>
                                    <td><?= $team['draws'] ?></td>
                                    <td><?= $team['losses'] ?></td>
                                    <td><?= $team['goals_for'] ?></td>
                                    <td><?= $team['goals_against'] ?></td>
                                    <td><?= $team['goals_for'] - $team['goals_against'] ?></td>
                                    <td><?= $team['points'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="tab-content" id="league-stats-content">
                    <h3>Estatísticas da Liga</h3>
                    <div id="league-stats">
                        <p>Estatísticas serão exibidas aqui.</p>
                    </div>
                </div>
            </div>
            
            <!-- Tela de Transferências -->
            <div id="transfers-screen" class="screen" style="display: none;">
                <h2><i class="fas fa-exchange-alt"></i> Transferências</h2>
                
                <div class="tabs">
                    <div class="tab active" data-tab="search">Procurar Jogadores</div>
                    <div class="tab" data-tab="offers">Ofertas Recebidas</div>
                    <div class="tab" data-tab="shortlist">Lista de Interesses</div>
                </div>
                
                <div class="tab-content active" id="transfer-search">
                    <div style="margin: 20px 0;">
                        <input type="text" id="player-search" placeholder="Procurar jogador..." style="padding: 10px; width: 300px; border: 1px solid #ddd; border-radius: 6px; font-size: 1rem;">
                        <button class="btn btn-primary" id="search-btn">
                            <i class="fas fa-search"></i> Procurar
                        </button>
                    </div>
                    
                    <div class="transfer-list" id="transfer-player-list">
                        <?php if(empty($transferPlayers)): ?>
                            <p style="text-align: center; color: #777;">Nenhum jogador disponível para transferência no momento.</p>
                        <?php else: 
                            foreach($transferPlayers as $player): ?>
                                <div class="transfer-player">
                                    <div>
                                        <strong><?= $player['name'] ?></strong> (<?= $player['age'] ?> anos) - <?= $positionNames[$player['position']] ?><br>
                                        <?= $player['club'] ?> | Rating: <?= $player['rating'] ?> | Potencial: <?= $player['potential'] ?>
                                    </div>
                                    <div style="text-align: right;">
                                        <strong>€<?= number_format($player['value'] / 1000000, 1) ?>M</strong><br>
                                        <button class="btn btn-primary view-player-btn" onclick="showPlayerModal(<?= $player['id'] ?>, true)">
                                            <i class="fas fa-eye"></i> Detalhes
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="tab-content" id="transfer-offers">
                    <p>Nenhuma oferta recebida no momento.</p>
                </div>
                
                <div class="tab-content" id="transfer-shortlist">
                    <p>Nenhum jogador na lista de interesses.</p>
                </div>
            </div>
            
            <!-- Tela de Táticas -->
            <div id="tactics-screen" class="screen" style="display: none;">
                <h2><i class="fas fa-chess-board"></i> Táticas</h2>
                
                <div>
                    <label for="formation-select" style="font-weight: 500; margin-right: 10px;">Formação:</label>
                    <select id="formation-select" style="padding: 8px; border-radius: 6px; border: 1px solid #ddd; font-size: 1rem;">
                        <option value="4-4-2" <?= $teamData['formation'] == '4-4-2' ? 'selected' : '' ?>>4-4-2</option>
                        <option value="4-3-3" <?= $teamData['formation'] == '4-3-3' ? 'selected' : '' ?>>4-3-3</option>
                        <option value="4-2-3-1" <?= $teamData['formation'] == '4-2-3-1' ? 'selected' : '' ?>>4-2-3-1</option>
                        <option value="3-5-2" <?= $teamData['formation'] == '3-5-2' ? 'selected' : '' ?>>3-5-2</option>
                        <option value="5-3-2" <?= $teamData['formation'] == '5-3-2' ? 'selected' : '' ?>>5-3-2</option>
                    </select>
                    <button class="btn btn-primary" id="change-formation-btn">
                        Aplicar Formação
                    </button>
                </div>
                
                <div class="formation-pitch" id="formation-pitch">
                    <!-- Campo de futebol com posicionamento dos jogadores -->
                    <?php
                    $gk = array_filter($players, function($p) { return $p['position'] == 'GK'; });
                    usort($gk, function($a, $b) { return $b['rating'] - $a['rating']; });
                    $gk = $gk[0] ?? null;
                    
                    $defenders = array_filter($players, function($p) { return in_array($p['position'], ['CB', 'RB', 'LB']); });
                    usort($defenders, function($a, $b) { return $b['rating'] - $a['rating']; });
                    
                    $midfielders = array_filter($players, function($p) { return in_array($p['position'], ['CM', 'RM', 'LM']); });
                    usort($midfielders, function($a, $b) { return $b['rating'] - $a['rating']; });
                    
                    $attackers = array_filter($players, function($p) { return $p['position'] == 'ST'; });
                    usort($attackers, function($a, $b) { return $b['rating'] - $a['rating']; });
                    
                    $formation = explode('-', $teamData['formation']);
                    $defCount = (int)$formation[0];
                    $midCount = (int)$formation[1];
                    $attCount = isset($formation[2]) ? (int)$formation[2] : 0;
                    ?>
                    
                    <!-- Linhas do campo -->
                    <div style="position: absolute; width: 80%; height: 60%; border: 2px solid rgba(255,255,255,0.8); top: 20%; left: 10%;"></div>
                    <div style="position: absolute; width: 40%; height: 30%; border: 2px solid rgba(255,255,255,0.8); top: 35%; left: 30%;"></div>
                    <div style="position: absolute; width: 10%; height: 30%; border: 2px solid rgba(255,255,255,0.8); top: 35%; left: 5%;"></div>
                    <div style="position: absolute; width: 10%; height: 30%; border: 2px solid rgba(255,255,255,0.8); top: 35%; right: 5%;"></div>
                    <div style="position: absolute; width: 2px; height: 60%; background: rgba(255,255,255,0.8); top: 20%; left: 50%;"></div>
                    <div style="position: absolute; width: 10px; height: 10px; background: rgba(255,255,255,0.8); border-radius: 50%; top: 50%; left: 50%; transform: translate(-50%, -50%);"></div>
                    
                    <!-- Goleiro -->
                    <?php if($gk): ?>
                        <div class="player-position" style="left: 10%; top: 50%;" title="<?= $gk['name'] ?> (<?= $positionNames[$gk['position']] ?>)">
                            <?= $gk['rating'] ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Defensores -->
                    <?php 
                    $defenders = array_slice($defenders, 0, $defCount);
                    foreach($defenders as $i => $player): 
                        $left = 0;
                        $top = 0;
                        
                        if($defCount == 4) {
                            if($player['position'] == 'CB') {
                                $left = 40 + ($i % 2) * 10;
                                $top = 30;
                            } else if($player['position'] == 'RB') {
                                $left = 60;
                                $top = 20;
                            } else if($player['position'] == 'LB') {
                                $left = 20;
                                $top = 20;
                            }
                        } else if($defCount == 3 || $defCount == 5) {
                            $left = 30 + ($i % $defCount) * 20;
                            $top = 25;
                        }
                    ?>
                        <div class="player-position" style="left: <?= $left ?>%; top: <?= $top ?>%;" title="<?= $player['name'] ?> (<?= $positionNames[$player['position']] ?>)">
                            <?= $player['rating'] ?>
                        </div>
                    <?php endforeach; ?>
                    
                    <!-- Meio-campistas -->
                    <?php 
                    $midfielders = array_slice($midfielders, 0, $midCount);
                    foreach($midfielders as $i => $player): 
                        $left = 0;
                        $top = 0;
                        
                        if($midCount == 4) {
                            if($player['position'] == 'CM') {
                                $left = 40 + ($i % 2) * 10;
                                $top = 50;
                            } else if($player['position'] == 'RM') {
                                $left = 70;
                                $top = 40;
                            } else if($player['position'] == 'LM') {
                                $left = 30;
                                $top = 40;
                            }
                        } else if($midCount == 3 || $midCount == 2) {
                            $left = 35 + ($i % $midCount) * 15;
                            $top = 50;
                        }
                    ?>
                        <div class="player-position" style="left: <?= $left ?>%; top: <?= $top ?>%;" title="<?= $player['name'] ?> (<?= $positionNames[$player['position']] ?>)">
                            <?= $player['rating'] ?>
                        </div>
                    <?php endforeach; ?>
                    
                    <!-- Atacantes -->
                    <?php 
                    $attackers = array_slice($attackers, 0, $attCount);
                    foreach($attackers as $i => $player): 
                        $left = 0;
                        $top = 0;
                        
                        if($attCount == 2) {
                            $left = 35 + ($i % $attCount) * 30;
                            $top = 75;
                        } else if($attCount == 1) {
                            $left = 50;
                            $top = 75;
                        } else if($attCount == 3) {
                            if($i == 0) {
                                $left = 50;
                                $top = 75;
                            } else {
                                $left = $i == 1 ? 30 : 70;
                                $top = 65;
                            }
                        }
                    ?>
                        <div class="player-position" style="left: <?= $left ?>%; top: <?= $top ?>%;" title="<?= $player['name'] ?> (<?= $positionNames[$player['position']] ?>)">
                            <?= $player['rating'] ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div style="margin-top: 20px;">
                    <h3>Estilo de Jogo</h3>
                    <div class="attribute-bar">
                        <div class="attribute-name">Pressão</div>
                        <div class="attribute-value">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 60%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="attribute-bar">
                        <div class="attribute-name">Linha Defensiva</div>
                        <div class="attribute-value">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 50%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="attribute-bar">
                        <div class="attribute-name">Ritmo de Jogo</div>
                        <div class="attribute-value">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 70%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tela de Treinamento -->
            <div id="training-screen" class="screen" style="display: none;">
                <h2><i class="fas fa-running"></i> Treinamento</h2>
                
                <div style="margin: 20px 0;">
                    <h3>Programa de Treino Semanal</h3>
                    <div class="attribute-bar">
                        <div class="attribute-name">Intensidade</div>
                        <div class="attribute-value">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 50%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 15px; margin-top: 20px;">
                        <div class="stat-card">
                            <div style="font-weight: 600;">Segunda</div>
                            <div>Defesa</div>
                        </div>
                        <div class="stat-card">
                            <div style="font-weight: 600;">Terça</div>
                            <div>Ataque</div>
                        </div>
                        <div class="stat-card">
                            <div style="font-weight: 600;">Quarta</div>
                            <div>Físico</div>
                        </div>
                        <div class="stat-card">
                            <div style="font-weight: 600;">Quinta</div>
                            <div>Tático</div>
                        </div>
                        <div class="stat-card">
                            <div style="font-weight: 600;">Sexta</div>
                            <div>Finalização</div>
                        </div>
                        <div class="stat-card">
                            <div style="font-weight: 600;">Sábado</div>
                            <div>Descanso</div>
                        </div>
                        <div class="stat-card">
                            <div style="font-weight: 600;">Domingo</div>
                            <div>Jogo</div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3>Desenvolvimento de Jogadores</h3>
                    <div id="player-development">
                        <!-- Progresso dos jogadores será mostrado aqui -->
                    </div>
                </div>
            </div>
            
            <!-- Tela de Instalações -->
            <div id="facilities-screen" class="screen" style="display: none;">
                <h2><i class="fas fa-building"></i> Instalações do Clube</h2>
                
                <div class="facilities-container">
                    <div class="facility-card">
                        <h3><i class="fas fa-home"></i> Estádio</h3>
                        <div class="facility-level">Nível: <span id="stadium-level"><?= $facilitiesData['stadium_level'] ?></span></div>
                        <div class="facility-benefits">
                            <p>Capacidade: <span id="stadium-capacity"><?= number_format($facilitiesData['stadium_capacity']) ?></span> torcedores</p>
                            <p>Receita por jogo: €<span id="stadium-income"><?= number_format($facilitiesData['stadium_income']) ?></span></p>
                        </div>
                        <div class="facility-upgrade">
                            <div class="facility-upgrade-cost">Custo: €<span id="stadium-upgrade-cost"><?= number_format($facilitiesData['stadium_level'] * 5000000) ?></span></div>
                            <button class="btn btn-facility" id="upgrade-stadium-btn">
                                <i class="fas fa-arrow-up"></i> Melhorar
                            </button>
                        </div>
                    </div>
                    
                    <div class="facility-card">
                        <h3><i class="fas fa-child"></i> Centro de Treinamento</h3>
                        <div class="facility-level">Nível: <span id="training-level"><?= $facilitiesData['training_level'] ?></span></div>
                        <div class="facility-benefits">
                            <p>Bônus de treinamento: +<span id="training-bonus"><?= $facilitiesData['training_bonus'] ?></span>%</p>
                            <p>Recuperação de lesões: +<span id="recovery-bonus"><?= $facilitiesData['training_bonus'] ?></span>%</p>
                        </div>
                        <div class="facility-upgrade">
                            <div class="facility-upgrade-cost">Custo: €<span id="training-upgrade-cost"><?= number_format($facilitiesData['training_level'] * 3000000) ?></span></div>
                            <button class="btn btn-facility" id="upgrade-training-btn">
                                <i class="fas fa-arrow-up"></i> Melhorar
                            </button>
                        </div>
                    </div>
                    
                    <div class="facility-card">
                        <h3><i class="fas fa-graduation-cap"></i> Centro de Juventude</h3>
                        <div class="facility-level">Nível: <span id="youth-level"><?= $facilitiesData['youth_level'] ?></span></div>
                        <div class="facility-benefits">
                            <p>Qualidade dos jovens: +<span id="youth-quality"><?= $facilitiesData['youth_quality'] ?></span>%</p>
                            <p>Jogadores por mês: <span id="youth-players"><?= max(3, $facilitiesData['youth_level']) ?>-<?= max(5, $facilitiesData['youth_level'] + 2) ?></span></p>
                        </div>
                        <div class="facility-upgrade">
                            <div class="facility-upgrade-cost">Custo: €<span id="youth-upgrade-cost"><?= number_format($facilitiesData['youth_level'] * 2500000) ?></span></div>
                            <button class="btn btn-facility" id="upgrade-youth-btn">
                                <i class="fas fa-arrow-up"></i> Melhorar
                            </button>
                        </div>
                    </div>
                    
                    <div class="facility-card">
                        <h3><i class="fas fa-hospital"></i> Centro Médico</h3>
                        <div class="facility-level">Nível: <span id="medical-level"><?= $facilitiesData['medical_level'] ?></span></div>
                        <div class="facility-benefits">
                            <p>Redução de lesões: +<span id="injury-reduction"><?= $facilitiesData['medical_level'] * 5 ?></span>%</p>
                            <p>Tempo de recuperação: -<span id="recovery-time"><?= $facilitiesData['medical_level'] * 5 ?></span>%</p>
                        </div>
                        <div class="facility-upgrade">
                            <div class="facility-upgrade-cost">Custo: €<span id="medical-upgrade-cost"><?= number_format($facilitiesData['medical_level'] * 2000000) ?></span></div>
                            <button class="btn btn-facility" id="upgrade-medical-btn">
                                <i class="fas fa-arrow-up"></i> Melhorar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para detalhes do jogador -->
    <div class="modal" id="player-modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2 id="modal-player-name">Jogador</h2>
            
            <div style="display: flex; margin-top: 20px; gap: 20px; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 200px;">
                    <div><strong>Idade:</strong> <span id="modal-player-age">-</span></div>
                    <div><strong>Posição:</strong> <span id="modal-player-position">-</span></div>
                    <div><strong>Nacionalidade:</strong> <span id="modal-player-nationality">-</span></div>
                    <div><strong>Valor:</strong> <span id="modal-player-value">-</span></div>
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <div><strong>Rating:</strong> <span id="modal-player-rating">-</span></div>
                    <div><strong>Potencial:</strong> <span id="modal-player-potential">-</span></div>
                    <div><strong>Contrato até:</strong> <span id="modal-player-contract">-</span></div>
                    <div><strong>Salário:</strong> <span id="modal-player-salary">-</span></div>
                </div>
            </div>
            
            <div style="margin-top: 25px;">
                <h3>Atributos</h3>
                <div id="player-attributes" style="margin-top: 15px;">
                    <!-- Atributos serão adicionados via JavaScript -->
                </div>
            </div>
            
            <div style="margin-top: 25px; display: flex; justify-content: flex-end; gap: 10px; flex-wrap: wrap;">
                <button class="btn btn-primary" id="modal-transfer-btn" style="display: none;">
                    <i class="fas fa-euro-sign"></i> Fazer Oferta
                </button>
                <button class="btn btn-success" id="modal-train-btn" style="display: none;">
                    <i class="fas fa-running"></i> Treinar
                </button>
                <button class="btn btn-youth" id="modal-promote-btn" style="display: none;">
                    <i class="fas fa-arrow-up"></i> Promover para o Time
                </button>
            </div>
        </div>
    </div>

    <!-- Modal para simulação de jogo -->
    <div class="modal" id="match-modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2 id="match-modal-title">Simulação de Jogo</h2>
            
            <div class="match-simulation">
                <div style="font-size: 20px; margin-bottom: 15px; font-weight: 500;" id="match-teams">Time A vs Time B</div>
                <div class="match-score" id="match-score">0 - 0</div>
                <div style="margin-bottom: 25px; font-size: 1.1rem;" id="match-minute">0'</div>
                
                <div id="match-events" style="max-height: 300px; overflow-y: auto; padding-right: 10px;">
                    <!-- Eventos do jogo serão adicionados aqui -->
                </div>
                
                <button class="btn btn-primary" id="continue-btn" style="margin-top: 25px; padding: 12px 25px;">
                    <i class="fas fa-forward"></i> Continuar
                </button>
            </div>
        </div>
    </div>

    <script>
        // Nomes para geração de jogadores da base (mantido)
        const firstNames = ['João', 'Pedro', 'Miguel', 'Carlos', 'André', 'Ricardo', 'Bruno', 'Luís', 'Hugo', 'Rui', 
                          'Diogo', 'Tiago', 'Gonçalo', 'Francisco', 'Rafael', 'Daniel', 'Sérgio', 'Paulo', 'Nuno', 'Vasco'];
        const lastNames = ['Silva', 'Santos', 'Oliveira', 'Costa', 'Fernandes', 'Pereira', 'Alves', 'Gomes', 'Martins', 
                         'Ribeiro', 'Carvalho', 'Lopes', 'Marques', 'Teixeira', 'Rodrigues', 'Ferreira', 'Neves', 
                         'Coelho', 'Figueiredo', 'Mendes'];
        const nationalities = ['Portugal', 'Brasil', 'Espanha', 'Argentina', 'França', 'Alemanha', 'Itália', 'Holanda', 
                             'Inglaterra', 'Colômbia', 'Uruguai', 'México'];
        const positions = ['GK', 'CB', 'RB', 'LB', 'CM', 'RM', 'LM', 'ST'];
        const positionNames = {
            'GK': 'Goleiro',
            'CB': 'Zagueiro',
            'RB': 'Lateral Direito',
            'LB': 'Lateral Esquerdo',
            'CM': 'Meio-Campista',
            'RM': 'Ponta Direita',
            'LM': 'Ponta Esquerda',
            'ST': 'Atacante'
        };
        const months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

        // Dados do jogo vindo do PHP
        let gameData = {
            team: <?= json_encode($teamData) ?>,
            facilities: <?= json_encode($facilitiesData) ?>,
            players: <?= json_encode($players) ?>,
            youthPlayers: <?= json_encode($youthPlayers) ?>,
            transferPlayers: <?= json_encode($transferPlayers) ?>,
            fixtures: <?= json_encode($fixtures) ?>,
            currentWeek: 1,
            currentMonth: 7, // Agosto (0-11)
            currentYear: 2023,
            league: <?= json_encode($leagueTeams) ?>,
            news: <?= json_encode($news) ?>,
            lastYouthGeneration: 0 // Semana da última geração de jogadores da base
        };

        // Variáveis de estado do jogo
        let currentScreen = 'team';
        let currentSquadTab = 'all';
        let currentTransferTab = 'search';
        let currentLeagueTab = 'league';
        let matchSimulation = null;
        let matchEvents = [];
        let matchCurrentMinute = 0;
        let leagueTableVisible = false;

        // Gera jogadores aleatórios para a base/juventude
        function generateYouthPlayers() {
            const numPlayers = 3 + Math.floor(Math.random() * 3); // 3-5 jogadores
            const newYouthPlayers = [];
            
            // Calcula qualidade baseada no nível da base
            const baseQuality = gameData.facilities.youth_level * 5 + Math.floor(Math.random() * 10);
            
            for (let i = 0; i < numPlayers; i++) {
                const firstName = firstNames[Math.floor(Math.random() * firstNames.length)];
                const lastName = lastNames[Math.floor(Math.random() * lastNames.length)];
                const position = positions[Math.floor(Math.random() * positions.length)];
                const nationality = nationalities[Math.floor(Math.random() * nationalities.length)];
                
                const rating = Math.max(40, Math.min(70, baseQuality + Math.floor(Math.random() * 10) - 5));
                const potential = Math.max(rating + 5, Math.min(90, rating + 10 + Math.floor(Math.random() * 15)));
                
                const player = {
                    id: Date.now() + i,
                    name: `${firstName} ${lastName}`,
                    position: position,
                    age: 16 + Math.floor(Math.random() * 3), // 16-18 anos
                    rating: rating,
                    potential: potential,
                    nationality: nationality,
                    value: rating * 50000,
                    salary: rating * 100,
                    contract: "2026",
                    isYouth: true,
                    attributes: generateAttributes(position, rating)
                };
                
                newYouthPlayers.push(player);
            }
            
            // Adiciona os novos jogadores ao array existente
            gameData.youthPlayers = gameData.youthPlayers.concat(newYouthPlayers);
            gameData.lastYouthGeneration = gameData.currentWeek;
            addNews(`Novos jogadores surgiram na base! Avalie os talentos jovens.`);
            renderYouthPlayers();
            
            // Aqui você pode adicionar código para salvar no banco de dados
            // Exemplo com AJAX:
            /*
            fetch('save_youth_players.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ players: newYouthPlayers, team_id: <?= $team_id ?> })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Jogadores da base salvos:', data);
            });
            */
        }

        // Gera atributos para um jogador baseado na posição
        function generateAttributes(position, rating) {
            const attributes = {};
            const variation = 10;
            
            switch(position) {
                case 'GK':
                    attributes.reflexos = rating + Math.floor(Math.random() * variation) - variation/2;
                    attributes.agilidade = rating + Math.floor(Math.random() * variation) - variation/2;
                    attributes.posicionamento = rating + Math.floor(Math.random() * variation) - variation/2;
                    attributes.saída = rating + Math.floor(Math.random() * variation) - variation/2;
                    attributes.passe = rating + Math.floor(Math.random() * variation) - variation/2;
                    break;
                case 'CB':
                    attributes.marcação = rating + Math.floor(Math.random() * variation) - variation/2;
                    attributes.desarme = rating + Math.floor(Math.random() * variation) - variation/2;
                    attributes.força = rating + Math.floor(Math.random() * variation) - variation/2;
                    attributes.cabeceio = rating + Math.floor(Math.random() * variation) - variation/2;
                    attributes.velocidade = rating + Math.floor(Math.random() * variation) - variation/2;
                    break;
                case 'RB':
                case 'LB':
                    attributes.marcação = rating + Math.floor(Math.random() * variation) - variation/2;
                    attributes.desarme = rating + Math.floor(Math.random() * variation) - variation/2;
                    attributes.cruzamento = rating + Math.floor(Math.random() * variation) - variation/2;
                    attributes.velocidade = rating + Math.floor(Math.random() * variation) - variation/2;
                    attributes.resistência = rating + Math.floor(Math.random() * variation) - variation/2;
                    break;
                case 'CM':
                    attributes.passe = rating + Math.floor(Math.random() * variation) - variation/2;
                    attributes.visão = rating + Math.floor(Math.random() * variation) - variation/2;
                    attributes.finalização = rating + Math.floor(Math.random() * variation) - variation/2;
                    attributes.dribles = rating + Math.floor(Math.random() * variation) - variation/2;
                    attributes.resistência = rating + Math.floor(Math.random() * variation) - variation/2;
                    break;
                case 'RM':
                case 'LM':
                    attributes.cruzamento = rating + Math.floor(Math.random() * variation) - variation/2;
                    attributes.velocidade = rating + Math.floor(Math.random() * variation) - variation/2;
                    attributes.dribles = rating + Math.floor(Math.random() * variation) - variation/2;
                    attributes.finalização = rating + Math.floor(Math.random() * variation) - variation/2;
                    attributes.passe = rating + Math.floor(Math.random() * variation) - variation/2;
                    break;
                case 'ST':
                    attributes.finalização = rating + Math.floor(Math.random() * variation) - variation/2;
                    attributes.cabeceio = rating + Math.floor(Math.random() * variation) - variation/2;
                    attributes.velocidade = rating + Math.floor(Math.random() * variation) - variation/2;
                    attributes.força = rating + Math.floor(Math.random() * variation) - variation/2;
                    attributes.posicionamento = rating + Math.floor(Math.random() * variation) - variation/2;
                    break;
            }
            
            // Garante que os atributos estão entre 1 e 99
            for (const attr in attributes) {
                attributes[attr] = Math.max(1, Math.min(99, attributes[attr]));
            }
            
            return attributes;
        }

        // Inicialização do jogo
        function initGame() {
            // Configura event listeners
            setupEventListeners();
            
            // Inicializa dados do jogo
            updateTeamInfo();
            renderPlayers();
            renderYouthPlayers();
            renderFixtures();
            showNextMatch();
            renderFormation();
            renderLeagueTable();
            updateFacilitiesInfo();
            updateCurrentMonth();
        }

        // Configura todos os event listeners
        function setupEventListeners() {
            // Menu lateral
            document.querySelectorAll('.menu-item').forEach(item => {
                item.addEventListener('click', function() {
                    showScreen(this.dataset.screen);
                });
            });
            
            // Tabs do elenco
            document.querySelectorAll('#squad-screen .tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('#squad-screen .tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    currentSquadTab = this.dataset.tab;
                    renderPlayers(currentSquadTab);
                });
            });
            
            // Tabs de transferências
            document.querySelectorAll('#transfers-screen .tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('#transfers-screen .tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    currentTransferTab = this.dataset.tab;
                    
                    document.querySelectorAll('#transfers-screen .tab-content').forEach(c => c.classList.remove('active'));
                    document.getElementById(`transfer-${currentTransferTab}`).classList.add('active');
                    
                    if (currentTransferTab === 'search') {
                        renderTransferPlayers();
                    }
                });
            });
            
            // Tabs da liga
            document.querySelectorAll('#league-screen .tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('#league-screen .tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    currentLeagueTab = this.dataset.tab;
                    
                    document.querySelectorAll('#league-screen .tab-content').forEach(c => c.classList.remove('active'));
                    document.getElementById(`league-${currentLeagueTab}-content`).classList.add('active');
                    
                    leagueTableVisible = currentLeagueTab === 'league';
                });
            });
            
            // Botão de simulação
            document.getElementById('simulate-btn').addEventListener('click', simulateNextMatch);
            document.getElementById('simulate-next-btn').addEventListener('click', simulateNextMatch);
            
            // Botão de avançar semana
            document.getElementById('advance-week-btn').addEventListener('click', advanceWeek);
            document.getElementById('advance-week-btn-2').addEventListener('click', advanceWeek);
            
            // Botão de procurar jogadores
            document.getElementById('search-btn').addEventListener('click', searchPlayers);
            
            // Botão de mudar formação
            document.getElementById('change-formation-btn').addEventListener('click', changeFormation);
            
            // Botão de continuar simulação
            document.getElementById('continue-btn').addEventListener('click', continueSimulation);
            
            // Botão de gerar jogadores da base
            document.getElementById('generate-youth-btn').addEventListener('click', generateYouthPlayers);
            
            // Botão de melhorar base
            document.getElementById('improve-youth-btn').addEventListener('click', function() {
                upgradeFacility('youth');
            });
            
            // Botões de melhorar instalações
            document.getElementById('upgrade-stadium-btn').addEventListener('click', function() {
                upgradeFacility('stadium');
            });
            document.getElementById('upgrade-training-btn').addEventListener('click', function() {
                upgradeFacility('training');
            });
            document.getElementById('upgrade-youth-btn').addEventListener('click', function() {
                upgradeFacility('youth');
            });
            document.getElementById('upgrade-medical-btn').addEventListener('click', function() {
                upgradeFacility('medical');
            });
            
            // Botões de fechar modal
            document.querySelectorAll('.close-modal').forEach(btn => {
                btn.addEventListener('click', closeModal);
            });
        }

        // Atualiza informações do time
        function updateTeamInfo() {
            document.getElementById('money').textContent = `€${(gameData.team.money / 1000000).toFixed(1)}M`;
            document.getElementById('week').textContent = gameData.currentWeek;
            document.getElementById('league-position').textContent = `${gameData.team.position}ª posição`;
            document.getElementById('team-position').textContent = `${gameData.team.position}º`;
            document.getElementById('team-players').textContent = gameData.players.length;
            
            // Calcula média de idade e rating
            const avgAge = gameData.players.reduce((sum, player) => sum + player.age, 0) / gameData.players.length;
            const avgRating = gameData.players.reduce((sum, player) => sum + player.rating, 0) / gameData.players.length;
            
            document.getElementById('team-age').textContent = avgAge.toFixed(1);
            document.getElementById('team-rating').textContent = avgRating.toFixed(1);
        }

        // Atualiza informações das instalações
        function updateFacilitiesInfo() {
            // Estádio
            document.getElementById('stadium-level').textContent = gameData.facilities.stadium_level;
            document.getElementById('stadium-capacity').textContent = gameData.facilities.stadium_capacity.toLocaleString();
            document.getElementById('stadium-income').textContent = gameData.facilities.stadium_income.toLocaleString();
            document.getElementById('stadium-upgrade-cost').textContent = (gameData.facilities.stadium_level * 5000000 / 1000000).toFixed(1) + "M";
            
            // Centro de Treinamento
            document.getElementById('training-level').textContent = gameData.facilities.training_level;
            document.getElementById('training-bonus').textContent = gameData.facilities.training_bonus;
            document.getElementById('recovery-bonus').textContent = gameData.facilities.training_bonus;
            document.getElementById('training-upgrade-cost').textContent = (gameData.facilities.training_level * 3000000 / 1000000).toFixed(1) + "M";
            
            // Centro de Juventude
            document.getElementById('youth-level').textContent = gameData.facilities.youth_level;
            document.getElementById('youth-quality').textContent = gameData.facilities.youth_level * 5;
            document.getElementById('youth-players').textContent = `${Math.max(3, gameData.facilities.youth_level)}-${Math.max(5, gameData.facilities.youth_level + 2)}`;
            document.getElementById('youth-upgrade-cost').textContent = (gameData.facilities.youth_level * 2500000 / 1000000).toFixed(1) + "M";
            
            // Centro Médico
            document.getElementById('medical-level').textContent = gameData.facilities.medical_level;
            document.getElementById('injury-reduction').textContent = gameData.facilities.medical_level * 5;
            document.getElementById('recovery-time').textContent = gameData.facilities.medical_level * 5;
            document.getElementById('medical-upgrade-cost').textContent = (gameData.facilities.medical_level * 2000000 / 1000000).toFixed(1) + "M";
        }

        // Melhora uma instalação
        function upgradeFacility(facility) {
            const currentLevel = gameData.facilities[`${facility}_level`];
            const upgradeCost = currentLevel * (facility === 'stadium' ? 5000000 : 
                                              facility === 'training' ? 3000000 : 
                                              facility === 'youth' ? 2500000 : 2000000);
            
            if (gameData.team.money >= upgradeCost) {
                gameData.team.money -= upgradeCost;
                gameData.facilities[`${facility}_level`]++;
                
                // Atualiza benefícios
                switch(facility) {
                    case 'stadium':
                        gameData.facilities.stadium_capacity = Math.round(gameData.facilities.stadium_capacity * 1.5);
                        gameData.facilities.stadium_income = Math.round(gameData.facilities.stadium_income * 1.3);
                        addNews(`Estádio melhorado para nível ${gameData.facilities.stadium_level}! Capacidade aumentada.`);
                        break;
                    case 'training':
                        gameData.facilities.training_bonus += 5;
                        addNews(`Centro de Treinamento melhorado para nível ${gameData.facilities.training_level}! Bônus aumentado.`);
                        break;
                    case 'youth':
                        addNews(`Centro de Juventude melhorado para nível ${gameData.facilities.youth_level}!`);
                        break;
                    case 'medical':
                        addNews(`Centro Médico melhorado para nível ${gameData.facilities.medical_level}!`);
                        break;
                }
                
                updateTeamInfo();
                updateFacilitiesInfo();
                
                // Aqui você pode adicionar código para salvar no banco de dados
                /*
                fetch('upgrade_facility.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ 
                        facility: facility, 
                        level: gameData.facilities[`${facility}_level`],
                        team_id: <?= $team_id ?>,
                        money: gameData.team.money
                    })
                });
                */
            } else {
                alert('Dinheiro insuficiente para esta melhoria.');
            }
        }

        // Mostra jogadores no elenco com filtros
        function renderPlayers(filter = 'all') {
            const playerList = document.getElementById('player-list');
            playerList.innerHTML = '';
            
            let filteredPlayers = gameData.players;
            
            if (filter === 'gk') {
                filteredPlayers = gameData.players.filter(p => p.position === 'GK');
            } else if (filter === 'df') {
                filteredPlayers = gameData.players.filter(p => ['CB', 'RB', 'LB'].includes(p.position));
            } else if (filter === 'mf') {
                filteredPlayers = gameData.players.filter(p => ['CM', 'RM', 'LM'].includes(p.position));
            } else if (filter === 'fw') {
                filteredPlayers = gameData.players.filter(p => p.position === 'ST');
            }
            
            filteredPlayers.forEach(player => {
                const potentialPercentage = ((player.potential - player.rating) / (100 - player.rating)) * 100;
                
                const playerCard = document.createElement('div');
                playerCard.className = `player-card ${player.isYouth ? 'youth-player' : ''}`;
                playerCard.innerHTML = `
                    <div class="player-rating ${player.isYouth ? 'youth-rating' : ''}">${player.rating}</div>
                    <h4>${player.name} ${player.isYouth ? '<span class="youth-player-tag">Base</span>' : ''}</h4>
                    <div>${positionNames[player.position]} | ${player.age} anos</div>
                    <div>Potencial: ${player.potential}</div>
                    <div class="player-potential">
                        <div class="potential-fill" style="width: ${potentialPercentage}%"></div>
                    </div>
                `;
                playerCard.addEventListener('click', () => showPlayerModal(player.id, false));
                playerList.appendChild(playerCard);
            });
        }

        // Mostra jogadores da base/juventude
        function renderYouthPlayers() {
            const youthList = document.getElementById('youth-player-list');
            youthList.innerHTML = '';
            
            if (gameData.youthPlayers.length === 0) {
                youthList.innerHTML = '<p style="grid-column: 1 / -1; text-align: center; color: #777;">Nenhum jogador na base no momento. Gere novos jogadores no próximo mês.</p>';
                return;
            }
            
            gameData.youthPlayers.forEach(player => {
                const potentialPercentage = ((player.potential - player.rating) / (100 - player.rating)) * 100;
                
                const playerCard = document.createElement('div');
                playerCard.className = 'player-card youth-player';
                playerCard.innerHTML = `
                    <div class="player-rating youth-rating">${player.rating}</div>
                    <h4>${player.name} <span class="youth-player-tag">Base</span></h4>
                    <div>${positionNames[player.position]} | ${player.age} anos</div>
                    <div>Potencial: ${player.potential}</div>
                    <div class="player-potential">
                        <div class="potential-fill" style="width: ${potentialPercentage}%"></div>
                    </div>
                `;
                playerCard.addEventListener('click', () => showPlayerModal(player.id, false, true));
                youthList.appendChild(playerCard);
            });
        }

        // Mostra próximos jogos
        function renderFixtures() {
            const fixturesList = document.getElementById('fixtures-list');
            fixturesList.innerHTML = '';
            
            gameData.fixtures.forEach(fixture => {
                const fixtureElement = document.createElement('div');
                fixtureElement.className = 'fixture';
                
                if (fixture.played) {
                    const isHome = fixture.home_team_id == <?= $team_id ?>;
                    const homeGoals = fixture.home_goals;
                    const awayGoals = fixture.away_goals;
                    const isWin = isHome ? homeGoals > awayGoals : awayGoals > homeGoals;
                    const isDraw = homeGoals === awayGoals;
                    
                    fixtureElement.innerHTML = `
                        <div class="fixture-date">${fixture.date}</div>
                        <div class="fixture-teams">
                            ${isHome ? gameData.team.name : fixture.away_team_name} ${isHome ? homeGoals : awayGoals} - 
                            ${isHome ? awayGoals : homeGoals} ${isHome ? fixture.away_team_name : gameData.team.name}
                        </div>
                        <div class="fixture-result" style="background-color: ${isWin ? 'var(--success-color)' : isDraw ? 'var(--warning-color)' : 'var(--danger-color)'}">
                            ${homeGoals}-${awayGoals}
                        </div>
                    `;
                } else {
                    fixtureElement.innerHTML = `
                        <div class="fixture-date">${fixture.date}</div>
                        <div class="fixture-teams">
                            ${fixture.home_team_id == <?= $team_id ?> ? gameData.team.name : fixture.away_team_name} vs ${fixture.home_team_id == <?= $team_id ?> ? fixture.away_team_name : gameData.team.name}
                        </div>
                        <div class="fixture-result">-:-</div>
                    `;
                }
                
                fixturesList.appendChild(fixtureElement);
            });
        }

        // Mostra tabela da liga
        function renderLeagueTable() {
            const tableBody = document.getElementById('league-table-body');
            tableBody.innerHTML = '';
            
            // Ordena a liga por pontos, saldo de gols e gols pró
            gameData.league.sort((a, b) => {
                if (b.points !== a.points) return b.points - a.points;
                const bDiff = b.goals_for - b.goals_against;
                const aDiff = a.goals_for - a.goals_against;
                if (bDiff !== aDiff) return bDiff - aDiff;
                return b.goals_for - a.goals_for;
            });
            
            gameData.league.forEach((team, index) => {
                const row = document.createElement('tr');
                if (team.name === gameData.team.name) {
                    row.style.fontWeight = 'bold';
                    row.style.backgroundColor = '#e6f7ff';
                }
                
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${team.name}</td>
                    <td>${team.wins + team.draws + team.losses}</td>
                    <td>${team.wins}</td>
                    <td>${team.draws}</td>
                    <td>${team.losses}</td>
                    <td>${team.goals_for}</td>
                    <td>${team.goals_against}</td>
                    <td>${team.goals_for - team.goals_against}</td>
                    <td>${team.points}</td>
                `;
                
                tableBody.appendChild(row);
            });
            
            // Atualiza posição do time
            const teamIndex = gameData.league.findIndex(t => t.name === gameData.team.name);
            if (teamIndex >= 0) {
                gameData.team.position = teamIndex + 1;
                document.getElementById('league-position').textContent = `${gameData.team.position}ª posição`;
            }
        }

        // Mostra próximo jogo
        function showNextMatch() {
            const nextMatch = gameData.fixtures.find(f => !f.played);
            
            if (nextMatch) {
                const nextMatchElement = document.getElementById('next-match');
                nextMatchElement.innerHTML = `
                    <div class="fixture-date">${nextMatch.date}</div>
                    <div class="fixture-teams">
                        ${nextMatch.home_team_id == <?= $team_id ?> ? gameData.team.name : nextMatch.away_team_name} vs ${nextMatch.home_team_id == <?= $team_id ?> ? nextMatch.away_team_name : gameData.team.name}
                    </div>
                    <div class="fixture-result">-:-</div>
                `;
                
                document.getElementById('simulate-btn').style.display = 'inline-flex';
            } else {
                document.getElementById('next-match').innerHTML = `
                    <div class="fixture-teams" style="grid-column: 1 / -1; text-align: center;">Temporada encerrada</div>
                `;
                document.getElementById('simulate-btn').style.display = 'none';
            }
        }

        // Mostra jogadores disponíveis para transferência
        function renderTransferPlayers() {
            const playerList = document.getElementById('transfer-player-list');
            playerList.innerHTML = '';
            
            if (gameData.transferPlayers.length === 0) {
                playerList.innerHTML = '<p style="text-align: center; color: #777;">Nenhum jogador disponível para transferência no momento.</p>';
                return;
            }
            
            gameData.transferPlayers.forEach(player => {
                const playerElement = document.createElement('div');
                playerElement.className = 'transfer-player';
                playerElement.innerHTML = `
                    <div>
                        <strong>${player.name}</strong> (${player.age} anos) - ${positionNames[player.position]}<br>
                        ${player.club} | Rating: ${player.rating} | Potencial: ${player.potential}
                    </div>
                    <div style="text-align: right;">
                        <strong>€${(player.value / 1000000).toFixed(1)}M</strong><br>
                        <button class="btn btn-primary view-player-btn" data-id="${player.id}">
                            <i class="fas fa-eye"></i> Detalhes
                        </button>
                    </div>
                `;
                playerList.appendChild(playerElement);
            });
            
            // Adiciona event listeners aos botões de visualização
            document.querySelectorAll('.view-player-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    showPlayerModal(parseInt(this.dataset.id), true);
                });
            });
        }

        // Mostra formação tática
        function renderFormation() {
            const pitch = document.getElementById('formation-pitch');
            pitch.innerHTML = '';
            
            // Desenha linhas do campo
            pitch.innerHTML += `
                <div style="position: absolute; width: 80%; height: 60%; border: 2px solid rgba(255,255,255,0.8); top: 20%; left: 10%;"></div>
                <div style="position: absolute; width: 40%; height: 30%; border: 2px solid rgba(255,255,255,0.8); top: 35%; left: 30%;"></div>
                <div style="position: absolute; width: 10%; height: 30%; border: 2px solid rgba(255,255,255,0.8); top: 35%; left: 5%;"></div>
                <div style="position: absolute; width: 10%; height: 30%; border: 2px solid rgba(255,255,255,0.8); top: 35%; right: 5%;"></div>
                <div style="position: absolute; width: 2px; height: 60%; background: rgba(255,255,255,0.8); top: 20%; left: 50%;"></div>
                <div style="position: absolute; width: 10px; height: 10px; background: rgba(255,255,255,0.8); border-radius: 50%; top: 50%; left: 50%; transform: translate(-50%, -50%);"></div>
            `;
            
            // Posiciona jogadores de acordo com a formação
            const formation = gameData.team.formation.split('-');
            const defenders = parseInt(formation[0]);
            const midfielders = parseInt(formation[1]);
            const attackers = formation.length > 2 ? parseInt(formation[2]) : 0;
            
            // Goleiro
            const gk = gameData.players.filter(p => p.position === 'GK').sort((a, b) => b.rating - a.rating)[0];
            if (gk) {
                const playerPos = document.createElement('div');
                playerPos.className = 'player-position';
                playerPos.style.left = '10%';
                playerPos.style.top = '50%';
                playerPos.textContent = gk.rating;
                playerPos.title = `${gk.name} (${positionNames[gk.position]})`;
                pitch.appendChild(playerPos);
            }
            
            // Defensores
            const defendersList = gameData.players
                .filter(p => ['CB', 'RB', 'LB'].includes(p.position))
                .sort((a, b) => b.rating - a.rating)
                .slice(0, defenders);
                
            for (let i = 0; i < defendersList.length; i++) {
                const player = defendersList[i];
                const playerPos = document.createElement('div');
                playerPos.className = 'player-position';
                
                if (defenders === 4) {
                    // 4 defensores (2 centrais e laterais)
                    if (player.position === 'CB') {
                        playerPos.style.left = `${40 + (i % 2) * 10}%`;
                        playerPos.style.top = '30%';
                    } else if (player.position === 'RB') {
                        playerPos.style.left = '60%';
                        playerPos.style.top = '20%';
                    } else if (player.position === 'LB') {
                        playerPos.style.left = '20%';
                        playerPos.style.top = '20%';
                    }
                } else if (defenders === 3 || defenders === 5) {
                    // 3 ou 5 defensores (centrais)
                    playerPos.style.left = `${30 + (i % defenders) * 20}%`;
                    playerPos.style.top = '25%';
                }
                
                playerPos.textContent = player.rating;
                playerPos.title = `${player.name} (${positionNames[player.position]})`;
                pitch.appendChild(playerPos);
            }
            
            // Meio-campistas
            const midfieldersList = gameData.players
                .filter(p => ['CM', 'RM', 'LM'].includes(p.position))
                .sort((a, b) => b.rating - a.rating)
                .slice(0, midfielders);
                
            for (let i = 0; i < midfieldersList.length; i++) {
                const player = midfieldersList[i];
                const playerPos = document.createElement('div');
                playerPos.className = 'player-position';
                
                if (midfielders === 4) {
                    // 4 meio-campistas (centrais e alas)
                    if (player.position === 'CM') {
                        playerPos.style.left = `${40 + (i % 2) * 10}%`;
                        playerPos.style.top = '50%';
                    } else if (player.position === 'RM') {
                        playerPos.style.left = '70%';
                        playerPos.style.top = '40%';
                    } else if (player.position === 'LM') {
                        playerPos.style.left = '30%';
                        playerPos.style.top = '40%';
                    }
                } else if (midfielders === 3 || midfielders === 2) {
                    // 3 ou 2 meio-campistas (centrais)
                    playerPos.style.left = `${35 + (i % midfielders) * 15}%`;
                    playerPos.style.top = '50%';
                }
                
                playerPos.textContent = player.rating;
                playerPos.title = `${player.name} (${positionNames[player.position]})`;
                pitch.appendChild(playerPos);
            }
            
            // Atacantes
            const attackersList = gameData.players
                .filter(p => p.position === 'ST')
                .sort((a, b) => b.rating - a.rating)
                .slice(0, attackers);
                
            for (let i = 0; i < attackersList.length; i++) {
                const player = attackersList[i];
                const playerPos = document.createElement('div');
                playerPos.className = 'player-position';
                
                if (attackers === 2) {
                    // 2 atacantes
                    playerPos.style.left = `${35 + (i % attackers) * 30}%`;
                    playerPos.style.top = '75%';
                } else if (attackers === 1) {
                    // 1 atacante
                    playerPos.style.left = '50%';
                    playerPos.style.top = '75%';
                } else if (attackers === 3) {
                    // 3 atacantes (centro e pontas)
                    if (i === 0) {
                        playerPos.style.left = '50%';
                        playerPos.style.top = '75%';
                    } else {
                        playerPos.style.left = i === 1 ? '30%' : '70%';
                        playerPos.style.top = '65%';
                    }
                }
                
                playerPos.textContent = player.rating;
                playerPos.title = `${player.name} (${positionNames[player.position]})`;
                pitch.appendChild(playerPos);
            }
        }

        // Atualiza o mês atual
        function updateCurrentMonth() {
            const month = months[gameData.currentMonth];
            document.getElementById('current-month').textContent = `${month} ${gameData.currentYear}`;
        }

        // Avança uma semana no calendário
        function advanceWeek() {
            gameData.currentWeek++;
            
            // Verifica se mudou de mês (4 semanas por mês)
            if (gameData.currentWeek % 4 === 0) {
                gameData.currentMonth = (gameData.currentMonth + 1) % 12;
                if (gameData.currentMonth === 0) {
                    gameData.currentYear++;
                }
                updateCurrentMonth();
                
                // Gera novos jogadores da base a cada 2 meses
                if (gameData.currentMonth % 2 === 0 && gameData.currentWeek - gameData.lastYouthGeneration >= 8) {
                    generateYouthPlayers();
                    renderYouthPlayers();
                }
            }
            
            // Gera receita do estádio se houver jogo em casa
            const nextMatch = gameData.fixtures.find(f => !f.played);
            if (nextMatch && nextMatch.home_team_id == <?= $team_id ?>) {
                const income = gameData.facilities.stadium_income;
                gameData.team.money += income;
                addNews(`Receita do estádio: €${income.toLocaleString()}`);
            }
            
            updateTeamInfo();
            
            // Verifica se há jogos para simular
            if (!nextMatch) {
                addNews("Sem jogos marcados para esta semana.");
                return;
            }
            
            // Verifica se o próximo jogo é nesta semana
            const matchDate = nextMatch.date.split('/');
            const matchWeek = Math.floor((parseInt(matchDate[1]) - 8) * 4 + parseInt(matchDate[0]) / 7);
            if (gameData.currentWeek >= matchWeek) {
                addNews(`Próximo jogo: ${nextMatch.home_team_id == <?= $team_id ?> ? nextMatch.away_team_name : nextMatch.home_team_name} na próxima semana.`);
            } else {
                addNews("Sem jogos marcados para esta semana.");
            }
        }

        // Simula um jogo
        function simulateNextMatch() {
            const nextMatch = gameData.fixtures.find(f => !f.played);
            if (!nextMatch) return;
            
            // Verifica se o jogo é para esta semana
            const matchDate = nextMatch.date.split('/');
            const matchWeek = Math.floor((parseInt(matchDate[1]) - 8) * 4 + parseInt(matchDate[0]) / 7);
            if (gameData.currentWeek < matchWeek) {
                alert(`Este jogo está marcado para a semana ${matchWeek}. Avance no calendário primeiro.`);
                return;
            }
            
            // Prepara simulação
            matchSimulation = nextMatch;
            matchEvents = [];
            matchCurrentMinute = 0;
            
            // Mostra modal de simulação
            document.getElementById('match-modal-title').textContent = `${nextMatch.home_team_id == <?= $team_id ?> ? gameData.team.name : nextMatch.away_team_name} vs ${nextMatch.home_team_id == <?= $team_id ?> ? nextMatch.away_team_name : gameData.team.name}`;
            document.getElementById('match-teams').textContent = `${nextMatch.home_team_id == <?= $team_id ?> ? gameData.team.name : nextMatch.away_team_name} vs ${nextMatch.home_team_id == <?= $team_id ?> ? nextMatch.away_team_name : gameData.team.name}`;
            document.getElementById('match-score').textContent = '0 - 0';
            document.getElementById('match-minute').textContent = '0\'';
            document.getElementById('match-events').innerHTML = '';
            document.getElementById('continue-btn').textContent = 'Iniciar Simulação';
            
            openModal('match-modal');
        }

        // Continua a simulação do jogo
        function continueSimulation() {
            const btn = document.getElementById('continue-btn');
            
            if (matchCurrentMinute === 0) {
                btn.textContent = 'Continuar';
                matchCurrentMinute = 1;
                document.getElementById('match-minute').textContent = '1\'';
                return;
            }
            
            // Simula eventos no jogo
            if (matchCurrentMinute < 90) {
                matchCurrentMinute += Math.floor(Math.random() * 5) + 1;
                if (matchCurrentMinute > 90) matchCurrentMinute = 90;
                
                document.getElementById('match-minute').textContent = `${matchCurrentMinute}'`;
                
                // Chance de evento acontecer
                if (Math.random() < 0.3) {
                    const eventTypes = ['goal', 'yellow-card', 'red-card', 'injury', 'chance'];
                    const eventType = eventTypes[Math.floor(Math.random() * eventTypes.length)];
                    
                    let eventText = '';
                    let eventClass = '';
                    
                    // Time que fez o evento (50% de chance para cada time)
                    const isHomeEvent = Math.random() < 0.5;
                    const teamName = isHomeEvent ? 
                        (matchSimulation.home_team_id == <?= $team_id ?> ? gameData.team.name : matchSimulation.away_team_name) : 
                        (matchSimulation.home_team_id == <?= $team_id ?> ? matchSimulation.away_team_name : gameData.team.name);
                    
                    switch (eventType) {
                        case 'goal':
                            eventText = `⚽ GOLO! ${teamName} marca aos ${matchCurrentMinute}'`;
                            eventClass = 'goal-event';
                            
                            // Atualiza placar
                            const score = document.getElementById('match-score').textContent.split(' - ');
                            if (isHomeEvent) {
                                score[0] = parseInt(score[0]) + 1;
                            } else {
                                score[1] = parseInt(score[1]) + 1;
                            }
                            document.getElementById('match-score').textContent = score.join(' - ');
                            break;
                        case 'yellow-card':
                            eventText = `🟨 Cartão amarelo para ${teamName} aos ${matchCurrentMinute}'`;
                            eventClass = 'card-event';
                            break;
                        case 'red-card':
                            eventText = `🟥 Cartão vermelho para ${teamName} aos ${matchCurrentMinute}'`;
                            eventClass = 'card-event';
                            break;
                        case 'injury':
                            eventText = `🏥 Lesão de um jogador do ${teamName} aos ${matchCurrentMinute}'`;
                            eventClass = 'injury-event';
                            break;
                        case 'chance':
                            eventText = `🎯 Ótima chance para ${teamName} aos ${matchCurrentMinute}'`;
                            break;
                    }
                    
                    const eventElement = document.createElement('div');
                    eventElement.className = `match-event ${eventClass}`;
                    eventElement.textContent = eventText;
                    document.getElementById('match-events').appendChild(eventElement);
                    
                    // Rolagem automática para o novo evento
                    eventElement.scrollIntoView({ behavior: 'smooth' });
                }
            } else {
                // Fim do jogo
                const score = document.getElementById('match-score').textContent.split(' - ');
                const homeGoals = parseInt(score[0]);
                const awayGoals = parseInt(score[1]);
                
                // Atualiza o jogo com o resultado
                matchSimulation.played = true;
                matchSimulation.home_goals = homeGoals;
                matchSimulation.away_goals = awayGoals;
                
                // Atualiza estatísticas do time
                const teamStats = gameData.league.find(t => t.name === gameData.team.name);
                const opponentStats = gameData.league.find(t => t.name === (matchSimulation.home_team_id == <?= $team_id ?> ? matchSimulation.away_team_name : matchSimulation.home_team_name));
                
                if (matchSimulation.home_team_id == <?= $team_id ?>) {
                    teamStats.goals_for += homeGoals;
                    teamStats.goals_against += awayGoals;
                    opponentStats.goals_for += awayGoals;
                    opponentStats.goals_against += homeGoals;
                    
                    if (homeGoals > awayGoals) {
                        gameData.team.wins++;
                        teamStats.wins++;
                        teamStats.points += 3;
                        opponentStats.losses++;
                        addNews(`Vitória sobre ${matchSimulation.away_team_name} por ${homeGoals}-${awayGoals}`);
                    } else if (homeGoals < awayGoals) {
                        gameData.team.losses++;
                        teamStats.losses++;
                        opponentStats.wins++;
                        opponentStats.points += 3;
                        addNews(`Derrota para ${matchSimulation.away_team_name} por ${homeGoals}-${awayGoals}`);
                    } else {
                        gameData.team.draws++;
                        teamStats.draws++;
                        teamStats.points += 1;
                        opponentStats.draws++;
                        opponentStats.points += 1;
                        addNews(`Empate com ${matchSimulation.away_team_name} por ${homeGoals}-${awayGoals}`);
                    }
                } else {
                    teamStats.goals_for += awayGoals;
                    teamStats.goals_against += homeGoals;
                    opponentStats.goals_for += homeGoals;
                    opponentStats.goals_against += awayGoals;
                    
                    if (awayGoals > homeGoals) {
                        gameData.team.wins++;
                        teamStats.wins++;
                        teamStats.points += 3;
                        opponentStats.losses++;
                        addNews(`Vitória sobre ${matchSimulation.home_team_name} por ${awayGoals}-${homeGoals}`);
                    } else if (awayGoals < homeGoals) {
                        gameData.team.losses++;
                        teamStats.losses++;
                        opponentStats.wins++;
                        opponentStats.points += 3;
                        addNews(`Derrota para ${matchSimulation.home_team_name} por ${awayGoals}-${homeGoals}`);
                    } else {
                        gameData.team.draws++;
                        teamStats.draws++;
                        teamStats.points += 1;
                        opponentStats.draws++;
                        opponentStats.points += 1;
                        addNews(`Empate com ${matchSimulation.home_team_name} por ${awayGoals}-${homeGoals}`);
                    }
                }
                
                // Atualiza tabela da liga
                renderLeagueTable();
                
                // Próxima semana
                gameData.currentWeek++;
                
                // Atualiza interface
                btn.textContent = 'Fechar';
                btn.onclick = function() {
                    closeModal();
                    updateTeamInfo();
                    renderFixtures();
                    showNextMatch();
                    
                    // Aqui você pode adicionar código para salvar no banco de dados
                    /*
                    fetch('save_match_result.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ 
                            fixture_id: matchSimulation.id,
                            home_goals: homeGoals,
                            away_goals: awayGoals,
                            team_stats: teamStats,
                            opponent_stats: opponentStats,
                            team_id: <?= $team_id ?>,
                            current_week: gameData.currentWeek,
                            team_money: gameData.team.money
                        })
                    });
                    */
                };
            }
        }

        // Adiciona notícia
        function addNews(text) {
            gameData.news.unshift(text);
            
            const newsElement = document.createElement('div');
            newsElement.className = 'news-item';
            newsElement.textContent = text;
            document.getElementById('news-feed').prepend(newsElement);
            
            // Limita a 10 notícias
            if (gameData.news.length > 10) {
                gameData.news.pop();
                const newsFeed = document.getElementById('news-feed');
                if (newsFeed.children.length > 10) {
                    newsFeed.removeChild(newsFeed.lastChild);
                }
            }
        }

        // Mostra modal do jogador
        function showPlayerModal(playerId, isTransfer, isYouth = false) {
            let player;
            
            if (isTransfer) {
                player = gameData.transferPlayers.find(p => p.id === playerId);
            } else if (isYouth) {
                player = gameData.youthPlayers.find(p => p.id === playerId);
            } else {
                player = gameData.players.find(p => p.id === playerId);
            }
            
            if (!player) return;
            
            // Preenche informações básicas
            document.getElementById('modal-player-name').textContent = player.name;
            document.getElementById('modal-player-age').textContent = player.age;
            document.getElementById('modal-player-position').textContent = positionNames[player.position];
            document.getElementById('modal-player-nationality').textContent = player.nationality;
            document.getElementById('modal-player-value').textContent = `€${(player.value / 1000000).toFixed(1)}M`;
            document.getElementById('modal-player-rating').textContent = player.rating;
            document.getElementById('modal-player-potential').textContent = player.potential;
            document.getElementById('modal-player-contract').textContent = player.contract;
            document.getElementById('modal-player-salary').textContent = `€${(player.salary / 1000).toFixed(1)}K/mês`;
            
            // Preenche atributos
            const attributesContainer = document.getElementById('player-attributes');
            attributesContainer.innerHTML = '';
            
            for (const [attr, value] of Object.entries(player.attributes)) {
                const attrElement = document.createElement('div');
                attrElement.className = 'attribute-bar';
                attrElement.innerHTML = `
                    <div class="attribute-name">${attr.charAt(0).toUpperCase() + attr.slice(1)}</div>
                    <div class="attribute-value">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: ${value}%"></div>
                        </div>
                    </div>
                `;
                attributesContainer.appendChild(attrElement);
            }
            
            // Configura botões
            document.getElementById('modal-transfer-btn').style.display = isTransfer ? 'block' : 'none';
            document.getElementById('modal-train-btn').style.display = (isTransfer || isYouth) ? 'none' : 'block';
            document.getElementById('modal-promote-btn').style.display = isYouth ? 'block' : 'none';
            
            if (isTransfer) {
                document.getElementById('modal-transfer-btn').onclick = function() {
                    makeTransferOffer(playerId);
                };
            } else if (isYouth) {
                document.getElementById('modal-promote-btn').onclick = function() {
                    promoteYouthPlayer(playerId);
                };
            } else {
                document.getElementById('modal-train-btn').onclick = function() {
                    trainPlayer(playerId);
                };
            }
            
            openModal('player-modal');
        }

        // Promove um jogador da base para o time principal
        function promoteYouthPlayer(playerId) {
            const playerIndex = gameData.youthPlayers.findIndex(p => p.id === playerId);
            if (playerIndex === -1) return;
            
            const player = gameData.youthPlayers[playerIndex];
            
            // Adiciona ao time principal
            gameData.players.push({
                ...player,
                isYouth: true,
                contract: (new Date().getFullYear() + 3).toString() // Contrato de 3 anos
            });
            
            // Remove da base
            gameData.youthPlayers.splice(playerIndex, 1);
            
            addNews(`${player.name} foi promovido da base para o time principal!`);
            alert(`${player.name} foi promovido com sucesso para o time principal!`);
            
            // Atualiza interfaces
            renderPlayers(currentSquadTab);
            renderYouthPlayers();
            closeModal();
            
            // Aqui você pode adicionar código para salvar no banco de dados
            /*
            fetch('promote_player.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    player_id: playerId,
                    team_id: <?= $team_id ?>,
                    contract: (new Date().getFullYear() + 3).toString()
                })
            });
            */
        }

        // Faz oferta por um jogador
        function makeTransferOffer(playerId) {
            const player = gameData.transferPlayers.find(p => p.id === playerId);
            if (!player) return;
            
            const offer = prompt(`Fazer oferta por ${player.name} (Valor: €${(player.value / 1000000).toFixed(1)}M)\nDigite o valor da sua oferta em milhões (ex: 15.5):`);
            
            if (offer && !isNaN(parseFloat(offer))) {
                const offerValue = parseFloat(offer) * 1000000;
                
                if (offerValue >= player.value * 0.8) { // Oferta precisa ser pelo menos 80% do valor
                    if (offerValue <= gameData.team.money) {
                        // Transferência bem-sucedida
                        gameData.team.money -= offerValue;
                        
                        // Adiciona jogador ao time
                        const newPlayer = {...player};
                        newPlayer.contract = (new Date().getFullYear() + 3).toString(); // Contrato de 3 anos
                        newPlayer.isYouth = false;
                        gameData.players.push(newPlayer);
                        
                        // Remove jogador da lista de transferências
                        gameData.transferPlayers = gameData.transferPlayers.filter(p => p.id !== playerId);
                        
                        // Atualiza interface
                        updateTeamInfo();
                        renderPlayers(currentSquadTab);
                        renderTransferPlayers();
                        
                        addNews(`Contratação: ${player.name} chegou por €${(offerValue / 1000000).toFixed(1)}M`);
                        alert(`Transferência bem-sucedida! ${player.name} é agora jogador do ${gameData.team.name}.`);
                        closeModal();
                        
                        // Aqui você pode adicionar código para salvar no banco de dados
                        /*
                        fetch('complete_transfer.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ 
                                player_id: playerId,
                                team_id: <?= $team_id ?>,
                                offer_value: offerValue,
                                contract: (new Date().getFullYear() + 3).toString(),
                                current_money: gameData.team.money
                            })
                        });
                        */
                    } else {
                        alert('Dinheiro insuficiente para esta transferência.');
                    }
                } else {
                    alert('Oferta rejeitada. O clube quer um valor mais alto.');
                }
            }
        }

        // Treina um jogador (melhora atributos)
        function trainPlayer(playerId) {
            const player = gameData.players.find(p => p.id === playerId);
            if (!player) return;
            
            // Melhora aleatoriamente alguns atributos
            const attributes = Object.keys(player.attributes);
            const attrToImprove = attributes[Math.floor(Math.random() * attributes.length)];
            
            if (player.attributes[attrToImprove] < player.potential) {
                // Bônus do centro de treinamento
                const trainingBonus = gameData.facilities.training_bonus / 100;
                
                player.attributes[attrToImprove] += Math.floor((Math.random() * 3 + 1) * (1 + trainingBonus));
                if (player.attributes[attrToImprove] > player.potential) {
                    player.attributes[attrToImprove] = player.potential;
                }
                
                // Recalcula rating geral
                const avgRating = Object.values(player.attributes).reduce((a, b) => a + b, 0) / Object.values(player.attributes).length;
                player.rating = Math.round(avgRating);
                
                addNews(`${player.name} melhorou seu ${attrToImprove} no treino!`);
                alert(`${player.name} melhorou seu ${attrToImprove} no treino!`);
                
                // Atualiza modal
                showPlayerModal(playerId, false);
                
                // Aqui você pode adicionar código para salvar no banco de dados
                /*
                fetch('train_player.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ 
                        player_id: playerId,
                        attribute: attrToImprove,
                        new_value: player.attributes[attrToImprove],
                        new_rating: player.rating
                    })
                });
                */
            } else {
                alert(`${player.name} já atingiu seu potencial máximo neste atributo.`);
            }
        }

        // Muda formação tática
        function changeFormation() {
            const formationSelect = document.getElementById('formation-select');
            gameData.team.formation = formationSelect.value;
            renderFormation();
            addNews(`Formação alterada para ${gameData.team.formation}`);
            
            // Aqui você pode adicionar código para salvar no banco de dados
            /*
            fetch('change_formation.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    team_id: <?= $team_id ?>,
                    formation: gameData.team.formation
                })
            });
            */
        }

        // Procura jogadores para transferência
        function searchPlayers() {
            const searchTerm = document.getElementById('player-search').value.toLowerCase();
            
            if (searchTerm) {
                const filteredPlayers = gameData.transferPlayers.filter(p => 
                    p.name.toLowerCase().includes(searchTerm) || 
                    positionNames[p.position].toLowerCase().includes(searchTerm) ||
                    (p.club && p.club.toLowerCase().includes(searchTerm))
                );
                
                if (filteredPlayers.length > 0) {
                    // Renderiza jogadores filtrados
                    const playerList = document.getElementById('transfer-player-list');
                    playerList.innerHTML = '';
                    
                    filteredPlayers.forEach(player => {
                        const playerElement = document.createElement('div');
                        playerElement.className = 'transfer-player';
                        playerElement.innerHTML = `
                            <div>
                                <strong>${player.name}</strong> (${player.age} anos) - ${positionNames[player.position]}<br>
                                ${player.club} | Rating: ${player.rating} | Potencial: ${player.potential}
                            </div>
                            <div style="text-align: right;">
                                <strong>€${(player.value / 1000000).toFixed(1)}M</strong><br>
                                <button class="btn btn-primary view-player-btn" data-id="${player.id}">
                                    <i class="fas fa-eye"></i> Detalhes
                                </button>
                            </div>
                        `;
                        playerList.appendChild(playerElement);
                    });
                    
                    // Adiciona event listeners aos novos botões
                    document.querySelectorAll('.view-player-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            showPlayerModal(parseInt(this.dataset.id), true);
                        });
                    });
                } else {
                    alert('Nenhum jogador encontrado com esse critério.');
                }
            } else {
                renderTransferPlayers();
            }
        }

        // Abre um modal
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }

        // Fecha o modal
        function closeModal() {
            document.querySelectorAll('.modal').forEach(modal => {
                modal.style.display = 'none';
            });
        }

        // Controle de telas
        function showScreen(screenName) {
            currentScreen = screenName;
            
            // Atualiza menu ativo
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active');
            });
            document.querySelector(`.menu-item[data-screen="${screenName}"]`).classList.add('active');
            
            // Esconde todas as telas
            document.querySelectorAll('.screen').forEach(screen => {
                screen.style.display = 'none';
            });
            
            // Mostra a tela selecionada
            document.getElementById(`${screenName}-screen`).style.display = 'block';
            
            // Carrega dados específicos se necessário
            switch(screenName) {
                case 'transfers':
                    renderTransferPlayers();
                    break;
                case 'tactics':
                    renderFormation();
                    break;
                case 'youth':
                    renderYouthPlayers();
                    break;
                case 'league':
                    renderLeagueTable();
                    leagueTableVisible = true;
                    break;
                case 'facilities':
                    updateFacilitiesInfo();
                    break;
                case 'team':
                    showNextMatch();
                    break;
            }
        }

        // Inicia o jogo quando a página carrega
        window.onload = initGame;
    </script>
</body>
</html>