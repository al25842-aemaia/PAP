<?php
session_start();
include 'db_connection.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['playerName'])) {
    $playerName = mysqli_real_escape_string($conn, $_POST['playerName']);
    $clubeAtual = $_SESSION['clube_atual'];

    $query = "SELECT * FROM jogador WHERE nome_jogador = '$playerName' AND id_clube = $clubeAtual";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Adiciona jogador à sessão para controle
        if (!in_array($playerName, $_SESSION['jogadores_adivinhados'])) {
            $_SESSION['jogadores_adivinhados'][] = $playerName;
        }
        
        // Verifica se todos os jogadores foram adivinhados
        $queryTotal = "SELECT COUNT(*) as total FROM jogador WHERE id_clube = $clubeAtual";
        $resultTotal = mysqli_query($conn, $queryTotal);
        $totalJogadores = mysqli_fetch_assoc($resultTotal)['total'];

        if (count($_SESSION['jogadores_adivinhados']) == $totalJogadores) {
            echo "Parabéns! Você adivinhou todos os jogadores!";
            session_destroy();
        } else {
            echo "Jogador correto!";
        }
    } else {
        echo "Jogador não encontrado neste clube.";
    }
}
?>
