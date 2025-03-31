<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abrir Pack - Futebol 12</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/packs.css">
    <style>
        /* Container principal da animação */
        #animation-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.95);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            perspective: 1000px;
        }
        
        /* Mensagem de status */
        #pack-status {
            font-size: 2rem;
            margin-bottom: 2rem;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: var(--gold-color);
            text-shadow: 0 0 10px rgba(255, 204, 0, 0.7);
            animation: pulse 1.5s infinite;
        }
        
        /* Estágios da animação */
        .animation-stage {
            position: absolute;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transform: scale(0.8) rotateY(20deg);
            transition: all 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }
        
        .animation-stage.active {
            opacity: 1;
            transform: scale(1) rotateY(0deg);
        }
        
        /* Estilo para cada estágio */
        #stage-1 img {
            width: 200px;
            height: 120px;
            object-fit: contain;
            filter: drop-shadow(0 0 20px rgba(255, 255, 255, 0.8));
            animation: float 3s ease-in-out infinite;
        }
        
        #stage-2 {
            color: var(--accent-color);
            font-size: 3.5rem;
            font-weight: bold;
            text-shadow: 0 0 20px rgba(231, 76, 60, 0.7);
        }
        
        #stage-3 img {
            width: 180px;
            height: 180px;
            object-fit: contain;
            filter: drop-shadow(0 0 25px rgba(52, 152, 219, 0.8));
            animation: pulse 2s infinite;
        }
        
        /* Carta final do jogador */
        #final-card {
            background: linear-gradient(145deg, #1a1a2e, #16213e);
            border-radius: 20px;
            padding: 30px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7);
            border: 3px solid var(--gold-color);
            transform: scale(0);
            opacity: 0;
            transition: all 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }
        
        #final-card.active {
            transform: scale(1);
            opacity: 1;
        }
        
        #final-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                to bottom right,
                transparent 45%,
                rgba(255, 255, 255, 0.1) 50%,
                transparent 55%
            );
            animation: shine 3s infinite;
        }
        
        #final-card img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid var(--gold-color);
            object-fit: cover;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(255, 204, 0, 0.5);
            animation: float 4s ease-in-out infinite;
        }
        
        #final-card p {
            margin: 10px 0;
            font-size: 1.1rem;
        }
        
        #final-card p strong {
            color: var(--gold-color);
        }
        
        /* Botões */
        .buttons-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .buttons-container button {
            background: linear-gradient(145deg, var(--gold-color), #e6b800);
            color: #000;
            border: none;
            padding: 12px 25px;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(255, 204, 0, 0.3);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .buttons-container button:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 20px rgba(255, 204, 0, 0.5);
        }
        
        /* Animações */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        
        @keyframes pulse {
            0% { opacity: 0.7; transform: scale(0.95); }
            50% { opacity: 1; transform: scale(1.05); }
            100% { opacity: 0.7; transform: scale(0.95); }
        }
        
        @keyframes shine {
            0% { transform: rotate(0deg) translate(-30%, -30%); }
            100% { transform: rotate(360deg) translate(-30%, -30%); }
        }
        
        /* Responsividade */
        @media (max-width: 768px) {
            #pack-status {
                font-size: 1.5rem;
            }
            
            #final-card {
                width: 95%;
                padding: 20px;
            }
            
            .buttons-container {
                flex-direction: column;
                gap: 10px;
            }
            
            .buttons-container button {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <?php
    include 'db_connection.php';

    $liga = isset($_GET['liga']) ? $_GET['liga'] : '';

    $sql = "SELECT j.*, c.nome_clube, c.imagem_clube, n.nacionalidade, n.imagem_nacionalidade, p.nome_posicao 
            FROM jogador j
            JOIN clube c ON j.id_clube = c.id_clube
            JOIN nacionalidade n ON j.id_nacionalidade = n.id_nacionalidade
            JOIN jogador_posicoes jp ON j.id_jogador = jp.id_jogador
            JOIN posicoes p ON jp.id_posicao = p.id_posicao
            WHERE c.local_clube = '$liga' 
            ORDER BY RAND() LIMIT 1";

    $result = $conn->query($sql);
    $player = $result->fetch_assoc();

    $conn->close();
    ?>

    <div id="animation-container">
        <p id="pack-status">Pack a abrir...</p>

        <div class="animation-stage hidden" id="stage-1">
            <img src="<?php echo $player['imagem_nacionalidade']; ?>" alt="Nacionalidade">
        </div>

        <div class="animation-stage hidden" id="stage-2">
            <p><?php echo $player['nome_posicao']; ?></p>
        </div>

        <div class="animation-stage hidden" id="stage-3">
            <img src="<?php echo $player['imagem_clube']; ?>" alt="Clube">
        </div>

        <div id="final-card" class="hidden">
            <img src="<?php echo $player['imagem_jogador']; ?>" alt="Jogador">
            <p><strong>Nome:</strong> <?php echo $player['nome_jogador']; ?></p>
            <p><strong>Clube:</strong> <?php echo $player['nome_clube']; ?></p>
            <p><strong>Posição:</strong> <?php echo $player['nome_posicao']; ?></p>
            <p><strong>Número:</strong> <?php echo $player['numero_camisola']; ?></p>
            <p><strong>Nacionalidade:</strong> <?php echo $player['nacionalidade']; ?></p>

            <div class="buttons-container">
                <button onclick="window.location.reload();"><i class="fas fa-sync-alt"></i> Abrir outro pack</button>
                <button onclick="window.location.href='packs.php';"><i class="fas fa-home"></i> Voltar</button>
            </div>
        </div>
    </div>

    <script src="js/packs.js"></script>
</body>
</html>