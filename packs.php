<?php
include 'db_connection.php';

$query = "SELECT DISTINCT local_clube FROM clube";
$result = mysqli_query($conn, $query);

$imagens_packs = [
    'Liga Portugal' => 'imagens/ligaPortugal.png',
    'La Liga' => 'imagens/laliga.png',
];
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolha Seu Pack - Futebol 12</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/packs.css">
    <style>
        /* Efeito de hover premium para os cards */
        .pack-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 15px 30px rgba(231, 76, 60, 0.3);
            z-index: 10;
        }
        
        /* Efeito de brilho no hover */
        .pack-card:hover .pack-overlay {
            background: linear-gradient(to bottom, rgba(255,204,0,0.2) 0%, rgba(231,76,60,0.4) 100%);
        }
        
        /* Animação de pulsação para chamar atenção */
        @keyframes pulse-glow {
            0% { box-shadow: 0 0 10px rgba(255,204,0,0.5); }
            50% { box-shadow: 0 0 25px rgba(255,204,0,0.8); }
            100% { box-shadow: 0 0 10px rgba(255,204,0,0.5); }
        }
        
        .pack-card.highlight {
            animation: pulse-glow 2s infinite;
            border: 2px solid var(--gold-color);
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="pack-header">
        <div class="pack-header-content">
            <h1><span>ESCOLHA SEU PACK</span></h1>
            <p class="subtitle">Selecione uma liga para revelar jogadores incríveis</p>
        </div>
    </div>

    <div class="packs-container">
        <?php while ($row = mysqli_fetch_assoc($result)): 
            $liga = $row['local_clube'];
            $imagemPack = isset($imagens_packs[$liga]) ? $imagens_packs[$liga] : 'imagens/default_pack.jpeg';
        ?>
            <div class="pack-card <?php echo array_key_exists($liga, $imagens_packs) ? 'highlight' : ''; ?>">
                <div class="pack-image-container">
                    <img src="<?php echo $imagemPack; ?>" alt="<?php echo $liga; ?>" class="pack-image">
                    <div class="pack-overlay"></div>
                </div>
                <div class="pack-info">
                    <h2><?php echo $liga; ?></h2>
                    <div class="pack-date">
                        <i class="far fa-calendar-alt"></i> <?php echo date('d/m/Y'); ?>
                    </div>
                    <form action="abrir_pack.php" method="GET" class="pack-form">
                        <input type="hidden" name="liga" value="<?php echo $liga; ?>">
                        <button type="submit" class="pack-button">
                            <span>ABRIR PACK</span>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <script>
        // Efeito de loading ao clicar no botão
        document.querySelectorAll('.pack-button').forEach(button => {
            button.addEventListener('click', function(e) {
                const form = this.closest('.pack-form');
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> PREPARANDO...';
                setTimeout(() => form.submit(), 1000);
            });
        });
    </script>
    <?php include 'footer.php'; ?>
</body>
</html>