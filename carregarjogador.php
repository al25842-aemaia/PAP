<?php
// Conexão com a base de dados
$conn = new mysqli('localhost', 'root', '', 'pap_futebol');
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Consulta para selecionar um jogador aleatório e suas informações
$result = $conn->query("SELECT jogador.*, 
                               clube.nome_clube, 
                               clube.local_clube, 
                               nacionalidade.nacionalidade, 
                               posicoes.nome_posicao 
                        FROM jogador 
                        JOIN clube ON jogador.id_clube = clube.id_clube
                        JOIN nacionalidade ON jogador.id_nacionalidade = nacionalidade.id_nacionalidade
                        JOIN jogador_posicoes ON jogador.id_jogador = jogador_posicoes.id_jogador
                        JOIN posicoes ON jogador_posicoes.id_posicao = posicoes.id_posicao
                        ORDER BY RAND() LIMIT 1");

// Retorna os dados do jogador em formato JSON
$jogador = $result->fetch_assoc();
if ($jogador) {
    // Convertendo o campo 'aposentado' para "Ativo" ou "Aposentado" com base no valor da coluna
    $jogador['status'] = $jogador['aposentado'] == 1 ? 'Aposentado' : 'Ativo'; // Adiciona o status apropriado
    echo json_encode($jogador);
} else {
    echo json_encode(['error' => 'Nenhum jogador encontrado.']);
}

$conn->close();
?>
