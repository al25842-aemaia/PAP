<?php
include 'db_connection.php';

// Buscar todas as ligas disponíveis no banco de dados
$query = "SELECT DISTINCT local_clube FROM clube";
$result = mysqli_query($conn, $query);
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
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="pack">
                <img src="pack.png" alt="Pack">
                <h2><?php echo $row['local_clube']; ?></h2>
                <form action="abrir_pack.php" method="GET">
                    <input type="hidden" name="liga" value="<?php echo $row['local_clube']; ?>">
                    <button type="submit">Abrir Pack</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
