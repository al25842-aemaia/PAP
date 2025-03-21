<?php
include 'db_connection.php'; // Garante que a conexão com o banco de dados está correta

// Carregar equipas
$equipas = $conn->query("SELECT id_clube, nome_clube FROM clube");

// Se receber um ID de equipa via AJAX, retorna os jogadores dessa equipa
if (isset($_GET['equipa'])) {
    $equipaId = intval($_GET['equipa']);
    $query = "SELECT id_jogador, nome_jogador, numero_camisola FROM jogador WHERE id_clube = $equipaId";
    $result = $conn->query($query);

    $jogadores = [];
    while ($row = $result->fetch_assoc()) {
        $jogadores[] = $row;
    }
    echo json_encode($jogadores);
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulador de Equipas</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .campo {
            width: 600px;
            height: 400px;
            background-color: green;
            border: 5px solid white;
            margin: 20px auto;
            position: relative;
        }
        .jogador {
            width: 50px;
            height: 50px;
            background-color: blue;
            border-radius: 50%;
            color: white;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            line-height: 50px;
            position: absolute;
            transition: transform 0.5s ease-in-out;
        }
        p { margin: 5px 0; font-size: 14px; }
    </style>
</head>
<body>

    <h1>Simulador de Equipas</h1>

    <label for="equipa">Escolha a Equipa:</label>
    <select id="equipa" onchange="carregarJogadores()">
        <option value="">Selecione uma equipa</option>
        <?php while ($equipa = $equipas->fetch_assoc()) { ?>
            <option value="<?= $equipa['id_clube'] ?>"><?= $equipa['nome_clube'] ?></option>
        <?php } ?>
    </select>

    <h2>Selecione 11 jogadores</h2>
    <div id="listaJogadores"></div>

    <h2>Configuração de Tática</h2>
    <label>Pressão:</label>
    <select id="pressao">
        <option value="muito">Muito</option>
        <option value="normal" selected>Normal</option>
        <option value="pouco">Pouco</option>
    </select>

    <label>Passes:</label>
    <select id="passes">
        <option value="muito">Muito</option>
        <option value="normal" selected>Normal</option>
        <option value="pouco">Pouco</option>
    </select>

    <label>Velocidade:</label>
    <select id="velocidade">
        <option value="muito">Muito</option>
        <option value="normal" selected>Normal</option>
        <option value="pouco">Pouco</option>
    </select>

    <br><br>
    <button onclick="iniciarJogo()">Iniciar Jogo</button>

    <h2>Campo de Jogo</h2>
    <div class="campo" id="campo"></div>

    <script>
        let jogadoresSelecionados = [];

        function carregarJogadores() {
            let equipaId = document.getElementById("equipa").value;
            let listaJogadores = document.getElementById("listaJogadores");
            listaJogadores.innerHTML = "";

            if (equipaId) {
                fetch("teste.php?equipa=" + equipaId)
                .then(response => response.json())
                .then(data => {
                    jogadoresSelecionados = [];
                    data.forEach(jogador => {
                        let checkbox = document.createElement("input");
                        checkbox.type = "checkbox";
                        checkbox.value = JSON.stringify(jogador);
                        checkbox.onchange = function () {
                            if (this.checked && jogadoresSelecionados.length < 11) {
                                jogadoresSelecionados.push(JSON.parse(this.value));
                            } else if (!this.checked) {
                                jogadoresSelecionados = jogadoresSelecionados.filter(j => j.numero_camisola !== JSON.parse(this.value).numero_camisola);
                            } else {
                                this.checked = false;
                                alert("Você só pode selecionar 11 jogadores!");
                            }
                        };

                        let label = document.createElement("label");
                        label.textContent = `${jogador.nome_jogador} (#${jogador.numero_camisola})`;

                        let br = document.createElement("br");
                        listaJogadores.appendChild(checkbox);
                        listaJogadores.appendChild(label);
                        listaJogadores.appendChild(br);
                    });
                });
            }
        }

        function iniciarJogo() {
            if (jogadoresSelecionados.length !== 11) {
                alert("Selecione exatamente 11 jogadores!");
                return;
            }

            let campo = document.getElementById("campo");
            campo.innerHTML = "";

            let posicoes = [
                [275, 350], // GR
                [50, 270], [500, 270], // Laterais
                [200, 300], [350, 300], // Zagueiros
                [150, 200], [400, 200], // Volantes
                [100, 100], [450, 100], // Meias
                [200, 50], [350, 50] // Atacantes
            ];

            jogadoresSelecionados.forEach((jogador, index) => {
                let jogadorDiv = document.createElement("div");
                jogadorDiv.classList.add("jogador");
                jogadorDiv.textContent = jogador.numero_camisola;
                jogadorDiv.style.left = posicoes[index][0] + "px";
                jogadorDiv.style.top = posicoes[index][1] + "px";
                campo.appendChild(jogadorDiv);
            });

            animarJogadores();
        }

        function animarJogadores() {
            let jogadores = document.querySelectorAll(".jogador");

            setInterval(() => {
                jogadores.forEach(jogador => {
                    let deslocamentoX = (Math.random() - 0.5) * 20;
                    let deslocamentoY = (Math.random() - 0.5)
