<?php
session_start();
if (!isset($_SESSION['correct_players'])) {
    $_SESSION['correct_players'] = 0;  // Conta jogadores corretos
}

// Conexão com a base de dados
$conn = new mysqli("localhost", "root", "", "pap_futebol");
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $playerName = $_POST["playerName"];
    
    // Consulta jogador e posições
    $sql = "SELECT jogador.*, clube.nome_clube, clube.imagem_clube, posicoes.nome_posicao 
            FROM jogador 
            JOIN clube ON jogador.id_clube = clube.id_clube 
            JOIN jogador_posicoes ON jogador.id_jogador = jogador_posicoes.id_jogador 
            JOIN posicoes ON jogador_posicoes.id_posicao = posicoes.id_posicao 
            WHERE jogador.nome_jogador = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $playerName);
    $stmt->execute();
    $result = $stmt->get_result();

    $posicoes = [];
    if ($result->num_rows > 0) {
        $jogador = $result->fetch_assoc();
        $clubeNome = $jogador["nome_clube"];
        $clubeImagem = "imagens_nacionalidade/" . $jogador["imagem_clube"];

        // Obtenção de posições e contagem de opções
        while ($row = $result->fetch_assoc()) {
            $posicoes[] = $row["nome_posicao"];
        }

        // Atualização de sessão e clube aleatório
        $_SESSION['correct_players']++;
        if ($_SESSION['correct_players'] < 11) {
            echo "<script>
                alert('Jogador $playerName adicionado com sucesso!');
                var posicoes = " . json_encode($posicoes) . ";
                document.getElementById('positions-options').innerHTML = posicoes.map(pos => 
                    `<div class='position-option'>${pos}</div>`).join('');
                document.getElementById('club-icon').src = '$clubeImagem';
                document.getElementById('club-name').innerText = '$clubeNome';
            </script>";
        } else {
            echo "<script>
                alert('Formação completa com sucesso!');
                window.location.href = 'futebol11clubes.php';
            </script>";
            $_SESSION['correct_players'] = 0;
        }
    } else {
        echo "<script>alert('Jogador ou clube/posição incorretos.');</script>";
    }
    $stmt->close();
}

$conn->close();
echo "<script>window.location.href='futebol11clubes.php';</script>";
?>
