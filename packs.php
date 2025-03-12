<?php
include 'db_connection.php'; // Arquivo de conexão com o banco de dados

// Buscar todas as ligas disponíveis (valores únicos de local_clube)
$query = "SELECT DISTINCT local_clube FROM clube";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolher Pack</title>
    <link rel="stylesheet" href="css/packs.css">
</head>
<body>
    <h1>Escolha um Pack</h1>
    <div class="packs-container">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <form action="abrir_pack.php" method="GET">
                <input type="hidden" name="liga" value="<?= htmlspecialchars($row['local_clube']) ?>">
                <button class="pack-btn"><?= htmlspecialchars($row['local_clube']) ?></button>
            </form>
        <?php } ?>
    </div>
</body>
</html>
