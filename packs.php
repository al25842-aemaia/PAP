    <?php
    include 'db_connection.php';

    // Buscar todas as ligas disponíveis no banco de dados
    $query = "SELECT DISTINCT local_clube FROM clube";
    $result = mysqli_query($conn, $query);

    // Definir manualmente as imagens para cada liga
    $imagens_packs = [
        'Liga Portugal' => 'imagens/ligaPortugal.png',
        'La Liga' => 'imagens/laliga.png',
        //'Premier League' => 'imagens/premier_league.jpeg',
        //'Bundesliga' => 'imagens/bundesliga.jpeg',
        //'Serie A' => 'imagens/serie_a.jpeg'
    ];
    ?>
    <!DOCTYPE html>
    <html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Packs</title>
        <link rel="stylesheet" href="css/packs.css">
    </head>
    <body>
        <h1>Escolha um Pack</h1>
        <div class="packs-container">
            <?php while ($row = mysqli_fetch_assoc($result)): 
                $liga = $row['local_clube'];
                // Se a liga tiver uma imagem definida, usa-a. Caso contrário, usa uma imagem padrão.
                $imagemPack = isset($imagens_packs[$liga]) ? $imagens_packs[$liga] : 'imagens/default_pack.jpeg';
            ?>
                <div class="pack">
                    <img src="<?php echo $imagemPack; ?>" alt="<?php echo $liga; ?>">
                    <h2><?php echo $liga; ?></h2>
                    <form action="abrir_pack.php" method="GET">
                        <input type="hidden" name="liga" value="<?php echo $liga; ?>">
                        <button type="submit">Abrir Pack</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </body>
    </html>
