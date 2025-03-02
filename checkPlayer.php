<?php
require 'conexao.php'; // Conexão com o banco de dados

if (isset($_POST['jogador']) && isset($_POST['formacao']) && isset($_POST['jogadoresSelecionados'])) {
    $nomeJogador = $_POST['jogador'];
    $formacao = $_POST['formacao'];
    $jogadoresSelecionados = json_decode($_POST['jogadoresSelecionados'], true);

    // Buscar o jogador no banco de dados
    $stmt = $conn->prepare("SELECT j.id_jogador, j.nome_jogador, j.imagem_jogador, jp.id_posicao FROM jogador j 
                            JOIN jogador_posicoes jp ON j.id_jogador = jp.id_jogador
                            WHERE j.nome_jogador = ?");
    $stmt->bind_param("s", $nomeJogador);
    $stmt->execute();
    $result = $stmt->get_result();

    $posicoesJogador = [];
    $dadosJogador = null;
    
    while ($row = $result->fetch_assoc()) {
        if (!$dadosJogador) {
            $dadosJogador = [
                'id' => $row['id_jogador'],
                'nome' => $row['nome_jogador'],
                'imagem' => $row['imagem_jogador']
            ];
        }
        $posicoesJogador[] = $row['id_posicao'];
    }

    if (!$dadosJogador) {
        echo json_encode(["error" => "Jogador não encontrado."]);
        exit;
    }

    // Mapeamento de posições por formação
    $formacoes = [
        "4-3-3" => [1, 2, 3, 3, 4, 5, 10, 12, 17, 15, 16],
        "4-2-4" => [1, 2, 3, 3, 4, 5, 10, 12, 13, 15, 16],
        "4-4-2" => [1, 2, 3, 3, 4, 5, 10, 12, 12, 16, 16]
    ];
    
    if (!isset($formacoes[$formacao])) {
        echo json_encode(["error" => "Formação inválida."]);
        exit;
    }
    
    $posicoesFormacao = $formacoes[$formacao];
    $posicoesDisponiveis = array_intersect($posicoesJogador, $posicoesFormacao);
    
    // Verificar quais posições já estão ocupadas
    $posicaoEscolhida = null;
    $posicoesLivres = [];
    foreach ($posicoesDisponiveis as $posicao) {
        if (!in_array($posicao, $jogadoresSelecionados)) {
            $posicoesLivres[] = $posicao;
        }
    }
    
    if (count($posicoesLivres) == 1) {
        $posicaoEscolhida = $posicoesLivres[0];
    }
    
    if ($posicaoEscolhida) {
        echo json_encode([
            "id" => $dadosJogador['id'],
            "nome" => $dadosJogador['nome'],
            "imagem" => $dadosJogador['imagem'],
            "posicao" => $posicaoEscolhida
        ]);
    } else {
        echo json_encode([
            "id" => $dadosJogador['id'],
            "nome" => $dadosJogador['nome'],
            "imagem" => $dadosJogador['imagem'],
            "posicoes" => $posicoesLivres
        ]);
    }
} else {
    echo json_encode(["error" => "Dados insuficientes."]);
}
?>
