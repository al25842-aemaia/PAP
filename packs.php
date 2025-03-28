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
    <link rel="stylesheet" href="css/packs.css">
</head>
<body>
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
            <div class="pack-card">
                <div class="pack-image-container">
                    <img src="<?php echo $imagemPack; ?>" alt="<?php echo $liga; ?>" class="pack-image">
                    <div class="pack-overlay"></div>
                </div>
                <div class="pack-info">
                    <h2><?php echo $liga; ?></h2>
                    <div class="pack-date">
                        <i class="far fa-calendar-alt"></i> <?php echo date('d/m/Y'); ?>
                    </div>
                    <form action="abrir_pack.php" method="GET">
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

    <div class="pack-footer">
    </div>
</body>
</html>