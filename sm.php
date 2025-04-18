<?php
include 'db_connection.php';

session_start();

// Initialize game session if it doesn't exist
if (!isset($_SESSION['game'])) {
    $_SESSION['game'] = [
        'career_started' => false,
        'current_week' => 1,
        'season' => 1,
        'team' => null,
        'budget' => 28000000,
        'facilities' => [
            'stadium' => 1,
            'training' => 1,
            'youth' => 1,
            'scouting' => 1,
            'medical' => 1
        ],
        'trained_this_week' => false,
        'youth_academy' => [],
        'youth_pulled' => false,
        'league' => [],
        'schedule' => [],
        'results' => [],
        'transfers' => [],
        'players' => [],
        'squad' => [
            'starting' => [],
            'subs' => [],
            'reserves' => []
        ],
        'match_in_progress' => false,
        'current_match' => null
    ];
}

// Function to get clubs from database
function getClubs($conn) {
    $clubs = [];
    
    if (!$conn) {
        error_log("Database connection not available");
        return $clubs;
    }

    try {
        $tableCheck = $conn->query("SHOW TABLES LIKE 'clube'");
        if ($tableCheck && $tableCheck->num_rows > 0) {
            $query = "SELECT id_clube, nome_clube FROM clube ORDER BY nome_clube";
            $result = $conn->query($query);
            
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    if (!empty($row['nome_clube'])) {
                        $clubs[$row['id_clube']] = $row['nome_clube'];
                    }
                }
            }
        }
    } catch (Exception $e) {
        error_log("Error fetching clubs: " . $e->getMessage());
    }
    
    return $clubs;
}

// Function to get team players
function getTeamPlayers($conn, $team) {
    $players = [];
    
    if (!$conn || !$team) {
        return $players;
    }

    try {
        $stmt = $conn->prepare("SELECT id_clube FROM clube WHERE nome_clube = ?");
        if ($stmt) {
            $stmt->bind_param("s", $team);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $clubId = $row['id_clube'];
                
                $query = "SELECT j.*, p.nome_posicao 
                          FROM jogador j 
                          JOIN posicao p ON j.id_posicao = p.id_posicao 
                          WHERE j.id_clube = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $clubId);
                $stmt->execute();
                $result = $stmt->get_result();
                
                while ($row = $result->fetch_assoc()) {
                    $players[] = [
                        'id' => $row['id_jogador'],
                        'nome' => $row['nome_jogador'],
                        'numero' => $row['numero_camisola'],
                        'clube' => $team,
                        'nacionalidade' => 'Portuguesa',
                        'valor' => $row['valor'],
                        'salario' => $row['salario'],
                        'overall' => $row['overall'],
                        'potencial' => $row['potencial'],
                        'posicao' => $row['nome_posicao'],
                        'idade' => calcularIdade($row['data_nascimento']),
                        'imagem' => $row['imagem_jogador'],
                        'aposentado' => $row['aposentado']
                    ];
                }
            }
            $stmt->close();
        }
    } catch (Exception $e) {
        error_log("Error fetching players: " . $e->getMessage());
    }
    
    return $players;
}

// Function to get all players except current team
function getMarketPlayers($conn, $current_team) {
    $players = [];
    
    if (!$conn) {
        return $players;
    }

    try {
        $stmt = $conn->prepare("SELECT c.id_clube FROM clube c WHERE c.nome_clube = ?");
        if ($stmt) {
            $stmt->bind_param("s", $current_team);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $current_club_id = $row['id_clube'];
                
                $query = "SELECT j.*, p.nome_posicao, c.nome_clube 
                          FROM jogador j 
                          JOIN posicao p ON j.id_posicao = p.id_posicao 
                          JOIN clube c ON j.id_clube = c.id_clube 
                          WHERE j.id_clube != ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $current_club_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                while ($row = $result->fetch_assoc()) {
                    $players[] = [
                        'id' => $row['id_jogador'],
                        'nome' => $row['nome_jogador'],
                        'numero' => $row['numero_camisola'],
                        'clube' => $row['nome_clube'],
                        'nacionalidade' => 'Portuguesa',
                        'valor' => $row['valor'],
                        'salario' => $row['salario'],
                        'overall' => $row['overall'],
                        'potencial' => $row['potencial'],
                        'posicao' => $row['nome_posicao'],
                        'idade' => calcularIdade($row['data_nascimento']),
                        'imagem' => $row['imagem_jogador'],
                        'aposentado' => $row['aposentado']
                    ];
                }
            }
            $stmt->close();
        }
    } catch (Exception $e) {
        error_log("Error fetching market players: " . $e->getMessage());
    }
    
    return $players;
}

// Helper function to calculate age
function calcularIdade($dataNascimento) {
    if (empty($dataNascimento)) return rand(18, 35);
    
    $dataNasc = new DateTime($dataNascimento);
    $hoje = new DateTime();
    $idade = $hoje->diff($dataNasc);
    return $idade->y;
}

// Function to generate random player stats
function generatePlayerStats($overall) {
    $base = max(40, min(99, $overall));
    return [
        'pace' => min(99, $base - 5 + rand(0, 10)),
        'stamina' => min(99, $base - 5 + rand(0, 10)),
        'strength' => min(99, $base - 5 + rand(0, 10)),
        'dribbling' => min(99, $base - 5 + rand(0, 10)),
        'passing' => min(99, $base - 5 + rand(0, 10)),
        'shooting' => min(99, $base - 5 + rand(0, 10)),
        'positioning' => min(99, $base - 5 + rand(0, 10)),
        'vision' => min(99, $base - 5 + rand(0, 10)),
        'composure' => min(99, $base - 5 + rand(0, 10))
    ];
}

// Function to generate youth players
function generateYouthPlayers($youth_level) {
    $players = [];
    $num_players = 3 + floor($youth_level / 2);
    
    $positions = ['Guarda-Redes', 'Defesa', 'Médio', 'Avançado'];
    $surnames = ['Silva', 'Santos', 'Ferreira', 'Oliveira', 'Pereira'];
    $first_names = ['João', 'Pedro', 'Miguel', 'Tiago', 'André', 'Ricardo', 'Daniel', 'Rui', 'Carlos', 'Luís'];
    
    for ($i = 0; $i < $num_players; $i++) {
        $potential = rand(60, 70 + ($youth_level * 3));
        $overall = rand(50, 60 + ($youth_level * 2));
        
        $players[] = [
            'id' => 'youth_' . uniqid(),
            'nome' => $first_names[array_rand($first_names)] . ' ' . $surnames[array_rand($surnames)],
            'numero' => rand(40, 99),
            'clube' => 'Academia',
            'nacionalidade' => 'Portuguesa',
            'valor' => rand(100000, 1000000),
            'salario' => rand(1000, 5000),
            'overall' => $overall,
            'potencial' => min(99, $potential),
            'posicao' => $positions[array_rand($positions)],
            'idade' => rand(16, 19),
            'stats' => generatePlayerStats($overall)
        ];
    }
    
    return $players;
}

// Function to simulate a match with more realistic results
function simulateMatch($home_team, $away_team, $home_strength, $away_strength) {
    $home_rating = max(1, min(100, $home_strength + rand(-5, 5)));
    $away_rating = max(1, min(100, $away_strength + rand(-5, 5)));
    
    $home_rating = min(100, $home_rating + 5);
    
    $diff = $home_rating - $away_rating;
    $home_goals = 0;
    $away_goals = 0;
    
    $home_goal_prob = 0.4 + ($diff * 0.005);
    $away_goal_prob = 0.4 - ($diff * 0.005);
    
    $match_events = rand(8, 15);
    
    for ($i = 0; $i < $match_events; $i++) {
        if (rand(1, 100) < ($home_goal_prob * 100)) {
            $home_goals += rand(0, 1);
        }
        
        if (rand(1, 100) < ($away_goal_prob * 100)) {
            $away_goals += rand(0, 1);
        }
    }
    
    if ($home_goals + $away_goals == 0) {
        if (rand(1, 100) < 70) {
            if (rand(1, 100) < 60) {
                $home_goals = 1;
            } else {
                $away_goals = 1;
            }
        }
    }
    
    return [
        'home' => $home_team,
        'away' => $away_team,
        'home_goals' => max(0, $home_goals),
        'away_goals' => max(0, $away_goals)
    ];
}

// Function to select best 11 players with required positions
function selectBestTeam($players) {
    $positionPriority = [
        'Guarda-Redes' => 1,
        'Defesa' => 2,
        'Lateral' => 3,
        'Médio' => 4,
        'Avançado' => 5
    ];
    
    usort($players, function($a, $b) {
        return $b['overall'] - $a['overall'];
    });
    
    $team = [];
    $positionsFilled = [
        'GR' => 0,
        'DC' => 0,
        'DD' => 0,
        'DE' => 0,
        'MC' => 0,
        'MCO' => 0,
        'EE' => 0,
        'PL' => 0,
        'ED' => 0
    ];
    
    foreach ($players as $player) {
        $pos = '';
        
        if ($player['posicao'] == 'Guarda-Redes' && $positionsFilled['GR'] < 1) {
            $pos = 'GR';
        } elseif (($player['posicao'] == 'Defesa' || $player['posicao'] == 'Central') && $positionsFilled['DC'] < 2) {
            $pos = 'DC';
        } elseif ($player['posicao'] == 'Lateral Direito' && $positionsFilled['DD'] < 1) {
            $pos = 'DD';
        } elseif ($player['posicao'] == 'Lateral Esquerdo' && $positionsFilled['DE'] < 1) {
            $pos = 'DE';
        } elseif ($player['posicao'] == 'Médio Centro' && $positionsFilled['MC'] < 2) {
            $pos = 'MC';
        } elseif ($player['posicao'] == 'Médio Ofensivo' && $positionsFilled['MCO'] < 1) {
            $pos = 'MCO';
        } elseif ($player['posicao'] == 'Extremo Esquerdo' && $positionsFilled['EE'] < 1) {
            $pos = 'EE';
        } elseif ($player['posicao'] == 'Pontade Lança' && $positionsFilled['PL'] < 1) {
            $pos = 'PL';
        } elseif ($player['posicao'] == 'Extremo Direito' && $positionsFilled['ED'] < 1) {
            $pos = 'ED';
        }
        
        if (!empty($pos) && count($team) < 11) {
            $player['position_field'] = $pos;
            $team[] = $player;
            $positionsFilled[$pos]++;
        }
    }
    
    if (count($team) < 11) {
        foreach ($players as $player) {
            if (!in_array($player, $team)) {
                $player['position_field'] = 'MC';
                $team[] = $player;
                if (count($team) >= 11) break;
            }
        }
    }
    
    return $team;
}

// Process form actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['start_career']) && !empty($_POST['team'])) {
            $_SESSION['game']['career_started'] = true;
            $_SESSION['game']['team'] = $_POST['team'];
            $_SESSION['game']['league'] = getClubs($conn);
            $_SESSION['game']['youth_academy'] = generateYouthPlayers($_SESSION['game']['facilities']['youth']);
            $_SESSION['game']['players'] = getTeamPlayers($conn, $_POST['team']);
            
            $_SESSION['game']['squad']['starting'] = selectBestTeam($_SESSION['game']['players']);
            
            $remainingPlayers = array_udiff($_SESSION['game']['players'], $_SESSION['game']['squad']['starting'], 
                function($a, $b) { return $a['id'] <=> $b['id']; });
            
            $_SESSION['game']['squad']['subs'] = array_slice($remainingPlayers, 0, 7);
            $_SESSION['game']['squad']['reserves'] = array_slice($remainingPlayers, 7);
            
            // Generate league schedule (each team plays each other twice)
            $teams = array_values($_SESSION['game']['league']);
            $schedule = [];
            
            // First round (home and away)
            for ($i = 0; $i < count($teams); $i++) {
                for ($j = $i + 1; $j < count($teams); $j++) {
                    $schedule[] = [
                        'home' => $teams[$i],
                        'away' => $teams[$j],
                        'played' => false,
                        'result' => null
                    ];
                    $schedule[] = [
                        'home' => $teams[$j],
                        'away' => $teams[$i],
                        'played' => false,
                        'result' => null
                    ];
                }
            }
            
            shuffle($schedule);
            $_SESSION['game']['schedule'] = $schedule;
        }
        
        if (isset($_POST['reset_game'])) {
            $_SESSION['game'] = [
                'career_started' => false,
                'current_week' => 1,
                'season' => 1,
                'team' => null,
                'budget' => 28000000,
                'facilities' => [
                    'stadium' => 1,
                    'training' => 1,
                    'youth' => 1,
                    'scouting' => 1,
                    'medical' => 1
                ],
                'trained_this_week' => false,
                'youth_academy' => [],
                'youth_pulled' => false,
                'league' => [],
                'schedule' => [],
                'results' => [],
                'transfers' => [],
                'players' => [],
                'squad' => [
                    'starting' => [],
                    'subs' => [],
                    'reserves' => []
                ],
                'match_in_progress' => false,
                'current_match' => null
            ];
            header("Location: sm.php");
            exit();
        }
        
        if (isset($_POST['advance_week']) && $_SESSION['game']['career_started']) {
            // Simulate all matches for the current week
            $matches_this_week = 0;
            $max_matches_per_week = 5; // Adjust as needed
            
            // First find our team's match
            $our_match_found = false;
            foreach ($_SESSION['game']['schedule'] as &$game) {
                if (!$game['played'] && ($game['home'] == $_SESSION['game']['team'] || $game['away'] == $_SESSION['game']['team'])) {
                    $home_strength = calculateTeamStrength($game['home']);
                    $away_strength = calculateTeamStrength($game['away']);
                    
                    if ($game['home'] == $_SESSION['game']['team']) {
                        $home_strength += $_SESSION['game']['facilities']['training'] * 2;
                    }
                    if ($game['away'] == $_SESSION['game']['team']) {
                        $away_strength += $_SESSION['game']['facilities']['training'] * 2;
                    }
                    
                    $result = simulateMatch($game['home'], $game['away'], $home_strength, $away_strength);
                    $game['result'] = $result;
                    $game['played'] = true;
                    
                    $_SESSION['game']['results'][] = $result;
                    
                    // Financial rewards
                    if ($result['home'] == $_SESSION['game']['team']) {
                        if ($result['home_goals'] > $result['away_goals']) {
                            $_SESSION['game']['budget'] += 500000 * $_SESSION['game']['facilities']['stadium'];
                        } elseif ($result['home_goals'] == $result['away_goals']) {
                            $_SESSION['game']['budget'] += 250000 * $_SESSION['game']['facilities']['stadium'];
                        }
                    }
                    if ($result['away'] == $_SESSION['game']['team']) {
                        if ($result['away_goals'] > $result['home_goals']) {
                            $_SESSION['game']['budget'] += 500000 * $_SESSION['game']['facilities']['stadium'];
                        } elseif ($result['away_goals'] == $result['home_goals']) {
                            $_SESSION['game']['budget'] += 250000 * $_SESSION['game']['facilities']['stadium'];
                        }
                    }
                    
                    $our_match_found = true;
                    $matches_this_week++;
                    break;
                }
            }
            
            // Then simulate other matches (up to max_matches_per_week)
            if ($matches_this_week < $max_matches_per_week) {
                foreach ($_SESSION['game']['schedule'] as &$game) {
                    if (!$game['played'] && $game['home'] != $_SESSION['game']['team'] && $game['away'] != $_SESSION['game']['team']) {
                        $home_strength = calculateTeamStrength($game['home']);
                        $away_strength = calculateTeamStrength($game['away']);
                        
                        $result = simulateMatch($game['home'], $game['away'], $home_strength, $away_strength);
                        $game['result'] = $result;
                        $game['played'] = true;
                        
                        $_SESSION['game']['results'][] = $result;
                        $matches_this_week++;
                        
                        if ($matches_this_week >= $max_matches_per_week) {
                            break;
                        }
                    }
                }
            }
            
            $_SESSION['game']['current_week']++;
            $_SESSION['game']['trained_this_week'] = false;
            
            // Check if season is over
            $games_remaining = count(array_filter($_SESSION['game']['schedule'], function($game) {
                return !$game['played'];
            }));
            
            if ($games_remaining == 0) {
                $_SESSION['game']['season']++;
                $_SESSION['game']['current_week'] = 1;
                $season_bonus = 28000000 + ($_SESSION['game']['facilities']['stadium'] * 2000000);
                $_SESSION['game']['budget'] += $season_bonus;
                $_SESSION['game']['youth_pulled'] = false;
                $_SESSION['game']['youth_academy'] = generateYouthPlayers($_SESSION['game']['facilities']['youth']);
                
                // Generate new schedule for the new season
                $teams = array_values($_SESSION['game']['league']);
                $schedule = [];
                
                // Each team plays each other twice (home and away)
                for ($i = 0; $i < count($teams); $i++) {
                    for ($j = $i + 1; $j < count($teams); $j++) {
                        $schedule[] = [
                            'home' => $teams[$i],
                            'away' => $teams[$j],
                            'played' => false,
                            'result' => null
                        ];
                        $schedule[] = [
                            'home' => $teams[$j],
                            'away' => $teams[$i],
                            'played' => false,
                            'result' => null
                        ];
                    }
                }
                
                shuffle($schedule);
                $_SESSION['game']['schedule'] = $schedule;
                $_SESSION['game']['results'] = [];
            }
        }
        
        if (isset($_POST['train_team']) && $_SESSION['game']['career_started'] && !$_SESSION['game']['trained_this_week']) {
            $training_boost = $_SESSION['game']['facilities']['training'] * 0.75;
            
            foreach ($_SESSION['game']['players'] as &$player) {
                if ($player['idade'] < 23 && $player['overall'] < $player['potencial']) {
                    $player['overall'] = min($player['potencial'], $player['overall'] + $training_boost);
                } elseif ($player['idade'] < 28) {
                    $player['overall'] = min($player['potencial'], $player['overall'] + ($training_boost * 0.5));
                }
            }
            
            foreach (['starting', 'subs', 'reserves'] as $squad_type) {
                foreach ($_SESSION['game']['squad'][$squad_type] as &$player) {
                    if ($player['idade'] < 23 && $player['overall'] < $player['potencial']) {
                        $player['overall'] = min($player['potencial'], $player['overall'] + $training_boost);
                    } elseif ($player['idade'] < 28) {
                        $player['overall'] = min($player['potencial'], $player['overall'] + ($training_boost * 0.5));
                    }
                }
            }
            
            $_SESSION['game']['trained_this_week'] = true;
        }
        
        if (isset($_POST['upgrade_facility']) && isset($_POST['facility']) && $_SESSION['game']['career_started']) {
            $facility = $_POST['facility'];
            if (isset($_SESSION['game']['facilities'][$facility])) {
                $cost = $_SESSION['game']['facilities'][$facility] * 2000000;
                
                if ($_SESSION['game']['budget'] >= $cost && $_SESSION['game']['facilities'][$facility] < 10) {
                    $_SESSION['game']['budget'] -= $cost;
                    $_SESSION['game']['facilities'][$facility]++;
                    
                    if ($facility == 'youth') {
                        $_SESSION['game']['youth_academy'] = generateYouthPlayers($_SESSION['game']['facilities']['youth']);
                    }
                }
            }
        }
        
        if (isset($_POST['sign_player']) && isset($_POST['player_id']) && $_SESSION['game']['career_started']) {
            $player_id = $_POST['player_id'];
            foreach ($market_players as $key => $player) {
                if ($player['id'] == $player_id && $_SESSION['game']['budget'] >= $player['valor']) {
                    $_SESSION['game']['budget'] -= $player['valor'];
                    $_SESSION['game']['players'][] = $player;
                    $_SESSION['game']['squad']['reserves'][] = $player;
                    unset($market_players[$key]);
                    $market_players = array_values($market_players);
                    break;
                }
            }
        }
        
        if (isset($_POST['promote_youth']) && isset($_POST['player_index']) && $_SESSION['game']['career_started'] && !$_SESSION['game']['youth_pulled']) {
            $index = (int)$_POST['player_index'];
            if (isset($_SESSION['game']['youth_academy'][$index])) {
                $youth_player = $_SESSION['game']['youth_academy'][$index];
                $_SESSION['game']['players'][] = $youth_player;
                $_SESSION['game']['squad']['reserves'][] = $youth_player;
                $_SESSION['game']['youth_pulled'] = true;
                unset($_SESSION['game']['youth_academy'][$index]);
                $_SESSION['game']['youth_academy'] = array_values($_SESSION['game']['youth_academy']);
            }
        }
        
        if (isset($_POST['move_player']) && isset($_POST['player_id']) && isset($_POST['from']) && isset($_POST['to']) && $_SESSION['game']['career_started']) {
            $player_id = $_POST['player_id'];
            $from = $_POST['from'];
            $to = $_POST['to'];
            
            $max_players = [
                'starting' => 11,
                'subs' => 7,
                'reserves' => PHP_INT_MAX
            ];
            
            if (count($_SESSION['game']['squad'][$to]) < $max_players[$to]) {
                foreach ($_SESSION['game']['squad'][$from] as $key => $player) {
                    if ($player['id'] == $player_id) {
                        $_SESSION['game']['squad'][$to][] = $player;
                        unset($_SESSION['game']['squad'][$from][$key]);
                        $_SESSION['game']['squad'][$from] = array_values($_SESSION['game']['squad'][$from]);
                        break;
                    }
                }
                
                if ($to == 'starting') {
                    $_SESSION['game']['squad']['starting'] = selectBestTeam(array_merge(
                        $_SESSION['game']['squad']['starting'],
                        $_SESSION['game']['squad']['subs'],
                        $_SESSION['game']['squad']['reserves']
                    ));
                }
            }
        }
        
        if (isset($_POST['sell_player']) && isset($_POST['player_id']) && $_SESSION['game']['career_started']) {
            $player_id = $_POST['player_id'];
            foreach ($_SESSION['game']['players'] as $key => $player) {
                if ($player['id'] == $player_id) {
                    $interested_clubs = array_diff(array_values($_SESSION['game']['league']), [$_SESSION['game']['team']]);
                    $new_club = $interested_clubs[array_rand($interested_clubs)];
                    
                    $player['clube'] = $new_club;
                    $_SESSION['game']['transfers'][] = $player;
                    
                    foreach (['starting', 'subs', 'reserves'] as $squad_type) {
                        foreach ($_SESSION['game']['squad'][$squad_type] as $squad_key => $squad_player) {
                            if ($squad_player['id'] == $player_id) {
                                unset($_SESSION['game']['squad'][$squad_type][$squad_key]);
                                $_SESSION['game']['squad'][$squad_type] = array_values($_SESSION['game']['squad'][$squad_type]);
                                break;
                            }
                        }
                    }
                    
                    unset($_SESSION['game']['players'][$key]);
                    $_SESSION['game']['players'] = array_values($_SESSION['game']['players']);
                    $_SESSION['game']['budget'] += $player['valor'];
                    break;
                }
            }
        }
    } catch (Exception $e) {
        error_log("Error processing form: " . $e->getMessage());
    }
}

// Function to calculate team strength based on starting lineup
function calculateTeamStrength($teamName) {
    if (!isset($_SESSION['game']['career_started'])) {
        return rand(70, 85);
    }
    
    if ($teamName == $_SESSION['game']['team']) {
        $players = $_SESSION['game']['squad']['starting'];
    } else {
        $base = 70 + ($_SESSION['game']['season'] * 2) + rand(-5, 5);
        return min(95, $base);
    }
    
    if (empty($players)) {
        return 70;
    }
    
    $total = 0;
    foreach ($players as $player) {
        $total += $player['overall'];
    }
    
    return $total / count($players);
}

// Get data for display
$clubs = getClubs($conn);
$team_players = $_SESSION['game']['career_started'] ? $_SESSION['game']['players'] : [];
$league_table = [];

// Get market players (real players from database except current team)
$market_players = [];
if ($_SESSION['game']['career_started']) {
    $market_players = array_merge(
        $_SESSION['game']['transfers'],
        getMarketPlayers($conn, $_SESSION['game']['team'])
    );
    
    // Sort by overall
    usort($market_players, function($a, $b) {
        return $b['overall'] - $a['overall'];
    });
}

// Calculate league table
if ($_SESSION['game']['career_started'] && !empty($_SESSION['game']['results'])) {
    $teams_points = [];
    $teams_goals = [];
    $teams_wins = [];
    $teams_draws = [];
    $teams_losses = [];
    
    foreach ($_SESSION['game']['league'] as $team) {
        $teams_points[$team] = 0;
        $teams_goals[$team] = ['for' => 0, 'against' => 0];
        $teams_wins[$team] = 0;
        $teams_draws[$team] = 0;
        $teams_losses[$team] = 0;
    }
    
    foreach ($_SESSION['game']['results'] as $result) {
        if ($result['home_goals'] > $result['away_goals']) {
            $teams_points[$result['home']] += 3;
            $teams_wins[$result['home']]++;
            $teams_losses[$result['away']]++;
        } elseif ($result['home_goals'] == $result['away_goals']) {
            $teams_points[$result['home']] += 1;
            $teams_points[$result['away']] += 1;
            $teams_draws[$result['home']]++;
            $teams_draws[$result['away']]++;
        } else {
            $teams_points[$result['away']] += 3;
            $teams_wins[$result['away']]++;
            $teams_losses[$result['home']]++;
        }
        
        $teams_goals[$result['home']]['for'] += $result['home_goals'];
        $teams_goals[$result['home']]['against'] += $result['away_goals'];
        $teams_goals[$result['away']]['for'] += $result['away_goals'];
        $teams_goals[$result['away']]['against'] += $result['home_goals'];
    }
    
    uasort($teams_points, function($a, $b) use ($teams_goals, $teams_points) {
        $teamA_points = $a;
        $teamB_points = $b;
        
        if ($teamA_points != $teamB_points) {
            return $teamB_points - $teamA_points;
        }
        
        $gdA = $teams_goals[key($teams_points)]['for'] - $teams_goals[key($teams_points)]['against'];
        $gdB = $teams_goals[key($teams_points)]['for'] - $teams_goals[key($teams_points)]['against'];
        
        if ($gdA != $gdB) {
            return $gdB - $gdA;
        }
        
        return $teams_goals[key($teams_points)]['for'] - $teams_goals[key($teams_points)]['for'];
    });
    
    foreach ($teams_points as $team => $points) {
        $league_table[] = [
            'team' => $team,
            'points' => $points,
            'played' => $teams_wins[$team] + $teams_draws[$team] + $teams_losses[$team],
            'wins' => $teams_wins[$team],
            'draws' => $teams_draws[$team],
            'losses' => $teams_losses[$team],
            'goals_for' => $teams_goals[$team]['for'],
            'goals_against' => $teams_goals[$team]['against'],
            'goal_difference' => $teams_goals[$team]['for'] - $teams_goals[$team]['against']
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soccer Manager 25</title>
    <link rel="stylesheet" href="css/sm.css">
</head>
<body>
    <div class="container">
        <?php if (!$_SESSION['game']['career_started']): ?>
            <div class="career-start">
                <h1>Bem-vindo ao Soccer Manager 25</h1>
                <?php if (empty($clubs)): ?>
                    <div class="error-message">
                        <p>Não foi possível carregar os clubes da base de dados.</p>
                        <p>Por favor, verifique:</p>
                        <ul>
                            <li>Se a tabela 'clube' existe</li>
                            <li>Se há dados na tabela de clubes</li>
                        </ul>
                    </div>
                <?php else: ?>
                    <form method="post">
                        <h2>Escolha seu clube:</h2>
                        <select name="team" required>
                            <option value="">-- Selecione um clube --</option>
                            <?php foreach ($clubs as $id => $club): ?>
                                <option value="<?= htmlspecialchars($club) ?>"><?= htmlspecialchars($club) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" name="start_career">Iniciar Carreira</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <header>
                <div class="club-info">
                    <h1><?= htmlspecialchars($_SESSION['game']['team']) ?></h1>
                    <p>Temporada: <?= $_SESSION['game']['season'] ?> | Semana: <?= $_SESSION['game']['current_week'] ?></p>
                </div>
                <div class="financial-info">
                    <p>Orçamento: €<?= number_format($_SESSION['game']['budget'], 0, ',', '.') ?></p>
                </div>
                <div class="reset-button">
                    <form method="post">
                        <button type="submit" name="reset_game">Resetar Jogo</button>
                    </form>
                </div>
            </header>
            
            <nav>
                <ul>
                    <li><a href="#" data-section="overview" class="active">Visão Geral</a></li>
                    <li><a href="#" data-section="squad">Plantel</a></li>
                    <li><a href="#" data-section="facilities">Instalações</a></li>
                    <li><a href="#" data-section="transfers">Mercado</a></li>
                    <li><a href="#" data-section="youth">Academia</a></li>
                </ul>
            </nav>
            
            <main>
                <section id="overview" class="content-section active">
                    <h2>Visão Geral</h2>
                    
                    <div class="league-table">
                        <h3>Classificação</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Pos</th>
                                    <th>Time</th>
                                    <th>Pts</th>
                                    <th>J</th>
                                    <th>V</th>
                                    <th>E</th>
                                    <th>D</th>
                                    <th>GP</th>
                                    <th>GC</th>
                                    <th>SG</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($league_table)): ?>
                                    <?php foreach ($league_table as $pos => $team): ?>
                                        <tr class="<?= $team['team'] == $_SESSION['game']['team'] ? 'highlight' : '' ?>">
                                            <td><?= $pos + 1 ?></td>
                                            <td><?= htmlspecialchars($team['team']) ?></td>
                                            <td><?= $team['points'] ?></td>
                                            <td><?= $team['played'] ?></td>
                                            <td><?= $team['wins'] ?></td>
                                            <td><?= $team['draws'] ?></td>
                                            <td><?= $team['losses'] ?></td>
                                            <td><?= $team['goals_for'] ?></td>
                                            <td><?= $team['goals_against'] ?></td>
                                            <td><?= $team['goal_difference'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10">Nenhum resultado disponível ainda</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="fixtures">
                        <h3>Próximos Jogos</h3>
                        <?php 
                        $upcoming = array_slice(array_filter($_SESSION['game']['schedule'], function($game) {
                            return !$game['played'] && ($game['home'] == $_SESSION['game']['team'] || $game['away'] == $_SESSION['game']['team']);
                        }), 0, 3);
                        
                        if (!empty($upcoming)): ?>
                            <?php foreach ($upcoming as $game): ?>
                                <div class="fixture">
                                    <p><?= htmlspecialchars($game['home']) ?> vs <?= htmlspecialchars($game['away']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Nenhum jogo agendado</p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="results">
                        <h3>Últimos Resultados</h3>
                        <?php 
                        $recent = array_slice(array_reverse($_SESSION['game']['results']), 0, 3);
                        if (!empty($recent)): ?>
                            <?php foreach ($recent as $result): ?>
                                <div class="result">
                                    <p><?= htmlspecialchars($result['home']) ?> <?= $result['home_goals'] ?> - <?= $result['away_goals'] ?> <?= htmlspecialchars($result['away']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Nenhum resultado disponível</p>
                        <?php endif; ?>
                    </div>
                    
                    <form method="post" class="advance-form">
                        <button type="submit" name="advance_week">Avançar Semana</button>
                    </form>
                </section>
                
                <section id="squad" class="content-section">
                    <h2>Plantel</h2>
                    <div class="squad-tabs">
                        <button class="tab-button active" data-tab="starting">Titulares</button>
                        <button class="tab-button" data-tab="subs">Suplentes</button>
                        <button class="tab-button" data-tab="reserves">Reservas</button>
                    </div>
                    
                    <div id="starting-content" class="squad-content active">
                        <h3>Titulares</h3>
                        <div class="player-list">
                            <?php if (!empty($_SESSION['game']['squad']['starting'])): ?>
                                <?php foreach ($_SESSION['game']['squad']['starting'] as $player): ?>
                                    <div class="player-card">
                                        <?php if (!empty($player['imagem'])): ?>
                                            <img src="<?= htmlspecialchars($player['imagem']) ?>" alt="<?= htmlspecialchars($player['nome']) ?>" class="player-image">
                                        <?php endif; ?>
                                        <div class="player-info">
                                            <span class="player-number"><?= htmlspecialchars($player['numero']) ?></span>
                                            <span class="player-name"><?= htmlspecialchars($player['nome']) ?></span>
                                            <span class="player-position"><?= htmlspecialchars($player['posicao']) ?> (<?= $player['position_field'] ?? 'PL' ?>)</span>
                                            <span class="player-rating"><?= htmlspecialchars($player['overall']) ?> (<?= htmlspecialchars($player['potencial']) ?>)</span>
                                            <span class="player-value">€<?= number_format($player['valor'], 0, ',', '.') ?></span>
                                            <span class="player-age"><?= htmlspecialchars($player['idade']) ?> anos</span>
                                        </div>
                                        <div class="player-actions">
                                            <form method="post">
                                                <input type="hidden" name="player_id" value="<?= htmlspecialchars($player['id']) ?>">
                                                <input type="hidden" name="from" value="starting">
                                                <input type="hidden" name="to" value="subs">
                                                <button type="submit" name="move_player">Mover para Suplentes</button>
                                            </form>
                                            <form method="post">
                                                <input type="hidden" name="player_id" value="<?= htmlspecialchars($player['id']) ?>">
                                                <button type="submit" name="sell_player">Vender</button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>Nenhum jogador titular</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div id="subs-content" class="squad-content">
                        <h3>Suplentes</h3>
                        <div class="player-list">
                            <?php if (!empty($_SESSION['game']['squad']['subs'])): ?>
                                <?php foreach ($_SESSION['game']['squad']['subs'] as $player): ?>
                                    <div class="player-card">
                                        <?php if (!empty($player['imagem'])): ?>
                                            <img src="<?= htmlspecialchars($player['imagem']) ?>" alt="<?= htmlspecialchars($player['nome']) ?>" class="player-image">
                                        <?php endif; ?>
                                        <div class="player-info">
                                            <span class="player-number"><?= htmlspecialchars($player['numero']) ?></span>
                                            <span class="player-name"><?= htmlspecialchars($player['nome']) ?></span>
                                            <span class="player-position"><?= htmlspecialchars($player['posicao']) ?></span>
                                            <span class="player-rating"><?= htmlspecialchars($player['overall']) ?> (<?= htmlspecialchars($player['potencial']) ?>)</span>
                                            <span class="player-value">€<?= number_format($player['valor'], 0, ',', '.') ?></span>
                                            <span class="player-age"><?= htmlspecialchars($player['idade']) ?> anos</span>
                                        </div>
                                        <div class="player-actions">
                                            <form method="post">
                                                <input type="hidden" name="player_id" value="<?= htmlspecialchars($player['id']) ?>">
                                                <input type="hidden" name="from" value="subs">
                                                <input type="hidden" name="to" value="starting">
                                                <button type="submit" name="move_player">Mover para Titulares</button>
                                            </form>
                                            <form method="post">
                                                <input type="hidden" name="player_id" value="<?= htmlspecialchars($player['id']) ?>">
                                                <input type="hidden" name="from" value="subs">
                                                <input type="hidden" name="to" value="reserves">
                                                <button type="submit" name="move_player">Mover para Reservas</button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>Nenhum jogador suplente</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div id="reserves-content" class="squad-content">
                        <h3>Reservas</h3>
                        <div class="player-list">
                            <?php if (!empty($_SESSION['game']['squad']['reserves'])): ?>
                                <?php foreach ($_SESSION['game']['squad']['reserves'] as $player): ?>
                                    <div class="player-card">
                                        <?php if (!empty($player['imagem'])): ?>
                                            <img src="<?= htmlspecialchars($player['imagem']) ?>" alt="<?= htmlspecialchars($player['nome']) ?>" class="player-image">
                                        <?php endif; ?>
                                        <div class="player-info">
                                            <span class="player-number"><?= htmlspecialchars($player['numero']) ?></span>
                                            <span class="player-name"><?= htmlspecialchars($player['nome']) ?></span>
                                            <span class="player-position"><?= htmlspecialchars($player['posicao']) ?></span>
                                            <span class="player-rating"><?= htmlspecialchars($player['overall']) ?> (<?= htmlspecialchars($player['potencial']) ?>)</span>
                                            <span class="player-value">€<?= number_format($player['valor'], 0, ',', '.') ?></span>
                                            <span class="player-age"><?= htmlspecialchars($player['idade']) ?> anos</span>
                                        </div>
                                        <div class="player-actions">
                                            <form method="post">
                                                <input type="hidden" name="player_id" value="<?= htmlspecialchars($player['id']) ?>">
                                                <input type="hidden" name="from" value="reserves">
                                                <input type="hidden" name="to" value="subs">
                                                <button type="submit" name="move_player">Mover para Suplentes</button>
                                            </form>
                                            <form method="post">
                                                <input type="hidden" name="player_id" value="<?= htmlspecialchars($player['id']) ?>">
                                                <button type="submit" name="sell_player">Vender</button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>Nenhum jogador nas reservas</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
                
                <section id="facilities" class="content-section">
                    <h2>Instalações</h2>
                    <div class="facilities-list">
                        <div class="facility">
                            <h3>Estádio (Nível <?= $_SESSION['game']['facilities']['stadium'] ?>)</h3>
                            <p>Aumenta a receita por temporada e por vitórias.</p>
                            <?php if ($_SESSION['game']['facilities']['stadium'] >= 10): ?>
                                <p class="max-level">Nível Máximo Atingido</p>
                            <?php else: ?>
                                <form method="post">
                                    <input type="hidden" name="facility" value="stadium">
                                    <button type="submit" name="upgrade_facility" <?= $_SESSION['game']['budget'] < ($_SESSION['game']['facilities']['stadium'] * 2000000) ? 'disabled' : '' ?>>
                                        Melhorar (€<?= number_format($_SESSION['game']['facilities']['stadium'] * 2000000, 0, ',', '.') ?>)
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                        
                        <div class="facility">
                            <h3>Centro de Treino (Nível <?= $_SESSION['game']['facilities']['training'] ?>)</h3>
                            <p>Melhora a eficácia dos treinos e o desenvolvimento dos jogadores.</p>
                            <?php if ($_SESSION['game']['facilities']['training'] >= 10): ?>
                                <p class="max-level">Nível Máximo Atingido</p>
                            <?php else: ?>
                                <form method="post">
                                    <input type="hidden" name="facility" value="training">
                                    <button type="submit" name="upgrade_facility" <?= $_SESSION['game']['budget'] < ($_SESSION['game']['facilities']['training'] * 2000000) ? 'disabled' : '' ?>>
                                        Melhorar (€<?= number_format($_SESSION['game']['facilities']['training'] * 2000000, 0, ',', '.') ?>)
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                        
                        <div class="facility">
                            <h3>Academia de Jovens (Nível <?= $_SESSION['game']['facilities']['youth'] ?>)</h3>
                            <p>Produz melhores jovens e em maior quantidade.</p>
                            <?php if ($_SESSION['game']['facilities']['youth'] >= 10): ?>
                                <p class="max-level">Nível Máximo Atingido</p>
                            <?php else: ?>
                                <form method="post">
                                    <input type="hidden" name="facility" value="youth">
                                    <button type="submit" name="upgrade_facility" <?= $_SESSION['game']['budget'] < ($_SESSION['game']['facilities']['youth'] * 2000000) ? 'disabled' : '' ?>>
                                        Melhorar (€<?= number_format($_SESSION['game']['facilities']['youth'] * 2000000, 0, ',', '.') ?>)
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
                
                <section id="transfers" class="content-section">
                    <h2>Mercado de Transferências</h2>
                    <div class="transfer-list">
                        <?php if (!empty($market_players)): ?>
                            <?php foreach ($market_players as $player): ?>
                                <div class="transfer-player">
                                    <div class="player-info">
                                        <?php if (!empty($player['imagem'])): ?>
                                            <img src="<?= htmlspecialchars($player['imagem']) ?>" alt="<?= htmlspecialchars($player['nome']) ?>" class="player-image">
                                        <?php endif; ?>
                                        <span class="player-name"><?= htmlspecialchars($player['nome']) ?></span>
                                        <span class="player-club"><?= htmlspecialchars($player['clube']) ?></span>
                                        <span class="player-position"><?= $player['posicao'] ?></span>
                                        <span class="player-age"><?= $player['idade'] ?? rand(18, 32) ?> anos</span>
                                        <span class="player-rating"><?= $player['overall'] ?> (<?= $player['potencial'] ?? $player['overall'] + rand(0, 5) ?>)</span>
                                        <span class="player-value">€<?= number_format($player['valor'], 0, ',', '.') ?></span>
                                        <span class="player-salary">€<?= number_format($player['salario'] ?? ($player['valor'] * 0.005), 0, ',', '.') ?>/mês</span>
                                    </div>
                                    <form method="post">
                                        <input type="hidden" name="player_id" value="<?= $player['id'] ?>">
                                        <button type="submit" name="sign_player" <?= $_SESSION['game']['budget'] < ($player['valor'] ?? 0) ? 'disabled' : '' ?>>Contratar</button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Nenhum jogador disponível no mercado no momento</p>
                        <?php endif; ?>
                    </div>
                </section>
                
                <section id="youth" class="content-section">
                    <h2>Academia de Jovens</h2>
                    <?php if (!$_SESSION['game']['youth_pulled'] && !empty($_SESSION['game']['youth_academy'])): ?>
                        <div class="youth-players">
                            <?php foreach ($_SESSION['game']['youth_academy'] as $index => $player): ?>
                                <div class="youth-player">
                                    <div class="player-info">
                                        <span class="player-name"><?= htmlspecialchars($player['nome']) ?></span>
                                        <span class="player-age"><?= $player['idade'] ?> anos</span>
                                        <span class="player-position"><?= $player['posicao'] ?></span>
                                        <span class="player-rating"><?= $player['overall'] ?> (<?= $player['potencial'] ?>)</span>
                                        <div class="player-stats">
                                            <span>Velocidade: <?= $player['stats']['pace'] ?></span>
                                            <span>Resistência: <?= $player['stats']['stamina'] ?></span>
                                            <span>Força: <?= $player['stats']['strength'] ?></span>
                                            <span>Drible: <?= $player['stats']['dribbling'] ?></span>
                                            <span>Passe: <?= $player['stats']['passing'] ?></span>
                                            <span>Finalização: <?= $player['stats']['shooting'] ?></span>
                                        </div>
                                    </div>
                                    <form method="post">
                                        <input type="hidden" name="player_index" value="<?= $index ?>">
                                        <button type="submit" name="promote_youth">Promover</button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php elseif ($_SESSION['game']['youth_pulled']): ?>
                        <p>Você já promoveu um jovem esta temporada.</p>
                    <?php else: ?>
                        <p>Não há jovens disponíveis na academia no momento.</p>
                    <?php endif; ?>
                </section>
            </main>
        <?php endif; ?>
    </div>
    
    <script src="js/sm.js"></script>
</body>
</html>