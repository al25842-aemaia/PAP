<?php
require_once 'db_connection.php';
session_start();

function getRandomClub($conn) {
    $query = "SELECT id_clube, nome_clube, imagem_clube FROM clube ORDER BY RAND() LIMIT 1";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

$clube = getRandomClub($conn);

if ($clube) {
    $clubeId = $clube['id_clube'];
    $clubeNome = $clube['nome_clube'];
    $clubeImagem = $clube['imagem_clube'];
} else {
    $clubeId = null;
    $clubeNome = "Nenhum clube encontrado";
    $clubeImagem = "img/default_club.png";
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Futebol 11 Clubes</title>
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/footer.css">
    <style>
        body { text-align: center; font-family: Arial, sans-serif; background-color: #222; color: white; }
        #field { width: 90%; max-width: 1000px; height: 500px; background: green; margin: 20px auto; position: relative; border-radius: 10px; border: 2px solid white; }
        .line { display: flex; justify-content: space-around; position: absolute; width: 100%; }
        .position { width: 80px; height: 80px; background: white; display: flex; justify-content: center; align-items: center; font-weight: bold; color: black; border-radius: 5px; font-size: 0.9em; position: relative; cursor: pointer; }
        .line1 { top: 10%; }
        .line2 { top: 30%; }
        .line3 { top: 50%; }
        .line4 { top: 70%; }
        #player-input { margin-top: 20px; }
        input, select, button { padding: 10px; margin: 5px; }
    </style>
</head>
<body>
<?php include 'menu.php'; ?>
<header><h1>Simulador de Equipas</h1></header>
<main>
    <div id="club-container">
        <img src="<?= htmlspecialchars($clubeImagem); ?>" alt="Clube" id="club-image">
        <span id="club-name"> <?= htmlspecialchars($clubeNome); ?> </span>
    </div>
    <div>
        <label for="tactic">Escolha a Tática:</label>
        <select id="tactic" onchange="generateField(this.value)">
            <option value="4-3-3">4-3-3</option>
            <option value="4-4-2">4-4-2</option>
            <option value="3-5-2">3-5-2</option>
            <option value="5-3-2">5-3-2</option>
            <option value="4-2-3-1">4-2-3-1</option>
        </select>
    </div>
    <div id="field"></div>
    <div id="player-input">
        <input type="text" id="playerName" placeholder="Digite o nome do jogador">
        <button onclick="addPlayerManually()">Adicionar Jogador</button>
    </div>
</main>
<?php include 'footer.php'; ?>
<script>
const tactics = {
    "4-3-3": {
        "GR": 1, "DD": 1, "DC1": 1, "DC2": 1, "DE": 1,
        "MC1": 1, "MCO": 1, "MC2": 1, "ED": 1, "PL": 1, "EE": 1
    },
    "4-4-2": {
        "GR": 1, "DD": 1, "DC1": 1, "DC2": 1, "DE": 1,
        "MD": 1, "MC1": 1, "MC2": 1, "ME": 1, "PL1": 1, "PL2": 1
    },
    "3-5-2": {
        "GR": 1, "DC1": 1, "DC2": 1, "DC3": 1, "MD": 1,
        "MC1": 1, "MC2": 1, "MCO": 1, "ME": 1, "PL1": 1, "PL2": 1
    },
    "5-3-2": {
        "GR": 1, "DD": 1, "DC1": 1, "DC2": 1, "DC3": 1,
        "DE": 1, "MC1": 1, "MC2": 1, "MCO": 1, "PL1": 1, "PL2": 1
    },
    "4-2-3-1": {
        "GR": 1, "DD": 1, "DC1": 1, "DC2": 1, "DE": 1,
        "MDC1": 1, "MDC2": 1, "ME": 1, "MCO": 1, "MD": 1, "PL": 1
    }
};

function generateField(tactic) {
    const field = document.getElementById("field");
    field.innerHTML = "";

    const positions = tactics[tactic] || {};
    const lines = {
        "GR": [],
        "DD": [], "DC1": [], "DC2": [], "DE": [],
        "MC1": [], "MCO": [], "MC2": [],
        "ED": [], "PL": [], "EE": [],
        "MD": [], "MDC1": [], "MDC2": [],
        "PL1": [], "PL2": []
    };

    for (const pos in positions) {
        const div = document.createElement("div");
        div.classList.add("position");
        div.innerHTML = pos;
        div.setAttribute("data-pos", pos);
        lines[pos] = div;
    }

    // Organizing players into lines, starting with the GR at the bottom
    createLine(["ED", "PL", "EE"], "line1");
    createLine(["MC1", "MCO", "MC2"], "line2");
    createLine(["DD", "DC1", "DC2", "DE"], "line3");
    createLine(["GR"], "line4");  // GR at the bottom

    function createLine(positions, lineClass) {
        const line = document.createElement("div");
        line.classList.add("line", lineClass);
        positions.forEach(pos => line.appendChild(lines[pos]));
        field.appendChild(line);
    }
}

function assignPlayerToPosition(positionDiv) {
    const playerName = document.getElementById("playerName").value.trim();
    if (!playerName) {
        alert("Digite um nome de jogador primeiro!");
        return;
    }
    positionDiv.innerHTML = playerName;
    positionDiv.style.background = "yellow";
}

document.addEventListener("DOMContentLoaded", () => {
    generateField(document.getElementById("tactic").value);
});
</script>
</body>
</html>
