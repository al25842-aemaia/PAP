<?php
require_once 'db_connection.php';

// Inclui o menu na página
include 'menu.php';

try {
    // Consulta SQL para buscar as notícias mais recentes primeiro
    $sql = "SELECT * FROM noticiais ORDER BY id_noticia DESC"; 
    $result = $pdo->query($sql);
    $noticias = $result->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/noticias.css">
    <title>Notícias</title>
</head>
<body>
    <!-- Conteúdo da página -->
    <div class="content">
        <h1>Notícias de Futebol</h1>
        
        <?php if (!empty($noticias)): ?>
            <?php foreach ($noticias as $noticia): ?>
                <div class="noticia">
                    <h2><?php echo htmlspecialchars($noticia['titulo']); ?></h2>
                    <p><?php echo htmlspecialchars($noticia['noticia']); ?></p>
                    <form method="post" action="excluir_noticia.php">
                        <input type="hidden" name="id_noticia" value="<?php echo $noticia['id_noticia']; ?>">
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhuma notícia disponível no momento.</p>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; // Rodapé deve estar no final ?>
</body>
</html>
