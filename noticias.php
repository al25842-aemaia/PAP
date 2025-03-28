<?php session_start(); ?>
<?php
require_once 'db_connection.php';
include 'menu.php';

try {
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/noticias.css">
    <title>Últimas Notícias de Futebol</title>
</head>
<body>
    <!-- Hero Section para Notícias -->
    <section class="news-hero">
        <div class="hero-content">
            <h1>ÚLTIMAS NOTÍCIAS</h1>
            <p>Tudo sobre o mundo do futebol em primeira mão</p>
        </div>
    </section>

    <!-- Conteúdo principal -->
    <main class="news-container">
        <?php if (!empty($noticias)): ?>
            <?php foreach ($noticias as $noticia): ?>
                <article class="news-card">
                    <div class="news-header">
                        <span class="news-category">Futebol Internacional</span>
                        <span class="news-date"><i class="far fa-calendar-alt"></i> <?php echo date('d/m/Y'); ?></span>
                    </div>
                    <h2><?php echo htmlspecialchars($noticia['titulo']); ?></h2>
                    <div class="news-content">
                        <p><?php echo htmlspecialchars($noticia['noticia']); ?></p>
                    </div>
                    <div class="news-footer">
                        <div class="news-author">
                            <i class="fas fa-user-edit"></i> Redação Futebol 12
                        </div>
                        <form method="post" action="excluir_noticia.php" class="news-actions">
                            <input type="hidden" name="id_noticia" value="<?php echo $noticia['id_noticia']; ?>">
                            
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-news">
                <i class="far fa-newspaper"></i>
                <p>Nenhuma notícia disponível no momento.</p>
            </div>
        <?php endif; ?>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>